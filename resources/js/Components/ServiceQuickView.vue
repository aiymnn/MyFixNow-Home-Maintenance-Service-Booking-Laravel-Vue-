<script setup>
import { Link } from "@inertiajs/vue3";
import { useEventListener } from "@vueuse/core";

const props = defineProps({
    service: Object,
});

const emit = defineEmits(["close"]);

function close() {
    emit("close");
}

useEventListener(window, "keydown", (e) => {
    if (e.key === "Escape") close();
});
</script>

<template>
    <div
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 px-4 sm:px-6"
        @click.self="close"
    >
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-md sm:max-w-lg relative animate-scale-fade overflow-hidden max-h-[90vh] flex flex-col"
        >
            <!-- Image with Badge -->
            <div class="relative w-full aspect-[4/3] sm:aspect-[16/9]">
                <img
                    :src="service.image"
                    :alt="service.name"
                    class="w-full h-full object-cover"
                />
                <span
                    v-if="service.location"
                    class="absolute top-3 right-3 bg-blue-600 text-white text-xs sm:text-sm font-semibold px-3 py-1 rounded-full shadow"
                >
                    {{ service.location }}
                </span>
            </div>
            <!-- Content -->
            <div class="p-4 sm:p-6 flex-1 overflow-y-auto space-y-3">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">
                    {{ service.name }}
                </h2>
                <div
                    v-if="service.rating"
                    class="flex items-center gap-2 text-yellow-500 text-sm sm:text-base"
                >
                    ⭐ {{ service.rating }}
                    <span class="text-gray-600"
                        >({{ service.totalBookings }} booked)</span
                    >
                </div>
                <p class="text-gray-600 text-sm sm:text-base">
                    {{ service.description }}
                </p>
                <p class="text-blue-600 font-semibold text-sm sm:text-base">
                    {{ service.priceRange }}
                </p>
                <!-- View Details Button -->
                <Link
                    :href="route('portal.services.show', service.id)"
                    class="block w-full mt-2 sm:mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg text-center transition"
                >
                    View Full Details
                </Link>
            </div>
            <!-- Close Button -->
            <button
                @click="close"
                class="absolute top-3 right-3 bg-white rounded-full shadow p-1.5 sm:p-2 text-gray-600 hover:text-gray-900 transition"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 sm:h-6 sm:w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>
    </div>
</template>

<style scoped>
@keyframes scale-fade {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
.animate-scale-fade {
    animation: scale-fade 0.3s ease-out;
}
</style>
