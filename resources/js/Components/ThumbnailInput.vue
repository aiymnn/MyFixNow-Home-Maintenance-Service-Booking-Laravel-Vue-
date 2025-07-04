<script setup>
import { ref, onMounted, watch } from "vue";

const model = defineModel({
    type: [File, String],
    required: false,
});

const props = defineProps({
    initialUrl: {
        type: String,
        default: "",
    },
});

const input = ref(null);
const previewUrl = ref(props.initialUrl);

onMounted(() => {
    if (input.value?.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

watch(model, (file) => {
    if (file instanceof File) {
        previewUrl.value = URL.createObjectURL(file);
    } else {
        previewUrl.value = props.initialUrl;
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
        <!-- Thumbnail Rectangle Preview -->
        <label
            for="thumbnail-upload"
            style="height: 500px"
            class="relative w-full min-w-xs rounded-md bg-gray-200 cursor-pointer overflow-hidden ring-1 ring-gray-300 hover:ring-indigo-500 transition duration-200"
        >
            <img
                v-if="previewUrl"
                :src="previewUrl"
                alt="Thumbnail Preview"
                class="object-full w-full h-full"
            />
            <div
                v-else
                class="w-full h-full flex items-center justify-center text-gray-400"
            >
                Upload Thumbnail
            </div>
            <input
                id="thumbnail-upload"
                type="file"
                accept="image/*"
                class="hidden"
                ref="input"
                @change="updateFile"
            />
        </label>
    </div>
</template>
