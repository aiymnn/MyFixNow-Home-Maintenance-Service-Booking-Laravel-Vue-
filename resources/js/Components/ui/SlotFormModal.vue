<script setup>
import { ref, watch, nextTick } from "vue";
import { useForm } from "@inertiajs/vue3";
import { LoaderCircle, Trash2, PlusCircle } from "lucide-vue-next";

const props = defineProps({
    modelValue: Boolean,
    userServices: Array, // pass dari controller (Auth::user services)
});

const emit = defineEmits(["update:modelValue", "saved"]);

const show = ref(false);

const form = useForm({
    service_id: "",
    day_of_week: "",
    slots: [{ start_time: "", end_time: "" }],
});

const addSlotRow = () => {
    form.slots.push({ start_time: "", end_time: "" });
};

const removeSlotRow = (index) => {
    form.slots.splice(index, 1);
};

watch(
    () => props.modelValue,
    (val) => {
        show.value = val;
        if (val) {
            nextTick(() => {
                form.reset();
                form.slots = [{ start_time: "", end_time: "" }];
            });
        }
    }
);

const close = () => {
    emit("update:modelValue", false);
    form.reset();
};

const save = () => {
    form.post(route("availabilities.store"), {
        onSuccess: () => {
            emit("saved");
            close();
        },
    });
};
</script>

<template>
    <transition name="fade">
        <div
            v-if="show"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
        >
            <div
                class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 space-y-6"
            >
                <h2 class="text-lg font-semibold text-gray-800">
                    Add Multiple Availability Slots
                </h2>

                <form @submit.prevent="save" class="space-y-4">
                    <!-- Service -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Service
                        </label>
                        <select
                            v-model="form.service_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-300 text-sm"
                        >
                            <option value="">Select a service</option>
                            <option
                                v-for="service in props.userServices"
                                :key="service.id"
                                :value="service.id"
                            >
                                {{ service.title }}
                            </option>
                        </select>
                        <div
                            v-if="form.errors.service_id"
                            class="text-sm text-red-600 mt-1"
                        >
                            {{ form.errors.service_id }}
                        </div>
                    </div>

                    <!-- Day -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Day
                        </label>
                        <select
                            v-model="form.day_of_week"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-300 text-sm"
                        >
                            <option value="">Select a day</option>
                            <option
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
                                :value="day"
                            >
                                {{ day }}
                            </option>
                        </select>
                        <div
                            v-if="form.errors.day_of_week"
                            class="text-sm text-red-600 mt-1"
                        >
                            {{ form.errors.day_of_week }}
                        </div>
                    </div>

                    <!-- Slot Rows -->
                    <div class="space-y-2">
                        <div
                            v-for="(slot, index) in form.slots"
                            :key="index"
                            class="flex items-center gap-2"
                        >
                            <input
                                v-model="slot.start_time"
                                type="time"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-300"
                            />
                            <span>to</span>
                            <input
                                v-model="slot.end_time"
                                type="time"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-300"
                            />
                            <button
                                type="button"
                                @click="removeSlotRow(index)"
                                class="text-red-500 hover:text-red-700"
                            >
                                <Trash2 class="w-5 h-5" />
                            </button>
                        </div>
                        <button
                            type="button"
                            @click="addSlotRow"
                            class="text-blue-600 hover:text-blue-800 text-sm inline-flex items-center gap-1"
                        >
                            <PlusCircle class="w-5 h-5" /> Add Slot
                        </button>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-2 pt-2">
                        <button
                            type="button"
                            @click="close"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-sm"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex justify-center items-center px-4 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:opacity-50 min-w-[100px]"
                        >
                            <template v-if="form.processing">
                                <LoaderCircle
                                    class="w-5 h-5 animate-spin text-white"
                                />
                            </template>
                            <template v-else> Submit </template>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
