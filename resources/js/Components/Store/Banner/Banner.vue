<template>
    <section class="relative max-w-7xl mx-auto">
        <swiper
            :modules="modules"
            :slides-per-view="1"
            :loop="true"
            :autoplay="{
                delay: 5000,
                disableOnInteraction: false,
            }"
            :pagination="{ clickable: true }"
            :navigation="true"
            class="hero-swiper"
        >
            <swiper-slide>
                <div class="relative h-[500px] md:h-[600px]">
                    <img
                        src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=1920&q=80"
                        alt="Notebooky"
                        class="w-full h-full object-cover"
                    />
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <div class="text-center text-white px-4">
                            <h2 class="text-4xl md:text-6xl font-bold mb-4">
                                Prémiové notebooky
                            </h2>
                            <p class="text-xl md:text-2xl mb-8">
                                Výkon a spolehlivost pro každý den
                            </p>
                            <Link
                                href="/products"
                                class="inline-block bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors"
                            >
                                Prohlédnout nabídku
                            </Link>
                        </div>
                    </div>
                </div>
            </swiper-slide>
            <swiper-slide>
                <div class="relative h-[500px] md:h-[600px]">
                    <img
                        src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=1920&q=80"
                        alt="Smartphony"
                        class="w-full h-full object-cover"
                    />
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <div class="text-center text-white px-4">
                            <h2 class="text-4xl md:text-6xl font-bold mb-4">
                                Nejnovější smartphony
                            </h2>
                            <p class="text-xl md:text-2xl mb-8">
                                Technologie budoucnosti už dnes
                            </p>
                            <Link
                                href="/products"
                                class="inline-block bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors"
                            >
                                Objevte více
                            </Link>
                        </div>
                    </div>
                </div>
            </swiper-slide>
            <swiper-slide>
                <div class="relative h-[500px] md:h-[600px]">
                    <img
                        src="https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=1920&q=80"
                        alt="Tablety"
                        class="w-full h-full object-cover"
                    />
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <div class="text-center text-white px-4">
                            <h2 class="text-4xl md:text-6xl font-bold mb-4">
                                Elegantní tablety
                            </h2>
                            <p class="text-xl md:text-2xl mb-8">
                                Svoboda a mobilita v jednom
                            </p>
                            <Link
                                href="/products"
                                class="inline-block bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors"
                            >
                                Prozkoumat
                            </Link>
                        </div>
                    </div>
                </div>
            </swiper-slide>
        </swiper>
    </section>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay, Pagination, Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/navigation";

const modules = [Autoplay, Pagination, Navigation];
</script>

<style scoped>
.hero-swiper {
    border-radius: 10px;
}
.hero-swiper :deep(.swiper-pagination-bullet) {
    background: white;
    opacity: 0.5;
}

.hero-swiper :deep(.swiper-pagination-bullet-active) {
    opacity: 1;
}

.hero-swiper :deep(.swiper-button-next),
.hero-swiper :deep(.swiper-button-prev) {
    color: white;
}
</style>

