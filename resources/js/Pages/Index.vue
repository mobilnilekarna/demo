<template>
    <AppLayout>
        <Banner2 :banners="banners" />

        <ServiceGuarantees :guarantees="serviceGuarantees" />

        <ProductListSwiper
            :products="featuredProducts"
            title="nabídka"
            title-highlight="Akční"
            title-highlight-color="green"
            description="Nejoblíbenější produkty za skvělé ceny"
            view-all-link="/products"
            link-color-class="text-black hover:text-primary-600 transition-colors font-semibold"
        />

        <ProductListSwiper
            :products="recommendedProducts"
            title="pro vás"
            title-highlight="Doporučené"
            title-highlight-color="red"
            description="Produkty, které by se vám mohly líbit"
            view-all-link="/products"
            link-color-class="text-black hover:text-primary-600 transition-colors font-semibold"
        />

        <ArticleSwiper :articles="articles" />

        <BrandSwiper :brands="brands" />

        <!-- Tlačítko pro otevření bedny s odměnami -->
        <div class="fixed bottom-8 left-8 z-40">
            <button
                @click="showCaseOpening = true"
                class="bg-gradient-to-r from-yellow-500 via-orange-500 to-red-500 hover:from-yellow-600 hover:via-orange-600 hover:to-red-600 text-white font-bold py-4 px-6 rounded-full shadow-lg hover:shadow-2xl transition-all duration-300 flex items-center gap-2 transform hover:scale-105 active:scale-95"
            >
                <span class="text-xl">📦</span>
                <span>Otevřít bednu</span>
            </button>
        </div>

        <!-- Tlačítko pro otevření sezónních odměn -->
        <div class="fixed bottom-8 right-8 z-40">
            <!-- Rozbalovací menu - nad tlačítkem -->
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 translate-y-2"
            >
                <div
                    v-if="showRewardMenu"
                    class="absolute bottom-full right-0 mb-3 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border-2 border-gray-200 dark:border-gray-700 p-2 flex flex-col gap-2 min-w-[200px]"
                >
                    <button
                        @click="openReward('valentine')"
                        class="px-4 py-3 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-left flex items-center gap-3"
                    >
                        <span class="text-2xl">💕</span>
                        <span class="font-semibold text-gray-700 dark:text-gray-200">Valentýn</span>
                    </button>
                    <button
                        @click="openReward('easter')"
                        class="px-4 py-3 rounded-xl hover:bg-pink-50 dark:hover:bg-pink-900/20 transition-colors text-left flex items-center gap-3"
                    >
                        <span class="text-2xl">🐰</span>
                        <span class="font-semibold text-gray-700 dark:text-gray-200">Velikonoce</span>
                    </button>
                    <button
                        @click="openReward('summer')"
                        class="px-4 py-3 rounded-xl hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition-colors text-left flex items-center gap-3"
                    >
                        <span class="text-2xl">☀️</span>
                        <span class="font-semibold text-gray-700 dark:text-gray-200">Léto</span>
                    </button>
                    <button
                        @click="openReward('halloween')"
                        class="px-4 py-3 rounded-xl hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-colors text-left flex items-center gap-3"
                    >
                        <span class="text-2xl">🎃</span>
                        <span class="font-semibold text-gray-700 dark:text-gray-200">Halloween</span>
                    </button>
                    <button
                        @click="openReward('saintNicholas')"
                        class="px-4 py-3 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-left flex items-center gap-3"
                    >
                        <span class="text-2xl">🎅</span>
                        <span class="font-semibold text-gray-700 dark:text-gray-200">Mikuláš</span>
                    </button>
                </div>
            </Transition>

            <!-- Hlavní tlačítko -->
            <button
                @click="showRewardMenu = !showRewardMenu"
                class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-4 px-6 rounded-full shadow-lg transition-all duration-300 flex items-center gap-2 relative"
            >
                <span class="text-xl">🎁</span>
                <span>Odměny</span>
            </button>
        </div>
    </AppLayout>

    <!-- Sezónní odměny -->
    <ValentineReward v-model="showValentineReward" />
    <EasterReward v-model="showEasterReward" />
    <SummerReward v-model="showSummerReward" />
    <HalloweenReward v-model="showHalloweenReward" />
    <SaintNicholasReward v-model="showSaintNicholasReward" />

    <!-- Otevírání bedny -->
    <CaseOpeningReward v-model="showCaseOpening" />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from "@/Layouts/AppLayout.vue";
import Banner2 from "@/Components/Store/Banner/Banner2.vue";
import ProductListSwiper from "@/Components/Store/Product/ProductListSwiper.vue";
import ArticleSwiper from "@/Components/Store/Article/ArticleSwiper.vue";
import BrandSwiper from "@/Components/Store/Brand/BrandSwiper.vue";
import ServiceGuarantees from "@/Components/ServiceGuarantees.vue";
import ValentineReward from "@/Components/ValentineReward.vue";
import EasterReward from "@/Components/EasterReward.vue";
import SummerReward from "@/Components/SummerReward.vue";
import HalloweenReward from "@/Components/HalloweenReward.vue";
import SaintNicholasReward from "@/Components/SaintNicholasReward.vue";
import CaseOpeningReward from "@/Components/CaseOpeningReward.vue";

const props = defineProps({
    featuredProducts: {
        type: Array,
        default: () => [],
    },
    recommendedProducts: {
        type: Array,
        default: () => [],
    },
    articles: {
        type: Array,
        default: () => [],
    },
    banners: {
        type: Array,
        default: () => [],
    },
    brands: {
        type: Array,
        default: () => [],
    },
    serviceGuarantees: {
        type: Array,
        default: () => [],
    },
});

// Sezónní odměny
const showValentineReward = ref(false);
const showEasterReward = ref(false);
const showSummerReward = ref(false);
const showHalloweenReward = ref(false);
const showSaintNicholasReward = ref(false);
const showRewardMenu = ref(false);
const showCaseOpening = ref(false);

const openReward = (type) => {
    showRewardMenu.value = false;

    // Zavřít všechny ostatní odměny
    showValentineReward.value = false;
    showEasterReward.value = false;
    showSummerReward.value = false;
    showHalloweenReward.value = false;
    showSaintNicholasReward.value = false;

    // Otevřít vybranou odměnu
    setTimeout(() => {
        switch(type) {
            case 'valentine':
                showValentineReward.value = true;
                break;
            case 'easter':
                showEasterReward.value = true;
                break;
            case 'summer':
                showSummerReward.value = true;
                break;
            case 'halloween':
                showHalloweenReward.value = true;
                break;
            case 'saintNicholas':
                showSaintNicholasReward.value = true;
                break;
        }
    }, 100);
};

onMounted(() => {
    // Zobrazit valentýnskou odměnu po načtení stránky
    setTimeout(() => {
        showValentineReward.value = true;
    }, 500);
});

</script>

