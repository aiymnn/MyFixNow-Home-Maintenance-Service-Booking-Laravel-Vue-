<script setup>
import FlashMessage from "@/Components/ui/FlashMessage.vue";
import UpdateBookingStatusModal from "@/Components/ui/UpdateBookingStatusModal.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { Eye } from "lucide-vue-next";
import { ref } from "vue";

const props = defineProps({
    bookings: Object,
});

const selectedBooking = ref(null);
const modalOpen = ref(false);

const openModal = (booking) => {
    selectedBooking.value = booking;
    modalOpen.value = true;
};
</script>

<template>
    <Head title="Bookings" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                My Bookings
            </h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <FlashMessage />

                <div class="overflow-x-auto bg-white rounded-2xl shadow-lg">
                    <table
                        class="min-w-full divide-y divide-gray-200 text-gray-700"
                    >
                        <caption class="text-left px-4 py-3 text-gray-500">
                            A list of your service bookings.
                        </caption>
                        <thead
                            class="bg-gray-100 text-xs font-semibold text-gray-600 uppercase tracking-wide"
                        >
                            <tr>
                                <th class="px-4 py-3 text-left">#</th>
                                <th class="px-4 py-3 text-left">Customer</th>
                                <th class="px-4 py-3 text-left">Service</th>
                                <th class="px-4 py-3 text-left">Date</th>
                                <th class="px-4 py-3 text-left">Time Slot</th>
                                <th class="px-4 py-3 text-left">Price</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr
                                v-for="(booking, index) in bookings.data"
                                :key="booking.id"
                                class="hover:bg-gray-50"
                            >
                                <td
                                    class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap"
                                >
                                    {{ index + bookings.from }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="font-semibold">
                                        {{ booking.customer.name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ booking.customer.email }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ booking.service.title }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ booking.date }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ booking.time_slot }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span
                                        :class="{
                                            'text-green-600 font-semibold':
                                                booking.payment,
                                            'text-gray-500':
                                                !booking.payment &&
                                                booking.status !== 'cancelled',
                                            'text-red-600 font-semibold':
                                                booking.status === 'cancelled',
                                        }"
                                    >
                                        RM{{
                                            Number(
                                                booking.price_at_booking
                                            ).toFixed(2)
                                        }}
                                        <span class="text-xs ml-1">
                                            (
                                            {{
                                                booking.status === "cancelled"
                                                    ? "Cancelled"
                                                    : booking.payment
                                                    ? "Paid"
                                                    : "Unpaid"
                                            }}
                                            )
                                        </span>
                                    </span>
                                </td>

                                <td
                                    class="px-4 py-3 whitespace-nowrap capitalize"
                                >
                                    {{ booking.status.replaceAll("_", " ") }}
                                </td>
                                <td
                                    class="px-4 py-3 text-right whitespace-nowrap"
                                >
                                    <button
                                        @click="openModal(booking)"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-sm text-green-600 hover:bg-green-50 border border-green-100"
                                    >
                                        <Eye class="w-4 h-4" />
                                        View
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <UpdateBookingStatusModal
                    v-model="modalOpen"
                    :booking="selectedBooking"
                />

                <!-- Pagination -->
                <div class="flex items-center justify-between mt-6">
                    <div>
                        <span>
                            Showing from
                            <strong>{{ bookings.from }}</strong> to
                            <strong>{{ bookings.to }}</strong> of
                            <strong>{{ bookings.total }}</strong> results
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-1">
                        <template
                            v-for="(link, index) in bookings.links"
                            :key="index"
                        >
                            <a
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    'px-3 py-1.5 shadow-sm border rounded-md',
                                    link.active
                                        ? 'bg-blue-600 text-white font-semibold'
                                        : 'bg-white text-gray-800 hover:bg-blue-600 hover:text-white hover:shadow-lg',
                                ]"
                            />
                            <span
                                v-else
                                v-html="link.label"
                                class="px-3 py-1.5 border rounded-md bg-gray-100 text-gray-400 cursor-default"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
