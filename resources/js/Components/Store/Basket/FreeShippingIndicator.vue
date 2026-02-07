<template>
    <!-- Zobrazit pouze pokud je v košíku něco (totalPrice > 0) -->
    <div v-if="totalPrice > 0">
        <div v-if="hasFreeShipping" class="bg-primary-50 p-4 rounded-lg border border-primary-200">
            <div class="flex items-center space-x-2 text-primary-600 mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                <span class="font-semibold text-sm">Skvělé! Doprava je zdarma!</span>
            </div>
            <!-- Progress bar pro dosaženou dopravu zdarma -->
            <div class="w-full bg-primary-200 rounded-full h-2.5">
                <div
                    class="bg-primary-600 h-2.5 rounded-full transition-all duration-300 ease-out"
                    :style="{ width: '100%' }"
                ></div>
            </div>
        </div>
        <div v-else class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="flex items-center space-x-2 text-gray-600 mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                <span class="text-sm">Do dopravy zdarma zbývá {{ formatCurrency(freeShippingThreshold - totalPrice) }}</span>
            </div>
            <!-- Progress bar pro pokrok k dopravě zdarma -->
            <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                <div
                    class="bg-gradient-to-r from-primary-500 to-primary-600 h-2.5 rounded-full transition-all duration-300 ease-out"
                    :style="{ width: `${progressPercentage}%` }"
                ></div>
            </div>
            <div class="mt-1 text-xs text-gray-500 text-right">
                {{ formatCurrency(totalPrice) }} / {{ formatCurrency(freeShippingThreshold) }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { formatCurrency } from "@/Utils/format";

const props = defineProps({
    totalPrice: {
        type: Number,
        required: true,
    },
    freeShippingThreshold: {
        type: Number,
        default: 2000,
    },
});

const hasFreeShipping = computed(() => props.totalPrice >= props.freeShippingThreshold);

const progressPercentage = computed(() => {
    if (props.totalPrice <= 0) return 0;
    if (props.totalPrice >= props.freeShippingThreshold) return 100;
    return Math.min((props.totalPrice / props.freeShippingThreshold) * 100, 100);
});
</script>

