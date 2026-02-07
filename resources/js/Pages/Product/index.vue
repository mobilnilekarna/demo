<template>
    <AppLayout title="Produkty">
       <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Naše produkty</h1>
                <p class="text-lg text-gray-600">
                    Vyberte si z naší široké nabídky kvalitních produktů
                </p>
            </div>
       </section>
       <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8">
        <BrandSwiper :brands="brands" />
       </section>
       <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8">
         <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
        >
            <CategoryCard
                v-for="category in categories"
                :key="category.id"
                :category="category"
            />
        </div>
       </section>
       <!-- Product List Component -->
       <ProductList
            :products="products"
            :pagination="pagination"
            :filters="filters"
            :current-filters="currentFilters"
            :current-sort="currentSort"
            base-url="/products"
       />
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProductList from "@/Components/Store/Product/ProductList.vue";
import CategoryCard from "@components/Store/Category/CategoryCard.vue";
import BrandSwiper from "@/Components/Store/Brand/BrandSwiper.vue";

const props = defineProps({
    categories: Object,
    products: {
        type: Array,
        default: () => [],
    },
    pagination: {
        type: Object,
        default: null,
    },
    filters: {
        type: Array,
        default: () => [],
    },
    currentFilters: {
        type: Object,
        default: () => ({}),
    },
    currentSort: {
        type: String,
        default: "default",
    },
});
</script>

