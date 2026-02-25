<template>
    <div class="checkout-steps w-full py-4 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-start justify-between relative">
                <!-- Krok 1: Nákupní košík -->
                <div class="flex flex-col items-center flex-1 relative z-10">
                    <div
                        :class="[
                            'step-icon w-12 h-12 rounded-full flex items-center justify-center border-2 transition-colors',
                            currentStep >= 1
                                ? 'border-primary-500 bg-primary-50'
                                : 'border-gray-300 bg-white',
                        ]"
                    >
                        <svg
                            v-if="currentStep >= 1"
                            class="w-6 h-6 text-primary-600"
                            fill="currentColor"
                            viewBox="0 0 16 16"
                        >
                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                        <svg
                            v-else
                            class="w-6 h-6 text-gray-400"
                            fill="currentColor"
                            viewBox="0 0 16 16"
                        >
                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                    </div>
                    <p
                        :class="[
                            'step-label mt-2 text-xs font-medium text-center',
                            currentStep >= 1 ? 'text-primary-600' : 'text-gray-400',
                        ]"
                    >
                        Nákupní košík
                    </p>
                </div>

                <!-- Spojovací čára 1 -->
                <div
                    :class="[
                        'step-line',
                        currentStep >= 2 ? 'bg-primary-500' : 'bg-gray-300',
                    ]"
                ></div>

                <!-- Krok 2: Doprava, platba a kontaktní údaje -->
                <div class="flex flex-col items-center flex-1 relative z-10">
                    <div
                        :class="[
                            'step-icon w-12 h-12 rounded-full flex items-center justify-center border-2 transition-colors',
                            currentStep >= 2
                                ? 'border-primary-500 bg-primary-50'
                                : 'border-gray-300 bg-white',
                        ]"
                    >
                        <svg
                            v-if="currentStep >= 2"
                            class="w-6 h-6 text-primary-600"
                            fill="currentColor"
                            viewBox="0 0 16 16"
                        >
                            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                            <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.5.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.489.22c-.206.028-.49-.013-.635-.08a5.01 5.01 0 0 1-.79-.24c-.365-.19-.594-.465-.74-.82-.146-.354-.113-.76.006-1.14a7.165 7.165 0 0 1 .088-.582c.044-.24.08-.45.11-.67.062-.434.132-.845.264-1.208.144-.39.38-.74.72-.99.34-.25.78-.38 1.23-.4.45-.02.9.08 1.28.26.19.09.35.22.47.38.12.16.19.35.2.54.01.19-.04.38-.13.55-.09.17-.23.31-.4.4a1.03 1.03 0 0 1-.5.12c-.2.01-.38-.04-.54-.13a1.5 1.5 0 0 1-.35-.3 1.5 1.5 0 0 1-.15-.45z"/>
                        </svg>
                        <svg
                            v-else
                            class="w-6 h-6 text-gray-400"
                            fill="currentColor"
                            viewBox="0 0 16 16"
                        >
                            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                            <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.5.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.489.22c-.206.028-.49-.013-.635-.08a5.01 5.01 0 0 1-.79-.24c-.365-.19-.594-.465-.74-.82-.146-.354-.113-.76.006-1.14a7.165 7.165 0 0 1 .088-.582c.044-.24.08-.45.11-.67.062-.434.132-.845.264-1.208.144-.39.38-.74.72-.99.34-.25.78-.38 1.23-.4.45-.02.9.08 1.28.26.19.09.35.22.47.38.12.16.19.35.2.54.01.19-.04.38-.13.55-.09.17-.23.31-.4.4a1.03 1.03 0 0 1-.5.12c-.2.01-.38-.04-.54-.13a1.5 1.5 0 0 1-.35-.3 1.5 1.5 0 0 1-.15-.45z"/>
                        </svg>
                    </div>
                    <p
                        :class="[
                            'step-label mt-2 text-xs font-medium text-center',
                            currentStep >= 2 ? 'text-primary-600' : 'text-gray-400',
                        ]"
                    >
                        Údaje a doprava
                    </p>
                </div>

                <!-- Spojovací čára 2 -->
                <div
                    :class="[
                        'step-line',
                        currentStep >= 3 ? 'bg-primary-500' : 'bg-gray-300',
                    ]"
                ></div>

                <!-- Krok 3: Rekapitulace -->
                <div class="flex flex-col items-center flex-1 relative z-10">
                    <div
                        :class="[
                            'step-icon w-12 h-12 rounded-full flex items-center justify-center border-2 transition-colors',
                            currentStep >= 3
                                ? 'border-primary-500 bg-primary-50'
                                : 'border-gray-300 bg-white',
                        ]"
                    >
                        <svg
                            v-if="currentStep >= 3"
                            class="w-6 h-6 text-primary-600"
                            fill="currentColor"
                            viewBox="0 0 16 16"
                        >
                            <path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                            <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                        </svg>
                        <svg
                            v-else
                            class="w-6 h-6 text-gray-400"
                            fill="currentColor"
                            viewBox="0 0 16 16"
                        >
                            <path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                            <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                        </svg>
                    </div>
                    <p
                        :class="[
                            'step-label mt-2 text-xs font-medium text-center',
                            currentStep >= 3 ? 'text-primary-600' : 'text-gray-400',
                        ]"
                    >
                        Rekapitulace
                    </p>
                </div>

                <!-- Spojovací čára 3 -->
                <div
                    :class="[
                        'step-line',
                        currentStep >= 4 ? 'bg-primary-500' : 'bg-gray-300',
                    ]"
                ></div>

                <!-- Krok 4: Potvrzení -->
                <div class="flex flex-col items-center flex-1 relative z-10">
                    <div
                        :class="[
                            'step-icon w-12 h-12 rounded-full flex items-center justify-center border-2 transition-colors',
                            currentStep >= 4
                                ? 'border-primary-500 bg-primary-50'
                                : 'border-gray-300 bg-white',
                        ]"
                    >
                        <svg
                            v-if="currentStep >= 4"
                            class="w-6 h-6 text-primary-600"
                            fill="currentColor"
                            viewBox="0 0 16 16"
                        >
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.061L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                        </svg>
                        <svg
                            v-else
                            class="w-6 h-6 text-gray-400"
                            fill="currentColor"
                            viewBox="0 0 16 16"
                        >
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.061L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                        </svg>
                    </div>
                    <p
                        :class="[
                            'step-label mt-2 text-xs font-medium text-center',
                            currentStep >= 4 ? 'text-primary-600' : 'text-gray-400',
                        ]"
                    >
                        Potvrzení
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useCheckoutSteps } from '@/Composables/useCheckoutSteps';

const { currentStep } = useCheckoutSteps();
</script>

<style scoped>
.checkout-steps .step-icon {
    flex-shrink: 0;
}

.checkout-steps .step-line {
    height: 1px;
    flex: 1 1 0%;
    margin: 0 0.25rem;
    min-width: 0;
    /* Čáry jsou zarovnané na střed ikon (ikony jsou 48px vysoké, takže střed je na 24px) */
    margin-top: 24px;
    margin-bottom: 0;
    align-self: flex-start;
}
</style>
