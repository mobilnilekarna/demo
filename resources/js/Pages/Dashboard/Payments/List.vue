<template>
  <DashboardLayout>
    <div class="p-4 relative">
      <!-- Header -->
      <div class="mb-6">
        <div class="flex items-center justify-between mb-2">
          <div class="flex items-center gap-2">
            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
              <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
            </div>
            <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Správa plateb</h1>
          </div>
          <button
            @click="showSortingModal = true"
            class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium transition-colors flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
            </svg>
            <span>Seřadit</span>
          </button>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-400">
          Spravujte způsoby platby
        </p>
      </div>

      <!-- DataTable (with CRUD form) -->
      <DataTable
        :items="payments"
        :columns="columns"
        :form-fields="formFields"
        :filter-sections="filterSections"
        item-key="id"
        persist-key="payments-table"
        :show-create-button="true"
        create-button-label="Přidat platbu"
        create-title="Nová platba"
        edit-title="Upravit platbu"
        save-button-label="Uložit"
        :enable-pagination="false"
        @create="handleCreate"
        @update="handleUpdate"
        @delete="handleDelete"
      >
        <!-- Custom column for type -->
        <template #cell-type="{ value }">
          <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 text-xs rounded font-medium">
            {{ getPaymentTypeLabel(value) }}
          </span>
        </template>

        <!-- Custom column for price -->
        <template #cell-price="{ value }">
          {{ formatPrice(value) }}
        </template>

        <!-- Custom column for active -->
        <template #cell-active="{ value }">
          <span :class="value ? 'text-green-600' : 'text-gray-400'" class="font-medium">
            {{ value ? 'Ano' : 'Ne' }}
          </span>
        </template>

        <!-- Custom form -->
        <template #form="{ form, editing }">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Název <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.name"
                type="text"
                required
                placeholder="Název platby"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Popis
              </label>
              <textarea
                v-model="form.description"
                rows="3"
                placeholder="Popis platby"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
              ></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Typ platby <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="form.type"
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                >
                  <option value="">Vyberte typ</option>
                  <option v-for="type in paymentTypes" :key="type.value" :value="type.value">
                    {{ type.label }}
                  </option>
                </select>
              </div>

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
                  placeholder="0.00"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
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
                  placeholder="0"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
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
          </div>
        </template>
      </DataTable>

      <!-- Sorting Modal -->
      <SortingModal
        :show="showSortingModal"
        :items="payments"
        item-key="id"
        title="Seřadit platby"
        description="Přetáhněte položky pro změnu jejich pořadí. Po uložení se pořadí použije ve výpisu."
        @close="showSortingModal = false"
        @save="handleSortingSave"
      >
        <template #item="{ item, index }">
          <div class="font-medium text-gray-900 dark:text-gray-100">
            {{ item.name }}
          </div>
          <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ getPaymentTypeLabel(item.type) }}
          </div>
        </template>
      </SortingModal>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import DataTable from '@/Components/Dashboard/DataTable.vue'
import SortingModal from '@/Components/Dashboard/SortingModal.vue'
import { useToast } from '@/Composables/useToast'

const toast = useToast()
const showSortingModal = ref(false)

const props = defineProps({
  payments: {
    type: Array,
    default: () => []
  },
  paymentTypes: {
    type: Array,
    default: () => []
  }
})

// Table columns configuration
const columns = [
  {
    key: 'name',
    label: 'Název',
    sortable: true,
    searchable: true
  },
  {
    key: 'type',
    label: 'Typ',
    sortable: true
  },
  {
    key: 'price',
    label: 'Cena',
    sortable: true
  },
  {
    key: 'order',
    label: 'Pořadí',
    sortable: true
  },
  {
    key: 'active',
    label: 'Aktivní',
    sortable: true
  }
]

// Form fields configuration
const formFields = [
  { key: 'name', label: 'Název', type: 'text', required: true, default: '' },
  { key: 'description', label: 'Popis', type: 'textarea', required: false, default: '' },
  { key: 'type', label: 'Typ', type: 'select', required: true, default: '' },
  { key: 'price', label: 'Cena', type: 'number', required: true, default: 0 },
  { key: 'order', label: 'Pořadí', type: 'number', required: false, default: 0 },
  { key: 'active', label: 'Aktivní', type: 'checkbox', required: false, default: true }
]

// Filter sections
const filterSections = computed(() => [
  {
    id: 'filters',
    label: 'Filtry',
    filters: [
      {
        key: 'type',
        type: 'select',
        label: 'Typ platby',
        options: [
          { value: '', label: 'Vše' },
          ...props.paymentTypes.map(type => ({
            value: type.value,
            label: type.label
          }))
        ]
      },
      {
        key: 'active',
        type: 'select',
        label: 'Aktivní',
        options: [
          { value: '', label: 'Vše' },
          { value: '1', label: 'Ano' },
          { value: '0', label: 'Ne' }
        ]
      }
    ]
  }
])

// Helper functions
const getPaymentTypeLabel = (value) => {
  const type = props.paymentTypes.find(t => t.value === value)
  return type ? type.label : value
}

const formatPrice = (value) => {
  if (value === null || value === undefined) return '-'
  return new Intl.NumberFormat('cs-CZ', {
    style: 'currency',
    currency: 'CZK'
  }).format(value)
}

// Handlers
const handleCreate = (formData) => {
  router.post('/dashboard/payments', formData, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Vytvořeno', 'Platba byla úspěšně vytvořena.')
    },
    onError: (errors) => {
      toast.error('Chyba', 'Nepodařilo se vytvořit platbu.')
    }
  })
}

const handleUpdate = ({ id, data }) => {
  router.put(`/dashboard/payments/${id}`, data, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Uloženo', 'Platba byla úspěšně aktualizována.')
    },
    onError: (errors) => {
      toast.error('Chyba', 'Nepodařilo se uložit změny platby.')
    }
  })
}

const handleDelete = (payment) => {
  if (confirm(`Opravdu chcete smazat platbu "${payment.name}"?`)) {
    router.delete(`/dashboard/payments/${payment.id}`, {
      preserveScroll: true,
      onSuccess: () => {
        toast.success('Smazáno', `Platba "${payment.name}" byla úspěšně smazána.`)
      },
      onError: (errors) => {
        toast.error('Chyba', 'Nepodařilo se smazat platbu.')
      }
    })
  }
}

const handleSortingSave = (itemsWithOrder) => {
  router.post('/dashboard/payments/order', {
    items: itemsWithOrder
  }, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Uloženo', 'Pořadí plateb bylo úspěšně aktualizováno.')
      showSortingModal.value = false
      router.reload({ only: ['payments'] })
    },
    onError: (errors) => {
      toast.error('Chyba', 'Nepodařilo se uložit pořadí plateb.')
      showSortingModal.value = false
    }
  })
}
</script>
