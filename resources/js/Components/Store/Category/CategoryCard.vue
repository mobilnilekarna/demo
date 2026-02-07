<template>
    <a
        href=""
        :class="$style['category-item']"
        :title="category.name"
        class="flex align-middle justify-start items-center gap-4"
    >
        <picture>
            <img src="https://marketnadruhou.local/favicon.ico" :alt="category.name">
        </picture>
        <h2>{{ category.name }}</h2>
    </a>
</template>

<script setup>
const props = defineProps({
    category: {
        type: Object,
        required: true,
    },
});
</script>

<style module>
    .category-list {
    }
    .category-item {
        border: 1px solid green;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
    }
</style>
