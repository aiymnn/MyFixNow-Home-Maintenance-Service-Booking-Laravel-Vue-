<script setup>
import { ref, onMounted, watch } from "vue";

const model = defineModel({
    type: File,
    required: false,
});

const input = ref(null);
const previewUrl = ref(null);

onMounted(() => {
    if (input.value?.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

// Watch for file change to generate preview
watch(model, (file) => {
    if (file instanceof File) {
        previewUrl.value = URL.createObjectURL(file);
    } else {
        previewUrl.value = null;
    }
});

const updateFile = (event) => {
    const file = event.target.files[0];
    model.value = file || null;
};

defineExpose({
    focus: () => input.value?.focus(),
});
</script>

<template>
    <div class="flex flex-col items-center space-y-2">
        <!-- Avatar Circle Preview -->
        <label
            for="avatar-upload"
            class="relative w-24 h-24 rounded-full bg-gray-200 cursor-pointer overflow-hidden ring-1 ring-gray-300 hover:ring-indigo-500 transition duration-200"
        >
            <img
                v-if="previewUrl"
                :src="previewUrl"
                alt="Avatar Preview"
                class="object-cover w-full h-full"
            />
            <div
                v-else
                class="w-full h-full flex items-center justify-center text-gray-400"
            >
                Upload
            </div>
            <input
                id="avatar-upload"
                type="file"
                accept="image/*"
                class="hidden"
                ref="input"
                @change="updateFile"
            />
        </label>
    </div>
</template>
