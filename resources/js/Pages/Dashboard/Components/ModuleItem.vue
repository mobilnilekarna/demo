<template>
  <div class="module-item">
    <div
      class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
      :class="{
        'bg-blue-50 dark:bg-blue-900/20': editingModule?.id === module.id
      }"
    >
      <div class="flex items-start justify-between">
        <div class="flex-1">
          <div class="flex items-center space-x-3 mb-2">
            <div class="cursor-move p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded" title="Přesunout">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
              </svg>
            </div>
            <div v-if="module.icon" v-html="module.icon" class="w-6 h-6 flex-shrink-0"></div>
            <div>
              <div class="flex items-center space-x-2">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100">
                  {{ module.name }}
                </h3>
                <button
                  @click.stop="hasChildren && toggleChildren()
"
                  class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-xs font-medium hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
                  :disabled="!hasChildren"
                  :title="
                    hasChildren
                      ? isExpanded
                        ? 'Skrýt podmoduly'
                        : 'Zobrazit podmoduly'
                      : 'Tento modul zatím nemá žádné podmoduly'
                  "
                >
                  <span>
                    {{
                      hasChildren
                        ? isExpanded
                          ? 'Skrýt podmoduly'
                          : 'Zobrazit podmoduly'
                        : 'Podmoduly'
                    }}
                  </span>
                  <svg
                    class="w-3 h-3 transition-transform"
                    :class="{ 'rotate-90': isExpanded && hasChildren }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
              </div>
              <p v-if="module.description" class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                {{ module.description }}
              </p>
            </div>
          </div>
          <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400 ml-11">
            <span>Slug: <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded">{{ module.slug }}</code></span>
            <span>Rank: <strong>{{ module.rank ?? 0 }}</strong></span>
            <span
              :class="[
                'px-2 py-1 rounded text-xs font-medium',
                module.active
                  ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                  : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
              ]"
            >
              {{ module.active ? 'Aktivní' : 'Neaktivní' }}
            </span>
          </div>
        </div>
        <div class="flex items-center space-x-2 ml-4">
          <button
            @click.stop="$emit('edit', module)"
            class="p-1.5 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded transition-colors"
            title="Upravit"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
          </button>
          <button
            v-if="!hasChildren"
            @click.stop="handleDelete"
            class="p-1.5 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 rounded transition-colors"
            title="Smazat modul"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
          <button
            v-if="index > 0"
            @click.stop="moveUp"
            class="p-1.5 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-colors"
            title="Nahoru"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
          </button>
          <button
            v-if="index < siblings.length - 1"
            @click.stop="moveDown"
            class="p-1.5 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-colors"
            title="Dolů"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Children modules -->
      <div
        v-if="hasChildren && isExpanded"
        :ref="childrenContainerRef"
        class="mt-4 ml-11 space-y-2"
      >
        <div
          v-for="(child, childIndex) in module.children"
          :key="child.id"
          class="module-item-wrapper"
        >
          <module-item
            :module="child"
            :index="childIndex"
            :parent-path="[...parentPath, module.id]"
            :siblings="module.children"
            :editing-module="editingModule"
            @edit="$emit('edit', $event)"
            @update-rank="$emit('update-rank', $event)"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch, nextTick } from 'vue'
import { router } from '@inertiajs/vue3'
import Sortable from 'sortablejs'
import ModuleItem from './ModuleItem.vue'

const props = defineProps({
  module: {
    type: Object,
    required: true
  },
  index: {
    type: Number,
    required: true
  },
  parentPath: {
    type: Array,
    default: () => []
  },
  siblings: {
    type: Array,
    required: true
  },
  editingModule: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['edit', 'update-rank', 'delete'])

const childrenContainerRef = ref(null)
let sortableInstance = null
const isExpanded = ref(false)
const hasChildren = computed(
  () => props.module.children && props.module.children.length > 0
)

const moveUp = () => {
  if (props.index === 0) return
  updateRanks(props.index, props.index - 1)
}

const moveDown = () => {
  if (props.index === props.siblings.length - 1) return
  updateRanks(props.index, props.index + 1)
}

const updateRanks = (fromIndex, toIndex) => {
  const updated = [...props.siblings]
  const [moved] = updated.splice(fromIndex, 1)
  updated.splice(toIndex, 0, moved)

  const modulesData = updated.map((m, i) => ({
    id: m.id,
    rank: i
  }))

  emit('update-rank', modulesData)
}

const initSortable = () => {
  if (sortableInstance) {
    sortableInstance.destroy()
    sortableInstance = null
  }

  const container = childrenContainerRef.value
  if (!container || !props.module.children || props.module.children.length === 0 || !isExpanded.value) {
    return
  }

  try {
    sortableInstance = Sortable.create(container, {
      animation: 150,
      handle: '.module-item-wrapper .cursor-move',
      ghostClass: 'sortable-ghost',
      chosenClass: 'sortable-chosen',
      dragClass: 'sortable-drag',
      draggable: '.module-item-wrapper',
      onEnd: (evt) => {
        const fromIndex = evt.oldIndex
        const toIndex = evt.newIndex

        if (fromIndex !== null && toIndex !== null && fromIndex !== toIndex) {
          const updated = [...props.module.children]
          const [moved] = updated.splice(fromIndex, 1)
          updated.splice(toIndex, 0, moved)

          const modulesData = updated.map((m, i) => ({
            id: m.id,
            rank: i
          }))

          emit('update-rank', modulesData)
        }
      }
    })
  } catch (error) {
    console.error('Error initializing Sortable for children:', error)
  }
}

const toggleChildren = () => {
  isExpanded.value = !isExpanded.value
  if (!isExpanded.value && sortableInstance) {
    sortableInstance.destroy()
    sortableInstance = null
  }
}

const handleDelete = () => {
  if (confirm(`Opravdu chcete smazat modul "${props.module.name}"? Tato akce je nevratná.`)) {
    router.delete(`/dashboard/modules/${props.module.id}`, {
      preserveScroll: true,
      onSuccess: () => {
        emit('delete', props.module.id)
      }
    })
  }
}

onMounted(() => {
  // Výchozí stav: pokud má modul děti, ponecháme je sbalené (uživatel si je rozbalí podle potřeby)
  isExpanded.value = false
})

watch(
  () => isExpanded.value,
  (expanded) => {
    if (expanded) {
      setTimeout(() => initSortable(), 100)
    } else if (sortableInstance) {
      sortableInstance.destroy()
      sortableInstance = null
    }
  }
)

onUnmounted(() => {
  if (sortableInstance) {
    sortableInstance.destroy()
  }
})
</script>
