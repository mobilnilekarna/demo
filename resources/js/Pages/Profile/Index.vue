<template>
    <AppLayout title="Můj profil">
        <div class="w-full">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Navigation -->
                <div class="lg:w-64 flex-shrink-0">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Můj profil</h2>
                    <nav class="space-y-2">
                        <Link
                            :href="`/profile?section=info`"
                            :class="[
                                'flex items-center gap-3 px-4 py-3 rounded-lg transition-colors',
                                activeSection === 'info'
                                    ? 'bg-primary-50 text-gray-900 border-l-4 border-primary-600'
                                    : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50'
                            ]"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="font-medium">Údaje</span>
                        </Link>

                        <Link
                            :href="`/profile?section=preferences`"
                            :class="[
                                'flex items-center gap-3 px-4 py-3 rounded-lg transition-colors',
                                activeSection === 'preferences'
                                    ? 'bg-primary-50 text-gray-900 border-l-4 border-primary-600'
                                    : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50'
                            ]"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="font-medium">Preference</span>
                        </Link>

                        <Link
                            :href="`/profile?section=orders`"
                            :class="[
                                'flex items-center gap-3 px-4 py-3 rounded-lg transition-colors',
                                activeSection === 'orders'
                                    ? 'bg-primary-50 text-gray-900 border-l-4 border-primary-600'
                                    : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50'
                            ]"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span class="font-medium">Moje objednávky</span>
                        </Link>
                    </nav>
                </div>

                <!-- Main Content -->
                <div class="flex-1">
                    <!-- User Info Section -->
                    <ProfileUserInfo
                        v-if="activeSection === 'info'"
                        :user="user"
                        @updated="handleUserUpdated"
                    />

                    <!-- Preferences Section -->
                    <ProfilePreferences
                        v-if="activeSection === 'preferences'"
                        :transports="transports"
                        :payments="payments"
                        :preferences="preferences"
                        @updated="handlePreferencesUpdated"
                    />

                    <!-- Orders Section -->
                    <ProfileOrders
                        v-if="activeSection === 'orders'"
                        :orders="orders"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ProfileUserInfo from '@/Components/Profile/UserInfo.vue';
import ProfilePreferences from '@/Components/Profile/Preferences.vue';
import ProfileOrders from '@/Components/Profile/Orders.vue';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    orders: {
        type: Array,
        default: () => [],
    },
    transports: {
        type: Array,
        default: () => [],
    },
    payments: {
        type: Array,
        default: () => [],
    },
    preferences: {
        type: Object,
        default: () => ({
            default_transport_id: null,
            default_payment_id: null,
        }),
    },
    activeSection: {
        type: String,
        default: 'info',
    },
});

const page = usePage();

const handleUserUpdated = () => {
    router.reload({ only: ['user'] });
};

const handlePreferencesUpdated = () => {
    router.reload({ only: ['preferences'] });
};
</script>
