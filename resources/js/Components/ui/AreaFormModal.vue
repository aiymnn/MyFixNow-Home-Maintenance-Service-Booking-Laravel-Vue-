<script setup>
import { ref, watch, nextTick } from "vue";
import { useForm } from "@inertiajs/vue3";
import { LoaderCircle } from "lucide-vue-next";

const props = defineProps({
    modelValue: Boolean,
    editData: Object,
    states: Array,
});

const emit = defineEmits(["update:modelValue", "saved"]);

const form = useForm({
    name: "",
    state_id: "",
});

const show = ref(false);

watch(
    () => props.modelValue,
    (val) => {
        show.value = val;
        if (val) {
            nextTick(() => {
                if (props.editData) {
                    form.name = props.editData.name;
                    form.state_id = props.editData.state_id;
                } else {
                    form.reset();
                }
            });
        }
    },
    { immediate: true }
);

const close = () => {
    emit("update:modelValue", false);
    form.reset();
};

const save = () => {
    const isUpdate = !!props.editData?.id;
    const routeName = isUpdate ? "service-areas.update" : "service-areas.store";
    const routeParam = isUpdate ? props.editData.id : undefined;

    form.submit(isUpdate ? "put" : "post", route(routeName, routeParam), {
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
                class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 space-y-6"
            >
                <h2 class="text-lg font-semibold text-gray-800">
                    {{
                        props.editData
                            ? "Edit Service Area"
                            : "Add New Service Area"
                    }}
                </h2>

                <form @submit.prevent="save" class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Name</label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-300"
                        />
                        <div
                            v-if="form.errors.name"
                            class="text-sm text-red-600 mt-1"
                        >
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <!-- State -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >State</label
                        >
                        <select
                            v-model="form.state_id"
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-300 text-sm"
                        >
                            <option value="">Select a state</option>
                            <option
                                v-for="state in props.states"
                                :key="state.id"
                                :value="state.id"
                            >
                                {{ state.name }}
                            </option>
                        </select>
                        <div
                            v-if="form.errors.state_id"
                            class="text-sm text-red-600 mt-1"
                        >
                            {{ form.errors.state_id }}
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
                            <template v-else>
                                {{ props.editData ? "Update" : "Create" }}
                            </template>
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
