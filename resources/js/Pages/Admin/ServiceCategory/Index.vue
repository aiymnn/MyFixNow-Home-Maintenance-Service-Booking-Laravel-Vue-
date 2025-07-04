<script setup>
import DeleteSweetAlert from "@/Components/ui/DeleteSweetAlert.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { Edit, Trash2, CirclePlus } from "lucide-vue-next";
import { router } from "@inertiajs/vue3";
import FlashMessage from "@/Components/ui/FlashMessage.vue";

const props = defineProps({
    serviceCategories: Object,
});

// const serviceCategories = ref(props.serviceCategories);

// console.log(props.serviceCategories);

const deleteItem = ({ id, route: routeName, method }) => {
    router[method](route(routeName, id), {
        preserveScroll: true,
        onSuccess: () => router.reload(),
        // onSuccess: () => console.log("Deleted successfully."),
        // onError: (errors) => console.error("Delete failed:", errors),
    });
};
</script>

<template>
    <Head title="Service Categories" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Service Categories Management
            </h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <FlashMessage />

                <div class="mb-4 flex justify-end">
                    <Link :href="route('service-categories.create')"
                        ><button
                            class="bg-blue-500 inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-sm text-white hover:bg-none border border-blue-100 hover:shadow-md"
                        >
                            <CirclePlus class="w-4 h-4" />
                            Add New Category
                        </button></Link
                    >
                </div>
                <div class="overflow-x-auto bg-white rounded-2xl shadow-lg">
                    <table
                        class="min-w-full divide-y divide-gray-200 text-gray-700"
                    >
                        <caption class="text-left px-4 py-3 text-gray-500">
                            A list of service categories.
                        </caption>
                        <thead
                            class="bg-gray-100 text-xs font-semibold text-gray-600 uppercase tracking-wide"
                        >
                            <tr>
                                <th class="px-4 py-3 text-left w-10">#</th>
                                <th class="px-4 py-3 text-left w-40">Name</th>
                                <th class="px-4 py-3 text-left">Description</th>
                                <th class="px-4 py-3 text-left">Icon</th>
                                <th class="px-4 py-3 text-left">
                                    Total Services
                                </th>
                                <th class="px-4 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr
                                v-for="(
                                    category, index
                                ) in serviceCategories.data"
                                :key="index"
                                class="hover:bg-gray-50"
                            >
                                <td
                                    class="px-4 py-3 font-medium text-gray-500 text-sm whitespace-nowrap"
                                >
                                    {{ index + serviceCategories.from }}
                                </td>
                                <td
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ category.name }}
                                </td>
                                <td class="px-4 py-3 text-gray-700">
                                    {{ category.description }}
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-700 whitespace-nowrap"
                                >
                                    {{ category.icon ? category.icon : "Icon" }}
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-700 whitespace-nowrap"
                                >
                                    <!-- {{ category.icon ? category.icon : 'Icon' }} -->
                                    4
                                </td>
                                <td
                                    class="px-4 py-3 text-right space-x-2 whitespace-nowrap"
                                >
                                    <!-- Edit Button -->
                                    <Link
                                        :href="
                                            route(
                                                'service-categories.edit',
                                                category.id
                                            )
                                        "
                                        ><button
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-sm text-blue-600 hover:bg-blue-50 border border-blue-100"
                                        >
                                            <Edit class="w-4 h-4" />
                                            Edit
                                        </button></Link
                                    >
                                    <!-- Delete Button -->
                                    <DeleteSweetAlert
                                        :id="category.id"
                                        route-name="service-categories.destroy"
                                        @confirm-delete="deleteItem"
                                    >
                                        <template #icon>
                                            <Trash2 class="w-4 h-4" />
                                        </template>
                                    </DeleteSweetAlert>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex items-center justify-between mt-6">
                    <div>
                        <span class=""
                            >Showing from
                            <strong>{{ serviceCategories.from }}</strong> to
                            <strong>{{ serviceCategories.to }}</strong> of
                            <strong>{{ serviceCategories.total }}</strong></span
                        >
                    </div>
                    <div class="flex flex-wrap gap-1">
                        <template
                            v-for="(link, index) in serviceCategories.links"
                            :key="index"
                        >
                            <a
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    'px-3 py-1.5 shadow-sm border rounded-md',
                                    link.active
                                        ? 'bg-blue-600 text-white font-semibold'
                                        : 'bg-white text-gray-800 hover:bg-blue-600 hover:text-white hover:shadow-lg',
                                ]"
                            />

                            <span
                                v-else
                                v-html="link.label"
                                class="px-3 py-1.5 border rounded-md bg-gray-100 text-gray-400 cursor-default"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
