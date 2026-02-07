<template>
    <section class="bg-white py-12">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <div
                    v-for="guarantee in guarantees"
                    :key="guarantee.id"
                    class="flex flex-col items-center text-center p-6 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors"
                >
                    <div
                        class="w-16 h-16 rounded-full flex items-center justify-center mb-4"
                        :style="{ backgroundColor: guarantee.iconBgColor }"
                    >
                        <svg
                            v-if="guarantee.icon === 'truck'"
                            class="w-8 h-8"
                            :style="{ color: guarantee.iconColor }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"
                            />
                        </svg>
                        <svg
                            v-else-if="guarantee.icon === 'payment'"
                            class="w-8 h-8"
                            :style="{ color: guarantee.iconColor }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                            />
                        </svg>
                        <svg
                            v-else-if="guarantee.icon === 'eye'"
                            class="w-8 h-8"
                            :style="{ color: guarantee.iconColor }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                            />
                        </svg>
                        <svg
                            v-else-if="guarantee.icon === 'clock'"
                            class="w-8 h-8"
                            :style="{ color: guarantee.iconColor }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                cx="12"
                                cy="12"
                                r="10"
                                stroke-width="2"
                            />
                            <text
                                x="12"
                                y="16"
                                text-anchor="middle"
                                font-size="8"
                                font-weight="bold"
                                fill="currentColor"
                            >
                                24
                            </text>
                        </svg>
                        <svg
                            v-else-if="guarantee.icon === 'check'"
                            class="w-8 h-8"
                            :style="{ color: guarantee.iconColor }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
                            />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                        {{ guarantee.title }}
                    </h3>
                    <p class="text-sm text-gray-600">
                        {{ guarantee.subtitle }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const props = defineProps({
    guarantees: {
        type: Array,
        default: () => [],
    },
});
</script>
