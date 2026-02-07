<template>
    <button
        @click="openPreferences"
        class="fixed bottom-4 left-4 z-50 bg-white dark:bg-gray-800 shadow-lg rounded-full p-3 hover:shadow-xl transition-shadow duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500"
        aria-label="Nastavení cookies"
        title="Nastavení cookies"
    >
        🍪
    </button>
</template>

<script setup>
import { showPreferences } from 'vanilla-cookieconsent';

const openPreferences = () => {
    showPreferences();
};
</script>

<style scoped>
/* Customizace ikony cookie */
button {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}
</style>

