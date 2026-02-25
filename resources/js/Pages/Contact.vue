<template>
    <AppLayout title="Kontakt">
        <div class="max-w-7xl mx-auto w-full">
            <!-- Hero sekce -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    <span class="text-primary-600">Kontaktujte</span> nás
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Máte dotaz nebo potřebujete pomoci? Jsme tu pro vás. Rádi vám odpovíme na všechny vaše otázky.
                </p>
            </div>

            <!-- Hlavní obsah -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <!-- Kontaktní informace -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                           Kontaktní informace
                        </h2>

                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Adresa</h3>
                                    <p class="text-gray-600">Václavské náměstí 1<br>110 00 Praha 1<br>Česká republika</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Telefon</h3>
                                    <a href="tel:+420737994450" class="text-primary-600 hover:text-primary-700 font-medium">
                                        +420 737 994 450
                                    </a>
                                    <p class="text-sm text-gray-500 mt-1">Pondělí - Pátek: 8:00 - 18:00</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Email</h3>
                                    <a href="mailto:info@example.com" class="text-primary-600 hover:text-primary-700 font-medium">
                                        info@example.com
                                    </a>
                                    <p class="text-sm text-gray-500 mt-1">Odpovídáme do 24 hodin</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Otevírací doba -->
                    <div class="bg-primary-50 rounded-xl p-8 border border-primary-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Otevírací doba
                        </h3>
                        <div class="space-y-2 text-gray-700">
                            <div class="flex justify-between">
                                <span class="font-medium">Pondělí - Pátek</span>
                                <span>8:00 - 18:00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Sobota</span>
                                <span>9:00 - 13:00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Neděle</span>
                                <span class="text-gray-400">Zavřeno</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kontaktní formulář -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <span class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </span>
                        Napište nám
                    </h2>

                    <ContactForm />
                </div>
            </div>

            <!-- Mapa -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                        <span class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                        </span>
                        Kde nás najdete
                    </h2>
                </div>
                <div class="relative w-full" style="height: 450px;">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2560.679506425038!2d14.437799315715933!3d50.07553897942516!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x470b948c2b5b5b5b%3A0x5b5b5b5b5b5b5b5b!2sV%C3%A1clavsk%C3%A9%20n%C3%A1m%C4%9Bst%C3%AD%2C%20Praha%201!5e0!3m2!1scs!2scz!4v1234567890123!5m2!1scs!2scz"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        class="absolute inset-0"
                    ></iframe>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import ContactForm from "@/Components/ContactForm.vue";

</script>

<style scoped>
/* Dodatečné styly pokud jsou potřeba */
</style>

