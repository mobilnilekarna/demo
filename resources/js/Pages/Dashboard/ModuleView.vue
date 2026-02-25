<template>
  <DashboardLayout>
    <div class="p-4">
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ module.name }}</h1>
        <p v-if="module.description" class="text-sm text-gray-600 dark:text-gray-400 mt-1">
          {{ module.description }}
        </p>
      </div>

      <!-- Samostatná hláška pro chybějící metodu -->
      <div v-if="error?.errors?.missing_method" class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 dark:border-amber-400 rounded-lg shadow-md p-6 mb-4">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <div class="ml-4 flex-1">
            <h3 class="text-lg font-semibold text-amber-900 dark:text-amber-100 mb-3">
              Chybějící metoda v ModuleService
            </h3>
            <p class="text-sm text-amber-800 dark:text-amber-200 mb-4">
              Pro tento modul chybí handler metoda, která by zpracovávala data.
            </p>

            <div class="bg-white dark:bg-gray-800/50 rounded-lg p-4 space-y-3 border border-amber-200 dark:border-amber-800">
              <div>
                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-1">
                  Název metody
                </p>
                <code class="block bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded font-mono text-sm text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600">
                  {{ error.errors.missing_method.method_name }}
                </code>
              </div>

              <div>
                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-1">
                  Třída
                </p>
                <code class="block bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded font-mono text-sm text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600">
                  {{ error.errors.missing_method.class_path }}
                </code>
              </div>

              <div>
                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-1">
                  Soubor
                </p>
                <code class="block bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded font-mono text-sm text-gray-900 dark:text-gray-100 break-all border border-gray-200 dark:border-gray-600">
                  {{ error.errors.missing_method.file_path }}
                </code>
              </div>

              <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-2">
                  Co udělat
                </p>
                <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed">
                  {{ error.errors.missing_method.instruction }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Samostatná hláška pro chybějící Vue soubor -->
      <div v-if="error?.errors?.missing_vue_file" class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 dark:border-green-400 rounded-lg shadow-md p-6 mb-4">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <div class="ml-4 flex-1">
            <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">
              Chybějící Vue soubor
            </h3>
            <p class="text-sm text-green-800 dark:text-green-200 mb-4">
              Pro tento modul chybí Vue komponenta, která by zobrazovala uživatelské rozhraní.
            </p>

            <div class="bg-white dark:bg-gray-800/50 rounded-lg p-4 space-y-3 border border-green-200 dark:border-green-800">
              <div>
                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-1">
                  Component Path
                </p>
                <code class="block bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded font-mono text-sm text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600">
                  {{ error.errors.missing_vue_file.component_path }}
                </code>
              </div>

              <div>
                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-1">
                  Relativní cesta
                </p>
                <code class="block bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded font-mono text-sm text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600">
                  {{ error.errors.missing_vue_file.relative_path }}
                </code>
              </div>

              <div>
                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-1">
                  Absolutní cesta
                </p>
                <code class="block bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded font-mono text-xs text-gray-900 dark:text-gray-100 break-all border border-gray-200 dark:border-gray-600">
                  {{ error.errors.missing_vue_file.file_path }}
                </code>
              </div>

              <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-2">
                  Co udělat
                </p>
                <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed">
                  {{ error.errors.missing_vue_file.instruction }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Normální zobrazení, pokud není chyba -->
      <div v-if="!error" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <p class="text-gray-700 dark:text-gray-300">
          Modul: <strong>{{ module.name }}</strong> ({{ module.slug }})
        </p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
          Tento modul je připraven k implementaci.
        </p>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

defineProps({
  module: {
    type: Object,
    required: true,
  },
  error: {
    type: Object,
    default: null,
  }
})
</script>

