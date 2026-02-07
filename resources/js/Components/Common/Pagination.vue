<template>
    <div
        v-if="pagination && pagination.last_page > 1"
        class="flex flex-col sm:flex-row items-center justify-between gap-4"
    >
        <!-- Pagination Info -->
        <div class="text-sm text-gray-600">
            <slot name="info" :pagination="pagination">
                Zobrazeno {{ pagination.from }} - {{ pagination.to }} z
                {{ pagination.total }} {{ itemLabel }}
            </slot>
        </div>

        <!-- Pagination Controls -->
        <nav
            class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
            aria-label="Pagination"
        >
            <!-- Previous Button -->
            <button
                v-if="pagination.current_page > 1"
                @click="goToPage(pagination.current_page - 1)"
                :class="[
                    'relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 text-sm font-medium transition-colors bg-white text-gray-700 hover:bg-gray-50',
                ]"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
                <span class="ml-1 hidden sm:inline">{{ previousLabel }}</span>
            </button>
            <span
                v-else
                :class="[
                    'relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 text-sm font-medium bg-gray-100 text-gray-400 cursor-not-allowed',
                ]"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
                <span class="ml-1 hidden sm:inline">{{ previousLabel }}</span>
            </span>

            <!-- Page Numbers -->
            <template v-for="page in getPageNumbers()" :key="page">
                <button
                    v-if="page !== '...' && page !== pagination.current_page"
                    @click="goToPage(page)"
                    :class="[
                        'relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors bg-white border-gray-300 text-gray-700 hover:bg-gray-50',
                    ]"
                >
                    {{ page }}
                </button>
                <span
                    v-else-if="page === pagination.current_page"
                    :class="[
                        'relative inline-flex items-center px-4 py-2 border text-sm font-medium z-10 bg-primary-600 border-primary-600 text-white cursor-default',
                    ]"
                >
                    {{ page }}
                </span>
                <span
                    v-else
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                >
                    ...
                </span>
            </template>

            <!-- Next Button -->
            <button
                v-if="pagination.current_page < pagination.last_page"
                @click="goToPage(pagination.current_page + 1)"
                :class="[
                    'relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 text-sm font-medium transition-colors bg-white text-gray-700 hover:bg-gray-50',
                ]"
            >
                <span class="mr-1 hidden sm:inline">{{ nextLabel }}</span>
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>
            </button>
            <span
                v-else
                :class="[
                    'relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 text-sm font-medium bg-gray-100 text-gray-400 cursor-not-allowed',
                ]"
            >
                <span class="mr-1 hidden sm:inline">{{ nextLabel }}</span>
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>
            </span>
        </nav>
    </div>
</template>

<script setup>
import { router } from "@inertiajs/vue3";
import { onMounted, nextTick } from "vue";

const props = defineProps({
    pagination: {
        type: Object,
        required: true,
    },
    baseUrl: {
        type: String,
        required: true,
    },
    itemLabel: {
        type: String,
        default: "položek",
    },
    previousLabel: {
        type: String,
        default: "Předchozí",
    },
    nextLabel: {
        type: String,
        default: "Další",
    },
    queryParams: {
        type: Object,
        default: () => ({}),
    },
    scrollAnchor: {
        type: String,
        default: "product-list-anchor",
    },
});

// Methods: Get page URL with query params
const getPageUrl = (page) => {
    const params = new URLSearchParams();

    if (page > 1) {
        params.set("page", page);
    }

    // Add additional query params
    Object.keys(props.queryParams).forEach((key) => {
        const value = props.queryParams[key];
        if (value !== "" && value !== null && value !== undefined) {
            if (Array.isArray(value)) {
                value.forEach((v) => params.append(`${key}[]`, v));
            } else {
                params.set(key, value);
            }
        }
    });

    const queryString = params.toString();
    return queryString ? `${props.baseUrl}?${queryString}` : props.baseUrl;
};

// Methods: Get page numbers for pagination
const getPageNumbers = () => {
    if (!props.pagination) return [];

    const current = props.pagination.current_page;
    const last = props.pagination.last_page;
    const pages = [];

    if (last <= 7) {
        // Pokud je málo stránek, zobraz všechny
        for (let i = 1; i <= last; i++) {
            pages.push(i);
        }
    } else {
        // Vždy zobraz první stránku
        pages.push(1);

        if (current > 3) {
            pages.push("...");
        }

        // Zobraz stránky kolem aktuální stránky
        const start = Math.max(2, current - 1);
        const end = Math.min(last - 1, current + 1);

        for (let i = start; i <= end; i++) {
            pages.push(i);
        }

        if (current < last - 2) {
            pages.push("...");
        }

        // Vždy zobraz poslední stránku
        if (last > 1) {
            pages.push(last);
        }
    }

    return pages;
};

// Methods: Scroll to anchor
const scrollToAnchor = () => {
    nextTick(() => {
        requestAnimationFrame(() => {
            const anchor = document.getElementById(props.scrollAnchor);
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

// Methods: Navigate to page and scroll to anchor
const goToPage = (page) => {
    const queryParams = { ...props.queryParams };

    if (page > 1) {
        queryParams.page = page;
    } else {
        delete queryParams.page;
    }

    // Prevent default scroll to top
    router.get(props.baseUrl, queryParams, {
        preserveState: true,
        preserveScroll: true,
        onStart: () => {
            // Store current scroll position or anchor position
            const anchor = document.getElementById(props.scrollAnchor);
            if (anchor) {
                sessionStorage.setItem('scrollToAnchor', props.scrollAnchor);
            }
        },
        onSuccess: () => {
            scrollToAnchor();
        },
    });
};

// Scroll to anchor on mount if page > 1 (when coming from another page)
onMounted(() => {
    if (props.pagination.current_page > 1) {
        scrollToAnchor();
    }
});
</script>

