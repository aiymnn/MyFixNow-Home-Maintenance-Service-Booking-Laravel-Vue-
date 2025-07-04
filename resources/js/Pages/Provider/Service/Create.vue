<script setup>
import GalleryInput from "@/Components/GalleryInput.vue";
import ThumbnailInput from "@/Components/ThumbnailInput.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link, router } from "@inertiajs/vue3";
import { LoaderCircle, Undo2 } from "lucide-vue-next";

const form = useForm({
    title: "",
    description: "",
    price: "",
    duration_minutes: "",
    image: "",
    gallery_images: [],
    category_id: "",
});

const props = defineProps({
    serviceCategories: Object,
});

console.log(props.serviceCategories);

const submit = () => {
    // console.log("Form values:", {
    //     name: form.name,
    //     description: form.description,
    //     icon: form.icon,
    // });
    form.post(route("services.store"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="New Service" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Create New Service
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <!-- Back button -->
                <div class="mb-4 flex justify-end">
                    <Link :href="route('services.index')"
                        ><button
                            class="bg-gray-500 inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-sm text-white hover:bg-gray-700 border border-gray-100 hover:shadow-md"
                        >
                            <Undo2 class="w-4 h-4" />
                            Back
                        </button></Link
                    >
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label
                                for="title"
                                class="block text-sm font-medium text-gray-700"
                                >Title</label
                            >
                            <input
                                v-model="form.title"
                                type="text"
                                id="title"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-300"
                            />
                            <div
                                v-if="form.errors.title"
                                class="text-sm text-red-600 mt-1"
                            >
                                {{ form.errors.title }}
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

                        <!-- Price -->
                        <div>
                            <label
                                for="price"
                                class="block text-sm font-medium text-gray-700"
                                >Price (RM)</label
                            >
                            <input
                                v-model="form.price"
                                type="number"
                                id="price"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-300"
                            />
                            <div
                                v-if="form.errors.price"
                                class="text-sm text-red-600 mt-1"
                            >
                                {{ form.errors.price }}
                            </div>
                        </div>

                        <!-- Duration -->
                        <div>
                            <label
                                for="duration_minutes"
                                class="block text-sm font-medium text-gray-700"
                                >Duration (minute)</label
                            >
                            <input
                                v-model="form.duration_minutes"
                                type="number"
                                id="duration_minutes"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-300"
                            />
                            <div
                                v-if="form.errors.duration_minutes"
                                class="text-sm text-red-600 mt-1"
                            >
                                {{ form.errors.duration_minutes }}
                            </div>
                        </div>

                        <!-- Category -->
                        <div>
                            <label
                                for="category_id"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Category
                            </label>
                            <select
                                v-model="form.category_id"
                                id="category_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-300"
                                option
                            >
                                <option disabled value="">
                                    Select a category
                                </option>
                                <option
                                    v-for="category in serviceCategories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                            <div
                                v-if="form.errors.category_id"
                                class="text-sm text-red-600 mt-1"
                            >
                                {{ form.errors.category_id }}
                            </div>
                        </div>

                        <!-- Thumbnail -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Thumbnail Image
                            </label>
                            <ThumbnailInput class="mt-1" v-model="form.image" />
                            <div
                                v-if="form.errors.image"
                                class="text-sm text-red-600 mt-1"
                            >
                                {{ form.errors.image }}
                            </div>
                        </div>

                        <!-- Gallery -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Gallery Images
                            </label>
                            <GalleryInput
                                class="mt-1"
                                v-model="form.gallery_images"
                            />
                            <div
                                v-if="form.errors.gallery_images"
                                class="text-sm text-red-600 mt-1"
                            >
                                {{ form.errors.gallery_images }}
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
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
                                <template v-else> Create </template>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
