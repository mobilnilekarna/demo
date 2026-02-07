<template>
    <section class="bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    Novinky a články
                </h2>
                <p class="text-gray-600">
                    Užitečné tipy, recenze a novinky ze světa technologií
                </p>
            </div>
        </div>
        <div class="articles-swiper-container">
            <swiper
                :modules="modules"
                :slides-per-view="1"
                :space-between="16"
                :breakpoints="{
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 16,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 16,
                    },
                    1280: {
                        slidesPerView: 3,
                        spaceBetween: 16,
                    },
                }"
                :navigation="canSwipe"
                :watch-overflow="true"
                class="articles-swiper"
            >
                <swiper-slide
                    v-for="article in articles"
                    :key="article.id"
                >
                    <ArticleCard
                        :article="article"
                        variant="compact"
                        image-height="small"
                        :show-icons="false"
                    />
                </swiper-slide>
            </swiper>
        </div>
    </section>
</template>

<script setup>
import { computed } from "vue";
import ArticleCard from "@/Components/Store/Article/ArticleCard.vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";

const modules = [Navigation];

const props = defineProps({
    articles: {
        type: Array,
        required: true,
        default: () => [],
    },
});

const canSwipe = computed(() => props.articles.length > 3);
</script>

<style scoped>
.articles-swiper-container {
    position: relative;
    width: 100%;
}

.articles-swiper {
    position: relative;
    padding: 0;
    width: 100%;
    padding-bottom: 60px;
    overflow: hidden;
}

.articles-swiper :deep(.swiper-wrapper) {
    padding-left: 0;
    padding-right: 0;
}

.articles-swiper :deep(.swiper-slide) {
    height: auto;
    padding: 0.25rem;
}

.articles-swiper :deep(.swiper-slide > *) {
    height: 100%;
    width: 100%;
}

.articles-swiper :deep(.swiper-button-next),
.articles-swiper :deep(.swiper-button-prev) {
    color: white;
    background: #16a34a;
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

.articles-swiper :deep(.swiper-button-next) {
    right: calc(50% - 60px);
}

.articles-swiper :deep(.swiper-button-prev) {
    left: calc(50% - 60px);
}

.articles-swiper :deep(.swiper-button-next:after),
.articles-swiper :deep(.swiper-button-prev:after) {
    font-size: 14px;
    font-weight: bold;
    color: white;
    margin: 0;
}

.articles-swiper :deep(.swiper-button-next:hover),
.articles-swiper :deep(.swiper-button-prev:hover) {
    background: #15803d;
}

.articles-swiper :deep(.swiper-button-disabled) {
    opacity: 0.35;
    cursor: auto;
    pointer-events: none;
}
</style>

