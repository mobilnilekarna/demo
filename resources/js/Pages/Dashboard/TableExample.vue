<template>
  <DashboardLayout>
    <div class="p-4">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Správa uživatelů</h1>
            <p class="text-sm text-gray-600 mt-1">
              Příklad použití DataTable komponenty s automatickou persistencí
            </p>
          </div>
          <button
            @click="clearTableState"
            class="px-3 py-2 text-sm text-gray-600 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center space-x-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <span>Vyčistit stav tabulky</span>
          </button>
        </div>

        <!-- DataTable (with CRUD form) -->
        <DataTable
          :items="users"
          :columns="columns"
          :form-fields="formFields"
          :bulk-actions="bulkActions"
          :filter-sections="filterSections"
          item-key="id"
          :initial-per-page="25"
          persist-key="users-table"
          :enable-persistence="true"
          :show-create-button="true"
          create-button-label="Přidat uživatele"
          create-title="Nový uživatel"
          edit-title="Upravit uživatele"
          save-button-label="Uložit"
          @create="handleCreate"
          @update="handleUpdate"
          @delete="handleDelete"
          @bulk-action="handleBulkAction"
          @selection-changed="handleSelectionChanged"
        >
          <!-- Custom cell for status -->
          <template #cell-status="{ value }">
            <span
              :class="[
                'inline-flex px-2 py-1 text-xs font-medium rounded-full',
                value === 'active' ? 'bg-green-100 text-green-800' :
                value === 'inactive' ? 'bg-gray-100 text-gray-800' :
                'bg-secondary-100 text-secondary-800'
              ]"
            >
              {{ statusLabels[value] }}
            </span>
          </template>

          <!-- Custom cell for role -->
          <template #cell-role="{ value }">
            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">
              {{ roleLabels[value] }}
            </span>
          </template>

          <!-- Custom form using slot -->
          <template #form="{ form, editing }">
            <div class="space-y-6">
              <!-- Personal Info Section -->
              <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center space-x-2">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  <span>Osobní údaje</span>
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Jméno <span class="text-secondary-500">*</span>
                    </label>
                    <input
                      v-model="form.name"
                      type="text"
                      required
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      placeholder="Jan Novák"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Email <span class="text-secondary-500">*</span>
                    </label>
                    <input
                      v-model="form.email"
                      type="email"
                      required
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      placeholder="jan.novak@example.com"
                    />
                  </div>
                </div>
              </div>

              <!-- Account Settings Section -->
              <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center space-x-2">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  <span>Nastavení účtu</span>
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Role <span class="text-secondary-500">*</span>
                    </label>
                    <select
                      v-model="form.role"
                      required
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                      <option value="">Vyberte roli</option>
                      <option value="admin">Administrátor</option>
                      <option value="editor">Editor</option>
                      <option value="user">Uživatel</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Stav <span class="text-secondary-500">*</span>
                    </label>
                    <select
                      v-model="form.status"
                      required
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                      <option value="">Vyberte stav</option>
                      <option value="active">Aktivní</option>
                      <option value="inactive">Neaktivní</option>
                      <option value="suspended">Pozastaveno</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Additional Info Section -->
              <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center space-x-2">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  <span>Další informace</span>
                </h4>

                <div class="grid grid-cols-1 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Poznámka
                    </label>
                    <textarea
                      v-model="form.note"
                      rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                      placeholder="Volitelná poznámka k uživateli..."
                    ></textarea>
                  </div>

                  <div v-if="!editing">
                    <label class="flex items-center space-x-2">
                      <input
                        v-model="form.sendWelcomeEmail"
                        type="checkbox"
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                      />
                      <span class="text-sm text-gray-700">Odeslat uvítací email</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </DataTable>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref } from 'vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import DataTable from '@/Components/Dashboard/DataTable.vue'
import { useToast } from '@/Composables/useToast'

// Toast
const toast = useToast()

// Table data
const users = ref([
  { id: 1, name: 'Jan Novák', email: 'jan.novak@example.com', role: 'admin', status: 'active', createdAt: '2024-01-15' },
  { id: 2, name: 'Marie Svobodová', email: 'marie.svobodova@example.com', role: 'editor', status: 'active', createdAt: '2024-01-18' },
  { id: 3, name: 'Petr Dvořák', email: 'petr.dvorak@example.com', role: 'user', status: 'inactive', createdAt: '2024-01-20' },
  { id: 4, name: 'Jana Nováková', email: 'jana.novakova@example.com', role: 'user', status: 'active', createdAt: '2024-01-22' },
  { id: 5, name: 'Tomáš Procházka', email: 'tomas.prochazka@example.com', role: 'editor', status: 'active', createdAt: '2024-01-25' },
  { id: 6, name: 'Eva Černá', email: 'eva.cerna@example.com', role: 'user', status: 'suspended', createdAt: '2024-01-28' },
  { id: 7, name: 'Martin Svoboda', email: 'martin.svoboda@example.com', role: 'admin', status: 'active', createdAt: '2024-02-01' },
  { id: 8, name: 'Lenka Horáková', email: 'lenka.horakova@example.com', role: 'user', status: 'active', createdAt: '2024-02-03' },
  { id: 9, name: 'Michal Veselý', email: 'michal.vesely@example.com', role: 'editor', status: 'inactive', createdAt: '2024-02-05' },
  { id: 10, name: 'Petra Malá', email: 'petra.mala@example.com', role: 'user', status: 'active', createdAt: '2024-02-08' },
])

// Table columns
const columns = [
  { key: 'id', label: 'ID', sortable: true },
  { key: 'name', label: 'Jméno', sortable: true },
  { key: 'email', label: 'Email', sortable: true },
  { key: 'role', label: 'Role', sortable: true },
  { key: 'status', label: 'Stav', sortable: true },
  { key: 'createdAt', label: 'Vytvořeno', sortable: true, format: 'date' },
]

// Form fields (used by DataTable to generate default form)
const formFields = [
  { key: 'name', label: 'Jméno', type: 'text', required: true, placeholder: 'Jan Novák' },
  { key: 'email', label: 'Email', type: 'email', required: true, placeholder: 'jan.novak@example.com' },
  { key: 'role', label: 'Role', type: 'select', required: true, options: [
    { value: 'admin', label: 'Administrátor' },
    { value: 'editor', label: 'Editor' },
    { value: 'user', label: 'Uživatel' },
  ]},
  { key: 'status', label: 'Stav', type: 'select', required: true, default: 'active', options: [
    { value: 'active', label: 'Aktivní' },
    { value: 'inactive', label: 'Neaktivní' },
    { value: 'suspended', label: 'Pozastaveno' },
  ]},
  { key: 'note', label: 'Poznámka', type: 'textarea', rows: 3, placeholder: 'Volitelná poznámka...' },
  { key: 'sendWelcomeEmail', label: 'Odeslat uvítací email', type: 'checkbox', default: true, checkboxLabel: 'Odeslat uvítací email' },
]

// Bulk actions
const bulkActions = [
  { id: 'activate', label: '✓ Aktivovat' },
  { id: 'deactivate', label: '⊘ Deaktivovat' },
  { id: 'suspend', label: '⏸ Pozastavit' },
  { id: 'export', label: '↓ Exportovat' },
  { id: 'send-email', label: '✉ Odeslat email' },
  { id: 'delete', label: '✕ Smazat' },
]

// Filter sections
const filterSections = [
  {
    id: 'account',
    label: 'Účet',
    filters: [
      {
        key: 'role',
        label: 'Role',
        type: 'select',
        options: [
          { value: 'admin', label: 'Administrátor' },
          { value: 'editor', label: 'Editor' },
          { value: 'user', label: 'Uživatel' },
        ]
      },
      {
        key: 'status',
        label: 'Stav',
        type: 'select',
        options: [
          { value: 'active', label: 'Aktivní' },
          { value: 'inactive', label: 'Neaktivní' },
          { value: 'suspended', label: 'Pozastaveno' },
        ]
      },
    ]
  },
  {
    id: 'dates',
    label: 'Datum',
    filters: [
      {
        key: 'createdAt',
        label: 'Vytvořeno od',
        type: 'date'
      },
      {
        key: 'createdTo',
        label: 'Vytvořeno do',
        type: 'date'
      },
    ]
  },
]

// Labels
const statusLabels = {
  active: 'Aktivní',
  inactive: 'Neaktivní',
  suspended: 'Pozastaveno'
}

const roleLabels = {
  admin: 'Administrátor',
  editor: 'Editor',
  user: 'Uživatel'
}

// Event handlers
const handleCreate = (formData) => {
  const newUser = {
    id: Math.max(...users.value.map(u => u.id)) + 1,
    ...formData,
    createdAt: new Date().toISOString().split('T')[0]
  }
  users.value.unshift(newUser)
  toast.success('Vytvořeno', `Uživatel ${formData.name} byl úspěšně vytvořen!`)
}

const handleUpdate = ({ id, data }) => {
  const index = users.value.findIndex(u => u.id === id)
  if (index > -1) {
    users.value[index] = {
      ...users.value[index],
      ...data
    }
    toast.success('Uloženo', `Uživatel ${data.name} byl úspěšně aktualizován!`)
  }
}

const handleDelete = (item) => {
  if (confirm(`Opravdu smazat ${item.name}?`)) {
    const index = users.value.findIndex(u => u.id === item.id)
    if (index > -1) {
      users.value.splice(index, 1)
      toast.error('Smazáno', `Uživatel ${item.name} byl smazán`)
    }
  }
}

const handleBulkAction = (data) => {
  switch (data.action) {
    case 'activate':
      data.items.forEach(id => {
        const user = users.value.find(u => u.id === id)
        if (user) user.status = 'active'
      })
      toast.success('Aktivováno', `${data.items.length} uživatelů bylo aktivováno`)
      break

    case 'deactivate':
      data.items.forEach(id => {
        const user = users.value.find(u => u.id === id)
        if (user) user.status = 'inactive'
      })
      toast.warning('Deaktivováno', `${data.items.length} uživatelů bylo deaktivováno`)
      break

    case 'suspend':
      data.items.forEach(id => {
        const user = users.value.find(u => u.id === id)
        if (user) user.status = 'suspended'
      })
      toast.info('Pozastaveno', `${data.items.length} uživatelů bylo pozastaveno`)
      break

    case 'export':
      toast.info('Export', `Exportování ${data.items.length} uživatelů...`)
      setTimeout(() => {
        toast.success('Exportováno', 'Data byla úspěšně exportována')
      }, 1500)
      break

    case 'send-email':
      toast.info('Odesílání', `Odesílání emailu ${data.items.length} uživatelům...`)
      setTimeout(() => {
        toast.success('Odesláno', `Email byl odeslán ${data.items.length} uživatelům`)
      }, 2000)
      break

    case 'delete':
      if (confirm(`Opravdu smazat ${data.items.length} položek?`)) {
        users.value = users.value.filter(u => !data.items.includes(u.id))
        toast.error('Smazáno', `${data.items.length} uživatelů bylo smazáno`)
      }
      break

    default:
      toast.info('Akce', `Akce: ${data.action} na ${data.items.length} položkách`)
  }
}

const handleSelectionChanged = (data) => {
  console.log('Selection changed:', data)
}

// Clear table state
const clearTableState = () => {
  if (confirm('Opravdu vyčistit všechny uložené stavy tabulky (vyhledávání, filtry, výběry, řazení)?')) {
    const keys = [
      'searchQuery', 'activeFilters', 'selectedItems',
      'selectionMode', 'sortKey', 'sortDirection',
      'currentPage', 'perPage'
    ]

    keys.forEach(key => {
      localStorage.removeItem(`users-table_${key}`)
    })

    toast.info('Vyčištěno', 'Stav tabulky byl resetován. Obnovte stránku pro aplikování změn.')

    setTimeout(() => {
      window.location.reload()
    }, 2000)
  }
}
</script>
