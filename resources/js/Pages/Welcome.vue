<script setup>
import { Head, Link } from "@inertiajs/vue3";
import PortalLayout from "@/Layouts/PortalLayout.vue";
</script>

<template>
    <PortalLayout>
        <Head title="Home - MyFixNow" />

        <!-- Hero Section -->
        <section
            class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-blue-100 py-20 text-center flex flex-col lg:flex-row items-center justify-center gap-12 px-4"
        >
            <!-- Text -->
            <div class="max-w-xl text-center lg:text-start">
                <h1
                    class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-blue-800 leading-tight animate-fade-in-up"
                >
                    Welcome to <span class="text-blue-600">MyFixNow</span>
                </h1>
                <p
                    class="text-lg sm:text-xl text-blue-700 mt-4 max-w-2xl mx-auto lg:mx-0 animate-fade-in-up delay-200"
                >
                    Book your home maintenance easily with trusted service
                    providers. Fast, reliable, and convenient for your busy
                    schedule.
                </p>
                <div
                    class="mt-8 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start animate-fade-in-up delay-300"
                >
                    <Link
                        :href="route('portal.services')"
                        class="rounded-lg bg-blue-600 text-white px-6 py-3 text-sm font-semibold hover:bg-blue-700 transition duration-300 transform hover:scale-105 shadow-lg"
                    >
                        Book a Service
                    </Link>
                    <Link
                        :href="route('portal.services')"
                        class="rounded-lg border border-blue-600 text-blue-600 px-6 py-3 text-sm font-semibold hover:bg-blue-50 transition duration-300 transform hover:scale-105 shadow"
                    >
                        How It Works
                    </Link>
                </div>
            </div>

            <!-- Layered Images with animations -->
            <div class="relative w-full max-w-md h-[400px] hidden lg:block">
                <img
                    src="https://i.pinimg.com/736x/f0/2a/2d/f02a2df70f51e1419d9cd86d93ab8477.jpg"
                    class="absolute top-0 left-0 w-60 rounded-xl shadow-xl transform rotate-[-6deg] opacity-0 animate-slide-in-top"
                    alt="Hero 1"
                />
                <img
                    src="https://i.pinimg.com/736x/93/5b/94/935b946385c8d4a87cb76dcf3ea1b001.jpg"
                    class="absolute top-10 left-24 w-60 rounded-xl shadow-xl transform rotate-[4deg] opacity-0 animate-slide-in-left delay-200"
                    alt="Hero 2"
                />
                <img
                    src="https://i.pinimg.com/736x/1d/92/a0/1d92a0fda5c13a18f07d1eb2d057e62f.jpg"
                    class="absolute top-20 left-12 w-60 rounded-xl shadow-xl transform rotate-[-3deg] opacity-0 animate-slide-in-right delay-400"
                    alt="Hero 3"
                />
            </div>
        </section>

        <!-- Service Categories Section -->
        <section class="mt-20 px-4">
            <h2
                class="text-3xl sm:text-4xl font-bold text-center text-blue-800 animate-fade-in-up"
            >
                Our Services
            </h2>
            <p
                class="text-blue-700 text-center mt-3 max-w-xl mx-auto animate-fade-in-up delay-200"
            >
                Explore a variety of home maintenance services tailored to your
                needs.
            </p>
            <div
                class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in-up delay-300"
            >
                <div
                    class="bg-white shadow-lg rounded-xl p-6 text-center hover:scale-105 transform transition duration-300 border-t-4 border-blue-500"
                >
                    <h3 class="text-xl font-semibold text-blue-800">
                        Plumbing
                    </h3>
                    <p class="text-blue-700 mt-2">
                        Reliable plumbing services for all your needs.
                    </p>
                    <Link
                        :href="route('portal.services')"
                        class="mt-4 inline-block text-blue-600 hover:underline"
                        >Learn More</Link
                    >
                </div>
                <div
                    class="bg-white shadow-lg rounded-xl p-6 text-center hover:scale-105 transform transition duration-300 border-t-4 border-green-500"
                >
                    <h3 class="text-xl font-semibold text-blue-800">
                        Cleaning
                    </h3>
                    <p class="text-blue-700 mt-2">
                        Professional cleaning services to keep your home
                        spotless.
                    </p>
                    <Link
                        :href="route('portal.services')"
                        class="mt-4 inline-block text-blue-600 hover:underline"
                        >Learn More</Link
                    >
                </div>
                <div
                    class="bg-white shadow-lg rounded-xl p-6 text-center hover:scale-105 transform transition duration-300 border-t-4 border-yellow-500"
                >
                    <h3 class="text-xl font-semibold text-blue-800">
                        Electrical
                    </h3>
                    <p class="text-blue-700 mt-2">
                        Expert electrical services for your safety and
                        convenience.
                    </p>
                    <Link
                        :href="route('portal.services')"
                        class="mt-4 inline-block text-blue-600 hover:underline"
                        >Learn More</Link
                    >
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="mt-20 bg-blue-50 py-12 px-4">
            <h2
                class="text-3xl sm:text-4xl font-bold text-center text-blue-800 animate-fade-in-up"
            >
                How It Works
            </h2>
            <p
                class="text-blue-700 text-center mt-3 max-w-xl mx-auto animate-fade-in-up delay-200"
            >
                MyFixNow simplifies the process of booking home maintenance
                services in these easy steps:
            </p>
            <div
                class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto animate-fade-in-up delay-300"
            >
                <div
                    class="bg-white shadow-md rounded-xl p-6 text-center hover:scale-105 transform transition duration-300"
                >
                    <h3 class="text-lg font-semibold text-blue-800">
                        1. Browse Services
                    </h3>
                    <p class="text-blue-700 mt-2">
                        Explore various services available in your area.
                    </p>
                </div>
                <div
                    class="bg-white shadow-md rounded-xl p-6 text-center hover:scale-105 transform transition duration-300"
                >
                    <h3 class="text-lg font-semibold text-blue-800">
                        2. Book a Service
                    </h3>
                    <p class="text-blue-700 mt-2">
                        Select a service, choose a provider, and book your
                        appointment.
                    </p>
                </div>
                <div
                    class="bg-white shadow-md rounded-xl p-6 text-center hover:scale-105 transform transition duration-300"
                >
                    <h3 class="text-lg font-semibold text-blue-800">
                        3. Enjoy Your Service
                    </h3>
                    <p class="text-blue-700 mt-2">
                        Sit back and relax while our trusted providers take care
                        of your needs.
                    </p>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="mt-20 px-4">
            <h2
                class="text-3xl sm:text-4xl font-bold text-center text-blue-800 animate-fade-in-up"
            >
                What Our Customers Say
            </h2>
            <div
                class="mt-8 flex flex-col md:flex-row md:justify-center gap-6 animate-fade-in-up delay-300"
            >
                <div
                    class="bg-white shadow-lg rounded-xl p-6 max-w-md text-center hover:scale-105 transform transition duration-300"
                >
                    <p class="text-blue-700 italic">
                        "MyFixNow made it so easy to find a reliable plumber.
                        Highly recommend!"
                    </p>
                    <p class="mt-4 font-semibold text-blue-800">- Sarah L.</p>
                </div>
                <div
                    class="bg-white shadow-lg rounded-xl p-6 max-w-md text-center hover:scale-105 transform transition duration-300"
                >
                    <p class="text-blue-700 italic">
                        "The cleaning service was top-notch! My home has never
                        looked better."
                    </p>
                    <p class="mt-4 font-semibold text-blue-800">- John D.</p>
                </div>
            </div>
        </section>

        <!-- Call to Action Section -->
        <section
            class="mt-20 bg-blue-600 text-white py-12 text-center animate-fade-in-up"
        >
            <h2 class="text-3xl sm:text-4xl font-bold">
                Ready to Get Started?
            </h2>
            <p class="mt-3 max-w-xl mx-auto">
                Join thousands of satisfied customers and book your service
                today with MyFixNow.
            </p>
            <div class="mt-6">
                <Link
                    :href="route('portal.services')"
                    class="inline-block rounded-lg bg-white text-blue-600 px-6 py-3 text-sm font-semibold hover:bg-gray-100 transition duration-300 transform hover:scale-105 shadow"
                >
                    Book a Service
                </Link>
            </div>
        </section>
    </PortalLayout>
</template>

<style scoped>
@keyframes fade-in-up {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slide-in-top {
    0% {
        opacity: 0;
        transform: translateY(-30px) rotate(-6deg);
    }
    100% {
        opacity: 1;
        transform: translateY(0) rotate(-6deg);
    }
}

@keyframes slide-in-left {
    0% {
        opacity: 0;
        transform: translateX(-30px) rotate(4deg);
    }
    100% {
        opacity: 1;
        transform: translateX(0) rotate(4deg);
    }
}

@keyframes slide-in-right {
    0% {
        opacity: 0;
        transform: translateX(30px) rotate(-3deg);
    }
    100% {
        opacity: 1;
        transform: translateX(0) rotate(-3deg);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease forwards;
}

.animate-slide-in-top {
    animation: slide-in-top 0.8s ease forwards;
}

.animate-slide-in-left {
    animation: slide-in-left 0.8s ease forwards;
}

.animate-slide-in-right {
    animation: slide-in-right 0.8s ease forwards;
}

.delay-200 {
    animation-delay: 0.2s;
}

.delay-400 {
    animation-delay: 0.4s;
}
</style>
