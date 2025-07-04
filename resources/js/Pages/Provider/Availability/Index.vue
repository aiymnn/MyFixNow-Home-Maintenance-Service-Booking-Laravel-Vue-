<script setup>
import DeleteSweetAlert from "@/Components/ui/DeleteSweetAlert.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import FlashMessage from "@/Components/ui/FlashMessage.vue";
import { Trash2, CirclePlus, Edit } from "lucide-vue-next";
import { ref } from "vue";
import SlotFormModal from "@/Components/ui/SlotFormModal.vue"; // ✅ Multi-slot modal for ADD only
import UpdateSlotModal from "@/Components/ui/UpdateSlotModal.vue";

const props = defineProps({
    availabilities: Object,
    userServices: Array, // ✅ required for service dropdown in modal
});

const showModal = ref(false);

// Create (add multiple slots)
const openCreate = () => {
    showModal.value = true;
};

const showUpdateModal = ref(false);
const updatingSlot = ref(null);

const openEdit = (slot) => {
    updatingSlot.value = { ...slot };
    showUpdateModal.value = true;
};

// Delete
const deleteItem = ({ id, route: routeName, method }) => {
    router[method](route(routeName, id), {
        preserveScroll: true,
        onSuccess: () => router.reload(),
    });
};
</script>

<template>
    <Head title="Availability Slots" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Availability Slot Management
            </h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <FlashMessage />

                <!-- Create Button -->
                <div class="mb-4 flex justify-end">
                    <button
                        @click="openCreate"
                        class="bg-blue-500 inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-sm text-white hover:bg-blue-600 border border-blue-100 hover:shadow-md"
                    >
                        <CirclePlus class="w-4 h-4" />
                        Add New Slot
                    </button>
                </div>

                <!-- Slot Modal (Multi-Slot Add) -->
                <SlotFormModal
                    v-model="showModal"
                    :user-services="userServices"
                    @saved="() => router.reload()"
                />

                <!-- Update Slot Modal -->
                <UpdateSlotModal
                    v-model="showUpdateModal"
                    :edit-data="updatingSlot"
                    @updated="() => router.reload()"
                />

                <!-- Table -->
                <div class="overflow-x-auto bg-white rounded-2xl shadow-lg">
                    <table
                        class="min-w-full divide-y divide-gray-200 text-gray-700"
                    >
                        <caption class="text-left px-4 py-3 text-gray-500">
                            A list of availability slots.
                        </caption>
                        <thead
                            class="bg-gray-100 text-xs font-semibold text-gray-600 uppercase tracking-wide"
                        >
                            <tr>
                                <th class="px-4 py-3 text-left w-10">#</th>
                                <th class="px-4 py-3 text-left">Service</th>
                                <th class="px-4 py-3 text-left">Day</th>
                                <th class="px-4 py-3 text-left">Start Time</th>
                                <th class="px-4 py-3 text-left">End Time</th>
                                <th class="px-4 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr
                                v-for="(slot, index) in availabilities.data"
                                :key="slot.id"
                                class="hover:bg-gray-50"
                            >
                                <td
                                    class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap"
                                >
                                    {{ index + availabilities.from }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ slot.service.title }}
                                </td>
                                <td
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ slot.day_of_week }}
                                </td>
                                <td class="px-4 py-3 text-gray-700">
                                    {{ slot.start_time }}
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-700 whitespace-nowrap"
                                >
                                    {{ slot.end_time }}
                                </td>
                                <td
                                    class="px-4 py-3 text-right space-x-2 whitespace-nowrap"
                                >
                                    <!-- Edit Button -->
                                    <button
                                        @click="openEdit(slot)"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-sm text-blue-600 hover:bg-blue-50 border border-blue-100"
                                    >
                                        <Edit class="w-4 h-4" />
                                        Edit
                                    </button>

                                    <!-- Delete Button -->
                                    <DeleteSweetAlert
                                        :id="slot.id"
                                        route-name="availabilities.destroy"
                                        @confirm-delete="deleteItem"
                                    >
                                        <template #icon>
                                            <Trash2 class="w-4 h-4" />
                                        </template>
                                    </DeleteSweetAlert>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-between mt-6">
                    <div>
                        <span>
                            Showing from
                            <strong>{{ availabilities.from }}</strong>
                            to <strong>{{ availabilities.to }}</strong> of
                            <strong>{{ availabilities.total }}</strong>
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-1">
                        <template
                            v-for="(link, index) in availabilities.links"
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
