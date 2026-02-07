<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-transparent dark:border-gray-700">
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-2">
        <div class="p-2 bg-primary-100 dark:bg-primary-900/30 rounded-lg">
          <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
          </svg>
        </div>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Nastavení cílů</h2>
      </div>
      <button
        @click="$emit('close')"
        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
      >
        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <div class="space-y-6">
      <!-- Objednávky -->
      <div>
        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100 mb-4">Počet objednávek</h3>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Měsíc
            </label>
            <input
              v-model.number="localGoals.orders.month"
              type="number"
              min="0"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              6 měsíců
            </label>
            <input
              v-model.number="localGoals.orders.sixMonths"
              type="number"
              min="0"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Rok
            </label>
            <input
              v-model.number="localGoals.orders.year"
              type="number"
              min="0"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
          </div>
        </div>
      </div>

      <!-- Tržby -->
      <div>
        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100 mb-4">Tržby (Kč)</h3>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Měsíc
            </label>
            <input
              v-model.number="localGoals.revenue.month"
              type="number"
              min="0"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              6 měsíců
            </label>
            <input
              v-model.number="localGoals.revenue.sixMonths"
              type="number"
              min="0"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Rok
            </label>
            <input
              v-model.number="localGoals.revenue.year"
              type="number"
              min="0"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
          </div>
        </div>
      </div>
    </div>

    <div class="mt-6 flex justify-end gap-3">
      <button
        @click="$emit('close')"
        class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors font-medium"
      >
        Zrušit
      </button>
      <button
        @click="saveGoals"
        class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors font-medium"
      >
        Uložit cíle
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, inject } from 'vue'
import { useGoals } from '@/Composables/useGoals'

const emit = defineEmits(['close'])

const { goals, updateGoals } = useGoals()

// Získat toast z provide (pokud je k dispozici)
const toast = inject('toast', null)

// Lokální kopie pro editaci
const localGoals = ref({
  orders: {
    month: goals.value.orders.month,
    sixMonths: goals.value.orders.sixMonths,
    year: goals.value.orders.year
  },
  revenue: {
    month: goals.value.revenue.month,
    sixMonths: goals.value.revenue.sixMonths,
    year: goals.value.revenue.year
  }
})

// Sledovat změny v goals a aktualizovat lokální kopii
watch(goals, (newGoals) => {
  localGoals.value = {
    orders: { ...newGoals.orders },
    revenue: { ...newGoals.revenue }
  }
}, { deep: true })

const saveGoals = () => {
  updateGoals(localGoals.value)
  
  // Zobrazit notifikaci
  if (toast) {
    toast.success('Cíle uloženy', 'Vaše cíle byly úspěšně uloženy.')
  }
  
  // Zavřít modal
  emit('close')
}
</script>

