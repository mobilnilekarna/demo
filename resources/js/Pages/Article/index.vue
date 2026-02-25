<template>
    <AppLayout title="Články">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-12 text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    Novinky a články
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Užitečné tipy, recenze a novinky ze světa technologií
                </p>
            </div>

            <ArticleList :articles="articles" :pagination="pagination" />
        </section>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ArticleList from "@/Components/Store/Article/ArticleList.vue";

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
</script>


