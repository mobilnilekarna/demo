<template>
    <AppLayout title="Landing Pages - Rozcestník">
        <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 bg-gradient-to-br from-primary-50 via-white to-secondary-50">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-4">
                        Landing <span class="text-primary-600">Pages</span>
                    </h1>
                    <p class="text-xl sm:text-2xl text-gray-600 max-w-3xl mx-auto">
                        Rozcestník všech dostupných landing pages
                    </p>
                </div>

                <!-- Landing Pages Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    <div
                        v-for="page in landingPages"
                        :key="page.id"
                        class="group relative bg-white rounded-2xl p-6 lg:p-8 shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border-2 border-transparent hover:border-primary-200"
                    >
                        <!-- Number Badge -->
                        <div class="absolute top-4 right-4 w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            {{ page.number }}
                        </div>

                        <!-- Content -->
                        <div class="pr-16">
                            <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-primary-600 transition-colors">
                                Landing Page {{ page.number }}
                            </h3>
                            <p class="text-gray-600 mb-6 leading-relaxed">
                                {{ page.title }}
                            </p>

                            <!-- Action Button -->
                            <Link
                                :href="page.route"
                                class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                            >
                                <span>Zobrazit</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </Link>
                        </div>

                        <!-- Decorative Element -->
                        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-primary-500 via-secondary-500 to-primary-500 rounded-b-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mt-12 bg-white rounded-2xl p-6 border-l-4 border-primary-600 shadow-md">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900 mb-2">Dynamický rozcestník</h4>
                            <p class="text-gray-600">
                                Tento rozcestník automaticky zobrazuje všechny dostupné landing pages. Když přidáte novou landing page do <code class="bg-gray-100 px-2 py-1 rounded text-sm">LandingController</code>, automaticky se zde zobrazí.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    landingPages: {
        type: Array,
        required: true,
        default: () => [],
    },
});
</script>
