<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import FlashMessage from "@/Components/ui/FlashMessage.vue";
import { ref, onMounted, computed } from "vue";
import {
    Undo2,
    ChevronLeft,
    ChevronRight,
    Trash2,
    LocationEdit,
} from "lucide-vue-next";
import AssignAreaModal from "@/Components/ui/AssignAreaModal.vue";
import DeleteSweetAlert from "@/Components/ui/DeleteSweetAlert.vue";

const flash = usePage().props.flash;

const props = defineProps({
    service: Object,
    states: Array,
    areas: Array,
});

console.log(props.service);

const baseUrl = "/storage/";
const previewImage = ref(baseUrl + props.service.image);
const galleryRef = ref(null);
const previewRef = ref(null);
const currentIndex = ref(0);

const allImages = [
    ...(props.service.image ? [{ image_path: props.service.image }] : []),
    ...(props.service.gallery ?? []),
];

const selectImage = (imgPath, index) => {
    previewImage.value = baseUrl + imgPath;
    currentIndex.value = index;
    scrollToPreview();
};

const scrollToPreview = () => {
    const target = previewRef.value;
    if (!target) return;
    target.scrollIntoView({ behavior: "smooth", block: "center" });
};

const scrollGallery = (direction) => {
    if (!galleryRef.value) return;

    if (direction === "right") {
        if (currentIndex.value < allImages.length - 1) {
            currentIndex.value++;
        }
    } else if (direction === "left") {
        if (currentIndex.value > 0) {
            currentIndex.value--;
        }
    }

    previewImage.value = baseUrl + allImages[currentIndex.value].image_path;

    scrollToPreview(); // ✅ keeps preview in view at top

    const container = galleryRef.value;
    const scrollAmount = 120; // adjust for thumbnail scroll speed

    if (direction === "right") {
        container.scrollLeft += scrollAmount;
    } else if (direction === "left") {
        container.scrollLeft -= scrollAmount;
    }
};

// Handle asign area
const form = useForm({
    area_id: null,
});

const handleAssign = ({ service_id, area_id }) => {
    form.area_id = area_id;

    form.post(route("services.assign.area", service_id), {
        preserveScroll: true,
        preserveState: false, // refresh prop
        onSuccess: () => {
            form.reset("area_id");
        },
    });
};

const groupedAreas = computed(() => {
    const result = {};

    props.service.areas?.forEach((area) => {
        const stateName = area.state?.name ?? "Unknown State";
        if (!result[stateName]) {
            result[stateName] = [];
        }
        result[stateName].push(area);
    });

    return result;
});

const deleteItem = ({ id, route: routeName, method }) => {
    router[method](route(routeName, { service: props.service.id, area: id }), {
        preserveScroll: true,
        onSuccess: () => {
            router.reload(); // or use FlashMessage
        },
    });
};
</script>

<template>
    <Head title="Service Details" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-semibold leading-tight text-gray-800">
                Service Information
            </h2>
        </template>

        <div class="">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 space-y-4">
                <FlashMessage />

                <!-- Back button -->
                <div class="mb-4 flex justify-end">
                    <Link :href="route('services.index')"
                        ><button
                            class="bg-gray-500 inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-sm text-white hover:bg-gray-700 border border-gray-100 hover:shadow-md"
                        >
                            <Undo2 class="w-4 h-4" />
                            Back
                        </button></Link
                    >
                </div>

                <div class="flex flex-col gap-4">
                    <!-- Image Section -->
                    <div
                        ref="previewRef"
                        class="bg-white rounded-xl shadow-md p-6"
                    >
                        <!-- Preview Image -->
                        <div
                            class="w-full aspect-video rounded-md overflow-hidden"
                        >
                            <img
                                :src="previewImage"
                                alt="Preview"
                                class="w-full h-full object-cover"
                            />
                        </div>
                    </div>

                    <!-- Gallery Images Carousel -->
                    <div class="flex justify-center">
                        <div class="w-full max-w-4xl">
                            <div class="carousel-wrapper">
                                <!-- Prev Button -->
                                <button
                                    @click="scrollGallery('left')"
                                    class="bg-white shadow p-2 rounded-full hover:bg-blue-100 flex items-center justify-center"
                                >
                                    <ChevronLeft
                                        class="w-5 h-5 text-gray-600"
                                    />
                                </button>

                                <!-- Thumbnail Container -->
                                <div
                                    ref="galleryRef"
                                    class="carousel-container flex-1"
                                >
                                    <img
                                        v-for="(img, index) in allImages"
                                        :key="index"
                                        :src="baseUrl + img.image_path"
                                        @click="
                                            selectImage(img.image_path, index)
                                        "
                                        :class="[
                                            'w-20 h-20 object-cover rounded-md cursor-pointer border img-select',
                                            previewImage ===
                                            baseUrl + img.image_path
                                                ? 'active-thumbnail'
                                                : 'hover:ring hover:ring-blue-300 hover:scale-105',
                                        ]"
                                    />
                                </div>

                                <!-- Next Button -->
                                <button
                                    @click="scrollGallery('right')"
                                    class="bg-white shadow p-2 rounded-full hover:bg-blue-100 flex items-center justify-center"
                                >
                                    <ChevronRight
                                        class="w-5 h-5 text-gray-600"
                                    />
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Service Title -->
                    <div
                        class="py-8 w-full px-2 sm:px-4 lg:px-0 text-center md:text-left"
                    >
                        <h1
                            style="font-size: clamp(1.75rem, 5vw, 4rem)"
                            class="font-black text-gray-900 leading-tight animate-fade-in"
                        >
                            {{ props.service.title }}
                        </h1>
                        <div
                            class="mt-2 h-1 w-24 bg-blue-600 rounded-full mx-auto md:mx-0 animate-slide-in"
                        ></div>
                    </div>

                    <!-- Service Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column (Details) -->
                        <div class="bg-white rounded-xl shadow-md">
                            <!-- Card Header with Border -->
                            <div class="border-b px-6 py-4">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    About Service
                                </h3>
                            </div>

                            <!-- Card Body -->
                            <div class="p-6 space-y-6">
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700"
                                >
                                    <div>
                                        <p class="font-semibold">Category</p>
                                        <p>{{ props.service.category.name }}</p>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Price</p>
                                        <p>RM {{ props.service.price }}</p>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Duration</p>
                                        <p>
                                            {{ props.service.duration_minutes }}
                                            minutes
                                        </p>
                                    </div>
                                    <div v-if="props.service.location">
                                        <p class="font-semibold">Location</p>
                                        <p>{{ props.service.location }}</p>
                                    </div>
                                </div>

                                <div>
                                    <p class="font-semibold text-gray-800 mb-2">
                                        Description
                                    </p>
                                    <p
                                        class="text-gray-600 whitespace-pre-line"
                                    >
                                        {{ props.service.description }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column (Service Areas) -->
                        <div class="bg-white rounded-xl shadow-md">
                            <!-- Card Header with Border -->
                            <div
                                class="border-b px-6 py-4 flex items-center justify-between"
                            >
                                <h3 class="text-xl font-semibold text-gray-900">
                                    Supported Service Areas
                                </h3>
                                <AssignAreaModal
                                    :service-id="service.id"
                                    :states="states"
                                    :service-areas="areas"
                                    :is-processing="form.processing"
                                    @assign-area="handleAssign"
                                >
                                    <template #icon>
                                        <LocationEdit class="w-4 h-4" />
                                    </template>
                                </AssignAreaModal>
                            </div>

                            <!-- Card Body -->
                            <div
                                class="p-6 space-y-6 max-h-[400px] overflow-y-auto"
                            >
                                <div
                                    v-if="service.areas && service.areas.length"
                                >
                                    <div
                                        v-for="(
                                            grouped, stateName
                                        ) in groupedAreas"
                                        :key="stateName"
                                        class="mb-6"
                                    >
                                        <h4
                                            class="text-base font-semibold text-indigo-700 mb-2"
                                        >
                                            📍 {{ stateName }}
                                        </h4>
                                        <ul class="space-y-2">
                                            <li
                                                v-for="area in grouped"
                                                :key="area.id"
                                                class="flex justify-between items-center bg-gray-50 px-3 py-2 rounded-md border"
                                            >
                                                <span class="text-gray-700">{{
                                                    area.name
                                                }}</span>
                                                <!-- Delete Button -->
                                                <DeleteSweetAlert
                                                    :id="area.id"
                                                    :route-name="`services.remove.area`"
                                                    @confirm-delete="
                                                        (payload) =>
                                                            deleteItem({
                                                                ...payload,
                                                                route: 'services.remove.area',
                                                                method: 'delete',
                                                            })
                                                    "
                                                >
                                                    <template #icon>
                                                        <Trash2
                                                            class="w-4 h-4"
                                                        />
                                                    </template>
                                                </DeleteSweetAlert>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div v-else>
                                    <p class="text-gray-400 italic">
                                        This service is not assigned to any area
                                        yet.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.img-select {
    transition: transform 0.3s ease, box-shadow 0.3s ease,
        border-color 0.3s ease;
    border-radius: 0.5rem;
    cursor: pointer;
}

.img-select:hover {
    transform: scale(1.05);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.4);
}

.active-thumbnail {
    transform: scale(1.05);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.4);
}

.carousel-wrapper {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.carousel-container {
    overflow-x: auto;
    scroll-behavior: smooth;
    display: flex;
    gap: 1rem;
    padding: 0.5rem;
}

/* Hide scrollbar (for WebKit & Firefox) */
.carousel-container::-webkit-scrollbar {
    display: none;
}
.carousel-container {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}
</style>
