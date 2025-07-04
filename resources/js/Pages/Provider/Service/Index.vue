<script setup>
import DeleteSweetAlert from "@/Components/ui/DeleteSweetAlert.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { Edit, Trash2, CirclePlus, Eye } from "lucide-vue-next";
import { router } from "@inertiajs/vue3";
import FlashMessage from "@/Components/ui/FlashMessage.vue";

const props = defineProps({
    services: Object,
});

// const services = ref(props.services);

// console.log(props.services);

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
    <Head title="Services" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Services Management
            </h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <FlashMessage />

                <div class="mb-4 flex justify-end">
                    <Link :href="route('services.create')"
                        ><button
                            class="bg-blue-500 inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-sm text-white hover:bg-none border border-blue-100 hover:shadow-md"
                        >
                            <CirclePlus class="w-4 h-4" />
                            Add New Service
                        </button></Link
                    >
                </div>
                <div class="overflow-x-auto bg-white rounded-2xl shadow-lg">
                    <table
                        class="min-w-full divide-y divide-gray-200 text-gray-700"
                    >
                        <caption class="text-left px-4 py-3 text-gray-500">
                            A list of services.
                        </caption>
                        <thead
                            class="bg-gray-100 text-xs font-semibold text-gray-600 uppercase tracking-wide"
                        >
                            <tr>
                                <th class="px-4 py-3 text-left w-10">#</th>
                                <th class="px-4 py-3 text-left w-40">Title</th>
                                <th class="px-4 py-3 text-left">Price</th>
                                <th class="px-4 py-3 text-left">Duration</th>
                                <th class="px-4 py-3 text-left">Approval</th>
                                <th class="px-4 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr
                                v-for="(service, index) in services.data"
                                :key="index"
                                class="hover:bg-gray-50"
                            >
                                <td
                                    class="px-4 py-3 font-medium text-gray-500 text-sm whitespace-nowrap"
                                >
                                    {{ index + services.from }}
                                </td>
                                <td
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ service.title }}
                                </td>
                                <td class="px-4 py-3 text-gray-700">
                                    {{ Number(service.price).toFixed(2) }}
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-700 whitespace-nowrap"
                                >
                                    {{ service.duration_minutes }}
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-700 whitespace-nowrap"
                                >
                                    {{
                                        service.is_approved
                                            ? "Approved"
                                            : "In review"
                                    }}
                                </td>
                                <td
                                    class="px-4 py-3 text-right space-x-2 whitespace-nowrap"
                                >
                                    <!-- View Button -->
                                    <Link
                                        :href="
                                            route('services.show', service.id)
                                        "
                                        ><button
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-sm text-green-600 hover:bg-green-50 border border-green-100"
                                        >
                                            <Eye class="w-4 h-4" />
                                            View
                                        </button></Link
                                    >
                                    <!-- Edit Button -->
                                    <Link
                                        :href="
                                            route('services.edit', service.id)
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
                                        :id="service.id"
                                        route-name="services.destroy"
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
                            <strong>{{ services.from }}</strong> to
                            <strong>{{ services.to }}</strong> of
                            <strong>{{ services.total }}</strong></span
                        >
                    </div>
                    <div class="flex flex-wrap gap-1">
                        <template
                            v-for="(link, index) in services.links"
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
