// PortalBreadCrumbs.vue
<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    links: {
        type: Array,
        required: true,
        // Format: [{ label: 'Home', url: '/' }, { label: 'Services' }]
    },
});
</script>

<template>
    <nav class="text-sm mb-4" aria-label="Breadcrumb">
        <ol class="flex flex-wrap gap-1 text-gray-500">
            <li
                v-for="(link, index) in links"
                :key="index"
                class="flex items-center gap-1"
            >
                <Link
                    v-if="link.url && index !== links.length - 1"
                    :href="link.url"
                    class="hover:text-blue-600 transition"
                >
                    {{ link.label }}
                </Link>
                <span
                    v-else
                    :class="
                        index === links.length - 1
                            ? 'text-gray-800 font-medium'
                            : ''
                    "
                >
                    {{ link.label }}
                </span>
                <span v-if="index !== links.length - 1">/</span>
            </li>
        </ol>
    </nav>
</template>

<style scoped>
nav {
    word-break: break-word;
}
</style>
