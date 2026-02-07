<template>
    <div>
         <swiper
                :slides-per-view="6"
                :space-between="50"
                @swiper="onSwiper"
                @slideChange="onSlideChange"
            >
                <swiper-slide
                    v-for="(slideContent, index) in 10"
                    :key="index"
                    :virtualIndex="index"
                >
                    <Image
                        src="/vichy.jpg"
                        height="40px"
                        class="m-auto"
                        itemprop="image"
                    />
                </swiper-slide>
            </swiper>
    </div>
</template>

<script setup>
import { Image } from "@unpic/vue";

// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";

// Import Swiper styles
import "swiper/css";

</script>
