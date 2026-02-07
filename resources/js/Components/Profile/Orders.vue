<template>
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Moje objednávky</h2>

        <div v-if="orders.length === 0" class="text-center py-12">
            <svg
                class="mx-auto h-12 w-12 text-gray-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Žádné objednávky</h3>
            <p class="mt-1 text-sm text-gray-500">
                Zatím jste neuskutečnili žádnou objednávku.
            </p>
        </div>

        <div v-else class="space-y-4">
            <div
                v-for="order in orders"
                :key="order.id"
                class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow"
            >
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Objednávka #{{ order.order_number }}
                            </h3>
                            <span
                                :class="[
                                    'px-3 py-1 rounded-full text-xs font-medium',
                                    getStatusClass(order.status)
                                ]"
                            >
                                {{ getStatusText(order.status) }}
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <span>
                                <span class="font-medium">Datum:</span> {{ order.created_at }}
                            </span>
                            <span>
                                <span class="font-medium">Celkem:</span> {{ formatCurrency(order.total) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <Link
                            :href="`/order/${order.id}/confirmation`"
                            class="px-4 py-2 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors"
                        >
                            Zobrazit detail
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { formatCurrency } from '@/Utils/format';

const props = defineProps({
    orders: {
        type: Array,
        default: () => [],
    },
});

const getStatusText = (status) => {
    const statusMap = {
        pending: 'Čeká na zpracování',
        processing: 'Zpracovává se',
        completed: 'Dokončeno',
        cancelled: 'Zrušeno',
    };
    return statusMap[status] || status;
};

const getStatusClass = (status) => {
    const classMap = {
        pending: 'bg-yellow-100 text-yellow-800',
        processing: 'bg-blue-100 text-blue-800',
        completed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800',
    };
    return classMap[status] || 'bg-gray-100 text-gray-800';
};
</script>
