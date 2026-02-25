<template>
    <div class="bg-primary-600 min-h-[200px] flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-2xl text-center">
            <!-- Heading -->
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-6 font-sans">
                Nejlepší akce a slevy ve vašem e-mailu
            </h2>

            <form @submit.prevent="handleSubscribe" class="w-full">
                <!-- Bílý kontejner pro input s relativní pozicí -->
                <div class="bg-white rounded-3xl relative flex items-center h-14 overflow-hidden">
                    <!-- Input pole s ikonou -->
                    <div class="relative flex-1 flex items-center h-full">
                        <div class="absolute left-4 text-gray-400 z-10">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <input
                            type="text"
                            v-model="email"
                            placeholder="Zadejte váš e-mail"
                            class="w-full h-full pl-12 pr-36 bg-transparent border-none focus:outline-none text-teal-900 placeholder-teal-700 font-sans"
                        />
                    </div>
                    <!-- Tlačítko absolutně pozicované uvnitř kontejneru s odsazením -->
                    <button
                        type="submit"
                        :disabled="isLoading"
                        class="absolute right-2 top-2 bottom-2 px-6 bg-primary-200 hover:bg-primary-500 hover:text-white text-primary-600 text-teal-900 font-medium transition-colors whitespace-nowrap rounded-3xl font-sans flex items-center"
                    >
                       <span>Odebírat newsletter</span>
                        <svg
                            v-if="!isLoading"
                            xmlns="http://www.w3.org/2000/svg"
                            width="16"
                            height="16"
                            fill="currentColor"
                            class="bi bi-arrow-right ml-2"
                            viewBox="0 0 16 16"
                        >
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                        </svg>
                        <svg
                            v-else
                            class="w-4 h-4 mr-2 animate-spin"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useAnalytics } from '@/Composables/useAnalytics.js';
import { useToast } from '@/Composables/useToast.js';
import { validateEmail } from '@/Utils/validator.js';
import { trans } from '@/i18n.js';

const toast = useToast();
const { trackSubscribe } = useAnalytics();

const email = ref('');
const isLoading = ref(false);

const subscribe = async (email) => {
  if (!validateEmail(email)) {
    return { success: false, error: trans('validation.email.invalid') };
  }

  try {
    await axios.post(route('api.newsletter.subscribe', {}, false), { email: email });
    trackSubscribe(email);
    return { success: true };
  } catch (error) {
    return { success: false, error: trans('newsletter.error') };
  }
};

const handleSubscribe = async () => {
  isLoading.value = true;

  const { success, error } = await subscribe(email.value);

  if (success) {
    email.value = '';
    toast.success(trans('newsletter.success'), trans('newsletter.success'));
  } else {
    toast.error(trans('newsletter.error'), error);
  }

  isLoading.value = false;
};

</script>

