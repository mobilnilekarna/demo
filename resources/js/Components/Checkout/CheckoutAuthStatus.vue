<template>
    <div class="mb-6">
        <div
            v-if="isAuthenticated"
            class="bg-primary-50 border-2 border-primary-200 rounded-lg p-4"
        >
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <svg
                        class="w-6 h-6 text-primary-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-primary-900">
                            Nakupujete jako přihlášený uživatel
                        </p>
                        <p class="text-sm text-primary-700">
                            {{ user.name }} ({{ user.email }})
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span
                        class="px-3 py-1 bg-primary-100 text-primary-800 text-xs font-semibold rounded-full"
                    >
                        Přihlášen
                    </span>
                    <button
                        @click="openAuthModal"
                        class="px-4 py-2 bg-white border-2 border-primary-600 text-primary-600 text-sm font-medium rounded-lg hover:bg-primary-50 transition-colors"
                    >
                        Profil
                    </button>
                </div>
            </div>
        </div>
        <div
            v-else
            class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-4"
        >
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-3">
                    <svg
                        class="w-6 h-6 text-yellow-600 mt-0.5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                        />
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-yellow-900 mb-1">
                            Nakupujete bez registrace
                        </p>
                        <p class="text-sm text-yellow-700 mb-3">
                            Pro rychlejší nákup a lepší služby se můžete přihlásit
                            nebo zaregistrovat.
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <button
                                @click="openAuthModal('login')"
                                class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors"
                            >
                                Přihlásit se
                            </button>
                            <button
                                @click="openAuthModal('register')"
                                class="px-4 py-2 bg-white border-2 border-primary-600 text-primary-600 text-sm font-medium rounded-lg hover:bg-primary-50 transition-colors"
                            >
                                Zaregistrovat se
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auth Modal -->
    <AuthModal
        v-model="isModalOpen"
        :initial-tab="initialTab"
        @closed="handleModalClosed"
    />
</template>

<script setup>
import { ref } from "vue";
import { useAuth } from "@/Composables/useAuth";
import AuthModal from "@/Components/Auth/AuthModal.vue";

const { user, isAuthenticated } = useAuth();

const isModalOpen = ref(false);
const initialTab = ref("login");

const openAuthModal = (tab = "login") => {
    initialTab.value = tab;
    isModalOpen.value = true;
};

const handleModalClosed = () => {
    isModalOpen.value = false;
};
</script>

