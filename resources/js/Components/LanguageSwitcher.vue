<template>
  <div class="flex items-center gap-2">
    <button
      v-for="availableLocale in locales"
      :key="availableLocale"
      @click="changeLocale(availableLocale)"
      :class="[
        'px-3 py-1 rounded text-sm font-medium transition-colors',
        currentLocale === availableLocale
          ? 'bg-blue-500 text-white'
          : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
      ]"
    >
      {{ availableLocale.toUpperCase() }}
    </button>
  </div>
</template>

<script setup>
import { useI18n } from '../Composables/useI18n';

const { currentLocale, changeLocale, locales } = useI18n();
</script>

