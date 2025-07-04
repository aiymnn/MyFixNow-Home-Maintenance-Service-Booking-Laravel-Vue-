<script setup>
import { onMounted, ref, watch } from "vue";
import { Plus, Trash2 } from "lucide-vue-next";

const model = defineModel({ type: Array, default: () => [] });

const props = defineProps({
    initialUrls: { type: Array, default: () => [] }, // [{ id, image_path }]
});

const emit = defineEmits(["remove-existed"]);

const input = ref(null);
const previews = ref([]);

onMounted(() => {
    previews.value = [...props.initialUrls];
});

const updateFiles = (event) => {
    const files = Array.from(event.target.files);
    model.value.push(...files);
    previews.value.push(
        ...files.map((file) => ({
            type: "new",
            file,
            url: URL.createObjectURL(file),
        }))
    );
};

const removeImage = (index) => {
    const removed = previews.value[index];

    // Check if it's an existing image from DB
    if (removed.id) {
        emit("remove-existed", removed.id); // Send to parent for deletion
    } else if (removed.type === "new") {
        const fileIndex = model.value.indexOf(removed.file);
        if (fileIndex !== -1) model.value.splice(fileIndex, 1);
    }

    previews.value.splice(index, 1);
};

watch(model, (val) => {
    if (!val || val.length === 0) {
        previews.value = [];
    }
});
</script>

<template>
    <div class="space-y-2">
        <div class="flex flex-wrap gap-3">
            <!-- Image Preview -->
            <div
                v-for="(item, index) in previews"
                :key="item.id ?? item.url ?? index"
                class="flex flex-col items-center w-24"
            >
                <div
                    class="relative w-24 h-24 rounded-md overflow-hidden border bg-white shadow-sm hover:ring-2 hover:ring-indigo-500 transition"
                >
                    <img
                        :src="item.url ?? item.image_path"
                        alt="Preview"
                        class="w-full h-full object-cover"
                    />
                </div>
                <!-- Remove Button -->
                <button
                    type="button"
                    @click="removeImage(index)"
                    class="mt-1 inline-flex items-center gap-1 px-2 py-0.5 text-xs text-red-600 bg-red-50 hover:bg-red-100 rounded-md shadow-sm transition"
                >
                    <Trash2 class="w-3.5 h-3.5" />
                </button>
            </div>

            <!-- Add New Button -->
            <label
                for="gallery-upload"
                class="w-24 h-24 rounded-md bg-gray-100 border border-dashed border-gray-300 flex items-center justify-center cursor-pointer hover:ring-2 hover:ring-indigo-500 transition relative"
            >
                <div
                    class="bg-white rounded-full p-2 shadow hover:scale-110 transition"
                >
                    <Plus class="w-5 h-5 text-gray-500" />
                </div>
                <input
                    id="gallery-upload"
                    type="file"
                    multiple
                    accept="image/*"
                    class="hidden"
                    ref="input"
                    @change="updateFiles"
                />
            </label>
        </div>
    </div>
</template>
