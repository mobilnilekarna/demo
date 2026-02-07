<template>
  <DashboardLayout>
    <div class="p-4">
      <!-- Header -->
      <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center gap-2">
            <button
              @click="router.visit('/dashboard/transports')"
              class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
            </button>
            <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Upravit dopravu</h1>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left: Transport Details -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Základní informace</h2>

          <form @submit.prevent="handleUpdate" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Název <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Popis
              </label>
              <textarea
                v-model="form.description"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
              ></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Typ dopravy <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="form.type"
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                >
                  <option value="">Vyberte typ</option>
                  <option v-for="type in transportTypes" :key="type.value" :value="type.value">
                    {{ type.label }}
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Typ doručení
                </label>
                <select
                  v-model="form.type_delivery"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                >
                  <option :value="null">-</option>
                  <option v-for="type in deliveryTypes" :key="type.value" :value="type.value">
                    {{ type.label }}
                  </option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Cena (Kč) <span class="text-red-500">*</span>
                </label>
                <input
                  v-model.number="form.price"
                  type="number"
                  step="0.01"
                  min="0"
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Doprava zdarma od (Kč)
                </label>
                <input
                  v-model.number="form.free_from"
                  type="number"
                  step="0.01"
                  min="0"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                />
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Pořadí
                </label>
                <input
                  v-model.number="form.order"
                  type="number"
                  min="0"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                />
              </div>

              <div>
                <label class="flex items-center space-x-2 mt-7">
                  <input
                    v-model="form.active"
                    type="checkbox"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                  />
                  <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Aktivní</span>
                </label>
              </div>
            </div>

            <div class="flex justify-end">
              <button
                type="submit"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors"
              >
                Uložit změny
              </button>
            </div>
          </form>
        </div>

        <!-- Right: Payments Assignment -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Přiřazené platby</h2>

          <div class="mb-4">
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Přetáhněte platby pro změnu pořadí. Zadejte cenu pro kombinaci doprava + platba.
            </p>
          </div>

          <!-- Available Payments -->
          <div class="mb-6">
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dostupné platby</h3>
            <div class="space-y-2 max-h-48 overflow-y-auto">
              <div
                v-for="payment in availablePayments"
                :key="payment.id"
                class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 flex items-center justify-between"
              >
                <div>
                  <div class="font-medium text-sm text-gray-900 dark:text-gray-100">{{ payment.name }}</div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ formatPrice(payment.price) }}</div>
                </div>
                <button
                  @click="addPayment(payment)"
                  class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors"
                >
                  Přidat
                </button>
              </div>
            </div>
          </div>

          <!-- Assigned Payments (Drag & Drop) -->
          <div>
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Přiřazené platby</h3>
            <div
              ref="paymentsListRef"
              class="space-y-2"
            >
              <div
                v-for="(item, index) in localAssignedPayments"
                :key="item.payment_id"
                class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 cursor-move"
                :data-index="index"
              >
                <div class="flex items-center justify-between mb-2">
                  <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                    </svg>
                    <div>
                      <div class="font-medium text-sm text-gray-900 dark:text-gray-100">{{ item.payment_name }}</div>
                      <div class="text-xs text-gray-500 dark:text-gray-400">Pořadí: {{ item.order }}</div>
                    </div>
                  </div>
                  <button
                    @click="removePayment(index)"
                    class="p-1 text-red-600 hover:text-red-700 hover:bg-red-100 rounded transition-colors"
                    title="Odebrat"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Cena (Kč) - nechte prázdné pro automatický součet
                  </label>
                  <input
                    v-model.number="item.price"
                    type="number"
                    step="0.01"
                    min="0"
                    placeholder="Auto"
                    class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                  />
                </div>
              </div>
              <div v-if="localAssignedPayments.length === 0" class="text-center py-8 text-gray-400 text-sm">
                Žádné platby nejsou přiřazeny
              </div>
            </div>
          </div>

          <div class="mt-6 flex justify-end">
            <button
              @click="handleUpdatePayments"
              class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors"
            >
              Uložit platby
            </button>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted, nextTick, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import Sortable from 'sortablejs'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { useToast } from '@/Composables/useToast'

const toast = useToast()

const props = defineProps({
  transport: {
    type: Object,
    required: true
  },
  allPayments: {
    type: Array,
    default: () => []
  },
  assignedPayments: {
    type: Array,
    default: () => []
  },
  transportTypes: {
    type: Array,
    default: () => []
  },
  deliveryTypes: {
    type: Array,
    default: () => []
  }
})

const form = reactive({ ...props.transport })
const localAssignedPayments = ref([...props.assignedPayments])
const paymentsListRef = ref(null)
let sortableInstance = null

// Available payments (not yet assigned)
const availablePayments = computed(() => {
  const assignedIds = localAssignedPayments.value.map(p => p.payment_id)
  return props.allPayments.filter(p => !assignedIds.includes(p.id))
})

const formatPrice = (value) => {
  if (value === null || value === undefined) return '-'
  return new Intl.NumberFormat('cs-CZ', {
    style: 'currency',
    currency: 'CZK'
  }).format(value)
}

const addPayment = (payment) => {
  const maxOrder = localAssignedPayments.value.length > 0
    ? Math.max(...localAssignedPayments.value.map(p => p.order))
    : -1

  localAssignedPayments.value.push({
    payment_id: payment.id,
    payment_name: payment.name,
    payment_type: payment.type,
    price: null,
    order: maxOrder + 1
  })
}

const removePayment = (index) => {
  localAssignedPayments.value.splice(index, 1)
  // Reorder
  localAssignedPayments.value.forEach((item, idx) => {
    item.order = idx
  })
}

const initSortable = () => {
  if (sortableInstance) {
    sortableInstance.destroy()
    sortableInstance = null
  }

  nextTick(() => {
    if (paymentsListRef.value && localAssignedPayments.value.length > 0) {
      sortableInstance = Sortable.create(paymentsListRef.value, {
        animation: 150,
        handle: '.cursor-move',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onEnd: (evt) => {
          const fromIndex = evt.oldIndex
          const toIndex = evt.newIndex

          if (fromIndex !== toIndex && fromIndex !== null && toIndex !== null) {
            const [moved] = localAssignedPayments.value.splice(fromIndex, 1)
            localAssignedPayments.value.splice(toIndex, 0, moved)

            // Update order
            localAssignedPayments.value.forEach((item, idx) => {
              item.order = idx
            })
          }
        }
      })
    }
  })
}

const handleUpdate = () => {
  router.put(`/dashboard/transports/${props.transport.id}`, form, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Uloženo', 'Doprava byla úspěšně aktualizována.')
    },
    onError: (errors) => {
      toast.error('Chyba', 'Nepodařilo se uložit změny dopravy.')
    }
  })
}

const handleUpdatePayments = () => {
  const paymentsData = localAssignedPayments.value.map((item, index) => ({
    payment_id: item.payment_id,
    price: item.price || null,
    order: index
  }))

  router.post(`/dashboard/transports/${props.transport.id}/payments`, {
    payments: paymentsData
  }, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Uloženo', 'Platby byly úspěšně uloženy.')
    },
    onError: (errors) => {
      toast.error('Chyba', 'Nepodařilo se uložit platby.')
    }
  })
}

onMounted(() => {
  initSortable()
})

watch(() => localAssignedPayments.value, () => {
  initSortable()
}, { deep: true })

onUnmounted(() => {
  if (sortableInstance) {
    sortableInstance.destroy()
  }
})
</script>

<style scoped>
.sortable-ghost {
  opacity: 0.4;
}

.sortable-chosen {
  cursor: grabbing;
}

.sortable-drag {
  opacity: 0.8;
}
</style>
