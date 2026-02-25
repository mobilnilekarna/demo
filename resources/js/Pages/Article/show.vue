<template>
    <AppLayout :title="article?.title || 'Článek'">
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <Breadcrumb
                custom-class="mb-8"
                :items="[
                    { label: 'Domů', href: '/' },
                    { label: 'Články', href: '/articles' },
                    { label: article.title },
                ]"
            />

            <!-- Article Header -->
            <article class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Featured Image -->
                <div class="relative h-96 overflow-hidden">
                    <img
                        :src="article.image"
                        :alt="article.title"
                        class="w-full h-full object-cover"
                    />
                </div>

                <!-- Article Content -->
                <div class="p-8 md:p-12">
                    <!-- Meta Information -->
                    <div
                        class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-6"
                    >
                        <div class="flex items-center">
                            <svg
                                class="w-4 h-4 mr-2"
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
                            <span>{{ formatDate(article.date) }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg
                                class="w-4 h-4 mr-2"
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
                            <span>{{ article.author }}</span>
                        </div>
                    </div>

                    <!-- Title -->
                    <h1
                        class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight"
                    >
                        {{ article.title }}
                    </h1>

                    <!-- Excerpt -->
                    <p
                        class="text-xl text-gray-600 mb-8 leading-relaxed"
                    >
                        {{ article.excerpt }}
                    </p>

                    <!-- Article Content -->
                    <div
                        class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-p:text-gray-700 prose-p:leading-relaxed prose-a:text-primary-600 prose-a:no-underline hover:prose-a:underline prose-strong:text-gray-900 prose-ul:text-gray-700 prose-ol:text-gray-700"
                        v-html="formatContent(article.content)"
                    ></div>
                </div>
            </article>

            <!-- Related Articles -->
            <div
                v-if="relatedArticles && relatedArticles.length > 0"
                class="mt-16"
            >
                <h2
                    class="text-3xl font-bold text-gray-900 mb-8"
                >
                    Související články
                </h2>
                <div
                    class="grid grid-cols-1 md:grid-cols-3 gap-6"
                >
                    <ArticleCard
                        v-for="relatedArticle in relatedArticles"
                        :key="relatedArticle.id"
                        :article="relatedArticle"
                        variant="minimal"
                        image-height="small"
                        :show-icons="false"
                    />
                </div>
            </div>

            <!-- Back to Articles -->
            <div class="mt-12 text-center">
                <Link
                    href="/articles"
                    class="inline-flex items-center text-primary-600 hover:text-primary-700 font-semibold transition-colors"
                >
                    <svg
                        class="w-5 h-5 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>
                    Zpět na všechny články
                </Link>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ArticleCard from "@/Components/Store/Article/ArticleCard.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";

const props = defineProps({
    article: {
        type: Object,
        required: true,
    },
    relatedArticles: {
        type: Array,
        default: () => [],
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

const formatContent = (content) => {
    if (!content) return "";

    // Jednoduché formátování markdown-like syntaxe na HTML
    let formatted = content;

    // Nadpisy ##
    formatted = formatted.replace(/^## (.+)$/gm, '<h2 class="text-3xl font-bold text-gray-900 mt-8 mb-4">$1</h2>');
    formatted = formatted.replace(/^### (.+)$/gm, '<h3 class="text-2xl font-bold text-gray-900 mt-6 mb-3">$1</h3>');

    // Tučný text **text**
    formatted = formatted.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');

    // Odstavce (dvojité nové řádky)
    formatted = formatted.split(/\n\n+/).map(paragraph => {
        paragraph = paragraph.trim();
        if (paragraph && !paragraph.startsWith('<')) {
            return `<p class="mb-4">${paragraph}</p>`;
        }
        return paragraph;
    }).join('\n');

    // Seznamy
    formatted = formatted.replace(/^\- (.+)$/gm, '<li class="ml-6 mb-2">$1</li>');
    formatted = formatted.replace(/(<li.*<\/li>)/s, '<ul class="list-disc mb-4">$1</ul>');

    // Odkazy [text](url)
    formatted = formatted.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" class="text-primary-600 hover:underline">$1</a>');

    return formatted;
};
</script>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

