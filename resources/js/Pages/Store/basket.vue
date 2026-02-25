<template>
    <AppLayout title="Košík">
        <CheckoutSteps />
        <FreeShippingIndicator :total-price="totalPrice" :free-shipping-threshold="2000" />
        <BasketList
            :basket-items="basketItems"
            :total-price="totalPrice"
            :total-items="totalItems"
            @update-quantity="handleUpdateQuantity"
            @remove-item="handleRemoveItem"
            @clear-basket="handleClearBasket"
        />
        <ProductListSwiper :products="products"
            title="produkty"
            title-highlight="Doporučené"
            title-highlight-color="primary"
            description="Produkty, které by se vám mohly líbit"
            view-all-link="/products"
            link-color-class="text-black hover:text-primary-600 transition-colors font-semibold"
        />
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import CheckoutSteps from "@/Components/Checkout/CheckoutSteps.vue";
import FreeShippingIndicator from "@/Components/Store/Basket/FreeShippingIndicator.vue";
import BasketList from "@/Components/Store/Basket/BasketList.vue";
import ProductListSwiper from "@/Components/Store/Product/ProductListSwiper.vue";
import { useBasket } from "@/Composables/useBasket";

const { basketItems, totalItems, totalPrice, updateQuantity, removeFromBasket, clearBasket } = useBasket();

const props = defineProps({
    products: {
        type: Array,
        required: true,
        default: () => [],
    },
});

const handleUpdateQuantity = async (itemId, quantity) => {
    await updateQuantity(itemId, quantity);
};

const handleRemoveItem = async (itemId) => {
    await removeFromBasket(itemId);
};

const handleClearBasket = async () => {
    if (confirm("Opravdu chcete vyprázdnit košík?")) {
        await clearBasket();
    }
};
</script>

