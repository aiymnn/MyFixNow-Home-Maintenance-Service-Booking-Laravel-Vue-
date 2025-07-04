<script setup>
import { ref, computed, watch } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    service: Object,
    availabilitySlots: Array,
    bookedSlotIds: Array,
});

const emit = defineEmits(["close"]);

const today = new Date().toISOString().split("T")[0];
const submitting = ref(false);

const form = useForm({
    date: today,
    availability_slot_id: "",
    notes: "",
});

const getDayOfWeek = (dateStr) => {
    const days = [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
    ];
    return days[new Date(dateStr).getDay()];
};

const availableTimeSlots = computed(() => {
    const selectedDay = getDayOfWeek(form.date);
    return props.availabilitySlots.filter(
        (slot) =>
            slot.day_of_week === selectedDay &&
            !props.bookedSlotIds.includes(slot.id)
    );
});

watch(
    () => form.date,
    () => {
        if (
            !availableTimeSlots.value.some(
                (slot) => slot.id === form.availability_slot_id
            )
        ) {
            form.availability_slot_id = "";
        }
    }
);

const submit = () => {
    if (!form.availability_slot_id) {
        alert("Please select a valid time slot.");
        return;
    }

    submitting.value = true;

    form.post(route("portal.booking", props.service.id), {
        preserveScroll: true,
        onSuccess: () => {
            submitting.value = false;
            emit("close");
        },
        onError: () => {
            submitting.value = false;
        },
    });
};
</script>

<template>
    <div
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
    >
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button
                @click="$emit('close')"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700"
            >
                ✕
            </button>

            <h2 class="text-lg font-bold mb-4">Book {{ service.title }}</h2>

            <div class="text-xs text-gray-600 mb-2">
                Available on {{ getDayOfWeek(form.date) }}:
                <span v-if="availableTimeSlots.length">
                    {{
                        availableTimeSlots
                            .map(
                                (slot) =>
                                    slot.start_time + " - " + slot.end_time
                            )
                            .join(", ")
                    }}
                </span>
                <span v-else class="text-red-500"
                    >No slots available for this day.</span
                >
            </div>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1"
                        >Date</label
                    >
                    <input
                        type="date"
                        v-model="form.date"
                        class="w-full border rounded px-3 py-2"
                        :min="today"
                        required
                    />
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1"
                        >Available Time Slot</label
                    >
                    <select
                        v-model="form.availability_slot_id"
                        class="w-full border rounded px-3 py-2"
                        :disabled="availableTimeSlots.length === 0"
                        required
                    >
                        <option value="" disabled>Select a time slot</option>
                        <option
                            v-for="slot in availableTimeSlots"
                            :key="slot.id"
                            :value="slot.id"
                        >
                            {{ slot.start_time }} - {{ slot.end_time }}
                        </option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1"
                        >Notes (Optional)</label
                    >
                    <textarea
                        v-model="form.notes"
                        rows="3"
                        class="w-full border rounded px-3 py-2"
                        placeholder="e.g., Please bring ladder"
                    ></textarea>
                </div>

                <div class="mb-4 text-right">
                    <div class="text-gray-700 text-sm mb-2">
                        Price:
                        <span class="font-semibold text-blue-600">
                            RM {{ Number(service.price).toFixed(2) }}
                        </span>
                    </div>
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded transition"
                        :disabled="
                            submitting || availableTimeSlots.length === 0
                        "
                    >
                        {{ submitting ? "Booking..." : "Confirm Booking" }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
