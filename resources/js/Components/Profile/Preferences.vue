<template>
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Preference dopravy a platby</h2>
        <p class="text-gray-600 mb-8">
            Nastavte si výchozí preferované způsoby dopravy a platby, které budou předvyplněny při nákupu.
        </p>

        <form @submit.prevent="submitForm">
            <!-- Default Transport -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-4">
                    Výchozí způsob dopravy
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label
                        v-for="transport in transports"
                        :key="transport.id"
                        class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors gap-4"
                        :class="
                            form.default_transport_id == transport.id
                                ? 'border-primary-500 bg-primary-50'
                                : 'border-gray-200 hover:border-gray-300'
                        "
                    >
                        <input
                            type="radio"
                            :value="transport.id"
                            v-model="form.default_transport_id"
                            class="text-primary-600 focus:ring-primary-500"
                        />
                        <div class="flex-shrink-0">
                            <img
                                v-if="transport.image"
                                :src="transport.image"
                                width="48"
                                height="48"
                                :alt="transport.name"
                                class="rounded-lg object-cover"
                            />
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">
                                {{ transport.name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ formatPrice(transport.price) }}
                            </div>
                            <div v-if="transport.description" class="text-xs text-gray-400 mt-1">
                                {{ transport.description }}
                            </div>
                        </div>
                    </label>
                </div>
                <p v-if="errors.default_transport_id" class="mt-2 text-sm text-red-600">
                    {{ errors.default_transport_id }}
                </p>
            </div>

            <!-- Default Payment -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-4">
                    Výchozí způsob platby
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label
                        v-for="payment in payments"
                        :key="payment.id"
                        class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors gap-4"
                        :class="
                            form.default_payment_id == payment.id
                                ? 'border-primary-500 bg-primary-50'
                                : 'border-gray-200 hover:border-gray-300'
                        "
                    >
                        <input
                            type="radio"
                            :value="payment.id"
                            v-model="form.default_payment_id"
                            class="text-primary-600 focus:ring-primary-500"
                        />
                        <div class="flex-shrink-0">
                            <img
                                v-if="payment.image"
                                :src="payment.image"
                                width="48"
                                height="48"
                                :alt="payment.name"
                                class="rounded-lg object-cover"
                            />
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">
                                {{ payment.name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ formatPrice(payment.price) }}
                            </div>
                            <div v-if="payment.description" class="text-xs text-gray-400 mt-1">
                                {{ payment.description }}
                            </div>
                        </div>
                    </label>
                </div>
                <p v-if="errors.default_payment_id" class="mt-2 text-sm text-red-600">
                    {{ errors.default_payment_id }}
                </p>
            </div>

            <!-- Success Message -->
            <div v-if="successMessage" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                {{ successMessage }}
            </div>

            <!-- Save Button -->
            <div class="flex justify-center">
                <button
                    type="submit"
                    :disabled="processing"
                    class="px-8 py-3 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span v-if="processing">Ukládání...</span>
                    <span v-else>Uložit preference</span>
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    transports: {
        type: Array,
        default: () => [],
    },
    payments: {
        type: Array,
        default: () => [],
    },
    preferences: {
        type: Object,
        default: () => ({
            default_transport_id: null,
            default_payment_id: null,
        }),
    },
});

const emit = defineEmits(['updated']);

const successMessage = ref('');

const form = useForm({
    default_transport_id: props.preferences.default_transport_id || null,
    default_payment_id: props.preferences.default_payment_id || null,
});

const processing = computed(() => form.processing);

const errors = computed(() => form.errors);

const formatPrice = (price) => {
    if (price === 0 || price === null) {
        return 'Zdarma';
    }
    return new Intl.NumberFormat('cs-CZ', {
        style: 'currency',
        currency: 'CZK',
    }).format(price);
};

const submitForm = () => {
    successMessage.value = '';
    
    form.put('/profile/preferences', {
        preserveScroll: true,
        onSuccess: () => {
            successMessage.value = 'Preference byly úspěšně uloženy.';
            emit('updated');
            setTimeout(() => {
                successMessage.value = '';
            }, 3000);
        },
    });
};
</script>
