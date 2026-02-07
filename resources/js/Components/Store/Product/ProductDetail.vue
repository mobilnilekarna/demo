<template>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- Product Images -->
        <div class="relative">
            <div v-if="currentImage" class="aspect-square rounded-2xl overflow-hidden bg-white relative shadow-lg flex items-center justify-center">
                <!-- Štítky v levém rohu obrázku -->
                <div v-if="product.badges && product.badges.length > 0" class="absolute top-2 left-2 z-10 flex flex-col items-start gap-1">
                    <span
                        v-for="badge in product.badges"
                        :key="badge"
                        :class="getBadgeClass(badge)"
                        class="inline-block px-2 py-1 text-xs font-semibold rounded-md shadow-md whitespace-nowrap"
                    >
                        {{ badge }}
                    </span>
                </div>
                <img
                    :src="currentImage"
                    :alt="product?.name || 'Produkt'"
                    class="max-w-[85%] max-h-[85%] object-contain cursor-pointer"
                    @click="openLightbox(getCurrentImageIndex())"
                />
                <!-- Swiper galerie absolutně na spodek -->
                <div v-if="product.images && product.images.length > 1" class="absolute bottom-0 left-0 right-0 z-10 bg-gradient-to-t from-black/30 via-black/10 to-transparent pb-4 pt-12 px-4">
                    <swiper
                        :modules="modules"
                        :slides-per-view="3"
                        :space-between="8"
                        :centesecondary-slides="product.images.length > 3"
                        :centesecondary-slides-bounds="true"
                        :navigation="product.images.length > 3"
                        :watch-overflow="true"
                        :grab-cursor="true"
                        :allow-touch-move="true"
                        :prevent-clicks="true"
                        :prevent-clicks-propagation="false"
                        :resistance="true"
                        :resistance-ratio="0.85"
                        class="product-gallery-swiper"
                        @slideChange="onSlideChange"
                    >
                        <swiper-slide
                            v-for="(image, index) in product.images"
                            :key="index"
                        >
                            <div
                                @click="currentImage = image"
                                class="w-full aspect-square rounded-lg overflow-hidden border-2 transition-all cursor-pointer"
                                :class="
                                    currentImage === image
                                        ? 'border-primary-500 ring-2 ring-primary-300'
                                        : 'border-white/50 hover:border-white'
                                "
                            >
                                <img
                                    :src="image"
                                    :alt="`${product.name} - obrázek ${index + 1}`"
                                    class="w-full h-full object-cover pointer-events-none"
                                />
                            </div>
                        </swiper-slide>
                    </swiper>
                </div>
            </div>
            <div v-else class="aspect-square rounded-2xl bg-gray-200 flex items-center justify-center shadow-lg">
                <span class="text-gray-400">Žádný obrázek</span>
            </div>
        </div>

        <!-- Product Info -->
        <div class="space-y-6">
            <!-- Category & Brand -->
            <div class="flex items-center space-x-4 text-sm text-gray-500">
                <span>{{ product.category?.name }}</span>
                <span>•</span>
                <span>{{ product.brand }}</span>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-900">
                {{ product.name }}
            </h1>

            <!-- Rating -->
            <div class="flex items-center space-x-2">
                <div class="flex items-center">
                    <svg
                        v-for="i in 5"
                        :key="i"
                        class="w-5 h-5"
                        :class="
                            i <= Math.floor(product.rating)
                                ? 'text-yellow-400'
                                : 'text-gray-300'
                        "
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                        />
                    </svg>
                </div>
                <span class="text-sm text-gray-600">
                    {{ product.rating }} ({{ product.reviews_count }} recenzí)
                </span>
            </div>

            <!-- Price -->
            <div class="flex items-baseline space-x-4">
                <span class="text-4xl font-bold text-gray-900">
                    {{ formatPrice(product.price) }} {{ product.currency }}
                </span>
                <span
                    v-if="product.original_price"
                    class="text-xl text-gray-500 line-through"
                >
                    {{ formatPrice(product.original_price) }} {{ product.currency }}
                </span>
                <span
                    v-if="product.original_price"
                    class="px-2 py-1 bg-secondary-100 text-secondary-800 text-sm font-semibold rounded-lg"
                >
                    -{{
                        Math.round(
                            ((product.original_price - product.price) /
                                product.original_price) *
                                100
                        )
                    }}%
                </span>
            </div>

            <!-- Description -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">
                    Popis produktu
                </h2>
                <p class="text-gray-600 leading-relaxed">
                    {{ product.description }}
                </p>
            </div>

            <!-- Stock Status -->
            <div
                class="flex items-center space-x-2"
                :class="product.in_stock ? 'text-primary-600' : 'text-secondary-600'"
            >
                <svg
                    v-if="product.in_stock"
                    class="w-5 h-5"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"
                    />
                </svg>
                <svg
                    v-else
                    class="w-5 h-5"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd"
                    />
                </svg>
                <span class="font-medium">
                    {{
                        product.in_stock
                            ? `Skladem (${product.stock_quantity} ks)`
                            : "Není skladem"
                    }}
                </span>
            </div>

            <!-- Quantity Selector (pouze pokud není v košíku) -->
            <div v-if="!isInBasketComputed" class="flex items-center space-x-4">
                <label class="text-sm font-medium text-gray-700">Množství:</label>
                <div class="flex items-center space-x-2">
                    <button
                        @click="decreaseQuantity"
                        :disabled="quantity <= 1"
                        class="w-10 h-10 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center font-semibold"
                    >
                        -
                    </button>
                    <span class="w-12 text-center font-medium text-lg">{{
                        quantity
                    }}</span>
                    <button
                        @click="increaseQuantity"
                        :disabled="quantity >= product.stock_quantity"
                        class="w-10 h-10 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center font-semibold"
                    >
                        +
                    </button>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col space-y-4">
                <!-- Pokud není v košíku -->
                <div v-if="!isInBasketComputed" class="flex space-x-4">
                    <button
                        :disabled="!product.in_stock || isLoading"
                        class="flex-1 bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold text-sm hover:bg-primary-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors flex items-center justify-center"
                        @click="handleAddToBasket"
                    >
                        <svg
                            v-if="!isLoading"
                            class="w-5 h-5 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8M17 18a2 2 0 100 4 2 2 0 000-4zM9 18a2 2 0 100 4 2 2 0 000-4z"
                            ></path>
                        </svg>
                        <svg
                            v-else
                            class="w-5 h-5 mr-2 animate-spin"
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
                        {{ isLoading ? "Přidávání..." : "Do košíku" }}
                    </button>
                    <button
                        @click="handleToggleWishlist"
                        :class="[
                            'px-6 py-3 border-2 rounded-lg font-semibold transition-all duration-200 flex items-center justify-center',
                            isInWishlistComputed
                                ? 'border-primary-500 bg-primary-500 text-white hover:bg-primary-600 hover:border-primary-600 shadow-md'
                                : 'border-gray-300 text-gray-600 hover:border-primary-400 hover:text-primary-600 hover:bg-primary-50'
                        ]"
                        :title="isInWishlistComputed ? 'Odebrat z oblíbených' : 'Přidat do oblíbených'"
                    >
                        <svg
                            class="w-6 h-6"
                            :fill="isInWishlistComputed ? 'currentColor' : 'none'"
                            :stroke="isInWishlistComputed ? 'currentColor' : 'currentColor'"
                            :stroke-width="isInWishlistComputed ? '0' : '2'"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Pokud je v košíku -->
                <div v-else-if="isInBasketComputed" class="space-y-3">
                    <div
                        class="flex items-center justify-between p-4 bg-primary-50 rounded-lg border border-primary-200"
                    >
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span class="text-sm font-medium text-primary-700">
                                V košíku: {{ getItemQuantity(product.id) }} ks
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button
                                @click="
                                    handleUpdateQuantity(
                                        getItemId(product.id),
                                        getItemQuantity(product.id) - 1
                                    )
                                "
                                class="w-8 h-8 rounded-full bg-primary-200 hover:bg-primary-300 flex items-center justify-center text-sm font-semibold"
                            >
                                -
                            </button>
                            <span class="w-8 text-center font-medium">{{
                                getItemQuantity(product.id)
                            }}</span>
                            <button
                                @click="
                                    handleUpdateQuantity(
                                        getItemId(product.id),
                                        getItemQuantity(product.id) + 1
                                    )
                                "
                                :disabled="
                                    getItemQuantity(product.id) >= product.stock_quantity
                                "
                                class="w-8 h-8 rounded-full bg-primary-200 hover:bg-primary-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center text-sm font-semibold"
                            >
                                +
                            </button>
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <button
                            @click="handleRemoveFromBasket"
                            class="flex-1 px-6 py-3 bg-secondary-600 text-white rounded-lg font-semibold text-sm hover:bg-secondary-700 transition-colors flex items-center justify-center"
                        >
                            <svg
                                class="w-5 h-5 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                ></path>
                            </svg>
                            Odebrat z košíku
                        </button>
                        <button
                            @click="handleToggleWishlist"
                            :class="[
                                'px-6 py-3 border-2 rounded-lg font-semibold transition-all duration-200 flex items-center justify-center space-x-2',
                                isInWishlistComputed
                                    ? 'border-primary-500 bg-primary-500 text-white hover:bg-primary-600 hover:border-primary-600 shadow-md'
                                    : 'border-gray-300 text-gray-600 hover:border-primary-400 hover:text-primary-600 hover:bg-primary-50'
                            ]"
                            :title="isInWishlistComputed ? 'Odebrat z oblíbených' : 'Přidat do oblíbených'"
                        >
                            <svg
                                class="w-6 h-6"
                                :fill="isInWishlistComputed ? 'currentColor' : 'none'"
                                :stroke="isInWishlistComputed ? 'currentColor' : 'currentColor'"
                                :stroke-width="isInWishlistComputed ? '0' : '2'"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                />
                            </svg>
                            <span>{{ isInWishlistComputed ? 'Odebrat z oblíbených' : 'Přidat do oblíbených' }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="border-t pt-6 space-y-3">
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            fill-rule="evenodd"
                            d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <span>Záruka: {{ product.warranty }}</span>
                </div>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <span>Dodání: {{ product.delivery_time }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Tabs -->
    <div class="border-t border-gray-200 mt-8">
        <!-- Tab Navigation -->
        <div class="flex border-b border-gray-200">
            <button
                @click="activeTab = 'popis'"
                :class="[
                    'flex items-center gap-2 px-6 py-4 text-sm font-medium border-b-2 transition-colors',
                    activeTab === 'popis'
                        ? 'border-primary-500 text-primary-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-file-text" viewBox="0 0 16 16">
                    <path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5M5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1z"/>
                    <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1"/>
                </svg>
                Popis
            </button>
            <button
                @click="activeTab = 'hodnoceni'"
                :class="[
                    'flex items-center gap-2 px-6 py-4 text-sm font-medium border-b-2 transition-colors',
                    activeTab === 'hodnoceni'
                        ? 'border-primary-500 text-primary-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                    <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z"/>
                </svg>
                Hodnocení
            </button>
            <button
                @click="activeTab = 'dotazy'"
                :class="[
                    'flex items-center gap-2 px-6 py-4 text-sm font-medium border-b-2 transition-colors',
                    activeTab === 'dotazy'
                        ? 'border-primary-500 text-primary-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16">
                    <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                    <path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9 9 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.4 10.4 0 0 1-.524 2.318l-.003.011a11 11 0 0 1-.244.637c-.079.186.074.394.273.362a22 22 0 0 0 .693-.125m.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6-3.004 6-7 6a8 8 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a11 11 0 0 0 .398-1.002"/>
                </svg>
                Dotazy
            </button>
        </div>

        <!-- Tab Content -->
        <div class="py-6">
            <!-- Popis Tab -->
            <div v-if="activeTab === 'popis'" class="prose max-w-none">
                <div v-if="product.full_description" v-html="product.full_description" class="text-gray-600 leading-relaxed"></div>
                <p v-else class="text-gray-600 leading-relaxed">
                    {{ product.description || defaultDescription }}
                </p>
            </div>

            <!-- Hodnocení Tab -->
            <div v-if="activeTab === 'hodnoceni'" class="space-y-6">
                <!-- Souhrn hodnocení -->
                <div class="flex flex-col md:flex-row items-start md:items-center gap-6 p-6 bg-gray-50 rounded-xl">
                    <div class="text-center min-w-[120px]">
                        <div class="text-5xl font-bold text-gray-900">{{ product.rating || 0 }}</div>
                        <div class="flex items-center justify-center mt-2">
                            <svg
                                v-for="i in 5"
                                :key="i"
                                class="w-5 h-5"
                                :class="i <= Math.floor(product.rating || 0) ? 'text-yellow-400' : 'text-gray-300'"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <div class="text-sm text-gray-500 mt-2">{{ product.reviews_count || 0 }} recenzí</div>
                    </div>
                    <div class="flex-1 w-full space-y-2">
                        <div v-for="stars in [5, 4, 3, 2, 1]" :key="stars" class="flex items-center gap-3">
                            <span class="text-sm text-gray-600 w-16 flex items-center gap-1">
                                {{ stars }}
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </span>
                            <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-yellow-400 rounded-full transition-all duration-300" :style="{ width: getReviewPercentage(stars) + '%' }"></div>
                            </div>
                            <span class="text-sm text-gray-500 w-12 text-right">{{ getReviewPercentage(stars) }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Seznam recenzí -->
                <div v-if="product.reviews && product.reviews.length > 0" class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">Recenze zákazníků</h3>

                    <div
                        v-for="review in displayedReviews"
                        :key="review.id"
                        class="p-4 bg-white border border-gray-200 rounded-lg hover:shadow-sm transition-shadow"
                    >
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                                    <span class="text-primary-600 font-semibold text-sm">{{ getInitials(review.author) }}</span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-900">{{ review.author }}</span>
                                        <span v-if="review.verified" class="inline-flex items-center gap-1 text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01zm.287 5.984-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708.708"/>
                                            </svg>
                                            Ověřený nákup
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ review.date }}</div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg
                                    v-for="i in 5"
                                    :key="i"
                                    class="w-4 h-4"
                                    :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-300'"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 leading-relaxed">{{ review.text }}</p>
                        <div class="flex items-center gap-4 mt-3 pt-3 border-t border-gray-100">
                            <button class="flex items-center gap-1 text-sm text-gray-500 hover:text-primary-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8.864.046C7.908-.193 7.02.53 6.956 1.466c-.072 1.051-.23 2.016-.428 2.59-.125.36-.479 1.013-1.04 1.639-.557.623-1.282 1.178-2.131 1.41C2.685 7.288 2 7.87 2 8.72v4.001c0 .845.682 1.464 1.448 1.545 1.07.114 1.564.415 2.068.723l.048.03c.272.165.578.348.97.484.397.136.861.217 1.466.217h3.5c.937 0 1.599-.477 1.934-1.064a1.86 1.86 0 0 0 .254-.912c0-.152-.023-.312-.077-.464.201-.263.38-.578.488-.901.11-.33.172-.762.004-1.149.069-.13.12-.269.159-.403.077-.27.113-.568.113-.857 0-.288-.036-.585-.113-.856a2 2 0 0 0-.138-.362 1.9 1.9 0 0 0 .234-1.734c-.206-.592-.682-1.1-1.2-1.272-.847-.282-1.803-.276-2.516-.211a10 10 0 0 0-.443.05 9.4 9.4 0 0 0-.062-4.509A1.38 1.38 0 0 0 9.125.111zM11.5 14.721H8c-.51 0-.863-.069-1.14-.164-.281-.097-.506-.228-.776-.393l-.04-.024c-.555-.339-1.198-.731-2.49-.868-.333-.036-.554-.29-.554-.55V8.72c0-.254.226-.543.62-.65 1.095-.3 1.977-.996 2.614-1.708.635-.71 1.064-1.475 1.238-1.978.243-.7.407-1.768.482-2.85.025-.362.36-.594.667-.518l.262.066c.16.04.258.143.288.255a8.34 8.34 0 0 1-.145 4.725.5.5 0 0 0 .595.644l.003-.001.014-.003.058-.014a9 9 0 0 1 1.036-.157c.663-.06 1.457-.054 2.11.164.175.058.45.3.57.65.107.308.087.67-.266 1.022l-.353.353.353.354c.043.043.105.141.154.315.048.167.075.37.075.581 0 .212-.027.414-.075.582-.05.174-.111.272-.154.315l-.353.353.353.354c.047.047.109.177.005.488a2.2 2.2 0 0 1-.505.805l-.353.353.353.354c.006.005.041.05.041.17a.9.9 0 0 1-.121.416c-.165.288-.503.56-1.066.56z"/>
                                </svg>
                                Užitečné ({{ review.helpful_count }})
                            </button>
                        </div>
                    </div>

                    <!-- Tlačítko pro zobrazení více recenzí -->
                    <div v-if="product.reviews.length > reviewsPerPage && !showAllReviews" class="text-center pt-4">
                        <button
                            @click="showAllReviews = true"
                            class="inline-flex items-center gap-2 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-medium hover:border-primary-500 hover:text-primary-600 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
                            </svg>
                            Zobrazit všechny recenze ({{ product.reviews.length }})
                        </button>
                    </div>
                </div>

                <!-- Žádné recenze -->
                <p v-else class="text-gray-500 text-center py-8">Zatím nejsou k dispozici žádné recenze.</p>
            </div>

            <!-- Dotazy Tab -->
            <div v-if="activeTab === 'dotazy'" class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-6 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-chat-dots mx-auto text-gray-400 mb-4" viewBox="0 0 16 16">
                        <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                        <path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9 9 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.4 10.4 0 0 1-.524 2.318l-.003.011a11 11 0 0 1-.244.637c-.079.186.074.394.273.362a22 22 0 0 0 .693-.125m.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6-3.004 6-7 6a8 8 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a11 11 0 0 0 .398-1.002"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Máte dotaz k produktu?</h3>
                    <p class="text-gray-600 mb-4">Zeptejte se nás a my vám rádi odpovíme.</p>
                    <button @click="showContactModal = true" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>
                        Položit dotaz
                    </button>
                </div>
                <p class="text-gray-500 text-center">K tomuto produktu zatím nebyl položen žádný dotaz.</p>
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    <Modal v-model="showContactModal" title="Položit dotaz k produktu">
        <ContactForm :product="product" />
    </Modal>

    <!-- Lightbox -->
    <EasyLightbox
        :visible="visibleRef"
        :imgs="images"
        :index="indexRef"
        @hide="visibleRef = false"
    />
</template>

<script setup>
import { ref, computed } from "vue";
import { useBasket } from "@/Composables/useBasket";
import { useWishList } from "@/Composables/useWishList";
import { Link } from "@inertiajs/vue3";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import EasyLightbox from "vue-easy-lightbox";
import AddToBasketModal from "@/Components/Store/Product/AddToBasketModal.vue";
import Modal from "@/Components/Modal.vue";
import ContactForm from "@/Components/ContactForm.vue";

const props = defineProps({
    product: {
        type: Object,
        required: true,
        default: () => ({}),
    },
});

const { openModal, isModalOpen, addToBasket, removeFromBasket, updateQuantity, getItemId, getItemQuantity, isInBasket } = useBasket();
const { toggleWishlist, isInWishlist } = useWishList();

const currentImage = ref(props.product?.images?.[0] || props.product?.image || '');

// Swiper modules
const modules = [Navigation];

// Local state
const quantity = ref(1);
const isLoading = ref(false);
const showModal = ref(false);
const showContactModal = ref(false);
const activeTab = ref('popis');
const showAllReviews = ref(false);
const reviewsPerPage = 5;

// Default description (Lorem ipsum 6 lines)
const defaultDescription = `Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.

Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.

Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. Nullam varius, turpis et commodo pharetra, est eros bibendum elit, nec luctus magna felis sollicitudin mauris. Integer in mauris eu nibh euismod gravida.

Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Aenean lacinia bibendum nulla sed consectetur.

Maecenas sed diam eget risus varius blandit sit amet non magna. Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper.

Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod.`;

// Lightbox state
const visibleRef = ref(false);
const indexRef = ref(0);

// Computed
const isInBasketComputed = isInBasket(props.product?.id);
const isInWishlistComputed = isInWishlist(props.product?.id);

// Images array for lightbox
const images = computed(() => {
    if (props.product?.images && props.product.images.length > 0) {
        return props.product.images.map(img => ({ src: img, title: props.product?.name || 'Produkt' }));
    }
    if (props.product?.image) {
        return [{ src: props.product.image, title: props.product?.name || 'Produkt' }];
    }
    return [];
});

// Zobrazené recenze (s omezeným počtem nebo všechny)
const displayedReviews = computed(() => {
    if (!props.product?.reviews) return [];
    return showAllReviews.value
        ? props.product.reviews
        : props.product.reviews.slice(0, reviewsPerPage);
});

const getCurrentImageIndex = () => {
    if (!props.product?.images || props.product.images.length === 0) return 0;
    const index = props.product.images.findIndex(img => img === currentImage.value);
    return index >= 0 ? index : 0;
};

const formatPrice = (price) => {
    return new Intl.NumberFormat("cs-CZ").format(price);
};

const getBadgeClass = (badge) => {
    const badgeClasses = {
        'TIP': 'bg-yellow-500 text-white',
        'Doporučujeme': 'bg-blue-500 text-white',
        'Doprava zdarma': 'bg-primary-500 text-white',
    };
    return badgeClasses[badge] || 'bg-gray-500 text-white';
};

// Methods
const increaseQuantity = () => {
    if (quantity.value < (props.product?.stock_quantity || props.product?.stock || 0)) {
        quantity.value++;
    }
};

const decreaseQuantity = () => {
    if (quantity.value > 1) {
        quantity.value--;
    }
};

const handleAddToBasket = async () => {
    if (!props.product || props.product.stock === 0) return;

    isLoading.value = true;

    try {
        await addToBasket(
            {
                id: props.product.id,
                name: props.product.name,
                price: props.product.price,
                quantity: quantity.value,
                image: props.product.images?.[0] || props.product.image || '',
            },
            quantity.value
        );

        quantity.value = 1;

        if(!isModalOpen.value)
            openModal();

        console.log("✅ Product added to basket successfully");
    } catch (error) {
        console.error("❌ Error adding product to basket:", error);
    } finally {
        isLoading.value = false;
    }
};

const handleRemoveFromBasket = async () => {
    const basketItemId = getItemId(props.product.id);
    if (basketItemId) {
        await removeFromBasket(basketItemId);
        console.log("✅ Product removed from basket");
    } else {
        console.error("❌ Basket item ID not found for product:", props.product.id);
    }
};

const handleUpdateQuantity = async (id, newQuantity) => {
    if (newQuantity <= 0) {
        await handleRemoveFromBasket();
    } else {
        await updateQuantity(id, newQuantity);
        console.log("✅ Product quantity updated");
    }
};

const onSlideChange = (swiper) => {
    // Volitelně můžete přepnout hlavní obrázek při změně slide
    // const activeIndex = swiper.activeIndex;
    // if (props.product?.images?.[activeIndex]) {
    //     currentImage.value = props.product.images[activeIndex];
    // }
};

const openLightbox = (index) => {
    if (images.value.length > 0) {
        indexRef.value = index >= 0 ? index : 0;
        visibleRef.value = true;
    }
};

const handleToggleWishlist = () => {
    toggleWishlist({
        id: props.product.id,
        name: props.product.name,
        price: props.product.price,
        image: props.product.images?.[0] || props.product.image || '',
        slug: props.product.slug || null,
    });
};

const getReviewPercentage = (stars) => {
    // Použije data z backendu pokud jsou k dispozici
    if (props.product?.rating_stats?.percentages) {
        return props.product.rating_stats.percentages[stars] || 0;
    }
    return 0;
};

const getInitials = (name) => {
    if (!name) return '?';
    const parts = name.split(' ');
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return name.substring(0, 2).toUpperCase();
};

</script>

<style scoped>
.product-gallery-swiper {
    padding: 0 40px !important;
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
    touch-action: pan-x pan-y;
    overflow: hidden !important;
}

.product-gallery-swiper :deep(.swiper) {
    overflow: hidden;
    touch-action: pan-x pan-y;
}

.product-gallery-swiper :deep(.swiper-wrapper) {
    display: flex;
    align-items: center;
    justify-content: center;
    touch-action: pan-x pan-y;
}

.product-gallery-swiper :deep(.swiper-slide) {
    display: flex;
    justify-content: center;
}

.product-gallery-swiper :deep(.swiper-button-next),
.product-gallery-swiper :deep(.swiper-button-prev) {
    color: white;
    background: rgba(255, 255, 255, 0.2);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
    margin-top: 0;
    z-index: 20;
}

.product-gallery-swiper :deep(.swiper-button-next) {
    right: 0;
}

.product-gallery-swiper :deep(.swiper-button-prev) {
    left: 0;
}

.product-gallery-swiper :deep(.swiper-button-next:after),
.product-gallery-swiper :deep(.swiper-button-prev:after) {
    font-size: 16px;
}

.product-gallery-swiper :deep(.swiper-button-next:hover),
.product-gallery-swiper :deep(.swiper-button-prev:hover) {
    background: rgba(255, 255, 255, 0.3);
}

.product-gallery-swiper :deep(.swiper-slide) {
    height: auto;
    width: auto;
}

.product-gallery-swiper :deep(.swiper-slide button) {
    pointer-events: auto;
}
</style>

