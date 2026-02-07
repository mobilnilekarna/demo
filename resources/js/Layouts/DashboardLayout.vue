<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors">
    <!-- Header -->
    <Header @toggle-menu="toggleMenu" />

    <!-- Menu -->
    <Menu
      :isMenuOpen="isMenuOpen"
      @close-menu="closeMenu"
      @menu-expanded="handleMenuExpanded"
    />

    <!-- Main Content -->
    <main
      :class="[
        'pt-14 transition-all duration-300',
        isMenuOpen && !isMobile ? (isMenuExpanded ? 'lg:pl-[350px]' : 'lg:pl-[80px]') : 'lg:pl-[80px]'
      ]"
    >
      <slot />
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import Header from '@/Components/Dashboard/Header.vue'
import Menu from '@/Components/Dashboard/Menu.vue'

defineProps({
  title: {
    type: String,
    default: "Dashboard",
  },
})

const isMenuOpen = ref(true)
const isMobile = ref(false)
const isMenuExpanded = ref(true)

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}

const closeMenu = () => {
  if (isMobile.value) {
    isMenuOpen.value = false
  }
}

const handleMenuExpanded = (expanded) => {
  isMenuExpanded.value = expanded
}

const checkMobile = () => {
  isMobile.value = window.innerWidth < 1024
  if (isMobile.value) {
    isMenuOpen.value = false
  } else {
    isMenuOpen.value = true
  }
}

onMounted(() => {
  checkMobile()
  window.addEventListener('resize', checkMobile)
})

onUnmounted(() => {
  window.removeEventListener('resize', checkMobile)
})
</script>
