<template>
    <AppLayout title="Potvrzení objednávky">
        <CheckoutSteps />
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-primary-50 border-2 border-primary-200 rounded-lg p-6 mb-8">
                <div class="flex items-center space-x-3">
                    <svg
                        class="w-8 h-8 text-primary-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    <div>
                        <h1 class="text-2xl font-bold text-primary-900">
                            Objednávka byla úspěšně vytvořena
                        </h1>
                        <p class="text-primary-700 mt-1">
                            Děkujeme za vaši objednávku. Číslo objednávky:
                            <span class="font-semibold">{{ order.order_number }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Informace o objednávce -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        Informace o objednávce
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                Číslo objednávky
                            </h3>
                            <p class="text-gray-900 font-medium">{{ order.order_number }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                Stav objednávky
                            </h3>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                :class="getStatusClass(order.status)"
                            >
                                {{ getStatusLabel(order.status) }}
                            </span>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                Datum vytvoření
                            </h3>
                            <p class="text-gray-900">
                                {{ formatDate(order.created_at) }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                Způsob dopravy
                            </h3>
                            <p class="text-gray-900">
                                {{ getShippingLabel(order.shipping) }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                Způsob platby
                            </h3>
                            <p class="text-gray-900">
                                {{ getPaymentLabel(order.payment) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Objednané zboží -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        Objednané zboží
                    </h2>
                    <div class="space-y-4">
                        <div
                            v-for="item in items"
                            :key="item.id"
                            class="flex items-center space-x-4 border-b pb-4 last:border-0 last:pb-0"
                        >
                            <img
                                v-if="item.product.image"
                                :src="item.product.image"
                                :alt="item.product.name"
                                class="w-20 h-20 object-cover rounded-lg"
                            />
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">
                                    {{ item.product.name }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ item.product.description }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-primary-500">
                                    {{ formatCurrency(item.price * item.quantity) }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ item.quantity }}x {{ formatCurrency(item.price) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Souhrn cen -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        Souhrn objednávky
                    </h2>
                    <div class="space-y-2">
                        <div class="flex justify-between text-gray-700">
                            <span>Mezisoučet:</span>
                            <span>{{ formatCurrency(order.subtotal) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>DPH:</span>
                            <span>{{ formatCurrency(order.vat_total) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Doprava:</span>
                            <span>{{ formatCurrency(order.shipping_price) }}</span>
                        </div>
                        <div class="border-t pt-2 mt-2">
                            <div class="flex justify-between text-lg font-semibold text-gray-900">
                                <span>Celkem:</span>
                                <span class="font-bold text-primary-500">{{ formatCurrency(order.total) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kontaktní údaje -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        Kontaktní údaje
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                Jméno a příjmení
                            </h3>
                            <p class="text-gray-900">
                                {{ order.first_name }} {{ order.last_name }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                Email
                            </h3>
                            <p class="text-gray-900">{{ order.email }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                Telefon
                            </h3>
                            <p class="text-gray-900">{{ order.phone }}</p>
                        </div>
                    </div>
                </div>

                <!-- Fakturační údaje -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        Fakturační údaje
                    </h2>
                    <div class="space-y-2">
                        <p class="text-gray-900">{{ order.street }}</p>
                        <p class="text-gray-900">
                            {{ order.zip }} {{ order.city }}
                        </p>
                        <p class="text-gray-900">
                            {{ getCountryLabel(order.country) }}
                        </p>
                    </div>
                </div>

                <!-- Firemní údaje -->
                <div v-if="order.is_company" class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        Firemní údaje
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                Název firmy
                            </h3>
                            <p class="text-gray-900">{{ order.company_name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                IČO
                            </h3>
                            <p class="text-gray-900">{{ order.company_id }}</p>
                        </div>
                        <div v-if="order.company_vat_id">
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                DIČ
                            </h3>
                            <p class="text-gray-900">{{ order.company_vat_id }}</p>
                        </div>
                    </div>
                </div>

                <!-- Dodací údaje -->
                <div v-if="order.has_delivery_address" class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        Dodací údaje
                    </h2>
                    <div class="space-y-2">
                        <p class="text-gray-900">
                            {{ order.delivery_first_name }}
                            {{ order.delivery_last_name }}
                        </p>
                        <p class="text-gray-900">{{ order.delivery_street }}</p>
                        <p class="text-gray-900">
                            {{ order.delivery_zip }} {{ order.delivery_city }}
                        </p>
                        <p class="text-gray-900">
                            {{ getCountryLabel(order.delivery_country) }}
                        </p>
                    </div>
                </div>

                <!-- Poznámka -->
                <div v-if="order.note" class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Poznámka</h2>
                    <p class="text-gray-700 whitespace-pre-wrap">
                        {{ order.note }}
                    </p>
                </div>

                <!-- Tlačítka -->
                <div class="flex flex-col sm:flex-row gap-4 justify-between pt-6">
                    <Link
                        href="/products"
                        class="px-6 py-3 border-2 border-gray-300 rounded-lg font-semibold text-gray-700 hover:border-gray-400 transition-colors text-center"
                    >
                        Pokračovat v nákupu
                    </Link>
                    <button
                        @click="printPage"
                        class="px-6 py-3 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition-colors"
                    >
                        Tisknout objednávku
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import CheckoutSteps from "@/Components/Checkout/CheckoutSteps.vue";
import { formatCurrency } from "@/Utils/format";

import { Link } from "@inertiajs/vue3";
import {
    getStatusLabel,
    getStatusClass,
    getShippingLabel,
    getPaymentLabel,
    getCountryLabel,
} from "@/Utils/order-helpers.js";

const props = defineProps({
    order: {
        type: Object,
        required: true,
    },
    items: {
        type: Array,
        required: true,
    },
});


// Formátování data
const formatDate = (date) => {
    return new Date(date).toLocaleString("cs-CZ", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

// Tisk stránky
const printPage = () => {
    window.print();
};
</script>

