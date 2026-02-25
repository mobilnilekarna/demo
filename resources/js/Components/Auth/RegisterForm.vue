<template>
    <form @submit.prevent="handleSubmit" class="space-y-4">
        <div>
            <label
                :for="nameId"
                class="block text-sm font-medium text-gray-700 mb-1"
            >
                Jméno
            </label>
            <input
                :id="nameId"
                v-model="form.name"
                type="text"
                required
                :class="[
                    'w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500',
                    errors.name ? 'border-secondary-500' : 'border-gray-300',
                ]"
                placeholder="Vaše jméno"
            />
            <p v-if="errors.name" class="mt-1 text-sm text-secondary-600">
                {{ Array.isArray(errors.name) ? errors.name[0] : errors.name }}
            </p>
        </div>

        <div>
            <label
                :for="emailId"
                class="block text-sm font-medium text-gray-700 mb-1"
            >
                Email
            </label>
            <input
                :id="emailId"
                v-model="form.email"
                type="email"
                required
                :class="[
                    'w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500',
                    errors.email ? 'border-secondary-500' : 'border-gray-300',
                ]"
                placeholder="vas@email.cz"
            />
            <p v-if="errors.email" class="mt-1 text-sm text-secondary-600">
                {{ Array.isArray(errors.email) ? errors.email[0] : errors.email }}
            </p>
        </div>

        <div>
            <label
                :for="passwordId"
                class="block text-sm font-medium text-gray-700 mb-1"
            >
                Heslo
            </label>
            <input
                :id="passwordId"
                v-model="form.password"
                type="password"
                required
                :class="[
                    'w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500',
                    errors.password ? 'border-secondary-500' : 'border-gray-300',
                ]"
                placeholder="Minimálně 8 znaků"
            />
            <p v-if="errors.password" class="mt-1 text-sm text-secondary-600">
                {{ Array.isArray(errors.password) ? errors.password[0] : errors.password }}
            </p>
        </div>

        <div>
            <label
                :for="passwordConfirmationId"
                class="block text-sm font-medium text-gray-700 mb-1"
            >
                Potvrzení hesla
            </label>
            <input
                :id="passwordConfirmationId"
                v-model="form.password_confirmation"
                type="password"
                required
                :class="[
                    'w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500',
                    errors.password_confirmation
                        ? 'border-secondary-500'
                        : 'border-gray-300',
                ]"
                placeholder="Zadejte heslo znovu"
            />
            <p
                v-if="errors.password_confirmation"
                class="mt-1 text-sm text-secondary-600"
            >
                {{
                    Array.isArray(errors.password_confirmation)
                        ? errors.password_confirmation[0]
                        : errors.password_confirmation
                }}
            </p>
        </div>

        <!-- General error message -->
        <div
            v-if="errors.general"
            class="p-3 bg-secondary-50 border border-secondary-200 rounded-lg"
        >
            <p class="text-sm text-secondary-600">
                {{
                    Array.isArray(errors.general)
                        ? errors.general[0]
                        : errors.general
                }}
            </p>
        </div>

        <Button
            type="submit"
            variant="primary"
            class="w-full"
            :disabled="loading"
        >
            {{ loading ? "Registruji..." : "Registrovat se" }}
        </Button>
    </form>

    <!-- Social Register Buttons -->
    <div class="mt-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">Nebo</span>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-3">
            <button
                type="button"
                @click="handleGoogleRegister"
                class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors"
            >
                <img
                    src="/icons/auth/google.svg"
                    alt="Google"
                    class="w-5 h-5"
                />
                <span>Google</span>
            </button>

            <button
                type="button"
                @click="handleSeznamRegister"
                class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors"
            >
                <img
                    src="/icons/auth/seznam.svg"
                    alt="Seznam"
                    class="w-5 h-5"
                />
                <span>Seznam</span>
            </button>
        </div>
    </div>
</template>

<script setup>
import { reactive, computed } from "vue";
import Button from "../Button.vue";

const props = defineProps({
    loading: {
        type: Boolean,
        default: false,
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
    idPrefix: {
        type: String,
        default: "register",
    },
});

const emit = defineEmits(["submit"]);

const form = reactive({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

// Computed IDs pro unikátní identifikátory
const nameId = computed(() => `${props.idPrefix}-name`);
const emailId = computed(() => `${props.idPrefix}-email`);
const passwordId = computed(() => `${props.idPrefix}-password`);
const passwordConfirmationId = computed(
    () => `${props.idPrefix}-password-confirmation`
);

const handleSubmit = () => {
    emit("submit", {
        name: form.name,
        email: form.email,
        password: form.password,
        password_confirmation: form.password_confirmation,
    });
};

const handleGoogleRegister = () => {
    // TODO: Implement Google register logic
    console.log("Google register clicked");
};

const handleSeznamRegister = () => {
    // TODO: Implement Seznam register logic
    console.log("Seznam register clicked");
};

// Expose form for parent component access
defineExpose({
    form,
    reset: () => {
        form.name = "";
        form.email = "";
        form.password = "";
        form.password_confirmation = "";
    },
});
</script>

