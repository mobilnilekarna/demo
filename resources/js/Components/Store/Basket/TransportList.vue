<template>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900 uppercase">
                DOPRAVA<template v-if="basketWeight"> (VÁHA KOŠÍKU: {{ basketWeight }})</template>
            </h2>
            <button
                v-if="isCollapsed"
                @click="expand"
                type="button"
                class="px-4 py-2 text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 rounded-lg transition-colors shadow-sm"
            >
                Změnit
            </button>
        </div>
        <div v-if="!isCollapsed" class="space-y-3">
            <label
                v-for="transport in transports"
                :key="transport.id"
                class="flex items-center gap-4 p-4 border-2 rounded-lg cursor-pointer transition-colors"
                :class="
                    formData.transport?.id === transport.id
                        ? 'border-green-500 bg-green-50'
                        : 'border-gray-200 hover:border-gray-300 bg-white'
                "
            >
                <input
                    type="radio"
                    :value="transport.id"
                    :checked="formData.transport?.id === transport.id"
                    @change="handleTransportChange"
                    class="hidden"
                />
                <div class="flex-shrink-0">
                    <img
                        v-if="transport.image && !transport.image.startsWith('data:')"
                        :src="transport.image"
                        width="48"
                        height="48"
                        :alt="transport.name"
                        class="rounded-lg object-cover"
                    />
                    <img
                        v-else-if="transport.image && transport.image.startsWith('data:')"
                        :src="transport.image"
                        width="48"
                        height="48"
                        :alt="transport.name"
                        class="rounded-lg object-cover"
                    />
                    <div v-else class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="font-medium text-gray-900">
                        {{ transport.name }}
                    </div>
                    <div v-if="formData.transport?.id === transport.id && isPickupType(transport.type) && formData.branch_name" class="text-sm text-gray-600 mt-1">
                        <span class="font-semibold">Výdejní místo:</span> {{ formData.branch_name }}
                    </div>
                </div>
                <div class="flex-shrink-0 text-right">
                    <div v-if="transport.price > 0" class="font-bold text-gray-900">
                        {{ formatPrice(transport.price) }}
                    </div>
                    <div v-else class="font-bold text-green-600">
                        ZDARMA
                    </div>
                    <div v-if="transport.free_from" class="text-sm text-orange-600 mt-1">
                        Zdarma od {{ formatPrice(transport.free_from) }}
                    </div>
                </div>
            </label>
        </div>

        <!-- Zobrazení vybrané dopravy při sbalení -->
        <div v-else-if="selectedTransport" class="p-4 border-2 border-green-500 bg-green-50 rounded-lg">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <img
                        v-if="selectedTransport.image && !selectedTransport.image.startsWith('data:')"
                        :src="selectedTransport.image"
                        width="48"
                        height="48"
                        :alt="selectedTransport.name"
                        class="rounded-lg object-cover"
                    />
                    <img
                        v-else-if="selectedTransport.image && selectedTransport.image.startsWith('data:')"
                        :src="selectedTransport.image"
                        width="48"
                        height="48"
                        :alt="selectedTransport.name"
                        class="rounded-lg object-cover"
                    />
                    <div v-else class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="font-medium text-gray-900">
                        {{ selectedTransport.name }}
                    </div>
                    <div v-if="isPickupType(selectedTransport.type) && formData.branch_name" class="text-sm text-gray-600 mt-1">
                        <span class="font-semibold">Výdejní místo:</span> {{ formData.branch_name }}
                    </div>
                </div>
                <div class="flex-shrink-0 text-right">
                    <div v-if="selectedTransport.price > 0" class="font-bold text-gray-900">
                        {{ formatPrice(selectedTransport.price) }}
                    </div>
                    <div v-else class="font-bold text-green-600">
                        ZDARMA
                    </div>
                </div>
            </div>
        </div>

        <p v-if="errors.transport" class="mt-1 text-sm text-red-600">
            {{ errors.transport }}
        </p>

        <!-- Komponenta pro výběr výdejních míst -->
        <PickupBranchSelector
            ref="branchSelectorRef"
            :packeta-settings="packetaSettings"
            @branch-selected="handleBranchSelected"
        />
    </div>
</template>
<script setup>
import { ref, computed, watch } from 'vue';
import { useCheckout } from "@/Composables/useCheckout";
import PickupBranchSelector from "./PickupBranches/PickupBranchSelector.vue";
import { TransportType } from "@/Enums/TransportType.js";

const props = defineProps({
    transports: {
        type: Array,
        required: true,
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
    basketWeight: {
        type: String,
        default: null,
    },
    packetaSettings: {
        type: Object,
        default: () => ({})
    }
});

const emit = defineEmits(['transport-changed']);

const { formData } = useCheckout();
const branchSelectorRef = ref(null);
const isCollapsed = ref(false);

// Vybraná doprava
const selectedTransport = computed(() => {
    if (!formData.value.transport?.id) return null;
    return props.transports.find(t => t.id === formData.value.transport.id);
});

// Funkce pro rozbalení
const expand = () => {
    isCollapsed.value = false;
};

// Kontrola, zda je doprava typu pickup (výdejní místo)
const isPickupType = (transportType) => {
    if (!transportType) return false;
    const pickupTypes = [
        TransportType.PERSONAL,
        TransportType.PACKETA_CZ,
        TransportType.PACKETA_SK,
        TransportType.PPL_PARCEL,
        TransportType.DPD_PICKUP,
        TransportType.CP_NP,
        TransportType.CP_BA
    ];
    return pickupTypes.includes(transportType);
};

// Handler pro změnu dopravy
const handleTransportChange = (event) => {
    const transportId = event.target.value ? parseInt(event.target.value) : null;

    if (transportId) {
        // Najít transport objekt
        const transport = props.transports.find(t => t.id === transportId);

        if (transport && isPickupType(transport.type) && transport.type !== TransportType.PERSONAL) {
            // Pokud je to výdejní místo (kromě personal), otevřít widget
            if (branchSelectorRef.value) {
                branchSelectorRef.value.openModal(transport.type);
            }
        }
    }

    // Emitovat event pro rodičovskou komponentu
    emit('transport-changed', transportId);
};

// Handler pro výběr výdejního místa
const handleBranchSelected = (data) => {
    formData.value.branch_id = data.branch_id;
    formData.value.branch_name = data.branch_name;
};

// Sledování, kdy sbalit - když je vybraná doprava a pro pickup i pobočka
watch(
    () => [
        formData.value.transport?.id,
        formData.value.branch_id,
        formData.value.branch_name
    ],
    ([transportId, branchId, branchName]) => {
        if (transportId) {
            const transport = props.transports.find(t => t.id === transportId);
            if (transport) {
                // Pro pickup typy potřebujeme i pobočku, jinak stačí doprava
                if (isPickupType(transport.type) && transport.type !== TransportType.PERSONAL) {
                    if (branchId && branchName) {
                        if (!isCollapsed.value) {
                            isCollapsed.value = true;
                            // Scroll na tento element po sbalení
                            setTimeout(() => {
                                const element = document.getElementById('delivery-transport-section');
                                if (element) {
                                    element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                }
                            }, 100);
                        }
                    }
                } else {
                    // Pro ne-pickup stačí doprava
                    if (!isCollapsed.value) {
                        isCollapsed.value = true;
                        // Scroll na tento element po sbalení
                        setTimeout(() => {
                            const element = document.getElementById('delivery-transport-section');
                            if (element) {
                                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }
                        }, 100);
                    }
                }
            }
        } else {
            isCollapsed.value = false;
        }
    },
    { immediate: true }
);

// Při změně dopravy rozbalit
watch(() => formData.value.transport?.id, () => {
    if (!formData.value.transport?.id) {
        isCollapsed.value = false;
    }
});

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
