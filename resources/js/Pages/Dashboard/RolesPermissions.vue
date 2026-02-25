<template>
  <DashboardLayout>
    <div class="p-4">
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Správa rolí a oprávnění</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
          Spravujte role, oprávnění a přiřazení uživatelům
        </p>
      </div>

      <!-- Tabs -->
      <div class="mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700">
          <nav class="-mb-px flex space-x-8">
            <button
              @click="activeTab = 'roles'"
              :class="[
                'py-4 px-1 border-b-2 font-medium text-sm transition-colors',
                activeTab === 'roles'
                  ? 'border-primary-500 text-primary-600 dark:text-primary-400'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'
              ]"
            >
              Role
            </button>
            <button
              @click="activeTab = 'users'"
              :class="[
                'py-4 px-1 border-b-2 font-medium text-sm transition-colors',
                activeTab === 'users'
                  ? 'border-primary-500 text-primary-600 dark:text-primary-400'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'
              ]"
            >
              Uživatelé
            </button>
          </nav>
        </div>
      </div>

      <!-- Roles Tab -->
      <div v-if="activeTab === 'roles'" class="space-y-6">
        <!-- Add Role Button -->
        <div class="flex justify-end">
          <button
            @click="openRoleModal()"
            class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Přidat roli</span>
          </button>
        </div>

        <!-- Roles List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
          <div class="p-6">
            <div v-if="roles.length === 0" class="text-center py-12">
              <p class="text-gray-500 dark:text-gray-400">Žádné role k zobrazení</p>
            </div>

            <div v-else class="space-y-4">
              <div
                v-for="role in roles"
                :key="role.id"
                class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
              >
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                      <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ role.name }}</h3>
                      <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs rounded">
                        {{ role.users_count }} {{ role.users_count === 1 ? 'uživatel' : 'uživatelů' }}
                      </span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                      <span
                        v-for="permission in role.permissions"
                        :key="permission"
                        class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 text-xs rounded"
                      >
                        {{ permission }}
                      </span>
                    </div>
                  </div>
                  <div class="flex items-center space-x-2 ml-4">
                    <button
                      @click="openRoleModal(role)"
                      class="p-1.5 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded transition-colors"
                      title="Upravit"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button
                      @click="deleteRole(role)"
                      class="p-1.5 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 rounded transition-colors"
                      title="Smazat"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Users Tab -->
      <div v-if="activeTab === 'users'" class="space-y-6">
        <!-- Users List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
          <div class="p-6">
            <div v-if="users.length === 0" class="text-center py-12">
              <p class="text-gray-500 dark:text-gray-400">Žádní uživatelé k zobrazení</p>
            </div>

            <div v-else class="space-y-4">
              <div
                v-for="user in users"
                :key="user.id"
                class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
              >
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">{{ user.name }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ user.email }}</p>
                    <div class="flex flex-wrap gap-2">
                      <span
                        v-for="role in user.roles"
                        :key="role"
                        class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 text-xs rounded"
                      >
                        {{ role }}
                      </span>
                      <span v-if="user.roles.length === 0" class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs rounded">
                        Bez role
                      </span>
                    </div>
                  </div>
                  <div class="flex items-center space-x-2 ml-4">
                    <button
                      @click="openUserRoleModal(user)"
                      class="px-3 py-1.5 text-sm text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors"
                    >
                      Upravit role
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Role Modal -->
      <Transition name="modal">
        <div
          v-if="showRoleModal"
          class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
          @click.self="closeRoleModal"
        >
          <Transition name="modal-content">
            <div
              v-if="showRoleModal"
              class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
            >
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                {{ editingRole ? 'Upravit roli' : 'Nová role' }}
              </h2>
              <button
                @click="closeRoleModal"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <form @submit.prevent="saveRole" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Název role <span class="text-secondary-500">*</span>
                </label>
                <input
                  v-model="roleForm.name"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                  Oprávnění
                </label>
                <div class="space-y-3 max-h-96 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                  <div
                    v-for="(groupPermissions, groupName) in permissions"
                    :key="groupName"
                    class="space-y-2"
                  >
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                      {{ groupName }}
                    </h4>
                    <div class="grid grid-cols-2 gap-2 ml-4">
                      <label
                        v-for="permission in groupPermissions"
                        :key="permission.id"
                        class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 p-2 rounded"
                      >
                        <input
                          type="checkbox"
                          :value="permission.name"
                          v-model="roleForm.permissions"
                          class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        />
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ permission.name }}</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="flex justify-end space-x-3 pt-4">
                <button
                  type="button"
                  @click="closeRoleModal"
                  class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors"
                >
                  Zrušit
                </button>
                <button
                  type="submit"
                  class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors"
                >
                  Uložit
                </button>
              </div>
            </form>
            </div>
            </div>
          </Transition>
        </div>
      </Transition>

      <!-- User Role Modal -->
      <Transition name="modal">
        <div
          v-if="showUserRoleModal"
          class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
          @click.self="closeUserRoleModal"
        >
          <Transition name="modal-content">
            <div
              v-if="showUserRoleModal"
              class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full"
            >
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                Upravit role uživatele
              </h2>
              <button
                @click="closeUserRoleModal"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <div class="mb-4">
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                <strong>{{ editingUser?.name }}</strong> ({{ editingUser?.email }})
              </p>
            </div>

            <form @submit.prevent="saveUserRoles" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                  Role
                </label>
                <div class="space-y-2">
                  <label
                    v-for="role in roles"
                    :key="role.id"
                    class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 p-2 rounded"
                  >
                    <input
                      type="radio"
                      :value="role.name"
                      v-model="userRoleForm.role"
                      :name="`user-role-${editingUser ? editingUser.id : 'new'}`"
                      class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                    />
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ role.name }}</span>
                  </label>
                </div>
              </div>

              <div class="flex justify-end space-x-3 pt-4">
                <button
                  type="button"
                  @click="closeUserRoleModal"
                  class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors"
                >
                  Zrušit
                </button>
                <button
                  type="submit"
                  class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors"
                >
                  Uložit
                </button>
              </div>
            </form>
            </div>
            </div>
          </Transition>
        </div>
      </Transition>
    </div>
  </DashboardLayout>
</template>

<style scoped>
/* Modal overlay transition */
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

/* Modal content transition */
.modal-content-enter-active {
  transition: all 0.3s ease;
}

.modal-content-leave-active {
  transition: all 0.25s ease;
}

.modal-content-enter-from {
  opacity: 0;
  transform: scale(0.9) translateY(-20px);
}

.modal-content-leave-to {
  opacity: 0;
  transform: scale(0.95) translateY(-10px);
}
</style>

<script setup>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const props = defineProps({
  roles: {
    type: Array,
    default: () => []
  },
  permissions: {
    type: Object,
    default: () => ({})
  },
  users: {
    type: Array,
    default: () => []
  },
  defaultTab: {
    type: String,
    default: 'roles'
  }
})

const activeTab = ref(props.defaultTab)
const showRoleModal = ref(false)
const showUserRoleModal = ref(false)
const editingRole = ref(null)
const editingUser = ref(null)

const roleForm = reactive({
  name: '',
  permissions: []
})

const userRoleForm = reactive({
  role: ''
})

const openRoleModal = (role = null) => {
  editingRole.value = role
  if (role) {
    roleForm.name = role.name
    roleForm.permissions = [...role.permissions]
  } else {
    roleForm.name = ''
    roleForm.permissions = []
  }
  showRoleModal.value = true
}

const closeRoleModal = () => {
  showRoleModal.value = false
  editingRole.value = null
  roleForm.name = ''
  roleForm.permissions = []
}

const saveRole = () => {
  if (editingRole.value) {
    router.put(`/dashboard/roles-permissions/roles/${editingRole.value.id}`, roleForm, {
      preserveScroll: true,
      onSuccess: () => {
        closeRoleModal()
      }
    })
  } else {
    router.post('/dashboard/roles-permissions/roles', roleForm, {
      preserveScroll: true,
      onSuccess: () => {
        closeRoleModal()
      }
    })
  }
}

const deleteRole = (role) => {
  if (confirm(`Opravdu chcete smazat roli "${role.name}"?`)) {
    router.delete(`/dashboard/roles-permissions/roles/${role.id}`, {
      preserveScroll: true
    })
  }
}

const openUserRoleModal = (user) => {
  editingUser.value = user
  userRoleForm.role = user.roles && user.roles.length > 0 ? user.roles[0] : ''
  showUserRoleModal.value = true
}

const closeUserRoleModal = () => {
  showUserRoleModal.value = false
  editingUser.value = null
  userRoleForm.role = ''
}

const saveUserRoles = () => {
  if (!editingUser.value) return

  // Převést na formát, který očekává backend (pole s jednou rolí)
  const formData = {
    roles: userRoleForm.role ? [userRoleForm.role] : []
  }

  router.post(`/dashboard/roles-permissions/users/${editingUser.value.id}/assign-role`, formData, {
    preserveScroll: true,
    onSuccess: () => {
      closeUserRoleModal()
    }
  })
}
</script>

