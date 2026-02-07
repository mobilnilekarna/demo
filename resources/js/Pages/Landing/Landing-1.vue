<template>
    <AppLayout title="Kreditový & Bonusový systém">
        <!-- Hero Section -->
        <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 bg-gradient-to-br from-primary-50 via-white to-secondary-50">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-primary-100 rounded-full mb-6 overflow-hidden">
                        <img src="https://placehold.co/80x80/00ab59/ffffff?text=💳" alt="Kreditový systém" class="w-full h-full object-cover rounded-full">
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
                        <span class="text-primary-600">Kreditový</span> & <span class="text-secondary-600">Bonusový</span> systém
                    </h1>
                    <p class="text-xl sm:text-2xl text-gray-600 max-w-3xl mx-auto mb-8">
                        Každý nákup se počítá! Získejte kredity a bonusy za každý nákup a použijte je na další nákupy.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <Link href="/products" class="btn btn-primary text-lg px-8 py-4 rounded-xl hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            Začít nakupovat
                        </Link>
                        <Link href="#jak-to-funguje" class="btn bg-white border-2 border-primary-600 text-primary-600 hover:bg-primary-50 text-lg px-8 py-4 rounded-xl transition-all duration-300">
                            Jak to funguje?
                        </Link>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                    <div class="bg-white rounded-2xl p-6 shadow-lg text-center">
                        <div class="text-3xl font-bold text-primary-600 mb-2">5%</div>
                        <div class="text-gray-600">z každého nákupu</div>
                        <div class="text-sm text-gray-500 mt-2">zpět na kreditový účet</div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-lg text-center">
                        <div class="text-3xl font-bold text-secondary-600 mb-2">500 Kč</div>
                        <div class="text-gray-600">při registraci</div>
                        <div class="text-sm text-gray-500 mt-2">bonus kredity zdarma</div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-lg text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2">Neomezeně</div>
                        <div class="text-gray-600">platnost kreditů</div>
                        <div class="text-sm text-gray-500 mt-2">nikdy neexspirují</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How it works Section -->
        <section id="jak-to-funguje" class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                        Jak <span class="text-primary-600">to funguje</span>?
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Jednoduše a přehledně - každý nákup vám přinese bonusy, které můžete využít na další nákupy
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Step 1 -->
                    <div class="relative">
                        <div class="bg-primary-50 rounded-2xl p-6 h-full">
                            <div class="flex items-center justify-center w-16 h-16 bg-primary-600 text-white rounded-full text-2xl font-bold mb-4">
                                1
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Registrace</h3>
                            <p class="text-gray-600">
                                Zaregistrujte se a získejte <strong class="text-primary-600">500 Kč bonus kreditů</strong> zdarma na svůj účet
                            </p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative">
                        <div class="bg-secondary-50 rounded-2xl p-6 h-full">
                            <div class="flex items-center justify-center w-16 h-16 bg-secondary-600 text-white rounded-full text-2xl font-bold mb-4">
                                2
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Nakupujte</h3>
                            <p class="text-gray-600">
                                Nakupujte produkty z naší nabídky a za každou korunu získáte <strong class="text-secondary-600">5% zpět</strong> na kreditový účet
                            </p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative">
                        <div class="bg-green-50 rounded-2xl p-6 h-full">
                            <div class="flex items-center justify-center w-16 h-16 bg-green-600 text-white rounded-full text-2xl font-bold mb-4">
                                3
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Hromadění kreditů</h3>
                            <p class="text-gray-600">
                                Vaše kredity se automaticky přičítají na váš účet a <strong class="text-green-600">nikdy neexspirují</strong>
                            </p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative">
                        <div class="bg-purple-50 rounded-2xl p-6 h-full">
                            <div class="flex items-center justify-center w-16 h-16 bg-purple-600 text-white rounded-full text-2xl font-bold mb-4">
                                4
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Použití kreditů</h3>
                            <p class="text-gray-600">
                                Při příštím nákupu použijte nahromaděné kredity a <strong class="text-purple-600">ušetřete</strong> na každém nákupu
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefits Section -->
        <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                        Proč používat <span class="text-primary-600">kreditový systém</span>?
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Benefit 1 -->
                    <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-center w-14 h-14 bg-primary-100 rounded-xl mb-4">
                            <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Automatické přičítání</h3>
                        <p class="text-gray-600">
                            Kredity se vám automaticky přičítají na účet po každém nákupu. Nemusíte nic řešit, vše probíhá automaticky.
                        </p>
                    </div>

                    <!-- Benefit 2 -->
                    <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-center w-14 h-14 bg-green-100 rounded-xl mb-4">
                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Neomezená platnost</h3>
                        <p class="text-gray-600">
                            Vaše kredity nikdy neexspirují. Můžete je používat kdykoliv budete chtít, bez časového omezení.
                        </p>
                    </div>

                    <!-- Benefit 3 -->
                    <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-center w-14 h-14 bg-secondary-100 rounded-xl mb-4">
                            <svg class="w-7 h-7 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Flexibilní použití</h3>
                        <p class="text-gray-600">
                            Použijte kredity částečně nebo celé - rozhodnutí je jen na vás. Můžete kombinovat kredity s běžnou platbou.
                        </p>
                    </div>

                    <!-- Benefit 4 -->
                    <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-center w-14 h-14 bg-blue-100 rounded-xl mb-4">
                            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Průhledná historie</h3>
                        <p class="text-gray-600">
                            Vždy vidíte, kolik kreditů máte k dispozici a jak jste je získali. Kompletní přehled všech transakcí.
                        </p>
                    </div>

                    <!-- Benefit 5 -->
                    <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-center w-14 h-14 bg-yellow-100 rounded-xl mb-4">
                            <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Bonusové akce</h3>
                        <p class="text-gray-600">
                            Pravidelně pořádáme speciální akce, kdy můžete získat dvojnásobné nebo trojnásobné kredity na vybrané produkty.
                        </p>
                    </div>

                    <!-- Benefit 6 -->
                    <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-center w-14 h-14 bg-purple-100 rounded-xl mb-4">
                            <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Bezpečné a jednoduché</h3>
                        <p class="text-gray-600">
                            Systém je plně zabezpečený a snadno použitelný. Vše je propojeno s vaším účtem, takže není potřeba nic navíc.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Example Calculation Section -->
        <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-10">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                        Příklad <span class="text-primary-600">výpočtu</span>
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Podívejte se, jak snadno můžete získat kredity na další nákupy
                    </p>
                </div>

                <div class="bg-gradient-to-br from-primary-50 to-secondary-50 rounded-3xl p-8 sm:p-12 shadow-xl">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                        <div>
                            <img src="https://placehold.co/600x400/e6f8f0/00ab59?text=Nákupy" alt="Příklad nákupů" class="w-full h-64 object-cover rounded-2xl mb-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Vaše nákupy</h3>
                            <div class="space-y-4">
                                <div class="bg-white rounded-xl p-4 flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-gray-900">První nákup</div>
                                        <div class="text-sm text-gray-500">1 000 Kč</div>
                                    </div>
                                    <div class="text-primary-600 font-bold">+50 Kč kreditů</div>
                                </div>
                                <div class="bg-white rounded-xl p-4 flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-gray-900">Druhý nákup</div>
                                        <div class="text-sm text-gray-500">2 500 Kč</div>
                                    </div>
                                    <div class="text-primary-600 font-bold">+125 Kč kreditů</div>
                                </div>
                                <div class="bg-white rounded-xl p-4 flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-gray-900">Třetí nákup</div>
                                        <div class="text-sm text-gray-500">1 500 Kč</div>
                                    </div>
                                    <div class="text-primary-600 font-bold">+75 Kč kreditů</div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-2xl p-8 text-center">
                            <div class="text-sm text-gray-500 mb-2">Celkem nasbírané kredity</div>
                            <div class="text-5xl font-bold text-primary-600 mb-4">750 Kč</div>
                            <div class="text-gray-600 mb-6">Plus 500 Kč bonus při registraci</div>
                            <div class="border-t-2 border-gray-200 pt-6">
                                <div class="text-3xl font-bold text-secondary-600 mb-2">1 250 Kč</div>
                                <div class="text-gray-500">celkem k dispozici</div>
                            </div>
                            <div class="mt-6 pt-6 border-t-2 border-gray-200">
                                <div class="text-sm text-gray-500 mb-2">To je úspora na příští nákup až</div>
                                <div class="text-2xl font-bold text-green-600">1 250 Kč</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16 bg-gray-50">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-10">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                        Často kladené <span class="text-primary-600">otázky</span>
                    </h2>
                </div>

                <div class="space-y-4">
                    <div class="bg-white rounded-xl p-6 shadow-md">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Jak získám kredity?</h3>
                        <p class="text-gray-600">
                            Kredity získáváte automaticky při každém nákupu. Z každé částky získáte 5% zpět na váš kreditový účet. Navíc získáte 500 Kč bonus kreditů při registraci.
                        </p>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-md">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Kdy exspirují kredity?</h3>
                        <p class="text-gray-600">
                            Kredity nikdy neexspirují! Můžete je používat kdykoliv budete chtít, bez časového omezení. Vaše kredity zůstávají na vašem účtu dokud je nepoužijete.
                        </p>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-md">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Jak použiji kredity při nákupu?</h3>
                        <p class="text-gray-600">
                            Při procesu placení uvidíte možnost použít dostupné kredity. Můžete použít všechny kredity najednou nebo jen část - rozhodnutí je jen na vás. Zbytek můžete zaplatit běžným způsobem.
                        </p>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-md">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Kde vidím své kredity?</h3>
                        <p class="text-gray-600">
                            Aktuální stav kreditů a kompletní historii můžete vidět v sekci "Můj účet" → "Kreditový účet". Tam uvidíte všechny příchozí i odchozí transakce kreditů.
                        </p>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-md">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Mohu převést kredity někomu jinému?</h3>
                        <p class="text-gray-600">
                            Kredity jsou vázány na váš účet a nelze je převádět na jiné účty. Jsou určeny výhradně pro vás a váš osobní nákup.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="max-w-4xl mx-auto bg-gray-900 rounded-3xl p-8 sm:p-12 lg:p-16 text-center">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6">
                    Začněte získávat kredity ještě dnes!
                </h2>
                <p class="text-xl text-white mb-8 max-w-2xl mx-auto">
                    Zaregistrujte se a získejte 500 Kč bonus kreditů zdarma. Za každý nákup pak získáte 5% zpět.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link href="/register" class="btn bg-white text-gray-900 hover:bg-gray-100 text-lg px-8 py-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg font-semibold">
                        Zaregistrovat se zdarma
                    </Link>
                    <Link href="/products" class="btn bg-transparent border-2 border-white text-white hover:bg-white/10 text-lg px-8 py-4 rounded-xl transition-all duration-300 font-semibold">
                        Prohlédnout produkty
                    </Link>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
</script>
