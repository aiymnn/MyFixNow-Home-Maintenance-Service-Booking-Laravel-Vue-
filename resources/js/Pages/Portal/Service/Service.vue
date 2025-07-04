// Service.vue (updated for real-world project clarity, MyFixNow)

<script setup>
import { Head } from "@inertiajs/vue3";
import PortalLayout from "@/Layouts/PortalLayout.vue";
import { ref, computed, onMounted } from "vue";
import { ChevronLeft, ChevronRight, Star } from "lucide-vue-next";
import AddBookingFormModal from "@/Components/ui/AddBookingFormModal.vue";
import FlashMessage from "@/Components/ui/FlashMessage.vue";

const props = defineProps({
    service: Object,
    availabilitySlots: Array,
    bookedSlotIds: Array,
});

const currentIndex = ref(0);
const baseUrl = "/storage/";
const previewImage = ref(baseUrl + props.service.image);
const galleryRef = ref(null);
const loading = ref(true);
const showAllReviews = ref(false);
const showBookingModal = ref(false);
const previewRef = ref(null);

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
    if (target) {
        target.scrollIntoView({ behavior: "smooth", block: "center" });
    }
};

const scrollGallery = (direction) => {
    if (!galleryRef.value) return;
    const container = galleryRef.value;
    const scrollAmount = 120;

    if (direction === "right") {
        if (currentIndex.value < allImages.length - 1) {
            currentIndex.value++;
            container.scrollLeft += scrollAmount;
        }
    } else {
        if (currentIndex.value > 0) {
            currentIndex.value--;
            container.scrollLeft -= scrollAmount;
        }
    }

    previewImage.value = baseUrl + allImages[currentIndex.value].image_path;
    scrollToPreview();
};

const groupedAreas = computed(() => {
    const map = {};
    (props.service.areas ?? []).forEach((area) => {
        const stateName = area.state?.name ?? "Unknown State";
        if (!map[stateName]) map[stateName] = [];
        map[stateName].push(area.name);
    });
    return map;
});

const displayedReviews = computed(() => {
    const reviews = props.service.reviews ?? [];
    return showAllReviews.value ? reviews : reviews.slice(0, 3);
});

const groupedSlotsByDayOfWeek = computed(() => {
    const grouped = {};
    (props.availabilitySlots ?? []).forEach((slot) => {
        const day = slot.day_of_week ?? "Unknown";
        if (!grouped[day]) grouped[day] = [];
        grouped[day].push(slot);
    });
    return grouped;
});

onMounted(() => {
    setTimeout(() => {
        loading.value = false;
    }, 300);
});
</script>

<template>
    <PortalLayout>
        <Head :title="`${props.service.title} - MyFixNow`" />

        <!-- Service Details -->
        <div
            class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-2 gap-8"
        >
            <FlashMessage />

            <!-- Gallery Section -->
            <div class="flex flex-col gap-4">
                <div ref="previewRef" class="bg-white rounded-xl shadow-md p-6">
                    <div class="w-full aspect-video rounded-md overflow-hidden">
                        <img
                            :src="previewImage"
                            alt="Preview"
                            class="w-full h-full object-cover"
                        />
                    </div>
                </div>

                <div class="flex items-center justify-center gap-2">
                    <button
                        @click="scrollGallery('left')"
                        class="bg-white shadow p-2 rounded-full hover:bg-blue-100"
                    >
                        <ChevronLeft class="w-5 h-5 text-gray-600" />
                    </button>
                    <div
                        ref="galleryRef"
                        class="carousel-container scrollbar-hide"
                    >
                        <div
                            v-if="loading"
                            v-for="n in 4"
                            :key="n"
                            class="w-20 h-20 bg-gray-300 rounded animate-pulse"
                        ></div>
                        <img
                            v-else
                            v-for="(img, index) in allImages"
                            :key="index"
                            :src="baseUrl + img.image_path"
                            class="w-20 h-20 object-cover rounded cursor-pointer border img-select"
                            :class="{
                                'active-thumbnail':
                                    previewImage === baseUrl + img.image_path,
                            }"
                            @click="selectImage(img.image_path, index)"
                        />
                    </div>
                    <button
                        @click="scrollGallery('right')"
                        class="bg-white shadow p-2 rounded-full hover:bg-blue-100"
                    >
                        <ChevronRight class="w-5 h-5 text-gray-600" />
                    </button>
                </div>
            </div>

            <!-- Details -->
            <div class="bg-white rounded-xl shadow p-6 flex flex-col gap-4">
                <template v-if="loading">
                    <div
                        class="h-6 w-3/4 bg-gray-300 rounded animate-pulse"
                    ></div>
                    <div
                        class="h-4 w-1/2 bg-gray-300 rounded animate-pulse"
                    ></div>
                    <div class="h-20 bg-gray-300 rounded animate-pulse"></div>
                </template>

                <template v-else>
                    <h1 class="text-2xl font-bold">
                        {{ props.service.title }}
                    </h1>
                    <div class="flex items-center gap-2 text-yellow-500">
                        <Star class="w-5 h-5" />
                        <span class="font-semibold text-gray-800">{{
                            (props.service.reviews_avg_rating ?? 4.5).toFixed(1)
                        }}</span>
                        <span class="text-gray-500"
                            >({{
                                props.service.bookings_count ?? 0
                            }}
                            bookings)</span
                        >
                    </div>
                    <p class="text-gray-700 whitespace-pre-line">
                        {{ props.service.description }}
                    </p>
                    <div class="text-sm text-gray-500">
                        Provider:
                        <span class="font-medium">{{
                            props.service.provider?.name ?? "N/A"
                        }}</span>
                        | Category:
                        <span class="font-medium">{{
                            props.service.category?.name ?? "N/A"
                        }}</span>
                        | Duration:
                        <span class="font-medium"
                            >{{ props.service.duration_minutes }} mins</span
                        >
                    </div>
                    <div class="text-blue-600 text-lg font-semibold">
                        RM {{ Number(props.service.price).toFixed(2) }}
                    </div>
                    <button
                        @click="showBookingModal = true"
                        class="bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition"
                    >
                        Book This Service
                    </button>
                </template>
            </div>
        </div>

        <!-- Availability Slots -->
        <div class="max-w-7xl mx-auto px-4 py-8 bg-white rounded shadow">
            <h2 class="text-xl font-bold mb-4">Availability Slots</h2>
            <div
                class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-4"
            >
                <div
                    v-for="day in [
                        'Monday',
                        'Tuesday',
                        'Wednesday',
                        'Thursday',
                        'Friday',
                        'Saturday',
                        'Sunday',
                    ]"
                    :key="day"
                    class="bg-gray-50 rounded p-2 flex flex-col items-center"
                >
                    <div class="font-semibold text-gray-800 mb-1">
                        {{ day }}
                    </div>
                    <div class="flex flex-col gap-1 w-full items-center">
                        <span
                            v-for="slot in groupedSlotsByDayOfWeek[day] ?? []"
                            :key="slot.id"
                            :class="
                                bookedSlotIds.includes(slot.id)
                                    ? 'bg-red-100 text-red-800'
                                    : 'bg-blue-100 text-blue-800' +
                                      ' px-2 py-0.5 rounded text-xs w-full text-center'
                            "
                        >
                            {{ slot.start_time }} - {{ slot.end_time }}
                        </span>
                        <span
                            v-if="!(groupedSlotsByDayOfWeek[day] ?? []).length"
                            class="text-gray-400 text-xs italic"
                            >No slots</span
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Areas Covered -->
        <div
            v-if="!loading && Object.keys(groupedAreas).length"
            class="max-w-7xl mx-auto px-4 py-8"
        >
            <h2 class="text-xl font-bold mb-4">Areas Covered</h2>
            <div
                v-for="(areas, state) in groupedAreas"
                :key="state"
                class="mb-4"
            >
                <div class="font-semibold text-gray-800">{{ state }}</div>
                <ul class="ml-5 list-disc text-gray-600 text-sm">
                    <li v-for="area in areas" :key="area">{{ area }}</li>
                </ul>
            </div>
        </div>

        <!-- Reviews -->
        <div class="max-w-7xl mx-auto px-4 py-8">
            <h2 class="text-xl font-bold mb-4">Customer Reviews</h2>
            <div
                v-if="loading"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
            >
                <div
                    v-for="n in 3"
                    :key="n"
                    class="bg-white rounded-xl shadow p-4 animate-pulse h-48"
                ></div>
            </div>
            <div
                v-else-if="displayedReviews.length === 0"
                class="text-gray-500"
            >
                No reviews yet.
            </div>
            <div
                v-else
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
            >
                <div
                    v-for="review in displayedReviews"
                    :key="review.id"
                    class="bg-white rounded-xl shadow p-4"
                >
                    <div class="flex items-center gap-2 mb-1">
                        <Star class="w-4 h-4 text-yellow-500" />
                        <span class="text-sm font-semibold">{{
                            review.rating.toFixed(1)
                        }}</span>
                        <span class="text-xs text-gray-500"
                            >• {{ review.created_at.substring(0, 10) }}</span
                        >
                    </div>
                    <p class="text-sm text-gray-700">{{ review.comment }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        - {{ review.booking?.customer?.name ?? "Anonymous" }}
                    </p>
                </div>
            </div>
            <div
                v-if="props.service.reviews && props.service.reviews.length > 3"
                class="flex justify-center mt-4"
            >
                <button
                    @click="showAllReviews = !showAllReviews"
                    class="text-blue-600 hover:underline"
                >
                    {{
                        showAllReviews
                            ? "View Less Reviews"
                            : "View More Reviews"
                    }}
                </button>
            </div>
        </div>

        <!-- Booking Modal -->
        <AddBookingFormModal
            v-if="showBookingModal"
            :service="props.service"
            :availability-slots="props.availabilitySlots"
            :booked-slot-ids="props.bookedSlotIds"
            @close="showBookingModal = false"
        />
    </PortalLayout>
</template>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.carousel-container {
    overflow-x: auto;
    scroll-behavior: smooth;
    display: flex;
    gap: 0.5rem;
    padding: 0.5rem;
}
.img-select {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
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
</style>
