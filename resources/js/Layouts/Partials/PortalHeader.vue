<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

const showingNav = ref(false);
const page = usePage();
const user = computed(() => page.props.auth.user);
const pendingBookings = computed(() => page.props.pending_bookings_count ?? 0);

const avatarUrl = computed(() => {
    if (user.value?.avatar) {
        return `/storage/${user.value.avatar}`;
    }
    return null;
});
</script>

<template>
    <header
        class="fixed top-0 z-50 w-full bg-white/80 backdrop-blur border-b border-gray-200 shadow-sm"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <ApplicationLogo class="w-8 h-8 text-primary" />
                    <span class="text-lg font-semibold text-gray-800"
                        >MyFixNow</span
                    >
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-6">
                    <Link
                        :href="route('portal.home')"
                        :class="
                            route().current('portal.home')
                                ? 'text-blue-600 font-semibold'
                                : 'text-gray-600 hover:text-gray-900'
                        "
                        class="text-sm transition"
                        >Home</Link
                    >

                    <Link
                        :href="route('portal.services')"
                        :class="
                            route().current('portal.services')
                                ? 'text-blue-600 font-semibold'
                                : 'text-gray-600 hover:text-gray-900'
                        "
                        class="text-sm transition"
                        >Services</Link
                    >

                    <Link
                        href="#how-it-works"
                        class="text-sm text-gray-600 hover:text-gray-900 transition"
                        >How It Works</Link
                    >
                    <Link
                        href="#contact"
                        class="text-sm text-gray-600 hover:text-gray-900 transition"
                        >Contact</Link
                    >
                </nav>

                <!-- User / Login -->
                <div class="flex items-center space-x-4">
                    <template v-if="user">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    class="flex items-center space-x-2 text-sm text-gray-700 hover:text-gray-900"
                                >
                                    <img
                                        v-if="avatarUrl"
                                        :src="avatarUrl"
                                        alt="Avatar"
                                        class="w-8 h-8 rounded-full object-cover"
                                    />
                                    <div
                                        v-else
                                        class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-white font-semibold"
                                    >
                                        {{ user.name[0] }}
                                    </div>
                                    <span>{{ user.name }}</span>
                                    <svg
                                        class="w-4 h-4"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.586l3.71-3.355a.75.75 0 011.04 1.08l-4 3.62a.75.75 0 01-1.04 0l-4-3.62a.75.75 0 01.02-1.06z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </template>
                            <template #content>
                                <DropdownLink :href="route('portal.profile')"
                                    >Profile</DropdownLink
                                >
                                <DropdownLink
                                    :href="route('portal.bookings')"
                                    class="flex items-center justify-between"
                                >
                                    <span>Bookings</span>
                                    <span
                                        v-if="pendingBookings > 0"
                                        class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold text-white bg-red-500 rounded-full"
                                    >
                                        {{ pendingBookings }}
                                    </span>
                                </DropdownLink>
                                <DropdownLink :href="route('portal.completed')"
                                    >Completed</DropdownLink
                                >
                                <DropdownLink
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    >Logout</DropdownLink
                                >
                            </template>
                        </Dropdown>
                    </template>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-sm text-gray-600 hover:text-gray-900 transition"
                            >Login</Link
                        >
                    </template>

                    <!-- Mobile Hamburger -->
                    <button
                        @click="showingNav = !showingNav"
                        class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-900 focus:outline-none transition"
                    >
                        <svg
                            v-if="!showingNav"
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                        <svg
                            v-else
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div
            v-if="showingNav"
            class="md:hidden bg-white border-t border-b transition-all duration-300"
        >
            <div class="px-4 py-3 space-y-2">
                <Link
                    :href="route('portal.home')"
                    class="block text-sm text-gray-700 hover:text-gray-900 transition"
                    >Home</Link
                >
                <Link
                    :href="route('portal.services')"
                    class="block text-sm text-gray-700 hover:text-gray-900 transition"
                    >Services</Link
                >
                <Link
                    href="#how-it-works"
                    class="block text-sm text-gray-700 hover:text-gray-900 transition"
                    >How It Works</Link
                >
                <Link
                    href="#contact"
                    class="block text-sm text-gray-700 hover:text-gray-900 transition"
                    >Contact</Link
                >

                <template v-if="user">
                    <div class="flex items-center space-x-2 mt-2">
                        <img
                            v-if="avatarUrl"
                            :src="avatarUrl"
                            alt="Avatar"
                            class="w-8 h-8 rounded-full object-cover"
                        />
                        <div
                            v-else
                            class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-white font-semibold"
                        >
                            {{ user.name[0] }}
                        </div>
                        <span class="text-sm text-gray-800">{{
                            user.name
                        }}</span>
                    </div>

                    <Link
                        :href="route('portal.profile')"
                        class="block text-sm text-gray-700 hover:text-gray-900 transition"
                        >Profile</Link
                    >
                    <div class="flex items-center justify-between">
                        <Link
                            :href="route('portal.bookings')"
                            class="block text-sm text-gray-700 hover:text-gray-900 transition"
                            >Bookings</Link
                        >
                        <span
                            v-if="pendingBookings > 0"
                            class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold text-white bg-red-500 rounded-full"
                        >
                            {{ pendingBookings }}
                        </span>
                    </div>
                    <Link
                        :href="route('portal.completed')"
                        class="block text-sm text-gray-700 hover:text-gray-900 transition"
                        >Completed</Link
                    >
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="block text-sm text-red-600 hover:text-red-700 transition"
                        >Logout</Link
                    >
                </template>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="block text-sm text-gray-700 hover:text-gray-900 transition"
                        >Login</Link
                    >
                </template>
            </div>
        </div>
    </header>
</template>

<style scoped>
header {
    transition: all 0.3s ease;
}
</style>
