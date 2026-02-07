<template>
    <div class="text-center py-16">
        <div class="max-w-md mx-auto">
            <svg
                class="w-24 h-24 text-gray-400 mx-auto mb-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                />
            </svg>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                Váš košík je prázdný
            </h2>
            <p class="text-gray-600 mb-8">
                Přidejte produkty do košíku a začněte nakupovat
            </p>
            <Link
                href="/products"
                class="inline-block bg-primary-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-700 transition-colors"
            >
                Prohlédnout produkty
            </Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>

