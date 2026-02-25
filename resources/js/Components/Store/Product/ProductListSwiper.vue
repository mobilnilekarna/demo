<template>
    <section class="bg-white">
        <div class="mb-8">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">
                        <span :class="titleHighlightClass">{{ titleHighlight }}</span> {{ title }}
                    </h2>
                    <p class="text-gray-600">
                        {{ description }}
                    </p>
                </div>
                <Link
                    :href="viewAllLink"
                    :class="linkColorClass"
                    class="hover:underline"
                >
                    Zobrazit vše →
                </Link>
            </div>
        </div>
        <div class="products-swiper-container">
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
                        slidesPerView: 4,
                        spaceBetween: 16,
                    },
                    1280: {
                        slidesPerView: 4,
                        spaceBetween: 16,
                    },
                }"
                :navigation="canSwipe"
                :watch-overflow="true"
                class="products-swiper"
            >
                <swiper-slide
                    v-for="product in products"
                    :key="product.id"
                >
                    <ProductCard :product="product" />
                </swiper-slide>
            </swiper>
        </div>
    </section>
</template>

<script setup>
import { computed } from "vue";
import ProductCard from "@/Components/Store/Product/ProductCard.vue";
import { Link } from "@inertiajs/vue3";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";

const modules = [Navigation];

const props = defineProps({
    products: {
        type: Array,
        required: true,
        default: () => [],
    },
    title: {
        type: String,
        required: true,
    },
    titleHighlight: {
        type: String,
        default: "",
    },
    titleHighlightColor: {
        type: String,
        default: "green",
        validator: (value) => ["green", "red"].includes(value),
    },
    description: {
        type: String,
        required: true,
    },
    viewAllLink: {
        type: String,
        required: true,
    },
    linkColorClass: {
        type: String,
        default: "text-black hover:text-primary-600 hover:underline  transition-colors font-semibold",
    },
});

const titleHighlightClass = computed(() => {
    if (props.titleHighlightColor === "red") {
        return "bg-secondary-100 text-secondary-600";
    }
    return "bg-primary-100 text-primary-600";
});

const canSwipe = computed(() => props.products.length > 4);
</script>

<style scoped>
.products-swiper-container {
    position: relative;
    width: 100%;
}

.products-swiper {
    position: relative;
    padding: 0;
    width: 100%;
    padding-bottom: 60px;
    overflow: hidden;
}

.products-swiper :deep(.swiper-wrapper) {
    padding-left: 0;
    padding-right: 0;
}

.products-swiper :deep(.swiper-slide) {
    height: auto;
    padding: 0.25rem;
}

.products-swiper :deep(.swiper-slide > *) {
    height: 100%;
    width: 100%;
}

.products-swiper :deep(.swiper-button-next),
.products-swiper :deep(.swiper-button-prev) {
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

.products-swiper :deep(.swiper-button-next) {
    right: calc(50% - 60px);
}

.products-swiper :deep(.swiper-button-prev) {
    left: calc(50% - 60px);
}

.products-swiper :deep(.swiper-button-next:after),
.products-swiper :deep(.swiper-button-prev:after) {
    font-size: 14px;
    font-weight: bold;
    color: white;
    margin: 0;
}

.products-swiper :deep(.swiper-button-next:hover),
.products-swiper :deep(.swiper-button-prev:hover) {
    background: #15803d;
}

.products-swiper :deep(.swiper-button-disabled) {
    opacity: 0.35;
    cursor: auto;
    pointer-events: none;
}
</style>

