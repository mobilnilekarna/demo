<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-transparent dark:border-gray-700">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center gap-2">
        <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
          <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
          </svg>
        </div>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Cíle</h2>
      </div>
      <button
        @click="showSettings = true"
        class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
        title="Nastavit cíle"
      >
        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
      </button>
    </div>

    <!-- Achievement Items -->
    <div class="space-y-4 max-h-[500px] overflow-y-auto">
      <!-- Měsíční cíle -->
      <div v-if="monthlyGoals.length > 0" class="mb-6">
        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Měsíční cíle</h3>
        <div class="space-y-4">
          <div
            v-for="goal in monthlyGoals"
            :key="goal.id"
            class="space-y-2"
          >
            <div class="flex items-center justify-between text-sm">
              <div class="flex items-center gap-2">
                <span class="text-xl">{{ goal.emoji }}</span>
                <span class="text-gray-700 dark:text-gray-300 font-medium">
                  {{ goal.label }}
                  <span class="text-gray-500 dark:text-gray-400 text-xs ml-1">
                    ({{ goal.formatValue ? goal.formatValue(goal.current) : goal.current }} / {{ goal.formatValue ? goal.formatValue(goal.target) : goal.target }})
                  </span>
                </span>
              </div>
              <span 
                :class="[
                  'font-semibold',
                  goal.status === 'completed' ? 'text-green-600 dark:text-green-400' :
                  goal.status === 'good' ? 'text-primary-600 dark:text-primary-400' :
                  goal.status === 'progress' ? 'text-warning' :
                  'text-secondary-600 dark:text-secondary-400'
                ]"
              >
                {{ goal.progress }}%
              </span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
              <div
                :class="['h-full rounded-full transition-all duration-500', goal.color]"
                :style="{ width: Math.min(goal.progress, 100) + '%' }"
              ></div>
            </div>
            <div v-if="goal.status === 'completed'" class="text-xs text-green-600 dark:text-green-400 font-medium">
              ✓ Cíl splněn!
            </div>
          </div>
        </div>
      </div>

      <!-- Cíle za 6 měsíců -->
      <div v-if="sixMonthsGoals.length > 0" class="mb-6">
        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Cíle za 6 měsíců</h3>
        <div class="space-y-4">
          <div
            v-for="goal in sixMonthsGoals"
            :key="goal.id"
            class="space-y-2"
          >
            <div class="flex items-center justify-between text-sm">
              <div class="flex items-center gap-2">
                <span class="text-xl">{{ goal.emoji }}</span>
                <span class="text-gray-700 dark:text-gray-300 font-medium">
                  {{ goal.label }}
                  <span class="text-gray-500 dark:text-gray-400 text-xs ml-1">
                    ({{ goal.formatValue ? goal.formatValue(goal.current) : goal.current }} / {{ goal.formatValue ? goal.formatValue(goal.target) : goal.target }})
                  </span>
                </span>
              </div>
              <span 
                :class="[
                  'font-semibold',
                  goal.status === 'completed' ? 'text-green-600 dark:text-green-400' :
                  goal.status === 'good' ? 'text-primary-600 dark:text-primary-400' :
                  goal.status === 'progress' ? 'text-warning' :
                  'text-secondary-600 dark:text-secondary-400'
                ]"
              >
                {{ goal.progress }}%
              </span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
              <div
                :class="['h-full rounded-full transition-all duration-500', goal.color]"
                :style="{ width: Math.min(goal.progress, 100) + '%' }"
              ></div>
            </div>
            <div v-if="goal.status === 'completed'" class="text-xs text-green-600 dark:text-green-400 font-medium">
              ✓ Cíl splněn!
            </div>
          </div>
        </div>
      </div>

      <!-- Roční cíle -->
      <div v-if="yearlyGoals.length > 0" class="mb-6">
        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Roční cíle</h3>
        <div class="space-y-4">
          <div
            v-for="goal in yearlyGoals"
            :key="goal.id"
            class="space-y-2"
          >
        <div class="flex items-center justify-between text-sm">
          <div class="flex items-center gap-2">
            <span class="text-xl">{{ goal.emoji }}</span>
            <span class="text-gray-700 dark:text-gray-300 font-medium">
              {{ goal.label }}
              <span class="text-gray-500 dark:text-gray-400 text-xs ml-1">
                ({{ goal.formatValue ? goal.formatValue(goal.current) : goal.current }} / {{ goal.formatValue ? goal.formatValue(goal.target) : goal.target }})
              </span>
            </span>
          </div>
          <span 
            :class="[
              'font-semibold',
              goal.status === 'completed' ? 'text-green-600 dark:text-green-400' :
              goal.status === 'good' ? 'text-primary-600 dark:text-primary-400' :
              goal.status === 'progress' ? 'text-warning' :
              'text-secondary-600 dark:text-secondary-400'
            ]"
          >
            {{ goal.progress }}%
          </span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
          <div
            :class="['h-full rounded-full transition-all duration-500', goal.color]"
            :style="{ width: Math.min(goal.progress, 100) + '%' }"
          ></div>
        </div>
        <div v-if="goal.status === 'completed'" class="text-xs text-green-600 dark:text-green-400 font-medium">
          ✓ Cíl splněn!
        </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Settings Modal -->
    <Teleport to="body">
      <transition name="modal">
        <div
          v-if="showSettings"
          class="fixed inset-0 z-50 overflow-y-auto"
          @click.self="showSettings = false"
        >
          <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <transition name="modal-backdrop">
              <div
                v-if="showSettings"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="showSettings = false"
              ></div>
            </transition>

            <!-- Modal Panel -->
            <transition name="modal-panel">
              <div
                v-if="showSettings"
                class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full max-h-[90vh] overflow-y-auto"
              >
                <GoalsSettings @close="showSettings = false" />
              </div>
            </transition>
          </div>
        </div>
      </transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useGoals } from '@/Composables/useGoals'
import GoalsSettings from './GoalsSettings.vue'

const { getAllGoalsWithProgress } = useGoals()
const showSettings = ref(false)

// Získat všechny cíle s progress
const allGoals = computed(() => getAllGoalsWithProgress())

// Rozdělit cíle podle období
const monthlyGoals = computed(() => {
  return allGoals.value.filter(goal => goal.period === 'month')
})

const sixMonthsGoals = computed(() => {
  return allGoals.value.filter(goal => goal.period === 'sixMonths')
})

const yearlyGoals = computed(() => {
  return allGoals.value.filter(goal => goal.period === 'year')
})
</script>

<style scoped>
/* Modal transitions */
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-backdrop-enter-active,
.modal-backdrop-leave-active {
  transition: opacity 0.3s ease;
}

.modal-backdrop-enter-from,
.modal-backdrop-leave-to {
  opacity: 0;
}

.modal-panel-enter-active,
.modal-panel-leave-active {
  transition: all 0.3s ease;
}

.modal-panel-enter-from,
.modal-panel-leave-to {
  opacity: 0;
  transform: scale(0.95);
}
</style>
