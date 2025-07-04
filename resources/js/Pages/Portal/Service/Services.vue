<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import PortalLayout from "@/Layouts/PortalLayout.vue";
import { ref, watch, computed, onMounted } from "vue";
import ServiceQuickView from "@/Components/ServiceQuickView.vue";

// Clean props
const props = defineProps({
    services: Object,
    categories: Array,
    filters: Object,
});

// State
// const quickViewService = ref(null);
const loading = ref(true);

// Data
const services = ref(props.services.data ?? []);
const meta = ref(props.services.meta ?? { current_page: 1, last_page: 1 });
const categories = ref(["All", ...props.categories]);
const filters = ref({
    search: props.filters.search ?? "",
    category: props.filters.category ?? "All",
    sort: props.filters.sort ?? "Newest",
});

// Computed for pagination
const currentPage = computed(() => meta.value?.current_page ?? 1);
const lastPage = computed(() => meta.value?.last_page ?? 1);

// Fetch Services
function fetchServices(page = 1, append = false) {
    loading.value = true;
    router.get(
        route("portal.services"),
        {
            search: filters.value.search,
            category: filters.value.category,
            sort: filters.value.sort,
            page,
        },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
            onSuccess: (page) => {
                const newServices = page.props.services;
                if (append) {
                    services.value = [...services.value, ...newServices.data];
                } else {
                    services.value = newServices.data;
                }
                meta.value = newServices.meta ?? {
                    current_page: 1,
                    last_page: 1,
                };
                loading.value = false;
            },
        }
    );
}

// Watch filters
watch(
    filters,
    () => {
        fetchServices(1);
    },
    { deep: true }
);

// Load more
function loadMore() {
    if (currentPage.value < lastPage.value) {
        fetchServices(currentPage.value + 1, true);
    }
}

// Quick View
// function openQuickView(service) {
//     quickViewService.value = service;
// }
// function closeQuickView() {
//     quickViewService.value = null;
// }

onMounted(() => {
    loading.value = false;
});
</script>

<template>
    <PortalLayout>
        <Head title="Services - MyFixNow" />

        <div
            class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-4 gap-6"
        >
            <!-- Filters Sidebar -->
            <aside class="md:col-span-1 space-y-4">
                <input
                    v-model="filters.search"
                    type="text"
                    placeholder="Search services..."
                    class="w-full border rounded-lg px-4 py-2 focus:ring focus:border-blue-400"
                />

                <div class="space-y-2">
                    <h3 class="font-semibold">Categories</h3>
                    <div class="flex flex-wrap md:flex-col gap-2">
                        <button
                            v-for="category in categories"
                            :key="category"
                            @click="filters.category = category"
                            :class="
                                filters.category === category
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-gray-200 hover:bg-gray-300 text-gray-800'
                            "
                            class="px-3 py-1 rounded-full text-sm"
                        >
                            {{ category }}
                        </button>
                    </div>
                </div>

                <div>
                    <h3 class="font-semibold mb-1">Sort By</h3>
                    <select
                        v-model="filters.sort"
                        class="w-full border rounded-lg px-4 py-2 focus:ring focus:border-blue-400"
                    >
                        <option>Newest</option>
                        <option>Price Low to High</option>
                        <option>Price High to Low</option>
                    </select>
                </div>
            </aside>

            <!-- Services Grid -->
            <main class="md:col-span-3 space-y-6">
                <!-- Skeleton -->
                <div
                    v-if="loading"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-1"
                >
                    <div
                        v-for="n in 6"
                        :key="n"
                        class="bg-white rounded-xl shadow animate-pulse"
                    >
                        <div class="h-48 w-full bg-gray-300 rounded-t-xl"></div>
                        <div class="p-4 space-y-2">
                            <div class="h-4 w-3/4 bg-gray-300 rounded"></div>
                            <div class="h-3 w-full bg-gray-300 rounded"></div>
                            <div class="h-3 w-5/6 bg-gray-300 rounded"></div>
                            <div
                                class="h-4 w-1/2 bg-gray-300 rounded mt-2"
                            ></div>
                        </div>
                    </div>
                </div>

                <!-- Services -->
                <!-- Place this to open quickview
                @click="openQuickView(service)" -->
                <div
                    v-else
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-1"
                >
                    <div
                        v-for="service in services"
                        :key="service.id"
                        class="bg-white rounded-xl shadow hover:shadow-lg transition flex flex-col group relative overflow-hidden"
                    >
                        <img
                            :src="
                                service.image
                                    ? `/storage/${service.image}`
                                    : '/placeholder.jpg'
                            "
                            :alt="service.title"
                            class="h-48 sm:h-44 w-full object-cover rounded-t-xl group-hover:scale-105 transition-transform duration-300"
                        />
                        <div
                            class="absolute top-3 left-3 bg-blue-600 text-white text-xs font-semibold px-2 py-0.5 rounded-full shadow"
                        >
                            ⭐
                            {{
                                parseFloat(
                                    service.reviews_avg_rating ?? 0
                                ).toFixed(1)
                            }}
                        </div>
                        <div
                            class="p-4 flex flex-col justify-between flex-1 space-y-2"
                        >
                            <h2
                                class="text-base sm:text-lg font-semibold truncate"
                            >
                                {{ service.title }}
                            </h2>
                            <p
                                class="text-xs sm:text-sm text-gray-600 line-clamp-3"
                            >
                                {{ service.description }}
                            </p>
                            <div class="flex items-center justify-between mt-1">
                                <p
                                    class="text-blue-600 font-semibold text-sm sm:text-base"
                                >
                                    RM {{ service.price }}
                                </p>
                                <span
                                    v-if="service.bookings_count"
                                    class="text-gray-500 text-xs flex items-center gap-1"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 text-blue-500"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6H7m-4 0h5m-6-4v2a4 4 0 004 4h1m4-6a4 4 0 014 4v2m0-10a4 4 0 00-4-4m0 0a4 4 0 00-4 4m8 0a4 4 0 014 4v2"
                                        />
                                    </svg>
                                    {{ service.bookings_count }}
                                </span>
                            </div>
                            <Link
                                :href="
                                    route('portal.services.show', service.id)
                                "
                                class="mt-2 bg-blue-600 hover:bg-blue-700 text-white text-center font-medium text-xs sm:text-sm px-3 py-1.5 rounded-lg transition"
                            >
                                View Details
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- View More -->
                <div
                    v-if="!loading && currentPage < lastPage"
                    class="text-center"
                >
                    <button
                        @click="loadMore"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition"
                    >
                        View More
                    </button>
                </div>

                <!-- No Data -->
                <div
                    v-if="!loading && services.length === 0"
                    class="text-center p-4"
                >
                    <p class="text-lg font-semibold">No services found</p>
                    <p class="text-gray-600">
                        Try adjusting your filters or search keywords.
                    </p>
                </div>
            </main>
        </div>

        <!-- Quick View Modal -->
        <!-- <ServiceQuickView
            v-if="quickViewService"
            :key="quickViewService.id"
            :service="quickViewService"
            @close="closeQuickView"
        /> -->
    </PortalLayout>
</template>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
