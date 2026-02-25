<template>
    <section class="w-full -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8">
        <!-- Anchor for scrolling -->
        <div id="product-list-anchor" class="scroll-mt-20"></div>

        <!-- Controls Bar -->
        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <!-- Left side: Filter button -->
            <Filtering
                :filters="filters"
                :current-filters="currentFilters"
                :base-url="baseUrl"
                :query-params="queryParams"
                button-label="Filtry"
                modal-title="Filtry produktů"
            />

            <!-- Right side: Sort dropdown -->
            <Sorting
                :current-sort="currentSort"
                :options="sortOptions"
                :base-url="baseUrl"
                :query-params="queryParams"
            />
        </div>

        <!-- Products Grid -->
        <div
            v-if="products.length > 0"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
        >
            <ProductCard
                v-for="product in products"
                :key="product.id"
                :product="product"
            />
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
            <svg
                class="w-16 h-16 text-gray-400 mx-auto mb-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                ></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                Žádné produkty
            </h3>
            <p class="text-gray-500">
                Momentálně nemáme žádné produkty k dispozici.
            </p>
        </div>

        <!-- Pagination -->
        <div v-if="pagination" class="mt-8">
            <Pagination
                :pagination="pagination"
                :base-url="baseUrl"
                :query-params="queryParams"
                item-label="produktů"
            />
        </div>
    </section>
</template>

<script setup>
import { computed, onMounted, watch, nextTick } from "vue";
import ProductCard from "@/Components/Store/Product/ProductCard.vue";
import Pagination from "@/Components/Common/Pagination.vue";
import Sorting from "@/Components/Common/Sorting.vue";
import Filtering from "@/Components/Common/Filtering.vue";

const props = defineProps({
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
    sortOptions: {
        type: Array,
        default: () => [
            { value: "default", label: "Výchozí" },
            { value: "price_asc", label: "Cena: od nejnižší" },
            { value: "price_desc", label: "Cena: od nejvyšší" },
            { value: "name_asc", label: "Název: A-Z" },
            { value: "name_desc", label: "Název: Z-A" },
        ],
    },
    baseUrl: {
        type: String,
        default: "/products",
    },
});

// Computed: Build query params for pagination, sorting, and filtering
const queryParams = computed(() => {
    const params = {};

    // Add filters
    Object.keys(props.currentFilters).forEach((key) => {
        const value = props.currentFilters[key];
        if (value !== "" && value !== null && value !== undefined) {
            if (Array.isArray(value)) {
                params[`filters[${key}]`] = value;
            } else {
                params[`filters[${key}]`] = value;
            }
        }
    });

    // Add sort
    if (props.currentSort && props.currentSort !== "default") {
        params.sort = props.currentSort;
    }

    return params;
});

// Scroll to anchor function
const scrollToAnchor = () => {
    nextTick(() => {
        requestAnimationFrame(() => {
            const anchor = document.getElementById('product-list-anchor');
            if (anchor) {
                const rect = anchor.getBoundingClientRect();
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const targetY = rect.top + scrollTop - 80; // 80px offset for header

                window.scrollTo({
                    top: targetY,
                    behavior: 'smooth'
                });
            }
        });
    });
};

// Watch for pagination changes to scroll to anchor
watch(() => props.pagination?.current_page, (newPage, oldPage) => {
    if (newPage && newPage > 1 && newPage !== oldPage) {
        scrollToAnchor();
    }
}, { immediate: false });

onMounted(() => {
    // Scroll on initial mount if page > 1
    if (props.pagination && props.pagination.current_page > 1) {
        scrollToAnchor();
    }
});
</script>

