<template>
    <DashboardLayout title="Registrace">
        <template #default>
            <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
                <div class="max-w-md w-full space-y-8">
                    <div>
                        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                            Vytvořit nový účet
                        </h2>
                        <p class="mt-2 text-center text-sm text-gray-600">
                            Nebo
                            <Link href="/dashboard/login" class="font-medium text-primary-600 hover:text-primary-500">
                                se přihlásit k existujícímu účtu
                            </Link>
                        </p>
                    </div>
                    <form class="mt-8 space-y-6" @submit.prevent="submit">
                        <div class="rounded-md shadow-sm -space-y-px">
                            <div>
                                <label for="name" class="sr-only">Jméno</label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    autocomplete="name"
                                    required
                                    class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm"
                                    :class="{ 'border-secondary-500': errors.name }"
                                    placeholder="Jméno"
                                />
                                <div v-if="errors.name" class="text-secondary-500 text-sm mt-1">
                                    {{ errors.name }}
                                </div>
                            </div>
                            <div>
                                <label for="email" class="sr-only">Emailová adresa</label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    autocomplete="email"
                                    required
                                    class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm"
                                    :class="{ 'border-secondary-500': errors.email }"
                                    placeholder="Emailová adresa"
                                />
                                <div v-if="errors.email" class="text-secondary-500 text-sm mt-1">
                                    {{ errors.email }}
                                </div>
                            </div>
                            <div>
                                <label for="password" class="sr-only">Heslo</label>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    autocomplete="new-password"
                                    required
                                    class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm"
                                    :class="{ 'border-secondary-500': errors.password }"
                                    placeholder="Heslo"
                                />
                                <div v-if="errors.password" class="text-secondary-500 text-sm mt-1">
                                    {{ errors.password }}
                                </div>
                            </div>
                            <div>
                                <label for="password_confirmation" class="sr-only">Potvrdit heslo</label>
                                <input
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    type="password"
                                    autocomplete="new-password"
                                    required
                                    class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm"
                                    placeholder="Potvrdit heslo"
                                />
                            </div>
                        </div>

                        <div>
                            <button
                                type="submit"
                                :disabled="processing"
                                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span v-if="processing" class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <svg class="animate-spin h-5 w-5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                                {{ processing ? 'Vytváření účtu...' : 'Vytvořit účet' }}
                            </button>
                        </div>

                        <div v-if="errors.general" class="text-secondary-500 text-sm text-center">
                            {{ errors.general }}
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </DashboardLayout>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

const processing = ref(false);
const errors = ref({});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    processing.value = true;
    errors.value = {};

    form.post(route('dashboard.register'), {
        onError: (errorBag) => {
            errors.value = errorBag;
            processing.value = false;
        },
        onFinish: () => {
            processing.value = false;
        }
    });
};
</script>
