<template>
  <div class="bg-white rounded-lg shadow">
    <!-- Header: Search, Filters, Bulk Actions -->
    <div class="p-4 border-b border-gray-200">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <!-- Left: Create Button + Search + Filters -->
        <div class="flex flex-col sm:flex-row gap-3 flex-1">
          <!-- Create Button -->
          <button
            v-if="showCreateButton"
            @click="handleCreateClick"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center space-x-2 whitespace-nowrap"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span>{{ createButtonLabel }}</span>
          </button>
          <!-- Search -->
          <div class="relative flex-1 max-w-md">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
              v-model="searchQuery"
              @input="handleSearch"
              type="text"
              placeholder="Vyhledat..."
              class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
            />
          </div>

          <!-- Filter Button -->
          <button
            @click="showFilterModal = true"
            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center space-x-2 text-sm font-medium text-gray-700"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            <span>Filtry</span>
            <span v-if="activeFiltersCount > 0" class="bg-blue-600 text-white text-xs px-2 py-0.5 rounded-full">
              {{ activeFiltersCount }}
            </span>
          </button>
        </div>

        <!-- Right: Bulk Actions -->
        <div v-if="selectedItems.length > 0" class="flex items-center gap-3">
          <span class="text-sm text-gray-600 whitespace-nowrap">
            <strong>{{ selectionMode === 'include' ? selectedItems.length : totalItems - selectedItems.length }}</strong> vybráno
          </span>

          <select
            v-model="selectedBulkAction"
            @change="executeBulkAction"
            class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white hover:border-gray-400 transition-colors"
          >
            <option value="">Vyberte akci...</option>
            <option
              v-for="action in bulkActions"
              :key="action.key"
              :value="action.key"
            >
              {{ action.label }}
            </option>
          </select>

          <button
            @click="clearSelection"
            class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
            title="Zrušit výběr"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Selection Mode Indicator -->
    <div v-if="selectionMode === 'exclude' && selectedItems.length > 0" class="px-4 py-2 bg-blue-50 border-b border-blue-100">
      <div class="flex items-center justify-between text-sm">
        <span class="text-blue-800">
          <strong>Režim vyloučení:</strong> Vybrány všechny položky kromě {{ selectedItems.length }}
        </span>
        <button
          @click="switchToIncludeMode"
          class="text-blue-600 hover:text-blue-800 font-medium"
        >
          Přepnout na režim výběru
        </button>
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <!-- Select All Checkbox -->
            <th class="w-12 px-4 py-3">
              <div class="flex items-center justify-center">
                <input
                  type="checkbox"
                  :checked="isAllSelected"
                  :indeterminate="isIndeterminate"
                  @change="toggleSelectAll"
                  class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                />
              </div>
            </th>

            <!-- Column Headers -->
            <th
              v-for="column in columns"
              :key="column.key"
              class="px-4 py-3 text-left"
            >
              <div
                v-if="column.sortable"
                @click="handleSort(column.key)"
                class="flex items-center space-x-1 cursor-pointer hover:text-blue-600 transition-colors group"
              >
                <span class="text-xs font-semibold text-gray-600 group-hover:text-blue-600 uppercase tracking-wider">
                  {{ column.label }}
                </span>
                <svg
                  v-if="sortKey === column.key"
                  :class="['w-4 h-4 transition-transform', sortDirection === 'desc' ? 'rotate-180' : '']"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
                <svg v-else class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                </svg>
              </div>
              <span v-else class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                {{ column.label }}
              </span>
            </th>

            <!-- Actions -->
            <th class="px-4 py-3 text-right">
              <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Akce</span>
            </th>
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-200">
          <tr
            v-for="item in paginatedItems"
            :key="item[itemKey]"
            :class="[
              'hover:bg-gray-50 transition-colors',
              isItemSelected(item[itemKey]) ? 'bg-blue-50' : ''
            ]"
          >
            <!-- Checkbox -->
            <td class="px-4 py-3">
              <div class="flex items-center justify-center">
                <input
                  type="checkbox"
                  :checked="isItemSelected(item[itemKey])"
                  @change="toggleItem(item[itemKey])"
                  class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                />
              </div>
            </td>

            <!-- Data Columns -->
            <td
              v-for="column in columns"
              :key="column.key"
              class="px-4 py-3 text-sm text-gray-900"
            >
              <slot :name="`cell-${column.key}`" :item="item" :value="item[column.key]">
                {{ formatCellValue(item[column.key], column) }}
              </slot>
            </td>

            <!-- Actions -->
            <td class="px-4 py-3 text-right">
              <slot name="actions" :item="item">
                <div class="flex justify-end gap-2">
                  <button
                    @click="handleEditClick(item)"
                    class="p-1.5 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded transition-colors"
                    title="Upravit"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </button>
                  <button
                    @click="handleDeleteClick(item)"
                    class="p-1.5 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 rounded transition-colors"
                    title="Smazat"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </slot>
            </td>
          </tr>

          <!-- Empty State -->
          <tr v-if="paginatedItems.length === 0">
            <td :colspan="columns.length + 2" class="px-4 py-12 text-center text-gray-500">
              <div class="flex flex-col items-center space-y-2">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="text-sm">Žádné záznamy</p>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Filter Modal -->
    <Teleport to="body">
      <transition name="modal">
        <div
          v-if="showFilterModal"
          class="fixed inset-0 z-50 overflow-y-auto"
          @click.self="showFilterModal = false"
        >
          <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <transition name="modal-backdrop">
              <div
                v-if="showFilterModal"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="showFilterModal = false"
              ></div>
            </transition>

            <!-- Modal Panel -->
            <transition name="modal-panel">
              <div
                v-if="showFilterModal"
                class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full"
              >
                <!-- Header -->
                <div class="bg-white px-6 py-4 border-b border-gray-200">
                  <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Filtry</h3>
                    <button
                      @click="showFilterModal = false"
                      class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </div>

                <!-- Body -->
                <div class="bg-gray-50 px-6 py-6 max-h-[60vh] overflow-y-auto">
                  <div v-if="filterSections.length > 0" class="space-y-6">
                    <div
                      v-for="section in filterSections"
                      :key="section.id"
                      class="bg-white rounded-lg p-4 shadow-sm"
                    >
                      <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center space-x-2">
                        <svg v-if="section.icon" class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="section.icon"></svg>
                        <span>{{ section.label }}</span>
                      </h4>

                      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div v-for="filter in section.filters" :key="filter.key" class="space-y-1.5">
                          <label class="text-xs font-medium text-gray-700">{{ filter.label }}</label>

                          <!-- Text Input -->
                          <input
                            v-if="filter.type === 'text'"
                            v-model="tempFilters[filter.key]"
                            type="text"
                            :placeholder="filter.placeholder"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                          />

                          <!-- Select -->
                          <select
                            v-else-if="filter.type === 'select'"
                            v-model="tempFilters[filter.key]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                          >
                            <option v-for="option in filter.options" :key="option.value" :value="option.value">
                              {{ option.label }}
                            </option>
                          </select>

                          <!-- Date -->
                          <input
                            v-else-if="filter.type === 'date'"
                            v-model="tempFilters[filter.key]"
                            type="date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                          />

                          <!-- Number -->
                          <input
                            v-else-if="filter.type === 'number'"
                            v-model.number="tempFilters[filter.key]"
                            type="number"
                            :placeholder="filter.placeholder"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                          />
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Fallback pro staré filtry bez sekcí -->
                  <div v-else class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                      <div v-for="filter in filters" :key="filter.key" class="space-y-1.5">
                        <label class="text-xs font-medium text-gray-700">{{ filter.label }}</label>

                        <!-- Text Input -->
                        <input
                          v-if="filter.type === 'text'"
                          v-model="tempFilters[filter.key]"
                          type="text"
                          :placeholder="filter.placeholder"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                        />

                        <!-- Select -->
                        <select
                          v-else-if="filter.type === 'select'"
                          v-model="tempFilters[filter.key]"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                        >
                          <option v-for="option in filter.options" :key="option.value" :value="option.value">
                            {{ option.label }}
                          </option>
                        </select>

                        <!-- Date -->
                        <input
                          v-else-if="filter.type === 'date'"
                          v-model="tempFilters[filter.key]"
                          type="date"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                        />
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between">
                  <button
                    @click="resetFilters"
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors"
                  >
                    Resetovat vše
                  </button>
                  <div class="flex gap-3">
                    <button
                      @click="showFilterModal = false"
                      class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                      Zrušit
                    </button>
                    <button
                      @click="applyFiltersFromModal"
                      class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors"
                    >
                      Použít filtry
                    </button>
                  </div>
                </div>
              </div>
            </transition>
          </div>
        </div>
      </transition>
    </Teleport>

    <!-- Edit/Create Modal (CRUD form) -->
    <Teleport to="body">
      <transition name="modal">
        <div
          v-if="showFormModal"
          class="fixed inset-0 z-50 overflow-y-auto"
          @click.self="closeFormModal"
        >
          <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <transition name="modal-backdrop">
              <div
                v-if="showFormModal"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="closeFormModal"
              ></div>
            </transition>

            <transition name="modal-panel">
              <div
                v-if="showFormModal"
                class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
              >
                <div class="bg-white dark:bg-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                  <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                      {{ editingItem ? editTitle : createTitle }}
                    </h3>
                    <button
                      @click="closeFormModal"
                      class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </div>

                <form @submit.prevent="saveItem">
                  <div class="bg-white dark:bg-gray-800 px-6 py-6">
                    <slot
                      name="form"
                      :form="form"
                      :editing="!!editingItem"
                      :item="editingItem"
                    >
                      <div class="space-y-4">
                        <div
                          v-for="field in formFields"
                          :key="field.key"
                          :class="field.fullWidth ? 'col-span-2' : ''"
                        >
                          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ field.label }}
                            <span v-if="field.required" class="text-secondary-500">*</span>
                          </label>

                          <input
                            v-if="field.type === 'text' || field.type === 'email'"
                            v-model="form[field.key]"
                            :type="field.type"
                            :required="field.required"
                            :placeholder="field.placeholder"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                          />

                          <select
                            v-else-if="field.type === 'select'"
                            v-model="form[field.key]"
                            :required="field.required"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                          >
                            <option value="">{{ field.placeholder || 'Vyberte...' }}</option>
                            <option
                              v-for="option in field.options"
                              :key="option.value"
                              :value="option.value"
                            >
                              {{ option.label }}
                            </option>
                          </select>

                          <textarea
                            v-else-if="field.type === 'textarea'"
                            v-model="form[field.key]"
                            :required="field.required"
                            :placeholder="field.placeholder"
                            :rows="field.rows || 3"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none dark:bg-gray-700 dark:text-gray-100"
                          ></textarea>

                          <label
                            v-else-if="field.type === 'checkbox'"
                            class="flex items-center space-x-2"
                          >
                            <input
                              v-model="form[field.key]"
                              type="checkbox"
                              class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            />
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ field.checkboxLabel }}</span>
                          </label>

                          <input
                            v-else-if="field.type === 'date'"
                            v-model="form[field.key]"
                            type="date"
                            :required="field.required"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                          />

                          <input
                            v-else-if="field.type === 'number'"
                            v-model.number="form[field.key]"
                            type="number"
                            :required="field.required"
                            :placeholder="field.placeholder"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                          />
                        </div>
                      </div>
                    </slot>
                  </div>

                  <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-t border-gray-200 dark:border-gray-600 flex justify-end gap-3">
                    <button
                      type="button"
                      @click="closeFormModal"
                      class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                    >
                      Zrušit
                    </button>
                    <button
                      type="submit"
                      class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                      <span>{{ editingItem ? saveButtonLabel : createButtonLabel }}</span>
                    </button>
                  </div>
                </form>
              </div>
            </transition>
          </div>
        </div>
      </transition>
    </Teleport>

    <!-- Pagination / Info -->
    <div class="px-4 py-3 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-3">
      <!-- Info -->
      <div class="text-sm text-gray-600">
        <template v-if="enablePagination">
          Zobrazeno <strong>{{ startIndex + 1 }}</strong> - <strong>{{ Math.min(endIndex, filteredItems.length) }}</strong> z <strong>{{ filteredItems.length }}</strong> záznamů
        </template>
        <template v-else>
          Celkem <strong>{{ filteredItems.length }}</strong> záznamů
        </template>
      </div>

      <!-- Per Page + Pagination -->
      <div v-if="enablePagination" class="flex items-center gap-4">
        <!-- Per Page Selector -->
        <div class="flex items-center gap-2">
          <label class="text-sm text-gray-600">Na stránku:</label>
          <select
            v-model.number="perPage"
            @change="changePage(1)"
            class="px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option :value="10">10</option>
            <option :value="25">25</option>
            <option :value="50">50</option>
            <option :value="100">100</option>
          </select>
        </div>

        <!-- Pagination Controls -->
        <div class="flex items-center gap-1">
          <button
            @click="changePage(currentPage - 1)"
            :disabled="currentPage === 1"
            :class="[
              'p-1.5 rounded transition-colors',
              currentPage === 1
                ? 'text-gray-400 cursor-not-allowed'
                : 'text-gray-600 hover:bg-gray-100'
            ]"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>

          <div class="flex gap-1">
            <button
              v-for="page in visiblePages"
              :key="page"
              @click="changePage(page)"
              :class="[
                'px-3 py-1.5 text-sm rounded transition-colors',
                page === currentPage
                  ? 'bg-blue-600 text-white font-medium'
                  : 'text-gray-600 hover:bg-gray-100'
              ]"
            >
              {{ page }}
            </button>
          </div>

          <button
            @click="changePage(currentPage + 1)"
            :disabled="currentPage === totalPages"
            :class="[
              'p-1.5 rounded transition-colors',
              currentPage === totalPages
                ? 'text-gray-400 cursor-not-allowed'
                : 'text-gray-600 hover:bg-gray-100'
            ]"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
  items: {
    type: Array,
    required: true
  },
  columns: {
    type: Array,
    required: true
  },
  itemKey: {
    type: String,
    default: 'id'
  },
  bulkActions: {
    type: Array,
    default: () => []
  },
  filters: {
    type: Array,
    default: () => []
  },
  filterSections: {
    type: Array,
    default: () => []
  },
  initialPerPage: {
    type: Number,
    default: 25
  },
  showCreateButton: {
    type: Boolean,
    default: false
  },
  createButtonLabel: {
    type: String,
    default: 'Vytvořit nový'
  },
  persistKey: {
    type: String,
    default: 'datatable'
  },
  enablePagination: {
    type: Boolean,
    default: true
  },
  enablePersistence: {
    type: Boolean,
    default: true
  },
  formFields: {
    type: Array,
    default: () => []
  },
  createTitle: {
    type: String,
    default: 'Nový záznam'
  },
  editTitle: {
    type: String,
    default: 'Upravit záznam'
  },
  saveButtonLabel: {
    type: String,
    default: 'Uložit změny'
  }
})

const emit = defineEmits(['bulk-action', 'edit', 'delete', 'update', 'selection-changed', 'create'])

// Helper function: Load from localStorage
const loadFromStorage = (key, defaultValue) => {
  if (!props.enablePersistence) return defaultValue

  try {
    const stored = localStorage.getItem(`${props.persistKey}_${key}`)
    return stored ? JSON.parse(stored) : defaultValue
  } catch (e) {
    console.warn('Failed to load from localStorage:', e)
    return defaultValue
  }
}

// Helper function: Save to localStorage
const saveToStorage = (key, value) => {
  if (!props.enablePersistence) return

  try {
    localStorage.setItem(`${props.persistKey}_${key}`, JSON.stringify(value))
  } catch (e) {
    console.warn('Failed to save to localStorage:', e)
  }
}

// Search - with persistence
const searchQuery = ref(loadFromStorage('searchQuery', ''))
const showFilterModal = ref(false)
const activeFilters = ref(loadFromStorage('activeFilters', {}))
const tempFilters = ref({})

// Selection - with persistence
const selectedItems = ref(loadFromStorage('selectedItems', []))
const selectionMode = ref(loadFromStorage('selectionMode', 'include'))
const selectedBulkAction = ref('')

// Sorting - with persistence
const sortKey = ref(loadFromStorage('sortKey', ''))
const sortDirection = ref(loadFromStorage('sortDirection', 'asc'))

// Pagination - with persistence
const currentPage = ref(loadFromStorage('currentPage', 1))
const perPage = ref(loadFromStorage('perPage', props.initialPerPage))

// CRUD form modal (when formFields is used)
const showFormModal = ref(false)
const editingItem = ref(null)
const form = ref({})

const hasCrudForm = computed(() => props.formFields.length > 0)

const initializeForm = (item = null) => {
  const newForm = {}
  if (props.formFields.length > 0) {
    props.formFields.forEach(field => {
      newForm[field.key] = item ? item[field.key] : (field.default || '')
    })
  } else if (item) {
    Object.keys(item).forEach(key => {
      newForm[key] = item[key]
    })
  }
  return newForm
}

const openCreateModal = () => {
  editingItem.value = null
  form.value = initializeForm()
  showFormModal.value = true
}

const openEditModal = (item) => {
  editingItem.value = item
  form.value = initializeForm(item)
  showFormModal.value = true
}

const closeFormModal = () => {
  showFormModal.value = false
  editingItem.value = null
  form.value = {}
}

const saveItem = () => {
  if (editingItem.value) {
    emit('update', {
      id: editingItem.value[props.itemKey],
      item: editingItem.value,
      data: form.value
    })
  } else {
    emit('create', form.value)
  }
  closeFormModal()
}

const handleCreateClick = () => {
  if (hasCrudForm.value) {
    openCreateModal()
  } else {
    emit('create')
  }
}

const handleEditClick = (item) => {
  if (hasCrudForm.value) {
    openEditModal(item)
  } else {
    emit('edit', item)
  }
}

const handleDeleteClick = (item) => {
  emit('delete', item)
}

// Computed: Active filters count
const activeFiltersCount = computed(() => {
  return Object.values(activeFilters.value).filter(val => val !== '' && val !== null).length
})

// Computed: Total items
const totalItems = computed(() => props.items.length)

// Computed: Filtered items
const filteredItems = computed(() => {
  let result = [...props.items]

  // Apply search
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(item => {
      return props.columns.some(column => {
        const value = item[column.key]
        return value && String(value).toLowerCase().includes(query)
      })
    })
  }

  // Apply filters
  Object.keys(activeFilters.value).forEach(key => {
    const filterValue = activeFilters.value[key]
    if (filterValue !== '' && filterValue !== null) {
      result = result.filter(item => {
        return String(item[key]).toLowerCase().includes(String(filterValue).toLowerCase())
      })
    }
  })

  // Apply sorting
  if (sortKey.value) {
    result.sort((a, b) => {
      const aVal = a[sortKey.value]
      const bVal = b[sortKey.value]

      let comparison = 0
      if (aVal > bVal) comparison = 1
      if (aVal < bVal) comparison = -1

      return sortDirection.value === 'asc' ? comparison : -comparison
    })
  }

  return result
})

// Computed: Pagination
const totalPages = computed(() => props.enablePagination ? Math.ceil(filteredItems.value.length / perPage.value) : 1)
const startIndex = computed(() => props.enablePagination ? (currentPage.value - 1) * perPage.value : 0)
const endIndex = computed(() => props.enablePagination ? startIndex.value + perPage.value : filteredItems.value.length)
const paginatedItems = computed(() => props.enablePagination ? filteredItems.value.slice(startIndex.value, endIndex.value) : filteredItems.value)

const visiblePages = computed(() => {
  const pages = []
  const maxVisible = 5
  let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2))
  let end = Math.min(totalPages.value, start + maxVisible - 1)

  if (end - start < maxVisible - 1) {
    start = Math.max(1, end - maxVisible + 1)
  }

  for (let i = start; i <= end; i++) {
    pages.push(i)
  }

  return pages
})

// Computed: Selection state
const isAllSelected = computed(() => {
  if (selectionMode.value === 'include') {
    return paginatedItems.value.length > 0 &&
           paginatedItems.value.every(item => selectedItems.value.includes(item[props.itemKey]))
  } else {
    return paginatedItems.value.length > 0 &&
           paginatedItems.value.every(item => !selectedItems.value.includes(item[props.itemKey]))
  }
})

const isIndeterminate = computed(() => {
  if (selectionMode.value === 'include') {
    const selectedOnPage = paginatedItems.value.filter(item =>
      selectedItems.value.includes(item[props.itemKey])
    ).length
    return selectedOnPage > 0 && selectedOnPage < paginatedItems.value.length
  } else {
    const unselectedOnPage = paginatedItems.value.filter(item =>
      selectedItems.value.includes(item[props.itemKey])
    ).length
    return unselectedOnPage > 0 && unselectedOnPage < paginatedItems.value.length
  }
})

// Methods: Selection
const isItemSelected = (itemId) => {
  if (selectionMode.value === 'include') {
    return selectedItems.value.includes(itemId)
  } else {
    return !selectedItems.value.includes(itemId)
  }
}

const toggleItem = (itemId) => {
  const index = selectedItems.value.indexOf(itemId)
  if (index > -1) {
    selectedItems.value.splice(index, 1)
  } else {
    selectedItems.value.push(itemId)
  }
  emitSelectionChanged()
}

const toggleSelectAll = () => {
  const pageIds = paginatedItems.value.map(item => item[props.itemKey])

  if (isAllSelected.value) {
    // Deselect all on current page
    if (selectionMode.value === 'include') {
      selectedItems.value = selectedItems.value.filter(id => !pageIds.includes(id))
    } else {
      selectedItems.value = [...selectedItems.value, ...pageIds]
    }
  } else {
    // Select all on current page
    if (selectionMode.value === 'include') {
      pageIds.forEach(id => {
        if (!selectedItems.value.includes(id)) {
          selectedItems.value.push(id)
        }
      })
    } else {
      selectedItems.value = selectedItems.value.filter(id => !pageIds.includes(id))
    }
  }
  emitSelectionChanged()
}

const clearSelection = () => {
  selectedItems.value = []
  selectionMode.value = 'include'
  emitSelectionChanged()
}

const clearPersistence = () => {
  if (!props.enablePersistence) return

  const keys = [
    'searchQuery', 'activeFilters', 'selectedItems',
    'selectionMode', 'sortKey', 'sortDirection',
    'currentPage', 'perPage'
  ]

  keys.forEach(key => {
    localStorage.removeItem(`${props.persistKey}_${key}`)
  })
}

const switchToIncludeMode = () => {
  // Switch from exclude to include mode
  const allIds = filteredItems.value.map(item => item[props.itemKey])
  selectedItems.value = allIds.filter(id => !selectedItems.value.includes(id))
  selectionMode.value = 'include'
  emitSelectionChanged()
}

const emitSelectionChanged = () => {
  const selected = selectionMode.value === 'include'
    ? selectedItems.value
    : filteredItems.value.map(item => item[props.itemKey]).filter(id => !selectedItems.value.includes(id))

  emit('selection-changed', {
    mode: selectionMode.value,
    items: selected,
    excludedItems: selectionMode.value === 'exclude' ? selectedItems.value : []
  })
}

// Methods: Search & Filters
const handleSearch = () => {
  currentPage.value = 1
}

const applyFiltersFromModal = () => {
  activeFilters.value = { ...tempFilters.value }
  showFilterModal.value = false
  currentPage.value = 1
}

const resetFilters = () => {
  activeFilters.value = {}
  tempFilters.value = {}
  searchQuery.value = ''
  showFilterModal.value = false
  currentPage.value = 1
}

// Methods: Sorting
const handleSort = (key) => {
  if (sortKey.value === key) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortKey.value = key
    sortDirection.value = 'asc'
  }
  currentPage.value = 1
}

// Methods: Pagination
const changePage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

// Methods: Bulk Actions
const executeBulkAction = () => {
  if (!selectedBulkAction.value) return

  const selected = selectionMode.value === 'include'
    ? selectedItems.value
    : filteredItems.value.map(item => item[props.itemKey]).filter(id => !selectedItems.value.includes(id))

  emit('bulk-action', {
    action: selectedBulkAction.value,
    mode: selectionMode.value,
    items: selected,
    excludedItems: selectionMode.value === 'exclude' ? selectedItems.value : []
  })

  selectedBulkAction.value = ''
}

// Methods: Cell formatting
const formatCellValue = (value, column) => {
  if (column.format === 'date' && value) {
    return new Date(value).toLocaleDateString('cs-CZ')
  }
  if (column.format === 'datetime' && value) {
    return new Date(value).toLocaleString('cs-CZ')
  }
  if (column.format === 'currency' && value !== null && value !== undefined) {
    return new Intl.NumberFormat('cs-CZ', { style: 'currency', currency: 'CZK' }).format(value)
  }
  return value
}

// Watch: Open filter modal - initialize tempFilters
watch(showFilterModal, (newValue) => {
  if (newValue) {
    // Initialize tempFilters with current activeFilters
    tempFilters.value = { ...activeFilters.value }
  }
})

// Watch: Reset page when items change
watch(() => props.items, () => {
  if (currentPage.value > totalPages.value) {
    currentPage.value = 1
  }
})

// Watch: Save search query to localStorage
watch(searchQuery, (newValue) => {
  saveToStorage('searchQuery', newValue)
})

// Watch: Save active filters to localStorage
watch(activeFilters, (newValue) => {
  saveToStorage('activeFilters', newValue)
}, { deep: true })

// Watch: Save selected items to localStorage
watch(selectedItems, (newValue) => {
  saveToStorage('selectedItems', newValue)
}, { deep: true })

// Watch: Save selection mode to localStorage
watch(selectionMode, (newValue) => {
  saveToStorage('selectionMode', newValue)
})

// Watch: Save sort settings to localStorage
watch(sortKey, (newValue) => {
  saveToStorage('sortKey', newValue)
})

watch(sortDirection, (newValue) => {
  saveToStorage('sortDirection', newValue)
})

// Watch: Save pagination settings to localStorage
watch(currentPage, (newValue) => {
  saveToStorage('currentPage', newValue)
})

watch(perPage, (newValue) => {
  saveToStorage('perPage', newValue)
})
</script>

<style scoped>
input[type="checkbox"]:indeterminate {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 16 16'%3e%3cpath stroke='white' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 8h8'/%3e%3c/svg%3e");
}

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

