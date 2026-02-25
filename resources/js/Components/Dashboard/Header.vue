<template>
  <header class="fixed top-0 left-0 right-0 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 z-40 h-14 shadow-sm transition-colors">
    <div class="flex items-center justify-between h-full">
      <!-- Left Section: Hamburger + Logo -->
      <div class="flex items-center h-full">
        <!-- Hamburger - zarovnané s menu ikonami -->
        <div class="w-[80px] flex items-center justify-center h-full border-r border-gray-200 dark:border-gray-700">
          <button
            @click="$emit('toggle-menu')"
            class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded transition-colors focus:outline-none"
            aria-label="Toggle menu"
          >
            <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>

        <!-- Logo - zarovnané s submenu -->
        <Link href="/dashboard" class="flex items-center space-x-2 px-5 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer">
          <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/>
              <circle cx="10" cy="10" r="3"/>
            </svg>
          </div>
          <span class="text-base font-semibold text-gray-800 dark:text-gray-100 hidden sm:block">Mobilní lékárna</span>
        </Link>
      </div>

      <!-- Right Section: Search + Tour + Theme + Language + Login -->
      <div class="flex items-center space-x-2 md:space-x-3 px-4">
        <!-- Search -->
        <div class="hidden md:flex items-center bg-gray-50 dark:bg-gray-800 rounded px-3 py-1.5 w-52">
          <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input
            type="text"
            placeholder="Search"
            class="ml-2 bg-transparent border-none focus:outline-none text-sm w-full text-gray-700 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500"
          />
        </div>

        <!-- Tour Guide Button -->
        <button
          v-if="startTour"
          @click="startTour"
          class="px-2 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded transition-colors flex items-center gap-1.5 text-xs font-medium"
          title="Spustit průvodce"
        >
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
          <span class="hidden sm:inline">Průvodce</span>
        </button>

        <!-- Theme Switcher -->
        <button
          @click="toggleTheme"
          class="flex items-center justify-center px-2.5 py-1.5 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded transition-colors"
          :title="theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'"
        >
          <!-- Sun icon (visible in dark mode) -->
          <svg v-if="theme === 'dark'" class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <!-- Moon icon (visible in light mode) -->
          <svg v-else class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
          </svg>
        </button>

        <!-- Home Page Link (Globe Icon) -->
        <a
          href="/"
          target="_blank"
          rel="noopener noreferrer"
          class="flex items-center justify-center px-2.5 py-1.5 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded transition-colors"
          title="Otevřít hlavní stránku"
        >
          <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </a>

        <!-- Language Selector -->
        <button class="flex items-center space-x-1.5 px-2.5 py-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded transition-colors">
          <span class="text-xl">🇬🇧</span>
          <span class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden sm:block">English</span>
        </button>

        <!-- User Avatar with Dropdown -->
        <div class="relative" v-if="user" data-tour-guide="step-2">
          <button
            @click="toggleUserMenu"
            class="user-avatar-button flex items-center space-x-2 px-2 py-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded transition-colors focus:outline-none"
          >
            <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center">
              <span class="text-white text-sm font-medium">{{ userInitials }}</span>
            </div>
            <svg
              class="w-4 h-4 text-gray-600 dark:text-gray-400 transition-transform"
              :class="{ 'rotate-180': isUserMenuOpen }"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>

          <!-- User Dropdown Menu -->
          <div
            v-if="isUserMenuOpen"
            class="user-dropdown-menu absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50"
          >
            <div class="py-2">
              <!-- User Info -->
              <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ user.name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ user.email }}</p>
              </div>

              <!-- Menu Items -->
              <Link
                href="/dashboard/profile"
                @click="closeUserMenu"
                class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Zobrazit profil</span>
              </Link>

              <Link
                href="/dashboard/profile/edit"
                @click="closeUserMenu"
                class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Upravit profil</span>
              </Link>

              <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

              <button
                @click="handleLogout"
                class="w-full flex items-center space-x-3 px-4 py-2 text-sm text-secondary-600 dark:text-secondary-400 hover:bg-secondary-50 dark:hover:bg-secondary-900/20 transition-colors"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Odhlásit se</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted, inject } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { useTheme } from '@/Composables/useTheme'

defineEmits(['toggle-menu'])

const { theme, toggleTheme } = useTheme()
const page = usePage()

const user = computed(() => page.props.auth?.user || null)
const isUserMenuOpen = ref(false)

// Získat funkci startTour z parent komponenty (pokud je k dispozici)
const startTour = inject('startTour', null)

const userInitials = computed(() => {
  if (!user.value?.name) return 'U'
  const names = user.value.name.split(' ')
  if (names.length >= 2) {
    return (names[0][0] + names[1][0]).toUpperCase()
  }
  return user.value.name.substring(0, 2).toUpperCase()
})

const toggleUserMenu = () => {
  isUserMenuOpen.value = !isUserMenuOpen.value
}

const closeUserMenu = () => {
  isUserMenuOpen.value = false
}

const handleLogout = () => {
  router.post('/dashboard/logout', {}, {
    onSuccess: () => {
      router.visit('/dashboard/login')
    }
  })
}

// Click outside handler
const handleClickOutside = (event) => {
  const dropdown = document.querySelector('.user-dropdown-menu')
  const button = document.querySelector('.user-avatar-button')
  if (dropdown && button && !dropdown.contains(event.target) && !button.contains(event.target)) {
    closeUserMenu()
  }
}

// Watch for menu open/close to add/remove event listener
watch(isUserMenuOpen, (isOpen) => {
  if (isOpen) {
    document.addEventListener('click', handleClickOutside)
  } else {
    document.removeEventListener('click', handleClickOutside)
  }
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

