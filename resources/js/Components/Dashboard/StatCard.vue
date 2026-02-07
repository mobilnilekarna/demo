<template>
  <div
    :class="[
      'rounded-lg p-4 shadow transition-all hover:shadow-md',
      bgColor
    ]"
  >
    <div class="flex items-center justify-between">
      <div>
        <h3 :class="['text-4xl font-bold mb-1', textColor]">{{ value }}</h3>
        <p :class="['text-sm font-medium', textColor, 'opacity-90']">{{ label }}</p>
      </div>
      <div :class="['p-3 rounded-lg', iconBgColor]">
        <component :is="iconComponent" :class="['w-7 h-7', iconColor]" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, markRaw } from 'vue'

// Icon components
const IconVideo = markRaw({
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>`
})

const IconUsers = markRaw({
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>`
})

const IconPencil = markRaw({
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>`
})

const IconBookmark = markRaw({
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>`
})

const IconCart = markRaw({
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>`
})

const IconPackage = markRaw({
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>`
})

const IconMoney = markRaw({
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
})

const icons = {
  IconVideo,
  IconUsers,
  IconPencil,
  IconBookmark,
  IconCart,
  IconPackage,
  IconMoney
}

const props = defineProps({
  value: {
    type: [String, Number],
    required: true
  },
  label: {
    type: String,
    required: true
  },
  icon: {
    type: String,
    required: true
  },
  variant: {
    type: String,
    default: 'blue',
    validator: (value) => ['blue', 'white', 'purple', 'green'].includes(value)
  }
})

const iconComponent = computed(() => icons[props.icon] || null)

const bgColor = computed(() => {
  const colors = {
    blue: 'bg-gradient-to-br from-primary-500 to-primary-600',
    white: 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700',
    purple: 'bg-gradient-to-br from-secondary-500 to-secondary-600',
    green: 'bg-gradient-to-br from-primary-400 to-primary-500'
  }
  return colors[props.variant]
})

const textColor = computed(() => {
  return props.variant === 'white' ? 'text-gray-800 dark:text-gray-100' : 'text-white'
})

const iconBgColor = computed(() => {
  const colors = {
    blue: 'bg-primary-400 bg-opacity-30',
    white: 'bg-gray-100 dark:bg-gray-700',
    purple: 'bg-secondary-400 bg-opacity-30',
    green: 'bg-primary-300 bg-opacity-30'
  }
  return colors[props.variant]
})

const iconColor = computed(() => {
  return props.variant === 'white' ? 'text-gray-600 dark:text-gray-400' : 'text-white'
})
</script>

