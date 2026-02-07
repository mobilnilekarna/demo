<template>
    <AppLayout title="O lékárně">
        <!-- Hero Section - "Pojďme společně růst ve zdraví" with gradient background -->
        <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 bg-gradient-to-br from-primary-100 via-green-50 to-gray-100 rounded-3xl">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
                            Pojďme společně růst <span class="text-primary-600">ve zdraví</span>!
                        </h1>
                        <Link href="/contact" class="btn bg-primary-600 hover:bg-primary-700 text-white text-lg px-8 py-4 rounded-xl hover:shadow-lg transition-all duration-300 inline-block">
                            Kontaktujte nás
                        </Link>
                    </div>
                    <div class="relative">
                        <img src="https://placehold.co/600x500/f3f4f6/6b7280?text=Lékárna+Hero" alt="Lékárna" class="w-full h-full object-cover rounded-2xl shadow-lg">
                    </div>
                </div>
            </div>
        </section>

        <!-- Three Profile Blocks Section -->
        <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Profile 1 -->
                    <div class="text-center">
                        <img src="https://placehold.co/150x150/e6f8f0/00ab59?text=JN" alt="Mgr. Jana Nováková" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Mgr. Jana Nováková</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            S více než 20 lety zkušeností v oboru zajišťuje nejvyšší standard péče o naše zákazníky a jejich zdraví. Odbornost a profesionalita jsou základem její práce.
                        </p>
                    </div>

                    <!-- Profile 2 -->
                    <div class="text-center">
                        <img src="https://placehold.co/150x150/fff0e8/ff4500?text=TS" alt="PharmDr. Tomáš Svoboda" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">PharmDr. Tomáš Svoboda</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Specialista na moderní léčiva a doplňky stravy. Vždy připraven poradit s výběrem správných produktů a odpovědět na všechny vaše dotazy ohledně zdraví.
                        </p>
                    </div>

                    <!-- Profile 3 -->
                    <div class="text-center">
                        <img src="https://placehold.co/150x150/f0fdf4/16a34a?text=PD" alt="Mgr. Petra Dvořáková" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Mgr. Petra Dvořáková</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Expertka na přírodní léčiva a homeopatii. Pomůže vám s výběrem přírodních alternativ pro vaše zdraví a nabídne komplexní přístup k péči o zdraví.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team Grid Section - 4 Square Blocks with title on left -->
        <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">
                    <div class="lg:col-span-1">
                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">
                            Náš <span class="text-primary-600">tým</span>
                        </h2>
                    </div>
                    <div class="lg:col-span-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Team Member 1 -->
                            <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                                <img src="https://placehold.co/400x400/e6f8f0/00ab59?text=JN" alt="Mgr. Jana Nováková" class="w-full aspect-square object-cover">
                                <div class="p-4">
                                    <h3 class="text-lg font-bold text-gray-900 mb-1">Mgr. Jana Nováková</h3>
                                    <p class="text-sm text-gray-600">Hlavní farmaceutka s dlouholetou praxí a odborností v oblasti farmacie a zdravotní péče.</p>
                                </div>
                            </div>

                            <!-- Team Member 2 -->
                            <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                                <img src="https://placehold.co/400x400/fff0e8/ff4500?text=TS" alt="PharmDr. Tomáš Svoboda" class="w-full aspect-square object-cover">
                                <div class="p-4">
                                    <h3 class="text-lg font-bold text-gray-900 mb-1">PharmDr. Tomáš Svoboda</h3>
                                    <p class="text-sm text-gray-600">Farmaceut specializující se na moderní léčiva a farmaceutické poradenství pro klienty.</p>
                                </div>
                            </div>

                            <!-- Team Member 3 -->
                            <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                                <img src="https://placehold.co/400x400/f0fdf4/16a34a?text=PD" alt="Mgr. Petra Dvořáková" class="w-full aspect-square object-cover">
                                <div class="p-4">
                                    <h3 class="text-lg font-bold text-gray-900 mb-1">Mgr. Petra Dvořáková</h3>
                                    <p class="text-sm text-gray-600">Farmaceutka s odborností v oblasti přírodní medicíny, homeopatie a alternativních léčeb.</p>
                                </div>
                            </div>

                            <!-- Team Member 4 -->
                            <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                                <img src="https://placehold.co/400x400/faf5ff/a855f7?text=MP" alt="Bc. Martin Procházka" class="w-full aspect-square object-cover">
                                <div class="p-4">
                                    <h3 class="text-lg font-bold text-gray-900 mb-1">Bc. Martin Procházka</h3>
                                    <p class="text-sm text-gray-600">Lékárnický asistent připraven pomoci s orientací v sortimentu a zodpovědět všechny dotazy.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Info Section - Title and small image on left, text on right -->
        <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">
                    <div class="lg:col-span-2">
                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6">
                            O naší <span class="text-primary-600">lékárně</span>
                        </h2>
                        <img src="https://placehold.co/200x200/f3f4f6/6b7280?text=Lékárna" alt="O lékárně" class="w-48 h-48 rounded-2xl object-cover shadow-md">
                    </div>
                    <div class="lg:col-span-3">
                        <div class="space-y-4 text-lg text-gray-600 leading-relaxed">
                            <p>
                                Lékárna byla založena před více než 15 lety s cílem poskytovat nejvyšší úroveň farmaceutické péče. Postupem času jsme se stali důvěryhodným partnerem pro tisíce zákazníků v celé České republice.
                            </p>
                            <p>
                                Našimi hlavními hodnotami jsou <strong class="text-primary-600">odbornost</strong>, <strong class="text-secondary-600">důvěryhodnost</strong> a <strong class="text-green-600">individuální přístup</strong>. Každý zákazník je pro nás jedinečný a zaslouží si nejlepší péči a poradenství.
                            </p>
                            <p>
                                Nabízíme široký sortiment přes 5000 produktů od předních farmaceutických firem, odborné poradenství, elektronické recepty a online nákupy s rychlým doručením. Naším cílem je být vaším spolehlivým partnerem na cestě ke zdraví.
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
</script>
