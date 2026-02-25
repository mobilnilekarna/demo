<template>
    <header class="bg-white shadow-sm">
        <!-- Promo Banner -->
        <div class="bg-primary-500 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-2">
                <div class="flex items-center justify-center">
                    <span class="text-sm font-medium">🎁 Speciální akce - dárky k nákupu!</span>
                </div>
            </div>
        </div>

        <!-- Top Row -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-2">
            <div class="header-grid h-16">
                <!-- Hamburger menu -->
                <button
                    @click="toggleMobileMenu"
                    class="header-hamburger p-2 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors"
                    aria-label="Otevřít menu"
                >
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path v-if="!props.isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Logo -->
                <Link href="/" class="header-logo flex items-center space-x-1">
                    <img src="/logo.svg" alt="Logo" :width="isMobile ? 150 : 200" />
                </Link>

                <!-- Navigation Links -->
                <nav class="header-nav flex items-center space-x-3">
                    <Link href="/find-pharmacy" class="flex items-center space-x-2 px-4 py-2 bg-green-50 text-green-600 rounded-2xl hover:bg-green-100 transition-colors">
                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shop" viewBox="0 0 16 16">
                          <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5M4 15h3v-5H4zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm3 0h-2v3h2z"/>
                        </svg>
                        <span class="text-sm font-medium">O lékárně</span>
                    </Link>
                    <Link href="/find-pharmacy" class="flex items-center space-x-2 px-4 py-2 bg-secondary-50 text-secondary-600 rounded-2xl hover:bg-secondary-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-text" viewBox="0 0 16 16">
                        <path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5"/>
                        <path d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                        </svg>
                        <span class="text-sm font-medium">Obchodní podmínky</span>
                    </Link>
                    <Link href="/find-pharmacy" class="flex items-center space-x-2 px-4 py-2 bg-purple-50 text-purple-600 rounded-2xl hover:bg-purple-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-truck" viewBox="0 0 16 16">
                        <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A2 2 0 0 1 4.732 11h5.536a2 2 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
                        </svg>
                        <span class="text-sm font-medium">Doprava a platba</span>
                    </Link>
                    <Link href="/find-pharmacy" class="flex items-center space-x-2 px-4 py-2 bg-yellow-50 text-yellow-600 rounded-2xl hover:bg-yellow-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upc-scan" viewBox="0 0 16 16">
                        <path d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5M.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5M3 4.5a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0zm2 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0z"/>
                        </svg>
                        <span class="text-sm font-medium">eRecept</span>
                    </Link>
                </nav>

                <!-- Contact Info -->
                <div class="header-contact flex items-center space-x-4">
                    <!-- Phone Icon Box -->
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-primary-50 border border-primary-500 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-headset text-primary-600" viewBox="0 0 16 16">
                                <path d="M8 1a5 5 0 0 0-5 5v1h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a6 6 0 1 1 12 0v6a2.5 2.5 0 0 1-2.5 2.5H9.366a1 1 0 0 1-.866.5h-1a1 1 0 1 1 0-2h1a1 1 0 0 1 .866.5H11.5A1.5 1.5 0 0 0 13 12h-1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h1V6a5 5 0 0 0-5-5"/>
                            </svg>
                        </div>
                    </div>
                    <!-- Phone and Hours -->
                    <div class="flex flex-col">
                        <a href="tel:+420737994450" title="Zavolejte nám" class="text-green-600 font-bold text-base hover:text-green-700 transition-colors">
                            +420 737 994 450
                        </a>
                        <span class="text-gray-500 text-sm">
                            Po–Pá: 8:00–18:00
                        </span>
                    </div>
                </div>

                <!-- User Actions (Auth, Wishlist, Basket) - pouze na mobilu -->
                <div class="header-actions-mobile flex items-center space-x-1">
                    <div class="h-auto">
                        <AuthDropDown variant="icon" />
                    </div>
                    <div class="h-auto">
                        <WishlistDropDown variant="icon" />
                    </div>
                    <div class="h-auto">
                        <BasketDropDown variant="icon" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Row - Search Bar with Actions -->
        <div class="border-t border-gray-200 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4">
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Algolia Search Bar -->
                    <div class="flex-1 relative search-wrapper" v-if="searchClient">
                        <div class="relative">
                            <div class="flex-1 relative border border-gray-300 rounded-lg h-12 flex items-center overflow-hidden focus-within:ring-2 focus-within:ring-primary-500 focus-within:border-primary-500">
                                <svg class="w-5 h-5 text-gray-400 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input
                                    type="search"
                                    v-model="searchQuery"
                                    @input="performSearch"
                                    @keyup.enter="handleSearch(searchQuery)"
                                    placeholder="Hledejte produkt, lékárnu, nebo co vás trápí"
                                    class="flex-1 h-full px-4 bg-transparent border-none focus:outline-none"
                                />
                                <button
                                    @click="handleSearch(searchQuery)"
                                    class="absolute right-1 top-1 bottom-1 px-3 sm:px-5 bg-gray-200 hover:bg-gray-800 hover:text-white text-black font-semibold rounded-md transition-colors flex items-center space-x-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <span class="hidden sm:inline">Vyhledat</span>
                                </button>
                            </div>

                            <!-- Search Results Dropdown -->
                            <div
                                v-if="showResults && searchQuery && searchResults.length > 0"
                                class="absolute z-50 w-full mt-2 bg-white border border-gray-300 rounded-lg shadow-lg max-h-96 overflow-y-auto"
                            >
                                <div class="p-4">
                                    <div
                                        v-for="hit in searchResults.slice(0, 5)"
                                        :key="hit.objectID"
                                        class="py-3 px-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                                        @click="handleResultClick(hit)"
                                    >
                                        <div class="flex items-start space-x-3">
                                            <div v-if="getImageUrl(hit)" class="flex-shrink-0">
                                                <img
                                                    :src="getImageUrl(hit)"
                                                    :alt="hit.title || 'Náhled filmu'"
                                                    class="w-16 h-24 object-cover rounded"
                                                    @error="handleImageError"
                                                />
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 truncate">
                                                    <span v-html="highlightText(hit.title, searchQuery)"></span>
                                                </p>
                                                <div v-if="hit.release_date" class="flex items-center space-x-2 mt-1">
                                                    <span class="text-xs text-gray-500">{{ formatDate(hit.release_date) }}</span>
                                                    <span v-if="hit.vote_average" class="text-xs text-yellow-600 flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                        {{ hit.vote_average.toFixed(1) }}
                                                    </span>
                                                </div>
                                                <p v-if="hit.overview" class="text-xs text-gray-600 line-clamp-2 mt-1">
                                                    {{ hit.overview }}
                                                </p>
                                                <div v-if="hit.genres && hit.genres.length > 0" class="flex flex-wrap gap-1 mt-2">
                                                    <span
                                                        v-for="genre in hit.genres.slice(0, 3)"
                                                        :key="genre"
                                                        class="text-xs px-2 py-0.5 bg-gray-100 text-gray-700 rounded"
                                                    >
                                                        {{ genre }}
                                                    </span>
                                                </div>
                                                <div v-if="hit.cast && hit.cast.length > 0" class="text-xs text-gray-500 mt-1">
                                                    <span class="font-medium">Hrají:</span> {{ hit.cast.slice(0, 2).map(c => c.name).join(', ') }}
                                                    <span v-if="hit.cast.length > 2">...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="totalResults > 5" class="pt-2 pb-1 text-center">
                                        <button
                                            @click="handleViewAll(searchQuery)"
                                            class="text-sm text-primary-600 hover:text-primary-800 font-medium"
                                        >
                                            Zobrazit všechny výsledky ({{ totalResults }})
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Fallback if Algolia is not configured -->
                    <form v-else @submit.prevent="handleFallbackSearch" class="flex-1 relative border border-gray-300 rounded-lg h-12 flex items-center overflow-hidden focus-within:ring-2 focus-within:ring-primary-500 focus-within:border-primary-500">
                        <input
                            type="text"
                            v-model="fallbackSearchQuery"
                            placeholder="Hledejte produkt, lékárnu, nebo co vás trápí"
                            class="flex-1 h-full px-4 pr-36 bg-transparent border-none focus:outline-none"
                        />
                        <button type="submit" class="absolute right-1 top-1 bottom-1 px-3 sm:px-5 bg-gray-100 hover:bg-gray-800 text-white font-semibold rounded-md transition-colors flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span class="hidden sm:inline">Vyhledat</span>
                        </button>
                    </form>

                    <!-- User Actions (Auth, Wishlist, Basket) - pouze na desktopu -->
                    <div class="header-actions-desktop hidden sm:flex items-center space-x-3 flex-shrink-0">
                        <div class="h-12">
                            <AuthDropDown variant="button" />
                        </div>
                        <div class="h-12">
                            <WishlistDropDown variant="button" />
                        </div>
                        <div class="h-12">
                            <BasketDropDown variant="button" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </header>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { algoliasearch } from 'algoliasearch';
import BasketDropDown from "@/Components/Basket/BasketDropDown.vue";
import AuthDropDown from "@/Components/Auth/AuthDropDown.vue";
import WishlistDropDown from "@/Components/Wishlist/WishlistDropDown.vue";
import { useAnalytics } from "@/Composables/useAnalytics";

const props = defineProps({
    isMobileMenuOpen: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['toggle-mobile-menu']);

const windowWidth = ref(typeof window !== 'undefined' ? window.innerWidth : 1024);

const isMobile = computed(() => windowWidth.value < 640);

const toggleMobileMenu = () => {
    emit('toggle-mobile-menu');
};

const handleResize = () => {
    windowWidth.value = window.innerWidth;
};

const { trackSearch } = useAnalytics();

// Algolia configuration
const appId = import.meta.env.VITE_ALGOLIA_APP_ID;
const apiKey = import.meta.env.VITE_ALGOLIA_APP_SEARCH_API_KEY;
const indexName = import.meta.env.VITE_ALGOLIA_APP_INDEX;

// Search state
const searchQuery = ref('');
const searchResults = ref([]);
const totalResults = ref(0);
const showResults = ref(false);
let searchTimeout = null;

// Fallback search state (when Algolia is not configured)
const fallbackSearchQuery = ref('');

// Initialize Algolia client
const searchClient = computed(() => {
    if (!appId || !apiKey) {
        console.warn('Algolia credentials are missing. Please check your environment variables.');
        return null;
    }
    return algoliasearch(appId, apiKey);
});

// Close search results
const closeResults = () => {
    showResults.value = false;
    searchResults.value = [];
    totalResults.value = 0;
};

// Perform search with debouncing
const performSearch = () => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }

    if (!searchQuery.value || searchQuery.value.trim().length < 2) {
        searchResults.value = [];
        totalResults.value = 0;
        showResults.value = false;
        return;
    }

    showResults.value = true;

    // Debouncing - čeká 300ms po posledním stisknutí klávesy, než pošle request
    // Tím se sníží počet requestů a šetří se API quota
    searchTimeout = setTimeout(async () => {
        if (!searchClient.value || !indexName) {
            return;
        }

        try {
            const { results } = await searchClient.value.search([
                {
                    indexName: indexName,
                    query: searchQuery.value,
                    params: {
                        hitsPerPage: 5,
                        attributesToRetrieve: [
                            'objectID',
                            'title',
                            'original_title',
                            'overview',
                            'poster_path',
                            'release_date',
                            'vote_average',
                            'vote_count',
                            'popularity',
                            'genres',
                            'cast',
                            'crew',
                            'keywords',
                            'original_language',
                            'id'
                        ]
                    }
                }
            ]);

            if (results && results[0]) {
                searchResults.value = results[0].hits || [];
                totalResults.value = results[0].nbHits || 0;
                showResults.value = true;
            } else {
                searchResults.value = [];
                totalResults.value = 0;
                showResults.value = false;
            }
        } catch (error) {
            console.error('Algolia search error:', error);
            searchResults.value = [];
            totalResults.value = 0;
        }
    }, 300); // 300ms debounce - můžete změnit na menší hodnotu (např. 150ms) pro rychlejší odezvu
};

// Get image URL from hit
const getImageUrl = (hit) => {
    if (hit.poster_path) {
        // poster_path už obsahuje plnou URL z TMDB
        return hit.poster_path;
    }
    return null;
};

// Format date
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('cs-CZ', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

// Handle image loading error
const handleImageError = (event) => {
    // Hide broken image
    event.target.style.display = 'none';
};

// Highlight search text in results
const highlightText = (text, query) => {
    if (!text || !query) return text;
    const regex = new RegExp(`(${query})`, 'gi');
    return text.replace(regex, '<mark class="bg-yellow-200">$1</mark>');
};

// Handle search button click
const handleSearch = (query) => {
    if (query && query.trim()) {
        const trimmedQuery = query.trim();
        trackSearch(trimmedQuery);
        router.visit(`/vyhledavani?q=${encodeURIComponent(trimmedQuery)}`, {
            preserveState: true,
            preserveScroll: true,
        });
    }
};

// Handle result click
const handleResultClick = (hit) => {
    // Navigate to movie detail page
    if (hit.id) {
        router.visit(`/movies/${hit.id}`);
    } else if (hit.objectID) {
        router.visit(`/movies/${hit.objectID}`);
    }
    // Clear search after click
    searchQuery.value = '';
    searchResults.value = [];
    showResults.value = false;
};

// Handle click outside to close results
const handleClickOutside = (event) => {
    const searchWrapper = event.target.closest('.search-wrapper');
    if (!searchWrapper && showResults.value) {
        closeResults();
    }
};

// Handle ESC key to close results
const handleEscapeKey = (event) => {
    if (event.key === 'Escape' && showResults.value) {
        closeResults();
    }
};

// Setup event listeners
onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    document.addEventListener('keydown', handleEscapeKey);
    window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
    document.removeEventListener('keydown', handleEscapeKey);
    window.removeEventListener('resize', handleResize);
});

// Handle "View All" click
const handleViewAll = (query) => {
    if (query && query.trim()) {
        const trimmedQuery = query.trim();
        trackSearch(trimmedQuery);
        router.visit(`/vyhledavani?q=${encodeURIComponent(trimmedQuery)}`, {
            preserveState: true,
            preserveScroll: true,
        });
    }
};

// Handle fallback search form submit
const handleFallbackSearch = (e) => {
    e?.preventDefault();
    const query = fallbackSearchQuery.value?.trim();
    if (query) {
        trackSearch(query);
        router.visit('/vyhledavani', { q: query }, {
            preserveState: true,
            preserveScroll: true,
        });
    }
};
</script>

<style scoped>
.header-grid {
    display: grid;
    grid-template-columns: auto 1fr auto;
    grid-template-areas:
        "hamburger logo actions";
    gap: 1rem;
    align-items: center;
}

.header-hamburger {
    grid-area: hamburger;
}

.header-logo {
    grid-area: logo;
}

.header-nav {
    grid-area: nav;
    display: none;
}

.header-contact {
    grid-area: contact;
    display: none;
}

.header-actions-mobile {
    grid-area: actions;
}

.header-actions-desktop {
    display: none;
}

/* Desktop (sm) - skryjeme mobilní akce a zobrazíme desktop akce v bottom row */
@media (min-width: 640px) {
    .header-actions-mobile {
        display: none;
    }

    .header-actions-desktop {
        display: flex;
    }
}

/* Desktop (lg) - zobrazíme nav */
@media (min-width: 1024px) {
    .header-grid {
        grid-template-columns: auto auto 1fr auto;
        grid-template-areas:
            "logo nav . contact";
    }

    .header-hamburger {
        display: none;
    }

    .header-nav {
        display: flex;
    }
}

/* Extra large (xl) - zobrazíme contact */
@media (min-width: 1280px) {
    .header-grid {
        grid-template-columns: auto auto 1fr auto;
        grid-template-areas:
            "logo nav . contact";
    }

    .header-contact {
        display: flex;
    }
}
</style>

