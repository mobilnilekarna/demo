<template>
  <DashboardLayout>
    <div class="p-4">
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Správa uživatelů</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
          Spravujte uživatele systému
        </p>
      </div>

      <!-- Add User Button -->
      <div class="mb-6 flex justify-end">
        <button
          @click="openUserModal()"
          class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors flex items-center space-x-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span>Přidat uživatele</span>
        </button>
      </div>

      <!-- Users List -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6">
          <div v-if="users.length === 0" class="text-center py-12">
            <p class="text-gray-500 dark:text-gray-400">Žádní uživatelé k zobrazení</p>
          </div>

          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Jméno
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Email
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Role
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Vytvořeno
                  </th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Akce
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr
                  v-for="user in users"
                  :key="user.id"
                  class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                >
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ user.name }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ user.email }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex flex-wrap gap-1">
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
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ user.created_at }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button
                      @click="openUserModal(user)"
                      class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-4"
                    >
                      Upravit
                    </button>
                    <button
                      @click="deleteUser(user)"
                      class="text-secondary-600 dark:text-secondary-400 hover:text-secondary-900 dark:hover:text-secondary-300"
                    >
                      Smazat
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- User Modal -->
      <Transition name="modal">
        <div
          v-if="showUserModal"
          class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
          @click.self="closeUserModal"
        >
          <Transition name="modal-content">
            <div
              v-if="showUserModal"
              class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full"
            >
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                {{ editingUser ? 'Upravit uživatele' : 'Nový uživatel' }}
              </h2>
              <button
                @click="closeUserModal"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <form @submit.prevent="saveUser" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Jméno <span class="text-secondary-500">*</span>
                </label>
                <input
                  v-model="userForm.name"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Email <span class="text-secondary-500">*</span>
                </label>
                <input
                  v-model="userForm.email"
                  type="email"
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Heslo {{ editingUser ? '(nechte prázdné pro zachování)' : '' }} <span class="text-secondary-500">*</span>
                </label>
                <input
                  v-model="userForm.password"
                  type="password"
                  :required="!editingUser"
                  :minlength="8"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                  Role
                </label>
                <div class="space-y-2">
                  <label
                    v-for="role in roles"
                    :key="role"
                    class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 p-2 rounded"
                  >
                    <input
                      type="radio"
                      :value="role"
                      v-model="userForm.role"
                      :name="`user-role-${editingUser ? editingUser.id : 'new'}`"
                      class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                    />
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ role }}</span>
                  </label>
                </div>
              </div>

              <div class="flex justify-end space-x-3 pt-4">
                <button
                  type="button"
                  @click="closeUserModal"
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
  users: {
    type: Array,
    default: () => []
  },
  roles: {
    type: Array,
    default: () => []
  }
})

const showUserModal = ref(false)
const editingUser = ref(null)

const userForm = reactive({
  name: '',
  email: '',
  password: '',
  role: ''
})

const openUserModal = (user = null) => {
  editingUser.value = user
  if (user) {
    userForm.name = user.name
    userForm.email = user.email
    userForm.password = ''
    userForm.role = user.roles && user.roles.length > 0 ? user.roles[0] : ''
  } else {
    userForm.name = ''
    userForm.email = ''
    userForm.password = ''
    userForm.role = ''
  }
  showUserModal.value = true
}

const closeUserModal = () => {
  showUserModal.value = false
  editingUser.value = null
  userForm.name = ''
  userForm.email = ''
  userForm.password = ''
  userForm.role = ''
}

const saveUser = () => {
  if (editingUser.value) {
    router.put(`/dashboard/users/${editingUser.value.id}`, userForm, {
      preserveScroll: true,
      onSuccess: () => {
        closeUserModal()
      }
    })
  } else {
    router.post('/dashboard/users', userForm, {
      preserveScroll: true,
      onSuccess: () => {
        closeUserModal()
      }
    })
  }
}

const deleteUser = (user) => {
  if (confirm(`Opravdu chcete smazat uživatele "${user.name}"?`)) {
    router.delete(`/dashboard/users/${user.id}`, {
      preserveScroll: true
    })
  }
}
</script>


