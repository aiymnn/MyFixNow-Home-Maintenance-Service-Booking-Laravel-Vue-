<script setup>
import { ref } from "vue";

const emit = defineEmits(["confirm-delete"]);

const props = defineProps({
    id: [Number, String],
    routeName: String,
    method: {
        type: String,
        default: "delete",
    },
    label: {
        type: String,
        default: "Delete",
    },
});

const isOpen = ref(false);

const confirm = () => {
    emit("confirm-delete", {
        id: props.id,
        route: props.routeName,
        method: props.method,
    });
    isOpen.value = false;
};
</script>

<template>
    <div class="inline-block flex-shrink-0">
        <!-- Trigger Button -->
        <button
            @click="isOpen = true"
            class="inline-flex items-center gap-1 px-2 py-1.5 rounded-md text-sm text-red-600 hover:bg-red-50 border border-red-100 whitespace-nowrap"
        >
            <slot name="icon" />
            <span>{{ label }}</span>
        </button>

        <!-- SweetAlert Modal -->
        <Teleport to="body">
            <Transition name="modal-fade">
                <div
                    v-if="isOpen"
                    class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-0"
                >
                    <!-- Blurred, soft dark backdrop -->
                    <div class="absolute inset-0 bg-black/10"></div>

                    <!-- Modal Content -->
                    <div
                        class="relative bg-white w-full max-w-md mx-auto p-6 rounded-xl shadow-xl text-center"
                    >
                        <h3 class="text-lg font-semibold text-gray-800">
                            Are you sure?
                        </h3>
                        <p class="text-sm text-gray-500 mt-2">
                            You’re about to delete this item. This action cannot
                            be undone.
                        </p>

                        <div class="mt-6 flex justify-center gap-3 flex-wrap">
                            <button
                                @click="isOpen = false"
                                class="px-4 py-1.5 rounded-md bg-gray-100 text-gray-600 hover:bg-gray-200"
                            >
                                Cancel
                            </button>
                            <button
                                @click="confirm"
                                class="px-4 py-1.5 rounded-md bg-red-600 text-white hover:bg-red-700"
                            >
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
    transform: scale(0.95);
}
</style>
