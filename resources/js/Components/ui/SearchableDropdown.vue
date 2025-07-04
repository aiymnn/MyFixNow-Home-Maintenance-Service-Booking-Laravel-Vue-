<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from "vue";

const emit = defineEmits(["update:modelValue"]);

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
    modelValue: [String, Number, null],
    label: {
        type: String,
        default: "Select",
    },
    placeholder: {
        type: String,
        default: "Search...",
    },
});

const search = ref("");
const isOpen = ref(false);
const dropdownRef = ref(null);

const filteredOptions = computed(() => {
    const term = search.value.toLowerCase().trim();
    return props.options.filter((opt) => opt.name.toLowerCase().includes(term));
});

const selectOption = (option) => {
    emit("update:modelValue", option.id);
    search.value = option.name;
    isOpen.value = false;
};

watch(
    () => props.modelValue,
    (newVal) => {
        if (newVal === null) {
            search.value = "";
            isOpen.value = false;
        } else {
            const selected = props.options.find((opt) => opt.id === newVal);
            search.value = selected ? selected.name : "";
        }
    },
    { immediate: true }
);

// 📌 Close dropdown when clicking outside
const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>

<template>
    <div class="relative" ref="dropdownRef">
        <input
            type="text"
            v-model="search"
            @focus="isOpen = true"
            :placeholder="placeholder"
            class="w-full px-3 py-2 rounded-md border border-gray-300"
        />

        <div
            v-if="isOpen"
            class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto"
        >
            <ul>
                <li
                    v-for="option in filteredOptions"
                    :key="option.id"
                    @click="selectOption(option)"
                    class="px-3 py-2 hover:bg-indigo-50 cursor-pointer"
                >
                    {{ option.name }}
                </li>
                <li
                    v-if="filteredOptions.length === 0"
                    class="px-3 py-2 text-gray-400"
                >
                    Not found
                </li>
            </ul>
        </div>
    </div>
</template>
