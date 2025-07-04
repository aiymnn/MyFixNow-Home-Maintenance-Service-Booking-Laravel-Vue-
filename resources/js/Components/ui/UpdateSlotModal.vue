<script setup>
import { ref, watch, nextTick } from "vue";
import { useForm } from "@inertiajs/vue3";
import { LoaderCircle } from "lucide-vue-next";

const props = defineProps({
    modelValue: Boolean,
    editData: Object, // slot yang dihantar dari Index
});

const emit = defineEmits(["update:modelValue", "updated"]);

const show = ref(false);

const form = useForm({
    day_of_week: "",
    start_time: "",
    end_time: "",
});

watch(
    () => props.modelValue,
    (val) => {
        show.value = val;

        if (val && props.editData) {
            nextTick(() => {
                form.day_of_week = props.editData.day_of_week;
                form.start_time = props.editData.start_time.slice(0, 5); // HH:MM
                form.end_time = props.editData.end_time.slice(0, 5); // HH:MM
            });
        }
    },
    { immediate: true }
);

const close = () => {
    emit("update:modelValue", false);
    form.reset();
};

const update = () => {
    form.put(route("availabilities.update", props.editData.id), {
        onSuccess: () => {
            emit("updated");
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
                class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 space-y-6"
            >
                <h2 class="text-lg font-semibold text-gray-800">
                    Edit Availability Slot
                </h2>

                <form @submit.prevent="update" class="space-y-4">
                    <!-- Day of Week -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Day of Week
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

                    <!-- Start Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Start Time
                        </label>
                        <input
                            v-model="form.start_time"
                            type="time"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-300"
                        />
                        <div
                            v-if="form.errors.start_time"
                            class="text-sm text-red-600 mt-1"
                        >
                            {{ form.errors.start_time }}
                        </div>
                    </div>

                    <!-- End Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            End Time
                        </label>
                        <input
                            v-model="form.end_time"
                            type="time"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-300"
                        />
                        <div
                            v-if="form.errors.end_time"
                            class="text-sm text-red-600 mt-1"
                        >
                            {{ form.errors.end_time }}
                        </div>
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
                            <template v-else> Update </template>
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
