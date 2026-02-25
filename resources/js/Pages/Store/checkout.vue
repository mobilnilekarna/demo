<template>
    <AppLayout title="Doručení a platba">
        <CheckoutSteps />
        <div class="max-w-7xl mx-auto py-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Doručení a platba</h1>
                <button
                    type="button"
                    @click="fillSampleData"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors"
                >
                    Doplnit údaje
                </button>
            </div>

            <div class="flex gap-8">
                <!-- Sticky Sidebar -->
                <aside class="hidden lg:block w-16 flex-shrink-0">
                    <div class="sticky top-8">
                        <nav class="space-y-3">
                            <button
                                @click="scrollToSection('basket')"
                                :class="[
                                    'w-16 h-16 min-w-16 min-h-16 rounded-lg font-medium transition-all duration-200 flex flex-col items-center justify-center gap-2 px-2',
                                    activeSection === 'basket'
                                        ? 'bg-primary-500 text-white shadow-md'
                                        : visitedSteps.has('basket')
                                        ? 'bg-gray-200 text-gray-900 hover:bg-gray-300'
                                        : 'bg-gray-200 text-gray-400 hover:bg-gray-300'
                                ]"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-basket" viewBox="0 0 16 16">
                                    <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172M2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9zM1 7v1h14V7zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5"/>
                                </svg>
                                <span class="hidden" style="writing-mode: vertical-rl; text-orientation: mixed;">Košík</span>
                            </button>
                            <button
                                @click="scrollToSection('delivery')"
                                :class="[
                                    'w-16 h-16 min-w-16 min-h-16 rounded-lg font-medium transition-all duration-200 flex flex-col items-center justify-center gap-2 px-2',
                                    activeSection === 'delivery'
                                        ? 'bg-primary-500 text-white shadow-md'
                                        : visitedSteps.has('delivery')
                                        ? 'bg-gray-200 text-gray-900 hover:bg-gray-300'
                                        : 'bg-gray-200 text-gray-400 hover:bg-gray-300'
                                ]"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-truck" viewBox="0 0 16 16">
                                    <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2 2 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
                                </svg>
                                <span class="hidden" style="writing-mode: vertical-rl; text-orientation: mixed;">Doprava</span>
                            </button>
                            <button
                                @click="scrollToSection('payment')"
                                :class="[
                                    'w-16 h-16 min-w-16 min-h-16 rounded-lg font-medium transition-all duration-200 flex flex-col items-center justify-center gap-2 px-2',
                                    activeSection === 'payment'
                                        ? 'bg-primary-500 text-white shadow-md'
                                        : visitedSteps.has('payment')
                                        ? 'bg-gray-200 text-gray-900 hover:bg-gray-300'
                                        : 'bg-gray-200 text-gray-400 hover:bg-gray-300'
                                ]"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-credit-card" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z"/>
                                    <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/>
                                </svg>
                                <span class="hidden" style="writing-mode: vertical-rl; text-orientation: mixed;">Platba</span>
                            </button>
                            <button
                                @click="scrollToSection('details')"
                                :class="[
                                    'w-16 h-16 min-w-16 min-h-16 rounded-lg font-medium transition-all duration-200 flex flex-col items-center justify-center gap-2 px-2',
                                    activeSection === 'details'
                                        ? 'bg-primary-500 text-white shadow-md'
                                        : visitedSteps.has('details')
                                        ? 'bg-gray-200 text-gray-900 hover:bg-gray-300'
                                        : 'bg-gray-200 text-gray-400 hover:bg-gray-300'
                                ]"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-text" viewBox="0 0 16 16">
                                    <path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm-1-5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zM5 11a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1z"/>
                                    <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1"/>
                                </svg>
                                <span class="hidden" style="writing-mode: vertical-rl; text-orientation: mixed;">Údaje</span>
                            </button>
                        </nav>
                    </div>
                </aside>

                <!-- Main Content -->
                <div class="flex-1 min-w-0">
                    <!-- Basket Toggle -->
                    <div id="basket-section" class="scroll-mt-8 mb-6">
                        <CheckoutBasketToggle :products="products" />
                    </div>

                    <!-- Stav přihlášení -->
                    <div id="auth-section" class="scroll-mt-8 mb-6">
                        <CheckoutAuthStatus />
                    </div>

            <form @submit.prevent="handleSubmit" class="space-y-8">
                <!-- Výběr typu doručení -->
                <div id="delivery-section" class="bg-white rounded-lg shadow-md p-6 scroll-mt-8">
                    <h2 class="text-lg font-bold text-gray-900 uppercase mb-4">
                        TYP DORUČENÍ
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label
                            class="flex items-center gap-4 p-4 border-2 rounded-lg cursor-pointer transition-colors"
                            :class="
                                formData.deliveryType === 'pickup'
                                    ? 'border-green-500 bg-green-50'
                                    : 'border-gray-200 hover:border-gray-300 bg-white'
                            "
                        >
                            <input
                                type="radio"
                                value="pickup"
                                :checked="formData.deliveryType === 'pickup'"
                                @change="handleDeliveryTypeChange"
                                class="hidden"
                            />
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">
                                    Výdejní místo
                                </div>
                                <div class="text-sm text-gray-500 mt-1">
                                    Osobní odběr na výdejním místě
                                </div>
                            </div>
                        </label>
                        <label
                            class="flex items-center gap-4 p-4 border-2 rounded-lg cursor-pointer transition-colors"
                            :class="
                                formData.deliveryType === 'address'
                                    ? 'border-green-500 bg-green-50'
                                    : 'border-gray-200 hover:border-gray-300 bg-white'
                            "
                        >
                            <input
                                type="radio"
                                value="address"
                                :checked="formData.deliveryType === 'address'"
                                @change="handleDeliveryTypeChange"
                                class="hidden"
                            />
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">
                                    Doprava na adresu
                                </div>
                                <div class="text-sm text-gray-500 mt-1">
                                    Doručení na zadanou adresu
                                </div>
                            </div>
                        </label>
                    </div>
                    <p v-if="errors.deliveryType" class="mt-2 text-sm text-red-600">
                        {{ errors.deliveryType }}
                    </p>
                </div>

                <!-- Výběr dopravy a platby -->
                <div v-if="formData.deliveryType" class="bg-white rounded-lg shadow-md p-6 space-y-6">
                    <div id="delivery-transport-section" class="scroll-mt-8" ref="transportSectionRef">
                        <TransportList
                            :transports="filteredTransports"
                            :errors="errors"
                            :basket-weight="basketWeight"
                            :packeta-settings="packetaSettings"
                            @transport-changed="handleTransportChange"
                        />
                    </div>
                    <div id="payment-section" class="scroll-mt-8" ref="paymentSectionRef">
                        <PaymentList
                            v-if="formData.transport"
                            :key="currentTransportId || 'no-transport'"
                            :payments="availablePayments"
                            :errors="errors"
                            :selected-payment-id="formData.payment?.id"
                            @payment-changed="handlePaymentChange"
                        />
                    </div>
                </div>

                <!-- Základní údaje -->
                <div id="details-section" class="bg-gray-50 rounded-lg shadow-md p-6 space-y-6 scroll-mt-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        Základní údaje
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Jméno -->
                        <div>
                            <label
                                for="firstName"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Jméno <span class="text-secondary-500">*</span>
                            </label>
                            <input
                                id="firstName"
                                v-model="formData.firstName"
                                type="text"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                :class="
                                    errors.firstName
                                        ? 'border-secondary-500'
                                        : 'border-gray-300'
                                "
                            />
                            <p v-if="errors.firstName" class="mt-1 text-sm text-secondary-600">
                                {{ errors.firstName }}
                            </p>
                        </div>

                        <!-- Příjmení -->
                        <div>
                            <label
                                for="lastName"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Příjmení <span class="text-secondary-500">*</span>
                            </label>
                            <input
                                id="lastName"
                                v-model="formData.lastName"
                                type="text"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                :class="
                                    errors.lastName
                                        ? 'border-secondary-500'
                                        : 'border-gray-300'
                                "
                            />
                            <p v-if="errors.lastName" class="mt-1 text-sm text-secondary-600">
                                {{ errors.lastName }}
                            </p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label
                                for="email"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Email <span class="text-secondary-500">*</span>
                            </label>
                            <input
                                id="email"
                                v-model="formData.email"
                                type="email"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                :class="
                                    errors.email
                                        ? 'border-secondary-500'
                                        : 'border-gray-300'
                                "
                            />
                            <p v-if="errors.email" class="mt-1 text-sm text-secondary-600">
                                {{ errors.email }}
                            </p>
                        </div>

                        <!-- Telefon -->
                        <div>
                            <label
                                for="phone"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Telefon <span class="text-secondary-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <!-- Prefix selector - vlastní dropdown s ul/li -->
                                <div class="absolute left-0 top-0 bottom-0 flex items-center pl-3 pr-2 z-10 border-r border-gray-300">
                                    <div class="relative h-full flex items-center" ref="phonePrefixDropdownRef">
                                        <button
                                            type="button"
                                            @click="togglePhonePrefixDropdown"
                                            class="flex items-center gap-2 cursor-pointer text-gray-700 font-medium focus:outline-none"
                                        >
                                            <!-- Vlajka -->
                                            <span class="flex-shrink-0" v-html="formatFlagSvg(currentPhonePrefix.flag)"></span>
                                            <!-- Prefix číslo -->
                                            <span>{{ currentPhonePrefix.code }}</span>
                                            <!-- Šipka dolů -->
                                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': isPhonePrefixOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        <!-- Dropdown seznam -->
                                        <ul
                                            v-if="isPhonePrefixOpen"
                                            class="absolute top-full inset-x-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-[100] min-w-[120px] whitespace-nowrap"
                                        >
                                            <li v-for="prefix in phonePrefixes" :key="prefix.code">
                                                <button
                                                    type="button"
                                                    @click="selectPhonePrefix(prefix.code)"
                                                    class="w-full flex items-center gap-2 px-3 py-2 text-left hover:bg-gray-100 transition-colors"
                                                    :class="{ 'bg-gray-100': formData.phonePrefix === prefix.code }"
                                                >
                                                    <span class="flex-shrink-0" v-html="formatFlagSvg(prefix.flag, 'flex-shrink-0')"></span>
                                                    <span>{{ prefix.code }}</span>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- Phone input s paddingem pro prefix -->
                                <input
                                    id="phone"
                                    v-model="formData.phone"
                                    type="tel"
                                    placeholder="123 456 789"
                                    class="w-full pl-28 pr-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    :class="
                                        errors.phone
                                            ? 'border-secondary-500'
                                            : 'border-gray-300'
                                    "
                                />
                            </div>
                            <p v-if="errors.phone" class="mt-1 text-sm text-secondary-600">
                                {{ errors.phone }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Fakturační údaje -->
                <div class="bg-gray-50 rounded-lg shadow-md p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <h2 class="text-xl font-semibold text-gray-900">
                                Fakturační údaje
                            </h2>
                            <span
                                v-if="!isAddressRequired && formData.deliveryType === 'pickup'"
                                class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full"
                            >
                                Nepovinné (zvoleno výdejní místo)
                            </span>
                        </div>
                        <label
                            v-if="!isAddressRequired && formData.deliveryType === 'pickup' && formData.transport?.id"
                            for="billingToggle"
                            class="relative inline-flex items-center cursor-pointer"
                        >
                            <input
                                id="billingToggle"
                                :checked="!isBillingCollapsed"
                                @change="isBillingCollapsed = !$event.target.checked"
                                type="checkbox"
                                class="sr-only peer"
                            />
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                        </label>
                    </div>

                    <div v-if="!isBillingCollapsed" class="space-y-4">
                        <!-- Ulice -->
                        <div>
                            <label
                                for="street"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Ulice a číslo <span v-if="isAddressRequired" class="text-secondary-500">*</span>
                            </label>
                            <input
                                id="street"
                                v-model="formData.street"
                                type="text"
                                :required="isAddressRequired"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                :class="
                                    errors.street
                                        ? 'border-secondary-500'
                                        : 'border-gray-300'
                                "
                            />
                            <p v-if="errors.street" class="mt-1 text-sm text-secondary-600">
                                {{ errors.street }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Město -->
                            <div class="md:col-span-2">
                                <label
                                    for="city"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Město <span v-if="isAddressRequired" class="text-secondary-500">*</span>
                                </label>
                                <input
                                    id="city"
                                    v-model="formData.city"
                                    type="text"
                                    :required="isAddressRequired"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    :class="
                                        errors.city
                                            ? 'border-secondary-500'
                                            : 'border-gray-300'
                                    "
                                />
                                <p v-if="errors.city" class="mt-1 text-sm text-secondary-600">
                                    {{ errors.city }}
                                </p>
                            </div>

                            <!-- PSČ -->
                            <div>
                                <label
                                    for="zip"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    PSČ <span v-if="isAddressRequired" class="text-secondary-500">*</span>
                                </label>
                                <input
                                    id="zip"
                                    v-model="formData.zip"
                                    type="text"
                                    maxlength="5"
                                    :required="isAddressRequired"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    :class="
                                        errors.zip
                                            ? 'border-secondary-500'
                                            : 'border-gray-300'
                                    "
                                />
                                <p v-if="errors.zip" class="mt-1 text-sm text-secondary-600">
                                    {{ errors.zip }}
                                </p>
                            </div>
                        </div>

                        <!-- Země -->
                        <div>
                            <label
                                for="country"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Země
                            </label>
                            <select
                                id="country"
                                v-model="formData.country"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            >
                                <option value="CZ">Česká republika</option>
                                <option value="SK">Slovensko</option>
                            </select>
                        </div>
                    </div>

                    <!-- Zobrazení při sbalení (pokud jsou vyplněné údaje) -->
                    <div v-else-if="formData.street || formData.city || formData.zip" class="p-4 border-2 border-gray-300 bg-white rounded-lg">
                        <div class="space-y-2">
                            <div v-if="formData.street" class="text-sm">
                                <span class="font-semibold text-gray-700">Ulice:</span> {{ formData.street }}
                            </div>
                            <div v-if="formData.city || formData.zip" class="text-sm">
                                <span class="font-semibold text-gray-700">Město, PSČ:</span> {{ formData.city }}{{ formData.zip ? `, ${formData.zip}` : '' }}
                            </div>
                            <div v-if="formData.country" class="text-sm">
                                <span class="font-semibold text-gray-700">Země:</span> {{ formData.country === 'CZ' ? 'Česká republika' : 'Slovensko' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Firemní údaje -->
                <div class="bg-gray-50 rounded-lg shadow-md p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <label
                            for="isCompany"
                            class="text-xl font-semibold text-gray-900 cursor-pointer"
                        >
                            Fakturovat na firmu
                        </label>
                        <label
                            for="isCompany"
                            class="relative inline-flex items-center cursor-pointer"
                        >
                            <input
                                id="isCompany"
                                v-model="formData.isCompany"
                                type="checkbox"
                                class="sr-only peer"
                            />
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                        </label>
                    </div>

                    <div v-if="formData.isCompany" class="space-y-4 pt-4 border-t">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Název firmy -->
                            <div>
                                <label
                                    for="companyName"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Název firmy <span class="text-secondary-500">*</span>
                                </label>
                                <input
                                    id="companyName"
                                    v-model="formData.companyName"
                                    type="text"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    :class="
                                        errors.companyName
                                            ? 'border-secondary-500'
                                            : 'border-gray-300'
                                    "
                                />
                                <p
                                    v-if="errors.companyName"
                                    class="mt-1 text-sm text-secondary-600"
                                >
                                    {{ errors.companyName }}
                                </p>
                            </div>

                            <!-- IČO -->
                            <div>
                                <label
                                    for="companyId"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    IČO <span class="text-secondary-500">*</span>
                                </label>
                                <input
                                    id="companyId"
                                    v-model="formData.companyId"
                                    type="text"
                                    maxlength="8"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    :class="
                                        errors.companyId
                                            ? 'border-secondary-500'
                                            : 'border-gray-300'
                                    "
                                />
                                <p
                                    v-if="errors.companyId"
                                    class="mt-1 text-sm text-secondary-600"
                                >
                                    {{ errors.companyId }}
                                </p>
                            </div>
                        </div>

                        <!-- DIČ -->
                        <div>
                            <label
                                for="companyVatId"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                DIČ (volitelné)
                            </label>
                            <input
                                id="companyVatId"
                                v-model="formData.companyVatId"
                                type="text"
                                placeholder="CZ12345678"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                :class="
                                    errors.companyVatId
                                        ? 'border-secondary-500'
                                        : 'border-gray-300'
                                "
                            />
                            <p
                                v-if="errors.companyVatId"
                                class="mt-1 text-sm text-secondary-600"
                            >
                                {{ errors.companyVatId }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Dodací údaje -->
                <div class="bg-gray-50 rounded-lg shadow-md p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <label
                            for="hasDeliveryAddress"
                            class="text-xl font-semibold text-gray-900 cursor-pointer"
                        >
                            Dodat na jinou adresu
                        </label>
                        <label
                            for="hasDeliveryAddress"
                            class="relative inline-flex items-center cursor-pointer"
                        >
                            <input
                                id="hasDeliveryAddress"
                                v-model="formData.hasDeliveryAddress"
                                type="checkbox"
                                class="sr-only peer"
                            />
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                        </label>
                    </div>

                    <div v-if="formData.hasDeliveryAddress" class="space-y-4 pt-4 border-t">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Dodací jméno -->
                            <div>
                                <label
                                    for="deliveryFirstName"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Jméno <span class="text-secondary-500">*</span>
                                </label>
                                <input
                                    id="deliveryFirstName"
                                    v-model="formData.deliveryFirstName"
                                    type="text"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    :class="
                                        errors.deliveryFirstName
                                            ? 'border-secondary-500'
                                            : 'border-gray-300'
                                    "
                                />
                                <p
                                    v-if="errors.deliveryFirstName"
                                    class="mt-1 text-sm text-secondary-600"
                                >
                                    {{ errors.deliveryFirstName }}
                                </p>
                            </div>

                            <!-- Dodací příjmení -->
                            <div>
                                <label
                                    for="deliveryLastName"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Příjmení <span class="text-secondary-500">*</span>
                                </label>
                                <input
                                    id="deliveryLastName"
                                    v-model="formData.deliveryLastName"
                                    type="text"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    :class="
                                        errors.deliveryLastName
                                            ? 'border-secondary-500'
                                            : 'border-gray-300'
                                    "
                                />
                                <p
                                    v-if="errors.deliveryLastName"
                                    class="mt-1 text-sm text-secondary-600"
                                >
                                    {{ errors.deliveryLastName }}
                                </p>
                            </div>
                        </div>

                        <!-- Dodací ulice -->
                        <div>
                            <label
                                for="deliveryStreet"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Ulice a číslo <span class="text-secondary-500">*</span>
                            </label>
                            <input
                                id="deliveryStreet"
                                v-model="formData.deliveryStreet"
                                type="text"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                :class="
                                    errors.deliveryStreet
                                        ? 'border-secondary-500'
                                        : 'border-gray-300'
                                "
                            />
                            <p
                                v-if="errors.deliveryStreet"
                                class="mt-1 text-sm text-secondary-600"
                            >
                                {{ errors.deliveryStreet }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Dodací město -->
                            <div class="md:col-span-2">
                                <label
                                    for="deliveryCity"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Město <span class="text-secondary-500">*</span>
                                </label>
                                <input
                                    id="deliveryCity"
                                    v-model="formData.deliveryCity"
                                    type="text"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    :class="
                                        errors.deliveryCity
                                            ? 'border-secondary-500'
                                            : 'border-gray-300'
                                    "
                                />
                                <p
                                    v-if="errors.deliveryCity"
                                    class="mt-1 text-sm text-secondary-600"
                                >
                                    {{ errors.deliveryCity }}
                                </p>
                            </div>

                            <!-- Dodací PSČ -->
                            <div>
                                <label
                                    for="deliveryZip"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    PSČ <span class="text-secondary-500">*</span>
                                </label>
                                <input
                                    id="deliveryZip"
                                    v-model="formData.deliveryZip"
                                    type="text"
                                    maxlength="5"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    :class="
                                        errors.deliveryZip
                                            ? 'border-secondary-500'
                                            : 'border-gray-300'
                                    "
                                />
                                <p
                                    v-if="errors.deliveryZip"
                                    class="mt-1 text-sm text-secondary-600"
                                >
                                    {{ errors.deliveryZip }}
                                </p>
                            </div>
                        </div>

                        <!-- Dodací země -->
                        <div>
                            <label
                                for="deliveryCountry"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Země
                            </label>
                            <select
                                id="deliveryCountry"
                                v-model="formData.deliveryCountry"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            >
                                <option value="CZ">Česká republika</option>
                                <option value="SK">Slovensko</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Poznámka -->
                <div class="bg-gray-50 rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Poznámka</h2>
                    <textarea
                        id="note"
                        v-model="formData.note"
                        rows="4"
                        placeholder="Zde můžete přidat poznámku k objednávce..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    ></textarea>
                </div>

                <!-- Tlačítka -->
                <div class="flex flex-col sm:flex-row gap-4 justify-between">
                    <Link
                        href="/basket"
                        class="px-6 py-3 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600 transition-colors text-center"
                    >
                        Zpět ke košíku
                    </Link>
                    <button
                        type="submit"
                        class="px-6 py-3 bg-green-700 text-white rounded-lg font-semibold hover:bg-green-800 transition-colors"
                    >
                        Další krok
                    </button>
                </div>
            </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import CheckoutSteps from "@/Components/Checkout/CheckoutSteps.vue";
import CheckoutAuthStatus from "@/Components/Checkout/CheckoutAuthStatus.vue";
import CheckoutBasketToggle from "@/Components/Checkout/CheckoutBasketToggle.vue";
import { useCheckout } from "@/Composables/useCheckout";
import { useAuth } from "@/Composables/useAuth";
import { useBasket } from "@/Composables/useBasket";
import { Link, router, usePage } from "@inertiajs/vue3";
import { formatCurrency } from "@/Utils/format";
import TransportList from "@/Components/Store/Basket/TransportList.vue";
import PaymentList from "@/Components/Store/Basket/PaymentList.vue";

const { formData, validateForm, fillSampleData, clearStorage } = useCheckout();
const { user } = useAuth();
const { isEmpty } = useBasket();

// Kontrola prázdného košíku - přesměrování na košík
watch(
    isEmpty,
    (empty) => {
        if (empty) {
            router.visit("/basket", {
                replace: true,
            });
        }
    },
    { immediate: true }
);

// Automatické vyplnění emailu z uživatelských dat, pokud je přihlášen a email není vyplněn
watch(
    [user, () => formData.value.email],
    ([newUser, email]) => {
        if (newUser && !email && newUser.email) {
            formData.value.email = newUser.email;
        }
    },
    { immediate: true }
);

const props = defineProps({
    transports: {
        type: Array,
        default: () => [],
    },
    products: {
        type: Array,
        default: () => [],
    },
    checkoutConfig: {
        type: Object,
        default: () => ({
            type: 'advanced',
        }),
    },
    phonePrefixes: {
        type: Array,
        default: () => [
            { code: '+420', country: 'CZ', flag: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600"><rect width="900" height="600" fill="#d7141a"/><rect width="900" height="300" fill="#ffffff"/><polygon points="0,0 450,300 0,600" fill="#11457e"/></svg>' },
            { code: '+421', country: 'SK', flag: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600"><rect width="900" height="600" fill="#ee1c25"/><rect width="900" height="400" fill="#0b4ea2"/><rect width="900" height="200" fill="#ffffff"/><g transform="translate(270 140) scale(1.1)"><path d="M150 0 C240 0 300 40 300 120 V230 C300 300 200 360 150 380 C100 360 0 300 0 230 V120 C0 40 60 0 150 0Z" fill="#ee1c25" stroke="#ffffff" stroke-width="10"/><rect x="135" y="70" width="30" height="160" fill="#ffffff"/><rect x="95" y="110" width="110" height="25" fill="#ffffff"/><rect x="105" y="150" width="90" height="22" fill="#ffffff"/><path d="M40 260 C80 210 120 210 150 250 C180 210 220 210 260 260 L260 300 L40 300Z" fill="#0b4ea2"/></g></svg>' },
        ],
    },
});

const errors = ref({});

// Computed property pro aktuálně vybraný prefix
const currentPhonePrefix = computed(() => {
    return props.phonePrefixes.find(p => p.code === formData.value.phonePrefix) || props.phonePrefixes[0];
});

// Helper funkce pro formátování SVG vlajky
const formatFlagSvg = (flagSvg, classes = 'inline-block') => {
    return flagSvg.replace(
        'viewBox="0 0 900 600"',
        `width="20" height="13.33" viewBox="0 0 900 600" class="${classes}"`
    );
};

// Phone prefix dropdown state
const isPhonePrefixOpen = ref(false);
const phonePrefixDropdownRef = ref(null);

// Toggle phone prefix dropdown
const togglePhonePrefixDropdown = () => {
    isPhonePrefixOpen.value = !isPhonePrefixOpen.value;
};

// Close phone prefix dropdown
const closePhonePrefixDropdown = () => {
    isPhonePrefixOpen.value = false;
};

// Select phone prefix
const selectPhonePrefix = (prefix) => {
    formData.value.phonePrefix = prefix;
    closePhonePrefixDropdown();
};

// Click outside handler pro phone prefix dropdown
const handlePhonePrefixClickOutside = (event) => {
    if (phonePrefixDropdownRef.value && !phonePrefixDropdownRef.value.contains(event.target)) {
        closePhonePrefixDropdown();
    }
};

// Watch pro phone prefix dropdown - přidat/odstranit event listener
watch(isPhonePrefixOpen, (isOpen) => {
    if (isOpen) {
        document.addEventListener('click', handlePhonePrefixClickOutside);
    } else {
        document.removeEventListener('click', handlePhonePrefixClickOutside);
    }
});

// Active section tracking
const activeSection = ref('basket');
const visitedSteps = ref(new Set(['basket'])); // Track visited steps

// Function to scroll to section
const scrollToSection = (sectionId) => {
    let element;
    if (sectionId === 'basket') {
        element = document.getElementById('basket-section');
    } else if (sectionId === 'auth') {
        element = document.getElementById('auth-section');
    } else if (sectionId === 'delivery') {
        element = document.getElementById('delivery-section');
    } else if (sectionId === 'payment') {
        element = document.getElementById('payment-section');
        // If payment section doesn't exist (transport not selected), scroll to delivery transport section instead
        if (!element) {
            element = document.getElementById('delivery-transport-section');
        }
        // If that doesn't exist either, scroll to delivery section
        if (!element) {
            element = document.getElementById('delivery-section');
        }
    } else if (sectionId === 'details') {
        element = document.getElementById('details-section');
    }

    if (element) {
        // Immediately set active section when clicked
        activeSection.value = sectionId;
        visitedSteps.value.add(sectionId);

        const headerOffset = 150; // Increased offset to account for sticky sidebar
        const elementPosition = element.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });

        // After scroll completes, ensure correct section is active
        // Use longer timeout to ensure scroll is complete
        setTimeout(() => {
            // Force set the active section to the clicked one
            activeSection.value = sectionId;
            // Then detect to update visited steps
            detectActiveSection();
        }, 500);
    }
};

// Function to detect active section based on scroll position
const detectActiveSection = () => {
    const scrollPosition = window.scrollY + 150; // Offset for sticky positioning - reduced to match scroll offset

    const basketSection = document.getElementById('basket-section');
    const authSection = document.getElementById('auth-section');
    const deliverySection = document.getElementById('delivery-section');
    const paymentSection = document.getElementById('payment-section');
    const detailsSection = document.getElementById('details-section');

    if (!basketSection || !authSection || !deliverySection || !detailsSection) {
        return;
    }

    const basketTop = basketSection.offsetTop;
    const basketBottom = basketTop + basketSection.offsetHeight;
    const authTop = authSection.offsetTop;
    const authBottom = authTop + authSection.offsetHeight;
    const deliveryTop = deliverySection.offsetTop;
    const deliveryBottom = deliveryTop + deliverySection.offsetHeight;
    const detailsTop = detailsSection.offsetTop;

    // Check if payment section exists (might not be visible initially)
    let paymentTop = null;
    let paymentBottom = null;
    if (paymentSection) {
        paymentTop = paymentSection.offsetTop;
        paymentBottom = paymentTop + paymentSection.offsetHeight;
    }

    let newActiveSection = 'basket';

    // Determine which section is currently in view
    // Check from top to bottom, but use a threshold to prefer the section we're "inside"
    // Priority: details > payment > delivery > auth > basket

    // Use a smaller threshold to detect which section we're currently "in"
    const threshold = 150; // How much into the section before we consider it active

    if (scrollPosition >= detailsTop - threshold) {
        newActiveSection = 'details';
        visitedSteps.value.add('basket');
        visitedSteps.value.add('auth');
        visitedSteps.value.add('delivery');
        if (paymentTop !== null) visitedSteps.value.add('payment');
        visitedSteps.value.add('details');
    } else if (paymentTop !== null && scrollPosition >= paymentTop - threshold && scrollPosition < detailsTop - threshold) {
        newActiveSection = 'payment';
        visitedSteps.value.add('basket');
        visitedSteps.value.add('auth');
        visitedSteps.value.add('delivery');
        visitedSteps.value.add('payment');
    } else if (scrollPosition >= deliveryTop - threshold && (paymentTop === null || scrollPosition < paymentTop - threshold)) {
        newActiveSection = 'delivery';
        visitedSteps.value.add('basket');
        visitedSteps.value.add('auth');
        visitedSteps.value.add('delivery');
    } else if (scrollPosition >= authTop - threshold && scrollPosition < deliveryTop - threshold) {
        // Auth section exists but is not in menu
        // Keep basket active until we reach delivery section
        newActiveSection = 'basket';
        visitedSteps.value.add('basket');
    } else if (scrollPosition >= basketTop - threshold && scrollPosition < authTop - threshold) {
        newActiveSection = 'basket';
        visitedSteps.value.add('basket');
    } else if (scrollPosition < basketTop) {
        newActiveSection = 'basket';
        visitedSteps.value.add('basket');
    }

    activeSection.value = newActiveSection;
};

// Setup scroll listener
onMounted(() => {
    window.addEventListener('scroll', detectActiveSection, { passive: true });
    // Initial detection after a short delay to ensure DOM is ready
    setTimeout(() => {
        detectActiveSection();
    }, 100);

    // Also detect when payment section becomes visible (when transport is selected)
    watch(
        () => formData.value.transport,
        () => {
            setTimeout(() => {
                detectActiveSection();
            }, 100);
        }
    );
});

onUnmounted(() => {
    window.removeEventListener('scroll', detectActiveSection);
    document.removeEventListener('click', handlePhonePrefixClickOutside);
});

// Reaktivní proměnná pro dostupné platby
const availablePayments = ref([]);

// Funkce pro aktualizaci dostupných plateb
const updateAvailablePayments = (transportId) => {
    if (!transportId) {
        availablePayments.value = [];
        return;
    }

    // Zajistit, že hledáme podle správného typu (číslo vs string)
    const selectedTransport = props.transports.find(
        (t) => t.id == transportId // Použít == místo === pro flexibilní porovnání
    );

    if (!selectedTransport || !selectedTransport.available_payments) {
        availablePayments.value = [];
        return;
    }

    // Aktualizovat dostupné platby
    availablePayments.value = [...selectedTransport.available_payments];
};

// Handler pro změnu typu doručení
const handleDeliveryTypeChange = (event) => {
    const deliveryType = event.target.value;
    formData.value.deliveryType = deliveryType;
    // Reset dopravy, platby a výdejního místa při změně typu doručení
    formData.value.transport = null;
    formData.value.payment = null;
    formData.value.branch_id = null;
    formData.value.branch_name = null;
    updateAvailablePayments(null);
};

// Packeta settings (lze později přidat z props nebo env)
const packetaSettings = ref({});

// Computed pro filtrované dopravy podle typu doručení
const filteredTransports = computed(() => {
    if (!formData.value.deliveryType) {
        return [];
    }
    return props.transports.filter(
        transport => transport.type_delivery === formData.value.deliveryType
    );
});

// Handler pro změnu dopravy z komponenty
const handleTransportChange = (transportId) => {
    // Najít transport objekt z props
    const transport = props.transports.find(t => t.id == transportId);
    if (transport) {
        formData.value.transport = {
            id: transport.id,
            name: transport.name,
            type: transport.type,
            price: transport.price,
        };
    }
    formData.value.payment = null; // Reset platby
    // Reset výdejního místa při změně dopravy
    formData.value.branch_id = null;
    formData.value.branch_name = null;
    updateAvailablePayments(transportId);
};

// Handler pro změnu platby z komponenty
const handlePaymentChange = (paymentId) => {
    // Najít payment objekt z availablePayments
    const payment = availablePayments.value.find(p => p.id == paymentId);
    if (payment) {
        formData.value.payment = {
            id: payment.id,
            name: payment.name,
            type: payment.type,
            price: payment.price,
            final_price: payment.final_price,
        };
    }
};

// Computed pro získání transport id (pro sledování změn)
const currentTransportId = computed(() => formData.value.transport?.id || null);

// Computed pro určení, zda jsou adresní údaje povinné
const isAddressRequired = computed(() => {
    const checkoutType = props.checkoutConfig.type ?? 'advanced';
    const isPickup = formData.value.deliveryType === 'pickup';
    // V simple režimu nejsou adresní údaje povinné při pickup, v advanced jsou vždy povinné
    return checkoutType === 'advanced' || !isPickup;
});

// Collapse state pro fakturační údaje
const isBillingCollapsed = ref(false);

// Funkce pro rozbalení fakturačních údajů
const expandBilling = () => {
    isBillingCollapsed.value = false;
};

// Funkce pro sbalení fakturačních údajů
const collapseBilling = () => {
    isBillingCollapsed.value = true;
};

// Sledování, kdy sbalit fakturační údaje
watch(
    () => [isAddressRequired.value, formData.value.deliveryType, formData.value.transport?.id],
    ([addressRequired, deliveryType, transportId]) => {
        // Sbalit, pokud jsou nepovinné a je vybraná doprava typu pickup
        if (!addressRequired && deliveryType === 'pickup' && transportId) {
            isBillingCollapsed.value = true;
        } else if (addressRequired) {
            // Vždy rozbalit, pokud jsou povinné
            isBillingCollapsed.value = false;
        }
    },
    { immediate: true }
);

// Computed pro výpočet váhy košíku (pokud je dostupná)
const basketWeight = computed(() => {
    if (!props.products || props.products.length === 0) {
        return null;
    }
    // Pokusíme se spočítat váhu z produktů (pokud mají weight vlastnost)
    // Prozatím vracíme null, pokud to není implementováno
    // V budoucnu lze přidat: props.products.reduce((total, product) => total + (product.weight || 0) * product.quantity, 0)
    return null;
});

// Watch pro aktualizaci availablePayments při změně transport
watch(
    currentTransportId,
    (transportId) => {
        updateAvailablePayments(transportId);
    },
    { immediate: true }
);

// Watch pro obnovení availablePayments při načtení transportu z localStorage
watch(
    () => formData.value.transport,
    (transport) => {
        if (transport && transport.id) {
            updateAvailablePayments(transport.id);
        }
    },
    { immediate: true }
);

// Watch pro reset payment, když se změní transport
watch(
    currentTransportId,
    (newTransportId, oldTransportId) => {
        // Resetuj payment při změně transportu
        if (newTransportId !== null && newTransportId !== oldTransportId) {
            formData.value.payment = null;
        }
    }
);



const handleSubmit = () => {
    const validation = validateForm(props.checkoutConfig);
    errors.value = validation.errors;

    if (validation.isValid) {
        // Přesměrování na summary stránku
        router.visit('/checkout/summary');
    } else {
        // Scroll na první chybu
        const firstError = Object.keys(errors.value)[0];
        const element = document.getElementById(firstError);
        if (element) {
            element.scrollIntoView({ behavior: "smooth", block: "center" });
            element.focus();
        }
    }
};
</script>

