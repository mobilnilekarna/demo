<template>
    <article
        :class="[
            'bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300',
            variant === 'compact'
                ? 'hover:shadow-lg'
                : 'hover:shadow-xl transform hover:-translate-y-1',
        ]"
        itemscope
        itemtype="https://schema.org/Article"
    >
        <Link
            :href="`/article/${article.slug || article.id}`"
            itemprop="url"
        >
            <div
                :class="[
                    'relative overflow-hidden',
                    imageHeight === 'small' ? 'h-48' : 'h-64',
                ]"
            >
                <img
                    :src="article.image"
                    :alt="article.title"
                    :class="[
                        'w-full h-full object-cover transition-transform duration-500',
                        variant === 'compact'
                            ? 'hover:scale-105'
                            : 'hover:scale-110',
                    ]"
                    itemprop="image"
                />
            </div>
            <div :class="variant === 'compact' ? 'p-6' : 'p-6'">
                <div
                    :class="[
                        'flex items-center mb-3',
                        variant === 'compact'
                            ? 'text-sm'
                            : 'text-sm',
                        'text-gray-500',
                    ]"
                >
                    <svg
                        v-if="showIcons"
                        class="w-4 h-4 mr-1"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                    <time
                        :datetime="article.date"
                        itemprop="datePublished"
                    >
                        {{ formatDate(article.date) }}
                    </time>
                    <span
                        v-if="variant !== 'minimal'"
                        class="mx-2"
                    >
                        •
                    </span>
                    <svg
                        v-if="showIcons && variant !== 'minimal'"
                        class="w-4 h-4 mr-1"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                        />
                    </svg>
                    <span
                        v-if="variant !== 'minimal'"
                        itemprop="author"
                        itemscope
                        itemtype="https://schema.org/Person"
                    >
                        <span itemprop="name">{{ article.author }}</span>
                    </span>
                </div>
                <h2
                    :class="[
                        'font-semibold text-gray-900 mb-3 hover:text-primary-600 transition-colors',
                        variant === 'compact'
                            ? 'text-xl'
                            : variant === 'minimal'
                            ? 'text-lg'
                            : 'text-xl',
                        variant === 'minimal' ? 'line-clamp-2' : 'line-clamp-2',
                    ]"
                    itemprop="headline"
                >
                    {{ article.title }}
                </h2>
                <p
                    v-if="variant !== 'minimal'"
                    :class="[
                        'text-gray-600 mb-4 leading-relaxed',
                        variant === 'compact'
                            ? 'line-clamp-3'
                            : 'line-clamp-3',
                        variant === 'minimal' ? 'text-sm' : '',
                    ]"
                    itemprop="description"
                >
                    {{ article.excerpt }}
                </p>
                <div
                    v-if="variant !== 'minimal'"
                    class="text-primary-600 hover:text-primary-700 font-semibold inline-flex items-center group"
                >
                    Číst více
                    <svg
                        class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform"
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
                </div>
            </div>
        </Link>
    </article>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    article: {
        type: Object,
        required: true,
    },
    variant: {
        type: String,
        default: "default", // 'default', 'compact', 'minimal'
        validator: (value) =>
            ["default", "compact", "minimal"].includes(value),
    },
    imageHeight: {
        type: String,
        default: "medium", // 'small', 'medium'
        validator: (value) => ["small", "medium"].includes(value),
    },
    showIcons: {
        type: Boolean,
        default: true,
    },
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("cs-CZ", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

