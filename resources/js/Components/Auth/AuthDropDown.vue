<template>
    <div class="relative h-full">
        <!-- Auth Button -->
        <div v-if="user" class="h-full">
            <button
                @click="toggleAuth"
                :class="[
                    'relative flex items-center justify-center h-full border-2 border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition-colors',
                    variant === 'icon' ? 'w-10 h-10 p-1.5 border border-gray-300 hover:bg-gray-50 rounded-md' : 'px-4 space-x-2'
                ]"
            >
                <svg
                    :class="variant === 'icon' ? 'w-7 h-7' : 'w-5 h-5'"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        :stroke-width="variant === 'icon' ? '2.5' : '2'"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    ></path>
                </svg>
                <span v-if="variant !== 'icon'" class="text-sm font-medium">Profil</span>
            </button>
        </div>

        <div v-else class="h-full">
            <button
                @click="openAuthModal"
                :class="[
                    'relative flex items-center justify-center h-full border-2 border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition-colors',
                    variant === 'icon' ? 'w-10 h-10 p-1.5 border border-gray-300 hover:bg-gray-50 rounded-md' : 'px-4 space-x-2'
                ]"
            >
                <svg
                    :class="variant === 'icon' ? 'w-7 h-7' : 'w-5 h-5'"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        :stroke-width="variant === 'icon' ? '2.5' : '2'"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    ></path>
                </svg>
                <span v-if="variant !== 'icon'" class="text-sm font-medium">Přihlášení</span>
            </button>
        </div>

        <!-- Dropdown (only for logged in users) -->
        <div
            v-if="isOpen && user"
            class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border z-50"
        >
            <div class="p-4">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Profil</h3>
                    <button
                        @click="toggleAuth"
                        class="text-gray-400 hover:text-gray-600"
                    >
                        <svg
                            class="w-5 h-5"
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

                <!-- User Profile -->
                <div class="space-y-4">
                    <div
                        class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg"
                    >
                        <div
                            class="w-10 h-10 bg-primary-500 rounded-full flex items-center justify-center"
                        >
                            <span class="text-white font-semibold text-sm">
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
                        class="block w-full px-4 py-2 text-center border-2 border-gray-300 text-gray-700 rounded-lg font-medium hover:border-gray-400 hover:bg-gray-50 transition-colors"
                        @click="isOpen = false"
                    >
                        Můj profil
                    </Link>

                    <Button
                        @click="handleLogout"
                        variant="secondary"
                        class="w-full"
                    >
                        Odhlásit se
                    </Button>
                </div>
            </div>
        </div>

        <!-- Auth Modal -->
        <AuthModal
            v-model="isModalOpen"
            :initial-tab="initialTab"
            @closed="handleModalClosed"
        />
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { useAuth } from "@/Composables/useAuth";
import Button from "../Button.vue";
import AuthModal from "./AuthModal.vue";

defineProps({
    variant: {
        type: String,
        default: 'button',
        validator: (value) => ['button', 'icon'].includes(value)
    }
});

const { user, logout } = useAuth();

// Local state
const isOpen = ref(false);
const isModalOpen = ref(false);
const initialTab = ref("login");

// Methods
const toggleAuth = () => {
    if (user.value) {
        // If logged in, toggle dropdown
        isOpen.value = !isOpen.value;
    }
};

const openAuthModal = () => {
    initialTab.value = "login";
    isModalOpen.value = true;
};

const handleModalClosed = () => {
    isModalOpen.value = false;
};

const handleLogout = async () => {
    try {
        await logout();
        isOpen.value = false;
    } catch (error) {
        console.error("Logout error:", error);
    }
};
</script>
