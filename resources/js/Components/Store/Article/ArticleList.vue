<template>
    <div>
        <!-- Articles Grid -->
        <div
            v-if="articles.length > 0"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12"
        >
            <ArticleCard
                v-for="article in articles"
                :key="article.id"
                :article="article"
                variant="default"
                image-height="medium"
                :show-icons="true"
            />
        </div>

        <!-- Empty State -->
        <div
            v-else
            class="text-center py-16"
        >
            <svg
                class="w-20 h-20 text-gray-400 mx-auto mb-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"
                />
            </svg>
            <h3 class="text-xl font-medium text-gray-900 mb-2">
                Žádné články
            </h3>
            <p class="text-gray-500">
                Momentálně nemáme žádné články k dispozici.
            </p>
        </div>

        <!-- Pagination -->
        <div
            v-if="pagination.last_page > 1"
            class="flex items-center justify-center mt-12"
        >
            <nav
                class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                aria-label="Pagination"
            >
                <!-- Previous Button -->
                <Link
                    :href="
                        pagination.current_page > 1
                            ? `/articles?page=${pagination.current_page - 1}`
                            : '#'
                    "
                    :class="[
                        'relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 text-sm font-medium transition-colors',
                        pagination.current_page === 1
                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                            : 'bg-white text-gray-700 hover:bg-gray-50',
                    ]"
                    :disabled="pagination.current_page === 1"
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
                    <span class="ml-1 hidden sm:inline">Předchozí</span>
                </Link>

                <!-- Page Numbers -->
                <template v-for="page in getPageNumbers()" :key="page">
                    <Link
                        v-if="page !== '...'"
                        :href="`/articles?page=${page}`"
                        :class="[
                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors',
                            page === pagination.current_page
                                ? 'z-10 bg-primary-600 border-primary-600 text-white'
                                : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50',
                        ]"
                    >
                        {{ page }}
                    </Link>
                    <span
                        v-else
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                    >
                        ...
                    </span>
                </template>

                <!-- Next Button -->
                <Link
                    :href="
                        pagination.current_page < pagination.last_page
                            ? `/articles?page=${pagination.current_page + 1}`
                            : '#'
                    "
                    :class="[
                        'relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 text-sm font-medium transition-colors',
                        pagination.current_page === pagination.last_page
                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                            : 'bg-white text-gray-700 hover:bg-gray-50',
                    ]"
                    :disabled="
                        pagination.current_page === pagination.last_page
                    "
                >
                    <span class="mr-1 hidden sm:inline">Další</span>
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
                </Link>
            </nav>
        </div>

        <!-- Pagination Info -->
        <div
            v-if="pagination.last_page > 1"
            class="text-center mt-6 text-sm text-gray-600"
        >
            Zobrazeno {{ pagination.from }} - {{ pagination.to }} z
            {{ pagination.total }} článků
        </div>
    </div>
</template>

<script setup>
import ArticleCard from "@/Components/Store/Article/ArticleCard.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    articles: {
        type: Array,
        default: () => [],
    },
    pagination: {
        type: Object,
        required: true,
    },
});

const getPageNumbers = () => {
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
</script>

