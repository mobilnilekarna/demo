<template>
    <div class="space-y-6">
        <!-- Informace o produktu, pokud je předán -->
        <div v-if="product" class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <p class="text-sm text-gray-600 mb-2">Dotaz k produktu:</p>
            <div class="flex items-center gap-3">
                <img
                    v-if="product.images && product.images[0]"
                    :src="product.images[0]"
                    :alt="product.name"
                    class="w-16 h-16 object-cover rounded-lg"
                />
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">{{ product.name }}</h3>
                    <p class="text-sm text-gray-600">{{ formatPrice(product.price) }} {{ product.currency }}</p>
                </div>
            </div>
        </div>

        <!-- Chyby validace -->
        <div
            v-if="Object.keys(errors).length > 0"
            class="rounded-lg border border-red-200 bg-red-50 p-4 text-red-800"
        >
            <p class="font-medium mb-2">Opravte prosím následující chyby:</p>
            <ul class="list-disc list-inside space-y-1 text-sm">
                <li v-for="(error, field) in errors" :key="field">
                    {{ error }}
                </li>
            </ul>
            <button
                type="button"
                @click="resetForm"
                class="mt-3 text-sm font-medium text-red-600 hover:text-red-800 underline"
            >
                Zrušit a vyplnit znovu
            </button>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-5">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Jméno a příjmení *
                </label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                    :class="errors.name ? 'border-red-500' : 'border-gray-300'"
                    placeholder="Jan Novák"
                />
                <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email *
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                    :class="errors.email ? 'border-red-500' : 'border-gray-300'"
                    placeholder="jan.novak@example.com"
                />
                <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
            </div>

            <div v-if="!product">
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                    Předmět *
                </label>
                <select
                    id="subject"
                    v-model="form.subject"
                    required
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                    :class="errors.subject ? 'border-red-500' : 'border-gray-300'"
                >
                    <option v-for="subject in subjects" :value="subject.value" :key="subject.value">
                        {{ subject.label }}
                    </option>
                </select>
                <p v-if="errors.subject" class="mt-1 text-sm text-red-600">{{ errors.subject }}</p>
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                    Zpráva *
                </label>
                <textarea
                    id="message"
                    v-model="form.message"
                    required
                    rows="5"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors resize-none"
                    :class="errors.message ? 'border-red-500' : 'border-gray-300'"
                    placeholder="Napište vaši zprávu..."
                ></textarea>
                <p v-if="errors.message" class="mt-1 text-sm text-red-600">{{ errors.message }}</p>
            </div>

            <button
                type="submit"
                :disabled="isSubmitting"
                class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed"
            >
                {{ isSubmitting ? 'Odesílám…' : 'Odeslat dotaz' }}
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import { useReCaptcha } from 'vue-recaptcha-v3';

const recaptcha = useReCaptcha();
const executeRecaptcha = recaptcha?.executeRecaptcha;
const recaptchaLoaded = recaptcha?.recaptchaLoaded;

const props = defineProps({
    product: {
        type: Object,
        required: false,
    },
});

const subjects = ref([
    { label: 'Dotaz k produktu', value: 'product' },
    { label: 'Obecný dotaz', value: 'general' },
]);

const form = reactive({
    name: '',
    email: '',
    subject: subjects.value[0].value,
    message: '',
    product: null,
    recaptcha: '',
});

const errors = reactive({});
const isSubmitting = ref(false);

onMounted(() => {
    if (props.product) {
        form.product = props.product;
    }
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('cs-CZ').format(price);
};

const setErrors = (newErrors) => {
    Object.keys(errors).forEach((k) => delete errors[k]);
    if (newErrors && typeof newErrors === 'object') {
        Object.entries(newErrors).forEach(([key, value]) => {
            errors[key] = Array.isArray(value) ? value[0] : value;
        });
    }
};

const resetForm = () => {
    form.name = '';
    form.email = '';
    form.subject = subjects.value[0].value;
    form.message = '';
    form.product = props.product ?? null;
    form.recaptcha = '';
    setErrors({});
};

const handleSubmit = async () => {
    if (isSubmitting.value) return;
    isSubmitting.value = true;
    setErrors({});

    try {
        if (recaptchaLoaded && executeRecaptcha) {
            await recaptchaLoaded();
            form.recaptcha = await executeRecaptcha('contact');
        }
        if (!form.recaptcha) {
            setErrors({ recaptcha: 'Ověření reCAPTCHA se nepodařilo načíst. Obnovte stránku a zkuste to znovu.' });
            isSubmitting.value = false;
            return;
        }

        const { data } = await axios.post(route('contact.store'), {
            name: form.name,
            email: form.email,
            subject: form.subject,
            message: form.message,
            product: form.product,
            recaptcha: form.recaptcha,
        });

        alert(data.message || 'Formulář byl úspěšně odeslán');
        resetForm();
    } catch (err) {
        if (err.response?.status === 422 && err.response?.data?.errors) {
            setErrors(err.response.data.errors);
        } else {
            setErrors({
                recaptcha: err.response?.data?.message || 'Nepodařilo se odeslat. Zkuste obnovit stránku a odeslat znovu.',
            });
        }
        console.error('Chyba při odesílání formuláře:', err);
    } finally {
        isSubmitting.value = false;
    }
};
</script>
