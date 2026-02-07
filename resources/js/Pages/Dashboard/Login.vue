<template>
  <div class="h-screen flex overflow-hidden">
    <!-- Left Panel - Login Form -->
    <div class="flex-1 flex flex-col justify-center px-4 sm:px-6 lg:px-8 bg-white overflow-y-auto">
      <div class="w-full max-w-md mx-auto py-8">
        <!-- Logo Section -->
        <div class="mb-12">
          <div class="flex flex-col items-start">
            <img
              src="/logo.svg"
              alt="Logo"
              class="w-[200px] h-auto object-contain mb-2"
              @error="$event.target.src = '/logo.svg'"
            />
            <h1 class="text-xl font-bold text-gray-900">{{ appName }}</h1>
            <p class="text-sm text-gray-500 mt-1">Přihlášení do administrace</p>
          </div>
        </div>

        <!-- Login Form -->
        <div>
          <h2 class="text-2xl font-bold text-gray-900 mb-8">Přihlásit se</h2>

          <form @submit.prevent="submit" class="space-y-6">
            <!-- Email Field -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                E-mail
              </label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                autocomplete="email"
                required
                class="w-full px-3 py-2 border border-primary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                :class="{ 'border-secondary-500': errors.email }"
                placeholder="vas@email.cz"
              />
              <p v-if="errors.email" class="mt-1 text-sm text-secondary-600">{{ errors.email }}</p>
            </div>

            <!-- Password Field with Button -->
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Heslo
              </label>
              <div class="flex items-center gap-3">
                <div class="relative flex-1">
                  <input
                    id="password"
                    v-model="form.password"
                    :type="showPassword ? 'text' : 'password'"
                    autocomplete="current-password"
                    required
                    class="w-full px-3 py-2 pr-10 border border-primary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    :class="{ 'border-secondary-500': errors.password }"
                    placeholder="••••••••"
                  />
                  <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                  >
                    <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                  </button>
                </div>
                <button
                  type="submit"
                  :disabled="processing"
                  class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2 whitespace-nowrap"
                >
                  <span v-if="processing" class="animate-spin">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                  </span>
                  <span>{{ processing ? 'Přihlašování...' : 'Přihlásit se' }}</span>
                </button>
              </div>
              <div class="flex items-center justify-between mt-2">
                <p v-if="errors.password" class="text-sm text-secondary-600">{{ errors.password }}</p>
                <Link
                  href="#"
                  class="text-sm text-gray-500 hover:text-gray-700 ml-auto"
                >
                  Zapomenuté heslo
                </Link>
              </div>
            </div>

            <!-- Error Message -->
            <div v-if="errors.general" class="text-secondary-600 text-sm text-center">
              {{ errors.general }}
            </div>
          </form>

          <!-- Quick Login Buttons -->
          <div class="mt-8 pt-8 border-t border-gray-200">
            <p class="text-xs text-gray-500 text-center mb-4">Rychlé přihlášení</p>
            <div class="grid grid-cols-2 gap-2">
              <button
                @click="quickLogin('superadmin@lekarna.cz', 'SuperAdmin123!')"
                class="px-3 py-2 text-xs bg-purple-600 hover:bg-purple-700 text-white rounded transition-colors"
              >
                Super Admin
              </button>
              <button
                @click="quickLogin('admin@lekarna.cz', 'Admin123!')"
                class="px-3 py-2 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors"
              >
                Admin
              </button>
              <button
                @click="quickLogin('manager@lekarna.cz', 'Manager123!')"
                class="px-3 py-2 text-xs bg-green-600 hover:bg-green-700 text-white rounded transition-colors"
              >
                Manager
              </button>
              <button
                @click="quickLogin('user@lekarna.cz', 'User123!')"
                class="px-3 py-2 text-xs bg-gray-600 hover:bg-gray-700 text-white rounded transition-colors"
              >
                User
              </button>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 flex items-center space-x-2 text-xs text-gray-500">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
          <span>Neověřená/-ý</span>
        </div>
      </div>
    </div>

    <!-- Right Panel - Image -->
    <div class="hidden lg:block lg:w-1/2 relative overflow-hidden">
      <img
        src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&h=1200&fit=crop"
        alt="Team collaboration"
        class="w-full h-full object-cover"
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";

const page = usePage();
const appName = page.props.appName || 'Aplikace';

const processing = ref(false);
const errors = ref({});
const showPassword = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    processing.value = true;
    errors.value = {};

    form.post('/dashboard/login', {
        onError: (errorBag) => {
            errors.value = errorBag;
            processing.value = false;
        },
        onFinish: () => {
            processing.value = false;
        }
    });
};

const quickLogin = (email, password) => {
    form.email = email;
    form.password = password;
    submit();
};
</script>
