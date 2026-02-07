# Použití Enumů ve Vue komponentách

Tento dokument popisuje, jak efektivně kontrolovat datové typy pomocí PHP enumů exportovaných do JavaScriptu.

## Import enumů

```javascript
// Import celého enumu
import BasketType from "@/Enums/BasketType";
import BasketItemType from "@/Enums/BasketItemType";
import Entity from "@/Enums/Entity";

// Import jednotlivých hodnot
import { OPEN, CHECKED_OUT } from "@/Enums/BasketType";
import { PRODUCT } from "@/Enums/BasketItemType";
```

## 1. Validace v Props

### Základní validátor

```vue
<script setup>
import BasketType from "@/Enums/BasketType";
import BasketItemType from "@/Enums/BasketItemType";

const props = defineProps({
    basketStatus: {
        type: String,
        default: BasketType.OPEN,
        // Validátor kontroluje, zda hodnota je v enumu
        validator: (value) => Object.values(BasketType).includes(value),
    },
    itemType: {
        type: String,
        required: true,
        validator: (value) => Object.values(BasketItemType).includes(value),
    },
});
</script>
```

### Validátor s vlastní logikou

```vue
<script setup>
const props = defineProps({
    item: {
        type: Object,
        required: true,
        validator: (value) => {
            // Kontrola více enum hodnot najednou
            const validType = Object.values(BasketItemType).includes(
                value.type
            );
            const validEntity = Object.values(Entity).includes(
                value.entity_type
            );

            if (!validType) {
                console.warn(`Invalid type: ${value.type}`);
            }

            return validType && validEntity;
        },
    },
});
</script>
```

## 2. Computed Properties pro Kontrolu

```vue
<script setup>
import { computed } from "vue";
import BasketType from "@/Enums/BasketType";
import BasketItemType from "@/Enums/BasketItemType";

const props = defineProps({
    item: Object,
    basketStatus: String,
});

// Kontrola typu
const isProduct = computed(() => {
    return props.item.type === BasketItemType.PRODUCT;
});

// Kontrola stavu košíku
const isBasketOpen = computed(() => {
    return props.basketStatus === BasketType.OPEN;
});

// Kontrola, zda lze editovat
const canEdit = computed(() => {
    return isBasketOpen.value && isProduct.value;
});
</script>

<template>
    <div v-if="isProduct">
        <p>Jedná se o produkt</p>
    </div>

    <button v-if="canEdit" @click="edit">Upravit</button>
</template>
```

## 3. Použití Helper Funkcí

```vue
<script setup>
import {
    isBasketType,
    isBasketItemType,
    isBasketEditable,
    getBasketTypeLabel,
    validateEnums,
} from "@/Utils/enum-helpers";

const props = defineProps({
    item: Object,
    basketStatus: String,
});

// Kontrola pomocí helper funkcí
const isValid = computed(() => {
    return (
        isBasketItemType(props.item.type) && isBasketType(props.basketStatus)
    );
});

// Validace s chybami
const errors = computed(() => {
    return validateEnums({
        itemType: props.item.type,
        basketType: props.basketStatus,
    });
});

// Získání labelu
const statusLabel = computed(() => {
    return getBasketTypeLabel(props.basketStatus);
});

// Kontrola editovatelnosti
const canEdit = computed(() => {
    return isBasketEditable(props.basketStatus);
});
</script>
```

## 4. Podmíněné Zobrazení v Template

```vue
<template>
    <!-- Jednoduchá kontrola -->
    <div v-if="item.type === BasketItemType.PRODUCT">Produkt</div>

    <!-- Více podmínek -->
    <div
        v-if="
            basketStatus === BasketType.OPEN &&
            item.type === BasketItemType.PRODUCT
        "
    >
        Otevřený košík s produktem
    </div>

    <!-- V-else-if řetězení -->
    <div v-if="item.entity_type === Entity.PRODUCT">Produkt</div>
    <div v-else-if="item.entity_type === Entity.ARTICLE">Článek</div>
    <div v-else>Neznámá entita</div>

    <!-- Podmíněné CSS třídy -->
    <div
        :class="{
            'bg-green-100': basketStatus === BasketType.OPEN,
            'bg-blue-100': basketStatus === BasketType.CHECKED_OUT,
            'bg-gray-100': basketStatus === BasketType.ABANDONED,
        }"
    >
        Status: {{ basketStatus }}
    </div>
</template>
```

## 5. Type Guards a Bezpečné Funkce

```vue
<script setup>
import { isBasketType, getBasketType } from "@/Utils/enum-helpers";

const props = defineProps({
    basketStatus: String,
});

// Bezpečné získání hodnoty s fallback
const safeStatus = computed(() => {
    return getBasketType(props.basketStatus, BasketType.OPEN);
});

// Kontrola před použitím
const handleAction = () => {
    if (!isBasketType(props.basketStatus)) {
        console.error("Neplatný status košíku");
        return;
    }

    // Bezpečné použití
    if (props.basketStatus === BasketType.OPEN) {
        // Akce pro otevřený košík
    }
};
</script>
```

## 6. Watch pro Sledování Změn

```vue
<script setup>
import { watch } from "vue";
import { isBasketType } from "@/Utils/enum-helpers";

const props = defineProps({
    basketStatus: String,
});

// Sledování změn a validace
watch(
    () => props.basketStatus,
    (newStatus, oldStatus) => {
        if (!isBasketType(newStatus)) {
            console.error(
                `Invalid status change: ${oldStatus} -> ${newStatus}`
            );
            return;
        }

        // Reakce na validní změnu
        console.log(`Status changed to: ${newStatus}`);
    }
);
</script>
```

## 7. Switch Statements

```vue
<script setup>
import BasketType from "@/Enums/BasketType";

const getStatusColor = (status) => {
    switch (status) {
        case BasketType.OPEN:
            return "green";
        case BasketType.CHECKED_OUT:
            return "blue";
        case BasketType.ABANDONED:
            return "gray";
        default:
            return "gray";
    }
};

const getStatusIcon = (status) => {
    const icons = {
        [BasketType.OPEN]: "🟢",
        [BasketType.CHECKED_OUT]: "✅",
        [BasketType.ABANDONED]: "⏸️",
    };

    return icons[status] || "❓";
};
</script>
```

## 8. Validace Formulářů

```vue
<script setup>
import { ref } from "vue";
import BasketType from "@/Enums/BasketType";
import { validateEnums } from "@/Utils/enum-helpers";

const form = ref({
    status: BasketType.OPEN,
    type: "",
});

const errors = ref({});

const validate = () => {
    errors.value = {};

    // Validace enum hodnot
    const enumErrors = validateEnums({
        basketType: form.value.status,
        itemType: form.value.type,
    });

    if (enumErrors.length > 0) {
        errors.value.enums = enumErrors;
        return false;
    }

    return true;
};

const submit = () => {
    if (!validate()) {
        return;
    }

    // Odeslání formuláře
};
</script>
```

## 9. Mapování na Konfigurace

```vue
<script setup>
import { computed } from "vue";
import BasketType from "@/Enums/BasketType";

const props = defineProps({
    basketStatus: String,
});

// Mapování enum hodnot na konfiguraci
const statusConfig = computed(() => {
    const configs = {
        [BasketType.OPEN]: {
            label: "Otevřený",
            color: "green",
            canEdit: true,
            canDelete: true,
        },
        [BasketType.CHECKED_OUT]: {
            label: "Dokončený",
            color: "blue",
            canEdit: false,
            canDelete: false,
        },
        [BasketType.ABANDONED]: {
            label: "Opuštěný",
            color: "gray",
            canEdit: false,
            canDelete: true,
        },
    };

    return (
        configs[props.basketStatus] || {
            label: "Neznámý",
            color: "gray",
            canEdit: false,
            canDelete: false,
        }
    );
});
</script>

<template>
    <div :class="`bg-${statusConfig.color}-100`">
        <p>{{ statusConfig.label }}</p>
        <button v-if="statusConfig.canEdit">Upravit</button>
        <button v-if="statusConfig.canDelete">Smazat</button>
    </div>
</template>
```

## 10. TypeScript-like Kontrola (s JSDoc)

```vue
<script setup>
/**
 * @typedef {import('@/Enums/BasketType').default} BasketType
 * @typedef {import('@/Enums/BasketItemType').default} BasketItemType
 */

/**
 * @param {BasketType} status
 * @returns {boolean}
 */
const isEditable = (status) => {
    return status === BasketType.OPEN;
};
</script>
```

## Best Practices

1. **Vždy používej validátory v props** pro runtime kontrolu
2. **Používej helper funkce** z `@/Utils/enum-helpers` pro konzistentní validaci
3. **Computed properties** pro odvozené stavy založené na enum hodnotách
4. **Type guards** před použitím enum hodnot v kritických operacích
5. **Fallback hodnoty** při získávání enum hodnot z neznámých zdrojů
6. **Error handling** při neplatných enum hodnotách

## Příklad Kompletní Komponenty

Viz `BasketItemExample.vue` pro kompletní příklad použití všech technik.
