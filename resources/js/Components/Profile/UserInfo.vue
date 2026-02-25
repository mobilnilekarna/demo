<template>
    <div class="bg-white rounded-lg shadow-md">
        <!-- Profile Header -->
        <div class="p-8 border-b border-gray-200">
            <div class="flex items-start gap-6">
                <!-- Profile Picture -->
                <div class="relative">
                    <div
                        class="w-24 h-24 rounded-full bg-gradient-to-br from-yellow-400 via-orange-400 to-red-500 flex items-center justify-center text-white text-3xl font-bold"
                    >
                        <span v-if="!form.avatar">{{ userInitials }}</span>
                        <img
                            v-else
                            :src="form.avatar"
                            :alt="form.name"
                            class="w-24 h-24 rounded-full object-cover"
                        />
                    </div>
                    <button
                        type="button"
                        @click="triggerFileUpload"
                        class="absolute bottom-0 right-0 w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center text-white hover:bg-primary-700 transition-colors shadow-lg"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <input
                        ref="fileInput"
                        type="file"
                        accept="image/*"
                        @change="handleFileChange"
                        class="hidden"
                    />
                </div>

                <!-- User Name and Location -->
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ form.name || user.name }}</h1>
                    <p class="text-gray-500">{{ form.location || user.location || 'Přidejte svou lokaci' }}</p>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <form @submit.prevent="submitForm" class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Jméno
                        </label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Jméno"
                        />
                        <p v-if="errors.name" class="mt-1 text-sm text-red-600">
                            {{ errors.name }}
                        </p>
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Emailová adresa
                        </label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="email@example.com"
                        />
                        <p v-if="errors.email" class="mt-1 text-sm text-red-600">
                            {{ errors.email }}
                        </p>
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                            Lokace
                        </label>
                        <input
                            id="location"
                            v-model="form.location"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="např. Praha, Česká republika"
                        />
                        <p v-if="errors.location" class="mt-1 text-sm text-red-600">
                            {{ errors.location }}
                        </p>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Full Name -->
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Celé jméno
                        </label>
                        <input
                            id="full_name"
                            v-model="form.full_name"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Celé jméno"
                        />
                        <p v-if="errors.full_name" class="mt-1 text-sm text-red-600">
                            {{ errors.full_name }}
                        </p>
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Telefonní číslo
                        </label>
                        <input
                            id="phone"
                            v-model="form.phone"
                            type="tel"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="(+420) 123 456 789"
                        />
                        <p v-if="errors.phone" class="mt-1 text-sm text-red-600">
                            {{ errors.phone }}
                        </p>
                    </div>

                    <!-- Postal Code -->
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                            PSČ
                        </label>
                        <input
                            id="postal_code"
                            v-model="form.postal_code"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="12345"
                        />
                        <p v-if="errors.postal_code" class="mt-1 text-sm text-red-600">
                            {{ errors.postal_code }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            <div v-if="successMessage" class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                {{ successMessage }}
            </div>

            <!-- Save Button -->
            <div class="mt-8 flex justify-center">
                <button
                    type="submit"
                    :disabled="processing"
                    class="px-8 py-3 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span v-if="processing">Ukládání...</span>
                    <span v-else>Uložit změny</span>
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, computed, reactive } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['updated']);

const fileInput = ref(null);
const successMessage = ref('');

const form = useForm({
    name: props.user.name || '',
    email: props.user.email || '',
    location: props.user.location || '',
    full_name: props.user.full_name || '',
    phone: props.user.phone || '',
    postal_code: props.user.postal_code || '',
    avatar: props.user.avatar || null,
});

const processing = computed(() => form.processing);

const errors = computed(() => form.errors);

const userInitials = computed(() => {
    const name = form.name || props.user.name || '';
    const parts = name.split(' ');
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return name.charAt(0).toUpperCase();
});

const triggerFileUpload = () => {
    fileInput.value?.click();
};

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        // For now, just show a preview - actual upload would need backend implementation
        const reader = new FileReader();
        reader.onload = (e) => {
            form.avatar = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submitForm = () => {
    successMessage.value = '';
    
    form.put('/profile/info', {
        preserveScroll: true,
        onSuccess: () => {
            successMessage.value = 'Údaje byly úspěšně uloženy.';
            emit('updated');
            setTimeout(() => {
                successMessage.value = '';
            }, 3000);
        },
    });
};
</script>
