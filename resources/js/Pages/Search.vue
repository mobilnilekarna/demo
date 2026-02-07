<template>
    <AppLayout>
        <!-- Hero Section s vyhledávacím polem -->
        <div class="w-full mb-8">
            <div class="w-full relative bg-gradient-to-br from-primary-50/30 via-primary-50/20 to-transparent py-8 md:py-12 overflow-hidden rounded-2xl md:rounded-3xl shadow-lg border border-primary-100/50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Dekorativní pozadí -->
                <div class="absolute inset-0 opacity-10 rounded-2xl md:rounded-3xl">
                    <div class="absolute top-20 left-10 w-32 h-32 bg-primary-300 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-20 right-10 w-40 h-40 bg-secondary-300 rounded-full blur-3xl"></div>
                    <div class="absolute top-1/2 left-1/2 w-24 h-24 bg-primary-200 rounded-full blur-2xl"></div>
                </div>

                <div class="relative z-10">
                    <!-- Nadpis -->
                    <div class="text-center mb-6">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-2">
                        Co hledáte?
                    </h1>
                    <p class="text-lg md:text-xl text-gray-600">
                        Lékárna, která skutečně doručuje
                    </p>
                    </div>

                    <!-- Vyhledávací pole -->
                    <div class="max-w-4xl mx-auto">
                    <form @submit.prevent="handleSearch" class="relative">
                        <div class="flex flex-col md:flex-row gap-2 bg-white rounded-2xl shadow-xl border-2 border-primary-200 overflow-hidden">
                            <!-- Dropdown kategorie -->
                            <div class="relative md:w-64">
                                <select
                                    v-model="selectedCategory"
                                    class="w-full h-14 px-4 pr-10 bg-gray-50 border-r border-gray-200 text-gray-700 font-medium focus:outline-none focus:bg-white cursor-pointer appearance-none"
                                >
                                    <option value="all">Všechny kategorie</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                        {{ cat.name }}
                                    </option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Input pole -->
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Hledejte něco zajímavého..."
                                class="flex-1 h-14 px-6 text-gray-900 placeholder-gray-400 focus:outline-none text-lg"
                            />

                            <!-- Tlačítko vyhledat -->
                            <button
                                type="submit"
                                class="h-14 px-8 bg-primary-600 hover:bg-primary-700 text-white font-semibold transition-colors flex items-center justify-center gap-2"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span class="hidden sm:inline">Vyhledat</span>
                            </button>
                        </div>
                    </form>
                    </div>

                    <!-- Kategorie -->
                    <div class="mt-8">
                    <div class="categories-swiper-container">
                        <swiper
                            :modules="modules"
                            :slides-per-view="2"
                            :space-between="16"
                            :breakpoints="{
                                640: {
                                    slidesPerView: 3,
                                    spaceBetween: 16,
                                },
                                768: {
                                    slidesPerView: 4,
                                    spaceBetween: 16,
                                },
                                1024: {
                                    slidesPerView: 5.2,
                                    spaceBetween: 16,
                                },
                                1280: {
                                    slidesPerView: 5.2,
                                    spaceBetween: 20,
                                },
                            }"
                            :navigation="canSwipe"
                            :watch-overflow="true"
                            class="categories-swiper"
                        >
                            <swiper-slide
                                v-for="cat in categories"
                                :key="cat.id"
                            >
                                <button
                                    @click="selectCategory(cat.id)"
                                    :class="[
                                        'w-full flex flex-col items-center p-6 rounded-2xl transition-all duration-200 hover:shadow-lg h-full',
                                        selectedCategory === cat.id 
                                            ? 'bg-white border-2 border-primary-500 shadow-md' 
                                            : 'bg-white bg-opacity-80 border-2 border-transparent hover:border-primary-200 hover:bg-opacity-100'
                                    ]"
                                >
                                    <div class="text-4xl mb-3">{{ cat.icon }}</div>
                                    <h3 class="font-semibold text-gray-900 mb-1 text-center">{{ cat.name }}</h3>
                                    <p class="text-sm text-gray-500 text-center">
                                        {{ cat.count > 0 ? `Přes ${cat.count} produktů` : 'Produkty' }}
                                    </p>
                                </button>
                            </swiper-slide>
                        </swiper>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>

        <!-- Výsledky vyhledávání -->
        <div v-if="hasResults" class="bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Počet výsledků -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        Výsledky vyhledávání
                    </h2>
                    <p class="text-gray-600">
                        Nalezeno <span class="font-semibold text-primary-600">{{ totalResults }}</span> výsledků pro "<span class="font-semibold">{{ query }}</span>"
                    </p>
                </div>

                <!-- Produkty -->
                <div v-if="products.length > 0" class="mb-12">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="text-2xl">🛍️</span>
                        Produkty ({{ products.length }})
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <ProductCard
                            v-for="product in products"
                            :key="product.id"
                            :product="product"
                        />
                    </div>
                </div>

                <!-- Články -->
                <div v-if="articles.length > 0">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="text-2xl">📰</span>
                        Články ({{ articles.length }})
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <Link
                            v-for="article in articles"
                            :key="article.id"
                            :href="`/article/${article.slug}`"
                            class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden group"
                        >
                            <div class="h-48 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                                <span class="text-6xl opacity-50">📄</span>
                            </div>
                            <div class="p-6">
                                <h4 class="font-semibold text-lg text-gray-900 mb-2 group-hover:text-primary-600 transition-colors line-clamp-2">
                                    {{ article.title }}
                                </h4>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ article.excerpt }}
                                </p>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>{{ article.author }}</span>
                                    <span>{{ formatDate(article.date) }}</span>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prázdný stav -->
        <div v-else-if="query && !hasResults" class="bg-gray-50 py-16">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">
                    Žádné výsledky
                </h3>
                <p class="text-gray-600 mb-8">
                    Pro dotaz "<span class="font-semibold">{{ query }}</span>" jsme nenašli žádné výsledky. Zkuste upravit vyhledávání.
                </p>
                <button
                    @click="clearSearch"
                    class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors"
                >
                    Vymazat vyhledávání
                </button>
            </div>
        </div>

        <!-- Výchozí stav - doporučené produkty -->
        <div v-else-if="featuredProducts.length > 0" class="bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">
                    Doporučené produkty
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <ProductCard
                        v-for="product in featuredProducts"
                        :key="product.id"
                        :product="product"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ProductCard from '@/Components/Store/Product/ProductCard.vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import { useAnalytics } from '@/Composables/useAnalytics';

const { trackSearch } = useAnalytics();

const modules = [Navigation];

const props = defineProps({
    query: {
        type: String,
        default: '',
    },
    category: {
        type: String,
        default: 'all',
    },
    products: {
        type: Array,
        default: () => [],
    },
    articles: {
        type: Array,
        default: () => [],
    },
    categories: {
        type: Array,
        default: () => [],
    },
    totalResults: {
        type: Number,
        default: 0,
    },
    featuredProducts: {
        type: Array,
        default: () => [],
    },
});

const searchQuery = ref(props.query);
const selectedCategory = ref(props.category);

const hasResults = computed(() => {
    return props.query && (props.products.length > 0 || props.articles.length > 0);
});

const canSwipe = computed(() => props.categories.length > 5);

const handleSearch = () => {
    const params = {};
    if (searchQuery.value) {
        params.q = searchQuery.value;
        trackSearch(searchQuery.value);
    }
    if (selectedCategory.value && selectedCategory.value !== 'all') {
        params.category = selectedCategory.value;
    }
    
    router.get('/vyhledavani', params, {
        preserveState: true,
        preserveScroll: true,
    });
};

const selectCategory = (categoryId) => {
    selectedCategory.value = categoryId;
    handleSearch();
};

const clearSearch = () => {
    searchQuery.value = '';
    selectedCategory.value = 'all';
    router.get('/vyhledavani', {}, {
        preserveState: false,
    });
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('cs-CZ', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

onMounted(() => {
    // Pokud je query v URL, nastavíme ho do inputu a odešleme search event
    if (props.query) {
        searchQuery.value = props.query;
        trackSearch(props.query);
    }
});
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.categories-swiper-container {
    position: relative;
    width: 100%;
    overflow: hidden;
}

.categories-swiper {
    position: relative;
    padding: 0;
    width: 100%;
    padding-bottom: 60px;
    overflow: hidden;
}

.categories-swiper :deep(.swiper-wrapper) {
    padding-left: 0;
    padding-right: 0;
    margin-left: 0;
    margin-right: 0;
}

.categories-swiper :deep(.swiper-slide) {
    height: auto;
    padding: 0.25rem;
}

.categories-swiper :deep(.swiper-slide > *) {
    height: 100%;
    width: 100%;
}

.categories-swiper :deep(.swiper-button-next),
.categories-swiper :deep(.swiper-button-prev) {
    color: white;
    background: #00ab59;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-top: 0;
    top: auto;
    bottom: 0;
    transform: translateY(0);
    z-index: 10;
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.categories-swiper :deep(.swiper-button-next) {
    right: calc(50% - 60px);
}

.categories-swiper :deep(.swiper-button-prev) {
    left: calc(50% - 60px);
}

.categories-swiper :deep(.swiper-button-next:after),
.categories-swiper :deep(.swiper-button-prev:after) {
    font-size: 14px;
    font-weight: bold;
    color: white;
    margin: 0;
}

.categories-swiper :deep(.swiper-button-next:hover),
.categories-swiper :deep(.swiper-button-prev:hover) {
    background: #009a50;
}

.categories-swiper :deep(.swiper-button-disabled) {
    opacity: 0.35;
    cursor: auto;
    pointer-events: none;
}
</style>

