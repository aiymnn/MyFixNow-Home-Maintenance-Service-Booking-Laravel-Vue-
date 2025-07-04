<script setup>
import { Head, router } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import PortalLayout from "@/Layouts/PortalLayout.vue";
import FlashMessage from "@/Components/ui/FlashMessage.vue";

const props = defineProps({
    bookings: Array,
    status: String,
});

const statuses = [
    { label: "All", value: "all" },
    { label: "Pending", value: "pending" },
    { label: "Accepted", value: "accepted" },
    { label: "Rejected", value: "rejected" },
    { label: "In Progress", value: "in_progress" },
    { label: "Completed", value: "completed" },
    { label: "Cancelled", value: "cancelled" },
];

const currentStatus = ref(props.status);
const loading = ref(true);

const setStatus = (status) => {
    currentStatus.value = status;
    loading.value = true;
    router.get(
        route("portal.bookings"),
        { status },
        {
            preserveScroll: true,
            preserveState: true,
            onFinish: () => {
                loading.value = false;
            },
        }
    );
};

const proceedToPayment = (bookingId) => {
    router.post(
        route("portal.payments.create", bookingId),
        {},
        {
            preserveScroll: true,
        }
    );
};

const cancelBooking = (bookingId) => {
    if (!confirm("Are you sure you want to cancel this booking?")) {
        return;
    }

    router.post(
        route("portal.booking.cancel", bookingId),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                // Optionally, show a toast success
            },
            onError: (errors) => {
                // Optionally, handle errors
            },
        }
    );
};

onMounted(() => {
    setTimeout(() => {
        loading.value = false;
    }, 500);
});
</script>

<template>
    <PortalLayout>
        <Head title="My Bookings - MyFixNow" />

        <div class="max-w-6xl mx-auto px-4 py-8">
            <FlashMessage />

            <h1 class="text-2xl font-bold mb-6">My Bookings</h1>

            <!-- Tabs -->
            <div class="flex flex-wrap gap-2 mb-6">
                <button
                    v-for="status in statuses"
                    :key="status.value"
                    @click="setStatus(status.value)"
                    :class="[
                        'px-4 py-2 rounded text-sm font-medium transition',
                        currentStatus === status.value
                            ? 'bg-blue-600 text-white'
                            : 'bg-gray-200 text-gray-700 hover:bg-gray-300',
                    ]"
                >
                    {{ status.label }}
                </button>
            </div>

            <!-- Skeleton Loading -->
            <div
                v-if="loading"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-1"
            >
                <div
                    v-for="n in 6"
                    :key="n"
                    class="bg-white dark:bg-gray-800 rounded-xl shadow animate-pulse"
                >
                    <div
                        class="h-40 w-full bg-gray-300 dark:bg-gray-700 rounded-t-xl"
                    ></div>
                    <div class="p-4 space-y-2">
                        <div
                            class="h-4 w-3/4 bg-gray-300 dark:bg-gray-700 rounded"
                        ></div>
                        <div
                            class="h-3 w-full bg-gray-300 dark:bg-gray-700 rounded"
                        ></div>
                        <div
                            class="h-3 w-5/6 bg-gray-300 dark:bg-gray-700 rounded"
                        ></div>
                        <div
                            class="h-4 w-1/2 bg-gray-300 dark:bg-gray-700 rounded mt-2"
                        ></div>
                    </div>
                </div>
            </div>

            <!-- Bookings Grid -->
            <div
                v-else-if="props.bookings.length"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
            >
                <div
                    v-for="booking in props.bookings"
                    :key="booking.id"
                    class="border rounded-lg p-4 shadow hover:shadow-md transition bg-white dark:bg-gray-800"
                >
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-lg font-semibold">
                            {{ booking.service.title }}
                        </h2>
                        <span
                            :class="{
                                'text-yellow-600': booking.status === 'pending',
                                'text-blue-600':
                                    booking.status === 'accepted' ||
                                    booking.status === 'in_progress',
                                'text-green-600':
                                    booking.status === 'completed',
                                'text-red-600':
                                    booking.status === 'rejected' ||
                                    booking.status === 'cancelled',
                            }"
                            class="capitalize text-sm"
                        >
                            {{ booking.status.replace("_", " ") }}
                        </span>
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-300 mb-1">
                        <strong>Date:</strong> {{ booking.date }} |
                        <strong>Time Slot:</strong> {{ booking.time_slot }}
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-300 mb-1">
                        <strong>Provider:</strong> {{ booking.provider.name }}
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-300 mb-1">
                        <strong>Price:</strong> RM
                        {{ Number(booking.price_at_booking).toFixed(2) }}
                    </div>

                    <div class="text-sm mb-2">
                        <strong>Payment Status: </strong>
                        <span
                            :class="{
                                'text-yellow-600':
                                    booking.payment?.status === 'pending',
                                'text-green-600':
                                    booking.payment?.status === 'paid',
                                'text-red-600':
                                    booking.payment?.status === 'failed' ||
                                    booking.payment?.status === 'refunded',
                            }"
                            class="uppercase"
                        >
                            {{ booking.payment?.status ?? "pending" }}
                        </span>
                    </div>

                    <p
                        v-if="booking.notes"
                        class="text-gray-700 dark:text-gray-200 text-sm"
                    >
                        <strong>Notes:</strong> {{ booking.notes }}
                    </p>

                    <!-- Proceed to Payment & Cancel Booking Buttons -->
                    <div
                        v-if="
                            booking.status === 'accepted' &&
                            (!booking.payment ||
                                booking.payment.status !== 'paid')
                        "
                        class="mt-3 space-y-2"
                    >
                        <button
                            @click="proceedToPayment(booking.id)"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-3 py-2 rounded transition w-full"
                        >
                            Proceed to Payment
                        </button>

                        <button
                            @click="cancelBooking(booking.id)"
                            class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-3 py-2 rounded transition w-full"
                        >
                            Cancel Booking
                        </button>
                    </div>
                </div>
            </div>

            <div
                v-else
                class="text-center text-gray-500 dark:text-gray-400 py-12"
            >
                No bookings found for "{{ currentStatus }}".
            </div>
        </div>
    </PortalLayout>
</template>
