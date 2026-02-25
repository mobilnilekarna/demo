<template>
    <div class="min-h-screen">
        <AddToBasketModalGlobal />

        <!-- Header Content -->
        <Header :title="title" :is-mobile-menu-open="isMobileMenuOpen" @toggle-mobile-menu="toggleMobileMenu" />
        <!-- Menu content-->
        <Menu :is-mobile-menu-open="isMobileMenuOpen" @close-mobile-menu="closeMobileMenu" />
        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 sm:py-6 lg:py-8 flex flex-col gap-[3rem]">
            <slot />
        </main>
        <!-- Newsletter Content -->
        <Newsletter />
        <!-- Footer Content -->
        <Footer />
        <!-- Cookie Consent Icon -->
        <CookieConsentIcon />
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import Header from "@/Components/Header.vue"
import Footer from "@/Components/Footer.vue";
import Newsletter from "@/Components/Newsletter.vue";
import Menu from "@/Components/Menu.vue";
import CookieConsentIcon from "@/Components/CookieConsentIcon.vue";
import AddToBasketModalGlobal from "@components/Store/Product/AddToBasketModalGlobal.vue";

defineProps({
    title: {
        type: String,
        default: "Laravel + Vue.js + Inertia.js",
    },
});

const isMobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const closeMobileMenu = () => {
    isMobileMenuOpen.value = false;
};

const handleResize = () => {
    if (window.innerWidth >= 1024) {
        isMobileMenuOpen.value = false;
    }
};

onMounted(() => {
    window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
});
</script>
