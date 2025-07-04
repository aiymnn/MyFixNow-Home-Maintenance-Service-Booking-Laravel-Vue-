<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link, router } from "@inertiajs/vue3";
import { LoaderCircle } from "lucide-vue-next";

const props = defineProps({
    serviceCategory: Object,
});

const serviceCategory = props.serviceCategory;

const form = useForm({
    name: serviceCategory.name,
    description: serviceCategory.description,
    icon: serviceCategory.icon,
});

const update = () => {
    // console.log("Form values:", {
    //     name: form.name,
    //     description: form.description,
    //     icon: form.icon,
    // });
    form.put(route("service-categories.update", serviceCategory.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="New Service Category" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Create New Service Category
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <form @submit.prevent="update" class="space-y-6">
                        <!-- Name -->
                        <div>
                            <label
                                for="name"
                                class="block text-sm font-medium text-gray-700"
                                >Name</label
                            >
                            <input
                                v-model="form.name"
                                type="text"
                                id="name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-300"
                            />
                            <div
                                v-if="form.errors.name"
                                class="text-sm text-red-600 mt-1"
                            >
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label
                                for="description"
                                class="block text-sm font-medium text-gray-700"
                                >Description</label
                            >
                            <textarea
                                v-model="form.description"
                                id="description"
                                rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-300"
                            ></textarea>
                            <div
                                v-if="form.errors.description"
                                class="text-sm text-red-600 mt-1"
                            >
                                {{ form.errors.description }}
                            </div>
                        </div>

                        <!-- Icon -->
                        <div>
                            <label
                                for="icon"
                                class="block text-sm font-medium text-gray-700"
                                >Icon (optional)</label
                            >
                            <input
                                v-model="form.icon"
                                type="text"
                                id="icon"
                                placeholder="e.g. fa-solid fa-hammer"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-300"
                            />
                            <div
                                v-if="form.errors.icon"
                                class="text-sm text-red-600 mt-1"
                            >
                                {{ form.errors.icon }}
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex justify-center items-center px-4 py-1.5 bg-blue-600 border border-transparent rounded-md text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:opacity-50 min-w-[100px]"
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
        </div>
    </AuthenticatedLayout>
</template>
