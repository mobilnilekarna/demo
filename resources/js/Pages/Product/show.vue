<template>
    <AppLayout :title="product?.name || 'Produkt'">
           <section class="max-w-7xl mx-auto w-full">
                <Breadcrumb
                    :items="[
                        { label: 'Domů', href: '/' },
                        { label: 'Produkty', href: '/products' },
                        { label: product.name },
                    ]"
                />
                <ProductDetail :product="product" />
           </section>
           <section class="">
                <ProductListSwiper
                :products="relatedProducts"
                title="produkty"
                title-highlight="Související"
                title-highlight-color="green"
                description="Produkty, které by se vám mohly líbit"
                view-all-link="/products"
                link-color-class="text-black hover:text-primary-600 transition-colors font-semibold"
                />
           </section>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import ProductDetail from "@/Components/Store/Product/ProductDetail.vue";
import ProductListSwiper from "@/Components/Store/Product/ProductListSwiper.vue";

const props = defineProps({
    product: {
        type: Object,
        required: true,
        default: () => ({}),
    },
    relatedProducts: {
        type: Array,
        required: true,
        default: () => [],
    },
});

const relatedProducts = props.relatedProducts;
</script>

