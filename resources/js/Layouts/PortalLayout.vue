<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

const showingNavigationDropdown = ref(false);

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
    <div class="min-h-screen flex flex-col bg-gray-50">
        <!-- Portal Header -->
        <nav class="bg-white border-b border-gray-100 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <Link :href="route('portal.home')">
                                <ApplicationLogo
                                    class="block h-9 w-auto text-primary"
                                />
                            </Link>
                            <span class="ml-2 text-lg font-bold text-gray-800"
                                >MyFixNow</span
                            >
                        </div>

                        <!-- Desktop Navigation -->
                        <div
                            class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"
                        >
                            <NavLink
                                :href="route('portal.home')"
                                :active="route().current('portal.home')"
                            >
                                Home
                            </NavLink>
                            <NavLink
                                :href="route('portal.services')"
                                :active="
                                    [
                                        'portal.services',
                                        'portal.services.show',
                                    ].some((r) => route().current(r))
                                "
                            >
                                Services
                            </NavLink>
                            <NavLink href="#how-it-works">
                                How It Works
                            </NavLink>
                            <NavLink href="#contact"> Contact </NavLink>
                        </div>
                    </div>

                    <!-- User Dropdown or Login Button -->
                    <div class="hidden sm:flex sm:items-center">
                        <template v-if="user">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition space-x-2"
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
                                            {{ user?.name?.charAt(0) ?? "?" }}
                                        </div>
                                        <span>{{ user?.name }}</span>
                                        <svg
                                            class="ml-1 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
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
                                    <DropdownLink
                                        :href="route('portal.profile')"
                                        >Profile</DropdownLink
                                    >
                                    <DropdownLink
                                        :href="route('portal.bookings')"
                                    >
                                        <div
                                            class="flex justify-between items-center w-full"
                                        >
                                            <span>Bookings</span>
                                            <span
                                                v-if="pendingBookings > 0"
                                                class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold text-white bg-red-500 rounded-full"
                                            >
                                                {{ pendingBookings }}
                                            </span>
                                        </div>
                                    </DropdownLink>
                                    <!-- <DropdownLink
                                        :href="route('portal.completed')"
                                        >Completed</DropdownLink
                                    > -->
                                    <DropdownLink
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                    >
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </template>
                        <template v-else>
                            <div class="space-x-2">
                                <Link
                                    :href="route('register')"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition text-sm font-medium"
                                >
                                    Register
                                </Link>
                                <Link
                                    :href="route('login')"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition text-sm font-medium"
                                >
                                    Log In
                                </Link>
                            </div>
                        </template>
                    </div>

                    <!-- Hamburger for Mobile -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button
                            @click="
                                showingNavigationDropdown =
                                    !showingNavigationDropdown
                            "
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none"
                        >
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex':
                                            !showingNavigationDropdown,
                                    }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex':
                                            showingNavigationDropdown,
                                    }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div
                :class="{
                    block: showingNavigationDropdown,
                    hidden: !showingNavigationDropdown,
                }"
                class="sm:hidden"
            >
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink
                        :href="route('portal.home')"
                        :active="route().current('portal.home')"
                    >
                        Home
                    </ResponsiveNavLink>
                    <ResponsiveNavLink
                        :href="route('portal.services')"
                        :active="
                            ['portal.services', 'portal.services.show'].some(
                                (r) => route().current(r)
                            )
                        "
                    >
                        Services
                    </ResponsiveNavLink>
                    <ResponsiveNavLink href="#how-it-works">
                        How It Works
                    </ResponsiveNavLink>
                    <ResponsiveNavLink href="#contact">
                        Contact
                    </ResponsiveNavLink>
                </div>

                <div class="border-t border-gray-200 pt-4 pb-1">
                    <div v-if="user" class="px-4 flex items-center space-x-3">
                        <img
                            v-if="avatarUrl"
                            :src="avatarUrl"
                            alt="Avatar"
                            class="w-9 h-9 rounded-full object-cover"
                        />
                        <div
                            v-else
                            class="w-9 h-9 rounded-full bg-gray-300 flex items-center justify-center text-white font-semibold"
                        >
                            {{ user?.name?.charAt(0) ?? "?" }}
                        </div>
                        <div>
                            <div class="text-base font-medium text-gray-800">
                                {{ user?.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ user?.email }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <template v-if="user">
                            <ResponsiveNavLink :href="route('portal.profile')"
                                >Profile</ResponsiveNavLink
                            >
                            <ResponsiveNavLink :href="route('portal.bookings')"
                                >Bookings</ResponsiveNavLink
                            >
                            <!-- <ResponsiveNavLink :href="route('portal.completed')"
                                >Completed</ResponsiveNavLink
                            > -->
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </template>
                        <template v-else>
                            <ResponsiveNavLink
                                :href="route('register')"
                                class="bg-blue-600 hover:bg-blue-700 text-white rounded text-center font-medium"
                            >
                                Register
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('login')"
                                class="bg-blue-600 hover:bg-blue-700 text-white rounded text-center font-medium"
                            >
                                Log In
                            </ResponsiveNavLink>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading (optional) -->
        <header class="bg-white shadow" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 w-full py-6">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-50 border-t border-gray-200 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center sm:text-left"
                >
                    <!-- Brand + About -->
                    <div>
                        <div
                            class="flex items-center justify-center sm:justify-start space-x-2 mb-3"
                        >
                            <ApplicationLogo class="w-12 h-12" />
                            <span class="text-lg font-semibold text-gray-800"
                                >MyFixNow</span
                            >
                        </div>
                        <p
                            class="text-gray-600 text-sm max-w-xs mx-auto sm:mx-0"
                        >
                            Simplifying your home maintenance with reliable and
                            professional services.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-gray-800 font-semibold mb-3">
                            Quick Links
                        </h3>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <Link
                                    href="/#how-it-works"
                                    class="text-gray-600 hover:text-blue-600 transition"
                                >
                                    How It Works
                                </Link>
                            </li>
                            <li>
                                <Link
                                    :href="route('portal.services')"
                                    class="text-gray-600 hover:text-blue-600 transition"
                                >
                                    Services
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/#contact"
                                    class="text-gray-600 hover:text-blue-600 transition"
                                >
                                    Contact Us
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/#faq"
                                    class="text-gray-600 hover:text-blue-600 transition"
                                >
                                    FAQ
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <!-- Legal & Social -->
                    <div>
                        <h3 class="text-gray-800 font-semibold mb-3">Legal</h3>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <Link
                                    href="/privacy-policy"
                                    class="text-gray-600 hover:text-blue-600 transition"
                                >
                                    Privacy Policy
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/terms-of-service"
                                    class="text-gray-600 hover:text-blue-600 transition"
                                >
                                    Terms of Service
                                </Link>
                            </li>
                        </ul>
                        <div
                            class="flex justify-center sm:justify-start mt-4 space-x-4"
                        >
                            <a
                                href="#"
                                class="text-gray-500 hover:text-blue-600 transition"
                            >
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a
                                href="#"
                                class="text-gray-500 hover:text-blue-600 transition"
                            >
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a
                                href="#"
                                class="text-gray-500 hover:text-blue-600 transition"
                            >
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a
                                href="#"
                                class="text-gray-500 hover:text-blue-600 transition"
                            >
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div
                    class="border-t border-gray-200 mt-8 pt-4 text-center text-xs text-gray-500"
                >
                    &copy; {{ new Date().getFullYear() }} MyFixNow. All rights
                    reserved.
                </div>
            </div>
        </footer>
    </div>
</template>
