<template>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900 uppercase">PLATBA</h2>
            <button
                v-if="isCollapsed"
                @click="expand"
                type="button"
                class="px-4 py-2 text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 rounded-lg transition-colors shadow-sm"
            >
                Změnit
            </button>
        </div>
        <div v-if="payments.length === 0" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800">
            Nejprve vyberte způsob dopravy
        </div>
        <div v-else-if="!isCollapsed" class="space-y-3">
            <label
                v-for="payment in payments"
                :key="payment.id"
                class="flex items-center gap-4 p-4 border-2 rounded-lg cursor-pointer transition-colors"
                :class="
                    (props.selectedPaymentId || formData.payment?.id) === payment.id
                        ? 'border-green-500 bg-green-50'
                        : 'border-gray-200 hover:border-gray-300 bg-white'
                "
            >
                <input
                    type="radio"
                    :value="payment.id"
                    :checked="(props.selectedPaymentId || formData.payment?.id) === payment.id"
                    @change="handlePaymentChange"
                    class="hidden"
                />
                <div class="flex-shrink-0">
                    <img
                        v-if="payment.image && !payment.image.startsWith('data:')"
                        :src="payment.image"
                        width="48"
                        height="48"
                        :alt="payment.name"
                        class="rounded-lg object-cover"
                    />
                    <img
                        v-else-if="payment.image && payment.image.startsWith('data:')"
                        :src="payment.image"
                        width="48"
                        height="48"
                        :alt="payment.name"
                        class="rounded-lg object-cover"
                    />
                    <div v-else class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="font-medium text-gray-900">
                        {{ payment.name }}
                    </div>
                </div>
                <div class="flex-shrink-0 text-right">
                    <div v-if="getPaymentPrice(payment) > 0" class="font-bold text-gray-900">
                        {{ formatPrice(getPaymentPrice(payment)) }}
                    </div>
                    <div v-else class="font-bold text-green-600">
                        ZDARMA
                    </div>
                </div>
            </label>
        </div>
        
        <!-- Zobrazení vybrané platby při sbalení -->
        <div v-else-if="selectedPayment" class="p-4 border-2 border-green-500 bg-green-50 rounded-lg">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <img
                        v-if="selectedPayment.image && !selectedPayment.image.startsWith('data:')"
                        :src="selectedPayment.image"
                        width="48"
                        height="48"
                        :alt="selectedPayment.name"
                        class="rounded-lg object-cover"
                    />
                    <img
                        v-else-if="selectedPayment.image && selectedPayment.image.startsWith('data:')"
                        :src="selectedPayment.image"
                        width="48"
                        height="48"
                        :alt="selectedPayment.name"
                        class="rounded-lg object-cover"
                    />
                    <div v-else class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="font-medium text-gray-900">
                        {{ selectedPayment.name }}
                    </div>
                </div>
                <div class="flex-shrink-0 text-right">
                    <div v-if="getPaymentPrice(selectedPayment) > 0" class="font-bold text-gray-900">
                        {{ formatPrice(getPaymentPrice(selectedPayment)) }}
                    </div>
                    <div v-else class="font-bold text-green-600">
                        ZDARMA
                    </div>
                </div>
            </div>
        </div>
        
        <p v-if="errors.payment" class="mt-1 text-sm text-red-600">
            {{ errors.payment }}
        </p>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useCheckout } from "@/Composables/useCheckout";

const props = defineProps({
    payments: {
        type: Array,
        required: true,
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
    selectedPaymentId: {
        type: Number,
        default: null,
    },
});

const emit = defineEmits(['payment-changed']);

const { formData } = useCheckout();
const isCollapsed = ref(false);

// Vybraná platba
const selectedPayment = computed(() => {
    const paymentId = props.selectedPaymentId || formData.value.payment?.id;
    if (!paymentId) return null;
    return props.payments.find(p => p.id === paymentId);
});

// Funkce pro rozbalení
const expand = () => {
    isCollapsed.value = false;
};

// Handler pro změnu platby
const handlePaymentChange = (event) => {
    const paymentId = event.target.value ? parseInt(event.target.value, 10) : null;
    // Neukládáme payment_id přímo, to dělá checkout.vue přes handlePaymentChange
    // Emitovat event pro rodičovskou komponentu
    emit('payment-changed', paymentId);
};

// Získání ceny platby (final_price má prioritu)
const getPaymentPrice = (payment) => {
    if (payment.final_price !== undefined) {
        return payment.final_price;
    }
    return payment.price || 0;
};

// Sledování, kdy sbalit - když je vybrána platba
watch(
    () => props.selectedPaymentId || formData.value.payment?.id,
    (paymentId) => {
        if (paymentId) {
            isCollapsed.value = true;
        } else {
            isCollapsed.value = false;
        }
    },
    { immediate: true }
);

// Formátování ceny
const formatPrice = (price) => {
    if (price === 0 || price === null) {
        return '0 Kč';
    }
    return new Intl.NumberFormat('cs-CZ', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(price) + ' Kč';
};
</script>
