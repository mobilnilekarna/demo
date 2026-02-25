<template>
    <Teleport to="body">
        <!-- Backdrop -->
        <Transition
            enter-active-class="transition-opacity duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-300"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isOpen"
                @click="closeModal"
                class="fixed inset-0 bg-black bg-opacity-50 z-50"
            ></div>
        </Transition>

        <!-- Modal -->
        <Transition
            enter-active-class="transition-all duration-300"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition-all duration-300"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="isOpen"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                @click.self="closeModal"
            >
                <div
                    class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto"
                    @click.stop
                >
                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-semibold text-gray-900">
                                {{ user ? "Profil" : "Přihlášení" }}
                            </h3>
                            <button
                                @click="closeModal"
                                class="text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <svg
                                    class="w-6 h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    ></path>
                                </svg>
                            </button>
                        </div>

                        <!-- User Profile (when logged in) -->
                        <div v-if="user" class="space-y-4">
                            <div
                                class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg"
                            >
                                <div
                                    class="w-12 h-12 bg-primary-500 rounded-full flex items-center justify-center"
                                >
                                    <span class="text-white font-semibold text-lg">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">
                                        {{ user.name }}
                                    </h4>
                                    <p class="text-sm text-gray-500">
                                        {{ user.email }}
                                    </p>
                                </div>
                            </div>

                            <Link
                                href="/profile"
                                @click="closeModal"
                                class="flex items-center justify-center gap-2 w-full px-4 py-2 border-2 border-primary-600 text-primary-600 rounded-lg font-medium hover:bg-primary-50 transition-colors"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Můj profil</span>
                            </Link>

                            <Button
                                @click="handleLogout"
                                variant="secondary"
                                class="w-full"
                            >
                                Odhlásit se
                            </Button>
                        </div>

                        <!-- Auth Forms (when not logged in) -->
                        <div v-else>
                            <!-- Tabs -->
                            <div class="flex border-b border-gray-200 mb-6">
                                <button
                                    @click="switchTab('login')"
                                    :class="[
                                        'flex-1 px-4 py-3 text-sm font-medium border-b-2 transition-colors',
                                        activeTab === 'login'
                                            ? 'border-primary-500 text-primary-600'
                                            : 'border-transparent text-gray-500 hover:text-gray-700',
                                    ]"
                                >
                                    Přihlášení
                                </button>
                                <button
                                    @click="switchTab('register')"
                                    :class="[
                                        'flex-1 px-4 py-3 text-sm font-medium border-b-2 transition-colors',
                                        activeTab === 'register'
                                            ? 'border-primary-500 text-primary-600'
                                            : 'border-transparent text-gray-500 hover:text-gray-700',
                                    ]"
                                >
                                    Registrace
                                </button>
                            </div>

                            <!-- Login Form -->
                            <LoginForm
                                v-if="activeTab === 'login'"
                                :loading="isLoading"
                                :errors="loginErrors"
                                :show-checkout-checkbox="true"
                                :show-quick-login="true"
                                id-prefix="modal-login"
                                @submit="handleLoginSubmit"
                                @quick-login="handleQuickLogin"
                                ref="loginFormRef"
                            />

                            <!-- Register Form -->
                            <RegisterForm
                                v-if="activeTab === 'register'"
                                :loading="isLoading"
                                :errors="registerErrors"
                                id-prefix="modal-register"
                                @submit="handleRegisterSubmit"
                                ref="registerFormRef"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, watch, nextTick } from "vue";
import { Link } from "@inertiajs/vue3";
import { useAuth } from "@/Composables/useAuth";
import { useCheckout } from "@/Composables/useCheckout";
import Button from "../Button.vue";
import LoginForm from "./LoginForm.vue";
import RegisterForm from "./RegisterForm.vue";

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    },
    initialTab: {
        type: String,
        default: "login",
        validator: (value) => ["login", "register"].includes(value),
    },
});

const emit = defineEmits(["update:modelValue", "closed"]);

const { user, login, register, logout } = useAuth();
const { fillUserData } = useCheckout();

// Local state
const isOpen = ref(props.modelValue);
const activeTab = ref(props.initialTab);
const isLoading = ref(false);

// Initialize activeTab from prop
if (props.modelValue) {
    activeTab.value = props.initialTab;
}

// Error states
const loginErrors = ref({});
const registerErrors = ref({});

// Form refs
const loginFormRef = ref(null);
const registerFormRef = ref(null);

// Watch for prop changes
watch(
    () => props.modelValue,
    (newValue) => {
        isOpen.value = newValue;
        if (newValue) {
            // When modal opens, set active tab from initialTab prop
            activeTab.value = props.initialTab;
        } else {
            // Clear errors when closing
            loginErrors.value = {};
            registerErrors.value = {};
            // Reset forms
            if (loginFormRef.value) {
                loginFormRef.value.reset();
            }
            if (registerFormRef.value) {
                registerFormRef.value.reset();
            }
        }
    }
);

// Watch for initialTab changes - always update activeTab
watch(
    () => props.initialTab,
    (newTab) => {
        activeTab.value = newTab;
    },
    { immediate: true }
);

watch(isOpen, (newValue) => {
    emit("update:modelValue", newValue);
});

// Methods
const closeModal = () => {
    isOpen.value = false;
    emit("closed");
};

const switchTab = (tab) => {
    activeTab.value = tab;
    // Clear errors when switching tabs
    loginErrors.value = {};
    registerErrors.value = {};
};

const handleLoginSubmit = async (formData) => {
    isLoading.value = true;
    loginErrors.value = {}; // Clear previous errors

    try {
        await login({
            email: formData.email,
            password: formData.password,
        });

        // Počkáme na aktualizaci user.value
        await nextTick();

        // Pokud je zaškrtnutý checkbox, vyplníme údaje do checkout formuláře
        if (formData.fillCheckoutData && user.value) {
            fillUserData(user.value);
        }

        // Reset form
        if (loginFormRef.value) {
            loginFormRef.value.reset();
        }

        // Close modal
        closeModal();
    } catch (error) {
        console.error("Login error:", error);

        // Handle validation errors from Laravel
        if (error.response && error.response.status === 422) {
            loginErrors.value = error.response.data.errors || {};
        } else if (error.response && error.response.data.message) {
            // Handle other API errors
            loginErrors.value = { general: [error.response.data.message] };
        } else {
            // Handle network or other errors
            loginErrors.value = {
                general: ["Přihlášení se nezdařilo. Zkontrolujte údaje."],
            };
        }
    } finally {
        isLoading.value = false;
    }
};

const handleQuickLogin = async (options) => {
    isLoading.value = true;
    loginErrors.value = {}; // Clear previous errors

    try {
        await login({
            email: 'user@lekarna.cz',
            password: 'User123!',
        });

        // Počkáme na aktualizaci user.value
        await nextTick();

        // Pokud je zaškrtnutý checkbox, vyplníme údaje do checkout formuláře
        if (options.fillCheckoutData && user.value) {
            fillUserData(user.value);
        }

        // Reset form
        if (loginFormRef.value) {
            loginFormRef.value.reset();
        }

        // Close modal
        closeModal();
    } catch (error) {
        console.error("Quick login error:", error);

        // Handle validation errors from Laravel
        if (error.response && error.response.status === 422) {
            loginErrors.value = error.response.data.errors || {};
        } else if (error.response && error.response.data.message) {
            // Handle other API errors
            loginErrors.value = { general: [error.response.data.message] };
        } else {
            // Handle network or other errors
            loginErrors.value = {
                general: ["Přihlášení se nezdařilo. Zkontrolujte údaje."],
            };
        }
    } finally {
        isLoading.value = false;
    }
};

const handleRegisterSubmit = async (formData) => {
    isLoading.value = true;
    registerErrors.value = {}; // Clear previous errors

    try {
        await register({
            name: formData.name,
            email: formData.email,
            password: formData.password,
            password_confirmation: formData.password_confirmation,
        });

        // Reset form
        if (registerFormRef.value) {
            registerFormRef.value.reset();
        }

        // Close modal
        closeModal();
    } catch (error) {
        console.error("Registration error:", error);

        // Handle validation errors from Laravel
        if (error.response && error.response.status === 422) {
            registerErrors.value = error.response.data.errors || {};
        } else if (error.response && error.response.data.message) {
            // Handle other API errors
            registerErrors.value = { general: [error.response.data.message] };
        } else {
            // Handle network or other errors
            registerErrors.value = {
                general: ["Registrace se nezdařila. Zkontrolujte údaje."],
            };
        }
    } finally {
        isLoading.value = false;
    }
};

const handleLogout = async () => {
    try {
        await logout();
        // Close modal
        closeModal();
    } catch (error) {
        console.error("Logout error:", error);
    }
};

</script>

