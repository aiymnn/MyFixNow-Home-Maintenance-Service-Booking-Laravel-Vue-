<template>
    <footer
        class="mt-10 border-t border-gray-200 bg-white/80 backdrop-blur-sm py-6 text-center text-sm text-gray-500"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            &copy; {{ new Date().getFullYear() }} MyFixNow. All rights reserved.
        </div>
    </footer>
</template>

<script setup>
// Nothing needed yet
</script>

<style scoped>
footer {
    transition: background-color 0.3s ease-in-out;
}
</style>
