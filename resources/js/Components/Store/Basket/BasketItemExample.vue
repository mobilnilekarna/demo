<template>
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Příklad 1: Podmíněné zobrazení na základě enum hodnoty -->
        <div v-if="isProductType" class="product-item">
            <h3>Produkt: {{ item.name }}</h3>
        </div>

        <!-- Příklad 2: Různé styly podle typu -->
        <div :class="itemTypeClass">
            <span>Typ: {{ itemTypeLabel }}</span>
        </div>

        <!-- Příklad 3: Podmíněné akce -->
        <button 
            v-if="canEdit" 
            @click="handleEdit"
            :disabled="!isValidBasketType"
        >
            Upravit
        </button>

        <!-- Příklad 4: Zobrazení podle entity -->
        <div v-if="isProductEntity">
            <p>Jedná se o produkt</p>
        </div>
        <div v-else-if="isArticleEntity">
            <p>Jedná se o článek</p>
        </div>

        <!-- Příklad 5: Validace a zobrazení chyb -->
        <div v-if="validationErrors.length > 0" class="errors">
            <p v-for="error in validationErrors" :key="error">{{ error }}</p>
        </div>
    </div>
</template>

<script setup>
import { computed, watch } from "vue";
import BasketType from "@/Enums/BasketType";
import BasketItemType from "@/Enums/BasketItemType";
import Entity from "@/Enums/Entity";

const props = defineProps({
    item: {
        type: Object,
        required: true,
        // Příklad 1: Validátor v props - kontrola enum hodnoty
        validator: (value) => {
            // Kontrola, že item má validní type
            if (value.type && !Object.values(BasketItemType).includes(value.type)) {
                console.warn(`Invalid BasketItemType: ${value.type}`);
                return false;
            }
            // Kontrola, že basket má validní status
            if (value.basket?.status && !Object.values(BasketType).includes(value.basket.status)) {
                console.warn(`Invalid BasketType: ${value.basket.status}`);
                return false;
            }
            return true;
        }
    },
    basketStatus: {
        type: String,
        default: BasketType.OPEN,
        // Příklad 2: Validátor pro enum hodnotu
        validator: (value) => Object.values(BasketType).includes(value)
    }
});

// Příklad 3: Computed properties pro kontrolu typu
const isProductType = computed(() => {
    return props.item.type === BasketItemType.PRODUCT;
});

const isProductEntity = computed(() => {
    return props.item.entity_type === Entity.PRODUCT;
});

const isArticleEntity = computed(() => {
    return props.item.entity_type === Entity.ARTICLE;
});

// Příklad 4: Computed pro CSS třídy podle typu
const itemTypeClass = computed(() => {
    const baseClass = "item-type-badge";
    
    if (isProductType.value) {
        return `${baseClass} product`;
    }
    
    return `${baseClass} unknown`;
});

// Příklad 5: Computed pro label podle typu
const itemTypeLabel = computed(() => {
    const typeMap = {
        [BasketItemType.PRODUCT]: 'Produkt'
    };
    
    return typeMap[props.item.type] || 'Neznámý typ';
});

// Příklad 6: Kontrola, zda je košík v editovatelném stavu
const canEdit = computed(() => {
    return props.basketStatus === BasketType.OPEN;
});

const isValidBasketType = computed(() => {
    return Object.values(BasketType).includes(props.basketStatus);
});

// Příklad 7: Validace s chybovými hláškami
const validationErrors = computed(() => {
    const errors = [];
    
    // Kontrola typu itemu
    if (props.item.type && !Object.values(BasketItemType).includes(props.item.type)) {
        errors.push(`Neplatný typ itemu: ${props.item.type}`);
    }
    
    // Kontrola entity typu
    if (props.item.entity_type && !Object.values(Entity).includes(props.item.entity_type)) {
        errors.push(`Neplatný typ entity: ${props.item.entity_type}`);
    }
    
    // Kontrola statusu košíku
    if (props.basketStatus && !Object.values(BasketType).includes(props.basketStatus)) {
        errors.push(`Neplatný status košíku: ${props.basketStatus}`);
    }
    
    return errors;
});

// Příklad 8: Helper funkce pro kontrolu typu (type guard)
const isBasketType = (value) => {
    return Object.values(BasketType).includes(value);
};

const isBasketItemType = (value) => {
    return Object.values(BasketItemType).includes(value);
};

const isEntityType = (value) => {
    return Object.values(Entity).includes(value);
};

// Příklad 9: Watch pro sledování změn a validaci
watch(() => props.item.type, (newType, oldType) => {
    if (newType && !isBasketItemType(newType)) {
        console.error(`Invalid type change: ${oldType} -> ${newType}`);
    }
});

// Příklad 10: Funkce pro bezpečné získání enum hodnoty
const getBasketType = (value, defaultValue = BasketType.OPEN) => {
    return isBasketType(value) ? value : defaultValue;
};

const handleEdit = () => {
    // Kontrola před akcí
    if (!canEdit.value) {
        console.warn('Košík není v editovatelném stavu');
        return;
    }
    
    if (!isValidBasketType.value) {
        console.error('Neplatný status košíku');
        return;
    }
    
    // Provedení akce
    console.log('Editování itemu:', props.item);
};

// Příklad 11: Type-safe switch podle enum hodnoty
const getItemIcon = (type) => {
    switch (type) {
        case BasketItemType.PRODUCT:
            return '📦';
        default:
            return '❓';
    }
};

// Příklad 12: Mapování enum hodnot na konfiguraci
const getTypeConfig = (type) => {
    const configs = {
        [BasketItemType.PRODUCT]: {
            color: 'blue',
            icon: '📦',
            canDelete: true,
            canEdit: true
        }
    };
    
    return configs[type] || {
        color: 'gray',
        icon: '❓',
        canDelete: false,
        canEdit: false
    };
};

// Export helper funkcí pro použití v jiných komponentách
defineExpose({
    isBasketType,
    isBasketItemType,
    isEntityType,
    getBasketType,
    getItemIcon,
    getTypeConfig
});
</script>

<style scoped>
.item-type-badge.product {
    @apply bg-blue-100 text-blue-800 px-2 py-1 rounded;
}

.item-type-badge.unknown {
    @apply bg-gray-100 text-gray-800 px-2 py-1 rounded;
}

.errors {
    @apply bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded;
}
</style>

