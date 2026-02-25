<template>
    <footer class="bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                <!-- Left Column - Logo and Apps -->
                <div class="space-y-6">
                    <!-- Logo -->
                    <div>
                        <img src="/logo.svg" alt="Vaše lékárna" width="200">
                    </div>
                </div>

                <!-- Middle Columns - Navigation Links -->
                <!-- Zákaznická podpora -->
                <div>
                    <h4 class="font-semibold mb-4">Zákaznická podpora</h4>
                    <ul class="space-y-2 ">
                        <li>
                            <Link href="/shipping-payment" class="hover:underline transition-colors">
                                Doprava a platba
                            </Link>
                        </li>
                        <li>
                            <Link href="/returns" class="hover:underline transition-colors">
                                Reklamace a vrácení zboží
                            </Link>
                        </li>
                        <li>
                            <Link href="/benefits" class="hover:underline transition-colors">
                                Benefitní programy
                            </Link>
                        </li>
                        <li>
                            <Link href="/faq" class="hover:underline transition-colors">
                                Často kladené otázky
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- O Pilulce -->
                <div>
                    <h4 class="font-semibold mb-4 ">O Lékárně</h4>
                    <ul class="space-y-2 ">
                        <li>
                            <Link href="/about" class="hover:underline transition-colors">
                                O nás
                            </Link>
                        </li>
                        <li>
                            <Link href="/contact" class="hover:underline transition-colors">
                                Kontakty
                            </Link>
                        </li>
                        <li>
                            <Link href="/pro" class="hover:underline transition-colors">
                                Lékárna PRO
                            </Link>
                        </li>
                        <li>
                            <Link href="/career" class="hover:underline transition-colors">
                                Kariéra
                            </Link>
                        </li>
                        <li>
                            <Link href="/media" class="hover:underline transition-colors">
                                Pro média
                            </Link>
                        </li>
                        <li>
                            <Link href="/articles" class="hover:underline transition-colors">
                                Články
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Právní informace -->
                <div>
                    <h4 class="font-semibold mb-4 ">Právní informace</h4>
                    <ul class="space-y-2 ">
                        <li>
                            <Link href="/terms" class="hover:underline transition-colors">
                                Obchodní podmínky
                            </Link>
                        </li>
                        <li>
                            <Link href="/privacy" class="hover:underline transition-colors">
                                Ochrana osobních údajů
                            </Link>
                        </li>
                        <li>
                            <Link href="/investors" class="hover:underline transition-colors">
                                Pro investory
                            </Link>
                        </li>
                    </ul>

                    <!-- Language Selector -->
                    <div class="mt-8">
                        <select class="w-full bg-white text-gray-900 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-400">
                            <option value="cs">🇨🇿 Čeština</option>
                            <option value="sk">🇸🇰 Slovenčina</option>
                            <option value="en">🇬🇧 English</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6 mt-8">
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <picture>
                        <img src="/images/payments/gateway/list.png" alt="Pilulka Lékárny" width="400">
                    </picture>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6 mt-8">
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="https://www.sukl.cz/" target="_blank" class="hover:opacity-80 transition-opacity duration-200" title="Sukl">
                        <img src="/images/general/sukl.png" alt="Sukl" width="90">
                    </a>
                     <a href="https://www.uskvbl.cz/" target="_blank" class="hover:opacity-80 transition-opacity duration-200" title="Venterina">
                        <img src="/images/general/uskvbl.png" alt="Venterina" width="90">
                    </a>
                      <a href="https://www.heureka.cz/" target="_blank" class="hover:opacity-80 transition-opacity duration-200" title="Heureka Ověřeno">
                        <img src="/images/general/heureka-overeno-basic.svg" alt="Heureka Ověřeno" width="90">
                    </a>
                     <a href="https://www.heureka.cz/" target="_blank" class="hover:opacity-80 transition-opacity duration-200" title="Heureka Ověřeno">
                        <img src="/images/general/heureka-overeno-gold.svg" alt="Heureka Ověřeno" width="90">
                    </a>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-200 pt-6 mt-8">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <!-- Copyright -->
                    <div class="text-sm ">
                        © {{ new Date().getFullYear() }} Pilulka Lékárny a.s.
                    </div>

                    <!-- Social Media Icons -->
                    <div class="flex items-center gap-4">
                        <a href="#" class=" hover:text-primary-400 transition-colors" aria-label="Facebook">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class=" hover:text-primary-400 transition-colors" aria-label="Instagram">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="#" class=" hover:text-primary-400 transition-colors" aria-label="X (Twitter)">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        <a href="#" class=" hover:text-primary-400 transition-colors" aria-label="LinkedIn">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>
