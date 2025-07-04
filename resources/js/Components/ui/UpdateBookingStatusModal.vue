<script setup>
import { ref, watch } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { LoaderCircle } from "lucide-vue-next";

const props = defineProps({
    modelValue: Boolean,
    booking: Object,
});

const emit = defineEmits(["update:modelValue"]);

const show = ref(false);
watch(
    () => props.modelValue,
    (val) => {
        show.value = val;
    }
);

const close = () => {
    emit("update:modelValue", false);
};

const form = useForm({
    status: "",
});

const updateStatus = (status) => {
    if (!props.booking) return;

    form.status = status;
    form.put(route("bookings.update", props.booking.id), {
        onSuccess: () => {
            close();
            router.reload();
        },
    });
};
</script>

<template>
    <transition name="fade">
        <div
            v-if="show && booking"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
        >
            <div
                class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 space-y-4"
            >
                <h2 class="text-lg font-semibold text-gray-800">
                    Booking Note
                </h2>

                <div class="text-gray-600 border p-3 rounded-md bg-gray-50">
                    {{ booking.notes ?? "No note provided." }}
                </div>

                <div>
                    <span
                        class="inline-block text-xs font-medium px-2 py-1 rounded"
                        :class="{
                            'bg-yellow-100 text-yellow-800':
                                booking.status === 'pending',
                            'bg-green-100 text-green-800':
                                booking.status === 'accepted' ||
                                booking.status === 'in_progress' ||
                                booking.status === 'completed',
                            'bg-red-100 text-red-800':
                                booking.status === 'rejected' ||
                                booking.status === 'cancelled',
                        }"
                    >
                        Status: {{ booking.status.replaceAll("_", " ") }}
                    </span>
                </div>

                <div class="flex justify-between items-center gap-2 pt-4">
                    <div v-if="booking.status === 'pending'" class="flex gap-2">
                        <button
                            @click="updateStatus('accepted')"
                            :disabled="form.processing"
                            class="inline-flex items-center px-4 py-1.5 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 disabled:opacity-50"
                        >
                            <LoaderCircle
                                v-if="
                                    form.processing &&
                                    form.status === 'accepted'
                                "
                                class="w-5 h-5 animate-spin text-white"
                            />
                            <span v-else>Accept</span>
                        </button>
                        <button
                            @click="updateStatus('rejected')"
                            :disabled="form.processing"
                            class="inline-flex items-center px-4 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 disabled:opacity-50"
                        >
                            <LoaderCircle
                                v-if="
                                    form.processing &&
                                    form.status === 'rejected'
                                "
                                class="w-5 h-5 animate-spin text-white"
                            />
                            <span v-else>Reject</span>
                        </button>
                    </div>

                    <button
                        @click="close"
                        class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-sm ml-auto"
                    >
                        Close
                    </button>
                </div>
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
