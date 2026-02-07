<template>
    <section class="max-w-7xl mx-auto overflow-x-hidden w-full">
        <div class="relative w-full">
            <swiper
                :modules="modules"
                :slides-per-view="1"
                :space-between="15"
                :breakpoints="{
                    640: {
                        slidesPerView: 2.2,
                        spaceBetween: 20,
                    },
                    1024: {
                        slidesPerView: 3.2,
                        spaceBetween: 24,
                    },
                    1280: {
                        slidesPerView: 4.2,
                        spaceBetween: 24,
                    },
                    1920: {
                        slidesPerView: 4.2,
                        spaceBetween: 24,
                    },
                }"
                :navigation="canNavigate"
                :watch-overflow="true"
                class="promo-banner-swiper"
            >
                <swiper-slide
                    v-for="(banner, index) in banners"
                    :key="index"
                >
                    <PromoCard :banner="banner" />
                </swiper-slide>
            </swiper>
        </div>
    </section>
</template>

<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import PromoCard from "./PromoCard.vue";

const modules = [Navigation];

const props = defineProps({
    banners: {
        type: Array,
        required: true,
        default: () => [],
    },
});

const canNavigate = computed(() => props.banners.length > 4);
</script>

<style scoped>


.promo-banner-swiper {
    position: relative;
    padding: 0;
    width: 100%;
    padding-bottom: 60px;
    overflow: hidden;
}


.promo-banner-swiper :deep(.swiper-slide) {
    height: auto;
}

.promo-banner-swiper :deep(.swiper-button-next),
.promo-banner-swiper :deep(.swiper-button-prev) {
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

.promo-banner-swiper :deep(.swiper-wrapper) {
    margin-bottom: 15px;
}

.promo-banner-swiper :deep(.swiper-button-next) {
    right: calc(50% - 60px);
}

.promo-banner-swiper :deep(.swiper-button-prev) {
    left: calc(50% - 60px);
}

.promo-banner-swiper :deep(.swiper-button-next:after),
.promo-banner-swiper :deep(.swiper-button-prev:after) {
    font-size: 14px;
    font-weight: bold;
    color: white;
    margin: 0;
}

.promo-banner-swiper :deep(.swiper-button-next:hover),
.promo-banner-swiper :deep(.swiper-button-prev:hover) {
    background: #15803d;
}

.promo-banner-swiper :deep(.swiper-button-disabled) {
    opacity: 0.35;
    cursor: auto;
    pointer-events: none;
}
</style>

