<template>
    <AppLayout title="O naší lékárně">
        <!-- About Section -->
        <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-4">
                        O naší <span class="text-primary-600">lékárně</span>
                    </h1>
                    <p class="text-xl sm:text-2xl text-gray-600 max-w-3xl mx-auto">
                        Vedoucí digitální platforma pro zdravotní péči v České republice
                    </p>
                </div>

                <!-- Four Feature Blocks -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                    <!-- E-Pharmacy -->
                    <div class="bg-gradient-to-br from-primary-50 to-white rounded-2xl p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">E-Lékárna</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Objednejte si léky a zdravotní produkty online a nechte si je doručit domů z licencovaných lékáren.
                        </p>
                    </div>

                    <!-- Online Consultations -->
                    <div class="bg-gradient-to-br from-info/10 to-white rounded-2xl p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Online konzultace</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Konzultujte s kvalifikovanými a registrovanými lékaři online zdarma.
                        </p>
                    </div>

                    <!-- Lab Tests -->
                    <div class="bg-gradient-to-br from-primary-50 to-white rounded-2xl p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Laboratorní testy</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Objednejte si laboratorní testy online pro bezproblémový odběr vzorků doma. Získejte výsledky online.
                        </p>
                    </div>

                    <!-- Authentic Information -->
                    <div class="bg-gradient-to-br from-secondary-50 to-white rounded-2xl p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="w-16 h-16 bg-secondary-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Ověřené informace</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Najděte spolehlivý a zdravotní obsah napsaný kvalifikovanými lékaři a zdravotníky.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How to Order Section -->
        <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Side - Image Collage -->
                    <div class="relative">
                        <div class="relative bg-gradient-to-br from-primary-200 via-secondary-200 to-primary-300 rounded-3xl p-8 overflow-hidden">
                            <!-- Main Image -->
                            <div class="relative z-10 mb-6">
                                <img 
                                    src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=600&h=400&fit=crop" 
                                    alt="Lékařka" 
                                    class="w-full h-64 object-cover rounded-2xl shadow-xl"
                                />
                            </div>
                            <!-- Top Left Image -->
                            <div class="absolute top-4 left-4 w-32 h-32 rounded-xl overflow-hidden shadow-lg z-20">
                                <img 
                                    src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?w=200&h=200&fit=crop" 
                                    alt="Lékař s pacientem" 
                                    class="w-full h-full object-cover"
                                />
                            </div>
                            <!-- Bottom Center Image -->
                            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 w-24 h-24 rounded-xl overflow-hidden shadow-lg z-20">
                                <img 
                                    src="https://images.unsplash.com/photo-1582750433449-648ed127bb54?w=200&h=200&fit=crop" 
                                    alt="Dítě s rouškou" 
                                    class="w-full h-full object-cover"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Steps -->
                    <div>
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-8 text-right">
                            Jak objednat léky? <span class="text-primary-600">Je to jednoduché.</span>
                        </h2>

                        <div class="space-y-6 mb-8">
                            <!-- Step 1 -->
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                    1
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Nahrajte platný recept</h3>
                                    <p class="text-gray-600">
                                        Nahrajte svůj platný recept a my se postaráme o zbytek.
                                    </p>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 bg-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                    2
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Obdržíte potvrzovací hovor</h3>
                                    <p class="text-gray-600">
                                        Naše farmaceutické týmy vás kontaktují pro potvrzení objednávky.
                                    </p>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 bg-secondary-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                    3
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Doručení na vaše dveře</h3>
                                    <p class="text-gray-600">
                                        Vaše léky budou doručeny přímo k vám domů rychle a bezpečně.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Don't have Prescription -->
                        <div class="bg-primary-50 rounded-2xl p-6 border-l-4 border-primary-600">
                            <h3 class="text-xl font-bold text-primary-700 mb-2">Nemáte recept? Nevadí!</h3>
                            <p class="text-gray-700">
                                Jednoduše vyhledejte a přidejte léky NEBO se poraďte s našim lékařem.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Video Section -->
        <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                    <div class="aspect-video bg-gray-900 relative">
                        <img 
                            src="https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=1200&h=675&fit=crop" 
                            alt="Video thumbnail" 
                            class="w-full h-full object-cover opacity-80"
                        />
                        <div class="absolute inset-0 flex items-center justify-center">
                            <button class="w-20 h-20 bg-white/90 hover:bg-white rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-xl">
                                <svg class="w-10 h-10 text-primary-600 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- What We Do Section -->
        <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-12 text-center">
                    Co <span class="text-primary-600">děláme</span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- 01. Creative Products -->
                    <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-shadow duration-300">
                        <div class="text-4xl font-bold text-primary-600 mb-4">01.</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Kvalitní produkty</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Nabízíme široký sortiment certifikovaných léků a zdravotních produktů od předních výrobců. Každý produkt prochází přísnou kontrolou kvality.
                        </p>
                    </div>

                    <!-- 02. Marketing -->
                    <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-shadow duration-300">
                        <div class="text-4xl font-bold text-secondary-600 mb-4">02.</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Odborné poradenství</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Naši zkušení farmaceuti vám poskytnou odborné poradenství a pomohou s výběrem správných produktů pro vaše zdraví a wellness.
                        </p>
                    </div>

                    <!-- 03. Home Essentials -->
                    <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-shadow duration-300">
                        <div class="text-4xl font-bold text-primary-600 mb-4">03.</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Doručení domů</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Rychlé a spolehlivé doručení přímo k vašim dveřím. Nabízíme různé možnosti dopravy, včetně expresního doručení pro urgentní případy.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonial Section -->
        <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 bg-white relative overflow-hidden">
            <!-- Background Map -->
            <div class="absolute inset-0 opacity-5">
                <img 
                    src="https://images.unsplash.com/photo-1524661135-423995f22d0b?w=1200&h=600&fit=crop" 
                    alt="Map background" 
                    class="w-full h-full object-cover"
                />
            </div>

            <div class="max-w-4xl mx-auto relative z-10">
                <div class="text-center">
                    <blockquote class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-8 leading-relaxed">
                        "Velmi spolehlivé, vždy ochotní pomoci, nic není příliš velké úsilí, vždy jdou nad rámec, aby pomohli."
                    </blockquote>
                    <div class="flex flex-col items-center gap-2">
                        <p class="text-xl font-semibold text-gray-800">Jana Nováková</p>
                        <p class="text-gray-500">15. srpna 2024</p>
                    </div>
                    
                    <!-- Pagination Dots -->
                    <div class="flex justify-center gap-2 mt-8">
                        <div class="w-3 h-3 bg-primary-600 rounded-full"></div>
                        <div class="w-3 h-3 bg-primary-200 rounded-full"></div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
</script>
