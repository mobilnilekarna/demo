<template>
  <Teleport to="body">
    <transition name="modal">
      <div
        v-if="show"
        class="fixed inset-0 z-50 overflow-y-auto"
        @click.self="handleClose"
      >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
          <!-- Backdrop -->
          <transition name="modal-backdrop">
            <div
              v-if="show"
              class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
              @click="handleClose"
            ></div>
          </transition>

          <!-- Modal Panel -->
          <transition name="modal-panel">
            <div
              v-if="show"
              class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
            >
              <!-- Header -->
              <div class="bg-white dark:bg-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                  <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ title }}
                  </h3>
                  <button
                    @click="handleClose"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                  >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Body -->
              <div class="bg-white dark:bg-gray-800 px-6 py-6">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                  {{ description }}
                </p>

                <!-- Sortable List -->
                <div
                  ref="sortableListRef"
                  class="space-y-2"
                >
                  <div
                    v-for="(item, index) in sortedItems"
                    :key="getItemKey(item, index)"
                    :data-id="getItemKey(item, index)"
                    class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg cursor-move hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors sortable-item"
                  >
                    <!-- Drag Handle -->
                    <div class="flex-shrink-0 text-gray-400 dark:text-gray-500">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                      </svg>
                    </div>

                    <!-- Item Content -->
                    <div class="flex-1 min-w-0">
                      <slot name="item" :item="item" :index="index">
                        <div class="font-medium text-gray-900 dark:text-gray-100">
                          {{ getItemLabel(item) }}
                        </div>
                      </slot>
                    </div>

                    <!-- Order Indicator -->
                    <div class="flex-shrink-0 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 text-xs font-medium rounded">
                      #{{ index + 1 }}
                    </div>
                  </div>

                  <!-- Empty State -->
                  <div v-if="sortedItems.length === 0" class="text-center py-12 text-gray-500 dark:text-gray-400">
                    <p>Žádné položky k seřazení</p>
                  </div>
                </div>
              </div>

              <!-- Footer -->
              <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                <button
                  type="button"
                  @click="handleClose"
                  class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                >
                  Zrušit
                </button>
                <button
                  type="button"
                  @click="handleSave"
                  :disabled="isSaving || !hasChanges"
                  class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors flex items-center space-x-2"
                >
                  <svg v-if="isSaving" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <span>{{ isSaving ? 'Ukládání...' : 'Uložit pořadí' }}</span>
                </button>
              </div>
            </div>
          </transition>
        </div>
      </div>
    </transition>
  </Teleport>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted, onUpdated } from 'vue'
import Sortable from 'sortablejs'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  items: {
    type: Array,
    required: true
  },
  itemKey: {
    type: String,
    default: 'id'
  },
  title: {
    type: String,
    default: 'Seřadit položky'
  },
  description: {
    type: String,
    default: 'Přetáhněte položky pro změnu jejich pořadí'
  },
  getItemLabel: {
    type: Function,
    default: (item) => item.name || item.id
  }
})

const emit = defineEmits(['close', 'save'])

const sortableListRef = ref(null)
const sortedItems = ref([])
const originalOrder = ref([])
let sortableInstance = null
const isSaving = ref(false)

// Computed
const hasChanges = computed(() => {
  if (originalOrder.value.length !== sortedItems.value.length) return true
  
  return originalOrder.value.some((id, index) => {
    const currentId = getItemKey(sortedItems.value[index], index)
    return id !== currentId
  })
})

// Methods
const getItemKey = (item, index) => {
  if (item && typeof item === 'object' && item[props.itemKey] !== undefined) {
    return item[props.itemKey]
  }
  return index
}

const initializeItems = () => {
  // Sort items by order if available, otherwise keep original order
  const itemsCopy = [...props.items]
  sortedItems.value = itemsCopy.sort((a, b) => {
    const aOrder = a.order ?? a.rank ?? 0
    const bOrder = b.order ?? b.rank ?? 0
    if (aOrder !== bOrder) {
      return aOrder - bOrder
    }
    return 0
  })
  
  // Store original order for comparison
  originalOrder.value = sortedItems.value.map((item, index) => getItemKey(item, index))
}

const initSortable = () => {
  if (sortableInstance) {
    sortableInstance.destroy()
    sortableInstance = null
  }

  if (sortableListRef.value && sortedItems.value.length > 0) {
    try {
      sortableInstance = Sortable.create(sortableListRef.value, {
        animation: 150,
        handle: '.sortable-item',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onEnd: (evt) => {
          const fromIndex = evt.oldIndex
          const toIndex = evt.newIndex

          if (fromIndex !== toIndex && fromIndex !== null && toIndex !== null) {
            const [moved] = sortedItems.value.splice(fromIndex, 1)
            sortedItems.value.splice(toIndex, 0, moved)
          }
        }
      })
    } catch (error) {
      console.error('Error initializing Sortable:', error)
    }
  }
}

const handleClose = () => {
  initializeItems()
  emit('close')
}

const handleSave = () => {
  // Create array with id and new order
  const itemsWithOrder = sortedItems.value.map((item, index) => ({
    id: getItemKey(item, index),
    order: index
  }))

  isSaving.value = true
  emit('save', itemsWithOrder)
}

const cleanup = () => {
  if (sortableInstance) {
    sortableInstance.destroy()
    sortableInstance = null
  }
}

// Watch
watch(() => props.show, (newValue) => {
  if (newValue) {
    initializeItems()
    isSaving.value = false
  } else {
    cleanup()
    isSaving.value = false
  }
})

watch(() => props.items, () => {
  if (props.show) {
    initializeItems()
  }
}, { deep: true })

watch([() => props.show, () => sortableListRef.value, () => sortedItems.value.length], ([show, ref, length]) => {
  if (show && ref && length > 0 && !sortableInstance) {
    initSortable()
  }
})

// Lifecycle
onUpdated(() => {
  if (props.show && sortableListRef.value && sortedItems.value.length > 0 && !sortableInstance) {
    initSortable()
  }
})

onMounted(() => {
  if (props.show) {
    initializeItems()
  }
})

onUnmounted(() => {
  cleanup()
})
</script>

<style scoped>
.sortable-ghost {
  opacity: 0.4;
  background-color: #e5e7eb;
}

.sortable-chosen {
  cursor: grabbing;
}

.sortable-drag {
  opacity: 0.8;
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
