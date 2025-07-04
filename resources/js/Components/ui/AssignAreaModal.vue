<script setup>
import { ref, computed, watch } from "vue";
import SearchableDropdown from "@/Components/ui/SearchableDropdown.vue";

const emit = defineEmits(["assign-area"]);

const props = defineProps({
    serviceId: [String, Number],
    states: Array,
    serviceAreas: Array,
    isProcessing: Boolean, // ✅ accept it here
});

const isOpen = ref(false);
const selectedStateId = ref(null);
const selectedAreaId = ref(null);

// ✅ Reset area when state changes
watch(selectedStateId, () => {
    selectedAreaId.value = null;
});

const filteredAreas = computed(() =>
    props.serviceAreas.filter((a) => a.state_id === selectedStateId.value)
);

const confirm = () => {
    if (selectedAreaId.value) {
        emit("assign-area", {
            service_id: props.serviceId,
            area_id: selectedAreaId.value,
        });

        // Reset modal state
        isOpen.value = false;
        selectedStateId.value = null;
        selectedAreaId.value = null;
    }
};
</script>

<template>
    <div class="inline-block flex-shrink-0">
        <!-- Open Modal Button -->
        <button
            @click="isOpen = true"
            class="inline-flex items-center gap-1 px-2 py-1.5 rounded-md text-sm text-indigo-600 hover:bg-indigo-50 border border-indigo-100 whitespace-nowrap"
        >
            <slot name="icon" />
            <span>Assign Area</span>
        </button>

        <!-- Modal -->
        <Teleport to="body">
            <Transition name="modal-fade">
                <div
                    v-if="isOpen"
                    class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-0"
                >
                    <div class="absolute inset-0 bg-black/10"></div>

                    <div
                        class="relative bg-white w-full max-w-md mx-auto p-6 rounded-xl shadow-xl text-center"
                    >
                        <h3 class="text-lg font-semibold text-gray-800">
                            Assign Area to Service
                        </h3>
                        <p class="text-sm text-gray-500 mt-2">
                            Search and select the state and area.
                        </p>

                        <div class="mt-4 space-y-4 text-left">
                            <!-- State Dropdown -->
                            <div>
                                <label class="block text-sm text-gray-700 mb-1"
                                    >State</label
                                >
                                <SearchableDropdown
                                    :options="states"
                                    v-model="selectedStateId"
                                    placeholder="Search state..."
                                />
                            </div>

                            <!-- Area Dropdown (Filtered by state) -->
                            <div v-if="selectedStateId">
                                <label class="block text-sm text-gray-700 mb-1"
                                    >Area</label
                                >
                                <SearchableDropdown
                                    :options="filteredAreas"
                                    v-model="selectedAreaId"
                                    placeholder="Search area..."
                                    :key="selectedStateId"
                                />
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 flex justify-center gap-3 flex-wrap">
                            <button
                                @click="isOpen = false"
                                class="px-4 py-1.5 rounded-md bg-gray-100 text-gray-600 hover:bg-gray-200"
                            >
                                Cancel
                            </button>
                            <button
                                @click="confirm"
                                :disabled="isProcessing || !selectedAreaId"
                                class="px-4 py-1.5 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 disabled:opacity-50"
                            >
                                <span v-if="isProcessing">Assigning...</span>
                                <span v-else>Assign</span>
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
