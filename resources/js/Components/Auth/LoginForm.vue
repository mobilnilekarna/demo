<template>
    <form @submit.prevent="handleSubmit" class="space-y-4">
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
                placeholder="Vaše heslo"
            />
            <p v-if="errors.password" class="mt-1 text-sm text-secondary-600">
                {{ Array.isArray(errors.password) ? errors.password[0] : errors.password }}
            </p>
        </div>

        <!-- General error message -->
        <div
            v-if="errors.general"
            class="p-3 bg-secondary-50 border border-secondary-200 rounded-lg"
        >
            <p class="text-sm text-secondary-600">
                {{ Array.isArray(errors.general) ? errors.general[0] : errors.general }}
            </p>
        </div>

        <!-- Checkbox pro vyplnění údajů do checkout formuláře -->
        <div v-if="showCheckoutCheckbox" class="flex items-center">
            <input
                :id="checkoutCheckboxId"
                v-model="fillCheckoutData"
                type="checkbox"
                class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
            />
            <label
                :for="checkoutCheckboxId"
                class="ml-2 text-sm text-gray-700"
            >
                Vyplnit údaje do checkout formuláře
            </label>
        </div>

        <Button
            type="submit"
            variant="primary"
            class="w-full"
            :disabled="loading"
        >
            {{ loading ? "Přihlašuji..." : "Přihlásit se" }}
        </Button>
    </form>

    <!-- Social Login Buttons -->
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
                @click="handleGoogleLogin"
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
                @click="handleSeznamLogin"
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

    <!-- Quick Login Button -->
    <div v-if="showQuickLogin" class="mt-4 pt-4 border-t border-gray-200">
        <button
            @click="handleQuickLogin"
            class="w-full px-4 py-2 text-sm bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors"
            :disabled="loading"
        >
            Přihlásit se jako uživatel
        </button>
    </div>
</template>

<script setup>
import { reactive, ref, computed } from "vue";
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
    showCheckoutCheckbox: {
        type: Boolean,
        default: true,
    },
    showQuickLogin: {
        type: Boolean,
        default: true,
    },
    idPrefix: {
        type: String,
        default: "login",
    },
});

const emit = defineEmits(["submit", "quick-login"]);

const form = reactive({
    email: "",
    password: "",
});

const fillCheckoutData = ref(false);

// Computed IDs pro unikátní identifikátory
const emailId = computed(() => `${props.idPrefix}-email`);
const passwordId = computed(() => `${props.idPrefix}-password`);
const checkoutCheckboxId = computed(() => `${props.idPrefix}-checkout-checkbox`);

const handleSubmit = () => {
    emit("submit", {
        email: form.email,
        password: form.password,
        fillCheckoutData: fillCheckoutData.value,
    });
};

const handleQuickLogin = () => {
    emit("quick-login", {
        fillCheckoutData: fillCheckoutData.value,
    });
};

const handleGoogleLogin = () => {
    // TODO: Implement Google login logic
    console.log("Google login clicked");
};

const handleSeznamLogin = () => {
    // TODO: Implement Seznam login logic
    console.log("Seznam login clicked");
};

// Expose form and fillCheckoutData for parent component access
defineExpose({
    form,
    fillCheckoutData,
    reset: () => {
        form.email = "";
        form.password = "";
        fillCheckoutData.value = false;
    },
});
</script>

