<template>
  <DashboardLayout>
    <div class="p-4">
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Můj profil</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
          Zobrazení informací o vašem profilu
        </p>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6">
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
              <div class="w-20 h-20 rounded-full bg-primary-600 flex items-center justify-center">
                <span class="text-white text-2xl font-medium">{{ userInitials }}</span>
              </div>
              <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ user.name }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ user.email }}</p>
              </div>
            </div>
            <Link
              href="/dashboard/profile/edit"
              class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors flex items-center space-x-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              <span>Upravit profil</span>
            </Link>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Jméno
              </label>
              <p class="text-sm text-gray-900 dark:text-gray-100">{{ user.name }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Email
              </label>
              <p class="text-sm text-gray-900 dark:text-gray-100">{{ user.email }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Role
              </label>
              <div class="flex flex-wrap gap-2 mt-1">
                <span
                  v-for="role in user.roles"
                  :key="role"
                  class="px-2 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-400 text-xs rounded"
                >
                  {{ role }}
                </span>
                <span v-if="user.roles.length === 0" class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs rounded">
                  Bez role
                </span>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Členem od
              </label>
              <p class="text-sm text-gray-900 dark:text-gray-100">{{ user.created_at }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const props = defineProps({
  user: {
    type: Object,
    required: true
  }
})

const userInitials = computed(() => {
  if (!props.user?.name) return 'U'
  const names = props.user.name.split(' ')
  if (names.length >= 2) {
    return (names[0][0] + names[1][0]).toUpperCase()
  }
  return props.user.name.substring(0, 2).toUpperCase()
})
</script>

