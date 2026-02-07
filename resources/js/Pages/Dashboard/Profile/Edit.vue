<template>
  <DashboardLayout>
    <div class="p-4">
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Upravit profil</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
          Upravte informace o vašem profilu
        </p>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6">
          <form @submit.prevent="saveProfile" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Jméno <span class="text-secondary-500">*</span>
                </label>
                <input
                  v-model="form.name"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                />
                <p v-if="errors.name" class="mt-1 text-sm text-secondary-600 dark:text-secondary-400">{{ errors.name }}</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Email <span class="text-secondary-500">*</span>
                </label>
                <input
                  v-model="form.email"
                  type="email"
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                />
                <p v-if="errors.email" class="mt-1 text-sm text-secondary-600 dark:text-secondary-400">{{ errors.email }}</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Nové heslo
                </label>
                <input
                  v-model="form.password"
                  type="password"
                  :minlength="8"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Ponechte prázdné, pokud nechcete změnit heslo</p>
                <p v-if="errors.password" class="mt-1 text-sm text-secondary-600 dark:text-secondary-400">{{ errors.password }}</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Potvrdit nové heslo
                </label>
                <input
                  v-model="form.password_confirmation"
                  type="password"
                  :minlength="8"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                />
                <p v-if="errors.password_confirmation" class="mt-1 text-sm text-secondary-600 dark:text-secondary-400">{{ errors.password_confirmation }}</p>
              </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
              <Link
                href="/dashboard/profile"
                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors"
              >
                Zrušit
              </Link>
              <button
                type="submit"
                :disabled="isSubmitting"
                class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="isSubmitting">Ukládání...</span>
                <span v-else>Uložit změny</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const props = defineProps({
  user: {
    type: Object,
    required: true
  }
})

const page = usePage()
const errors = computed(() => page.props.errors || {})
const isSubmitting = ref(false)

const form = reactive({
  name: props.user.name,
  email: props.user.email,
  password: '',
  password_confirmation: ''
})

const saveProfile = () => {
  isSubmitting.value = true
  
  router.put('/dashboard/profile', form, {
    preserveScroll: true,
    onSuccess: () => {
      isSubmitting.value = false
    },
    onError: () => {
      isSubmitting.value = false
    }
  })
}
</script>

