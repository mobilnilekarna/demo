<template>
    <!-- Mobile Menu Overlay -->
    <div
        v-if="props.isMobileMenuOpen"
        @click="closeMobileMenu"
        class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
    ></div>

    <!-- Mobile Slide-out Menu -->
    <nav
        v-if="props.isMobileMenuOpen"
        class="fixed top-0 left-0 h-full w-80 bg-white shadow-2xl z-50 lg:hidden overflow-y-auto transform transition-transform duration-300 ease-in-out"
    >
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Menu</h2>
            <button
                @click="closeMobileMenu"
                class="p-2 hover:bg-gray-100 rounded-md transition-colors"
                aria-label="Zavřít menu"
            >
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <ul v-if="menuCategories && menuCategories.length > 0" class="flex flex-col">
            <li
                v-for="(category, index) in menuCategories"
                :key="category.id"
                class="border-b border-gray-200 last:border-b-0"
            >
                <Link
                    :href="`/category/${category.slug}`"
                    @click="closeMobileMenu"
                    class="block py-4 px-4 text-gray-900 hover:bg-primary-50 hover:text-primary-600 transition-colors duration-200"
                    :title="category.name"
                >
                    <span class="font-medium">{{ category.name }}</span>
                </Link>
                <!-- Subcategories for mobile -->
                <ul v-if="category.subcategories && category.subcategories.length > 0" class="bg-gray-50">
                    <li
                        v-for="subcategory in category.subcategories.slice(0, 5)"
                        :key="subcategory.id"
                    >
                        <Link
                            :href="`/category/${category.slug}/${subcategory.slug}`"
                            @click="closeMobileMenu"
                            class="block py-2 px-8 text-sm text-gray-600 hover:bg-primary-100 hover:text-primary-700 transition-colors duration-200"
                        >
                            {{ subcategory.name }}
                        </Link>
                    </li>
                    <li v-if="category.subcategories.length > 5">
                        <Link
                            :href="`/category/${category.slug}`"
                            @click="closeMobileMenu"
                            class="block py-2 px-8 text-sm font-medium text-primary-600 hover:bg-primary-100 transition-colors duration-200"
                        >
                            Zobrazit vše →
                        </Link>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Desktop Menu -->
    <nav class="bg-primary-500 text-white relative hidden lg:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 relative">
            <ul v-if="menuCategories && menuCategories.length > 0" class="flex items-center">
                <li
                    v-for="category in menuCategories"
                    :key="category.id"
                    class="category-item flex-1 flex items-center justify-center border-r border-primary-600 py-3 last:border-r-0 hover:bg-primary-700 transition-colors duration-200"
                >
                    <Link
                        :href="`/category/${category.slug}`"
                        class="hover:text-primary-100 transition-colors duration-200 py-2 px-2 block text-center text-xs md:text-sm w-full"
                        :title="category.name"
                    >
                        <span class="block truncate">{{ category.name }}</span>
                    </Link>

                    <!-- Dropdown menu - zobrazí se při hoveru pomocí CSS -->
                    <div
                        v-if="category.hasDropdown && category.subcategories"
                        class="category-dropdown absolute bg-white text-gray-900 shadow-lg z-50 opacity-0 invisible"
                    >
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                            <div class="grid grid-cols-5 gap-6">
                                <!-- Levý sloupec - Seznam podkategorií -->
                                <div class="col-span-3 flex flex-col">
                                    <Link
                                        :href="`/category/${category.slug}`"
                                        class="bg-amber-50 px-4 py-3 mb-4 rounded flex items-center justify-between hover:bg-amber-100 transition-colors"
                                    >
                                        <span class="text-gray-900 font-medium">Zobrazit vše v kategorii</span>
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </Link>
                                    <ul class="space-y-1">
                                        <li
                                            v-for="subcategory in category.subcategories"
                                            :key="subcategory.id"
                                        >
                                            <Link
                                                :href="`/category/${category.slug}/${subcategory.slug}`"
                                                class="text-gray-700 hover:text-primary-600 transition-colors duration-200 block py-2 px-4 hover:bg-primary-50 rounded"
                                            >
                                                {{ subcategory.name }}
                                            </Link>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Pravý sloupec - Akční produkty slider -->
                                <div class="col-span-2 border-l border-gray-200 pl-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Dnešní akční cena</h3>
                                    <div class="products-swiper-container">
                                        <swiper
                                            :modules="modules"
                                            :slides-per-view="1.5"
                                            :space-between="16"
                                            :watch-overflow="true"
                                            class="products-swiper"
                                        >
                                            <swiper-slide
                                                v-for="product in testProducts"
                                                :key="product.id"
                                            >
                                                <ProductCard :product="product" />
                                            </swiper-slide>
                                        </swiper>
                                    </div>
                                </div>
                            </div>

                            <!-- Brandy v spodní části -->
                            <div class="border-t border-gray-200 pt-6 mt-6">
                                <div class="flex items-center justify-center gap-8 flex-wrap">
                                    <div
                                        v-for="brand in brands"
                                        :key="brand.id"
                                        class="flex flex-col items-center justify-center opacity-70 hover:opacity-100 transition-opacity"
                                    >
                                        <img
                                            v-if="brand.logo"
                                            :src="brand.logo"
                                            :alt="brand.name"
                                            class="h-10 object-contain"
                                        />
                                        <div v-else class="text-center">
                                            <div class="text-sm font-semibold text-gray-700">{{ brand.name }}</div>
                                            <div v-if="brand.tagline" class="text-xs text-gray-500 mt-1">{{ brand.tagline }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div v-else class="py-3 text-white text-center">
                Žádné kategorie k zobrazení
            </div>
        </div>
    </nav>
</template>

<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import ProductCard from "@/Components/Store/Product/ProductCard.vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";

const props = defineProps({
    isMobileMenuOpen: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['close-mobile-menu']);

const modules = [Navigation];

const page = usePage();

const closeMobileMenu = () => {
    emit('close-mobile-menu');
};

const menuCategories = computed(() => {
    const categories = page.props.menuCategories || [];
    // Vždy zobrazíme pouze prvních 8 kategorií
    return categories.slice(0, 8);
});

// Testovací produkty pro dropdown slider (10 produktů)
const testProducts = computed(() => [
    {
        id: 1,
        name: 'Vitamin C 1200 mg s šípky + Vitamin D + Zinek PREMIUM 90 tablet',
        slug: 'vitamin-c-1200-mg-sipky-vitamin-d-zinek-premium',
        description: 'MOVit Energy',
        price: 239,
        originalPrice: 289,
        discount: 17,
        image: 'https://www.dervit.cz/image/5465886',
        badges: ['Dárek zdarma'],
        stock: 10,
    },
    {
        id: 2,
        name: 'Smartphone X',
        description: 'Nejnovější model s pokročilými funkcemi',
        price: 15000,
        stock: 10,
        image: 'https://www.dervit.cz/image/3067692',
        badges: ['Doporučujeme', 'Doprava zdarma'],
        slug: 'smartphone-x',
    },
    {
        id: 3,
        name: 'Tablet Air',
        description: 'Lehký a elegantní tablet',
        price: 12000,
        stock: 8,
        image: 'https://www.dervit.cz/image/2828763',
        slug: 'tablet-air',
    },
    {
        id: 4,
        name: 'Sluchátka Pro',
        description: 'Bezdrátová sluchátka s aktivním potlačením hluku',
        price: 5000,
        stock: 15,
        image: 'https://www.dervit.cz/image/2828763',
        badges: ['Doprava zdarma'],
        slug: 'sluchatka-pro',
    },
    {
        id: 5,
        name: 'Myš Gaming',
        description: 'Precizní myš pro hráče',
        price: 2000,
        stock: 20,
        image: 'https://www.dervit.cz/image/5453346',
        slug: 'mys-gaming',
    },
    {
        id: 6,
        name: 'Klávesnice Mechanická',
        description: 'Mechanická klávesnice s RGB osvětlením',
        price: 3000,
        stock: 12,
        image: 'https://www.dervit.cz/image/5007910',
        badges: ['TIP'],
        slug: 'klavesnice-mechanicka',
    },
    {
        id: 7,
        name: 'Monitor 4K',
        description: '27" monitor s 4K rozlišením',
        price: 8000,
        stock: 6,
        image: 'https://www.dervit.cz/image/5338976',
        badges: ['Doporučujeme'],
        slug: 'monitor-4k',
    },
    {
        id: 8,
        name: 'Webová kamera',
        description: 'HD webová kamera pro videohovory',
        price: 1500,
        stock: 25,
        image: 'https://www.dervit.cz/image/4093077',
        slug: 'webova-kamera',
    },
    {
        id: 9,
        name: 'Laptop Pro',
        description: 'Výkonný notebook pro profesionály',
        price: 25000,
        stock: 5,
        image: 'https://www.dervit.cz/image/5465886',
        badges: ['TIP'],
        slug: 'laptop-pro',
    },
    {
        id: 10,
        name: 'Vitamin D3 + K2',
        description: 'Kombinace vitamínů pro zdravé kosti',
        price: 299,
        stock: 15,
        image: 'https://www.dervit.cz/image/3067692',
        badges: ['Akce'],
        slug: 'vitamin-d3-k2',
    },
]);

// Brandy pro spodní část dropdown menu
const brands = computed(() => [
    {
        id: 1,
        name: 'VICHY',
        logo: 'https://via.placeholder.com/120x40/2563eb/ffffff?text=VICHY',
    },
    {
        id: 2,
        name: 'BRAIN MAQ',
        logo: 'https://via.placeholder.com/120x40/1e40af/ffffff?text=BRAIN+MAQ',
    },
    {
        id: 3,
        name: 'NATIOS',
        tagline: "It's better to be the best",
        logo: 'https://via.placeholder.com/120x50/059669/ffffff?text=NATIOS',
    },
    {
        id: 4,
        name: 'CARNIUM',
        tagline: 'CBOTANICALS',
        logo: 'https://via.placeholder.com/120x50/7c3aed/ffffff?text=CARNIUM',
    },
]);
</script>

<style scoped>
.category-column {
    min-height: 200px;
}

.category-item:hover .category-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.category-dropdown {
    top: 100%;
    left: 0;
    right: 0;
    width: 100%;
    transform: translateY(-10px);
    transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease;
}

.products-swiper-container {
    overflow: hidden;
}

.products-swiper {
    padding-bottom: 10px;
}

.products-swiper :deep(.swiper-slide) {
    height: auto;
}

@media (max-width: 1280px) {
    .category-dropdown {
        left: calc(-1 * (100vw - 100%) / 2);
        right: calc(-1 * (100vw - 100%) / 2);
        margin-left: 0;
        width: 100vw;
        max-width: none;
    }
}
</style>
