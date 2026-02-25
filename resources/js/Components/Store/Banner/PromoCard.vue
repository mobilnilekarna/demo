<template>
    <Link
        :href="banner.url || '#'"
        class="block h-full"
    >
        <div
            :class="[
                'relative rounded-xl overflow-hidden h-full flex flex-col transition-all duration-300 hover:shadow-xl hover:-translate-y-1',
                getBackgroundClass(banner.variant),
            ]"
        >
            <!-- Pattern overlay -->
            <div
                v-if="banner.variant === 'light-green' || banner.variant === 'product'"
                class="absolute top-0 left-0 right-0 h-24 opacity-20 pointer-events-none polka-dot-pattern"
            ></div>

            <!-- Header badge (např. "Pilulka Friday") - skryjeme pro text-only karty -->
            <div
                v-if="banner.headerBadge && (banner.image || banner.icon || banner.price || banner.discount)"
                class="px-5 pt-5 pb-2 relative z-10"
            >
                <span
                    :class="[
                        'text-sm font-semibold',
                        getHeaderBadgeClass(banner.variant),
                    ]"
                >
                    {{ banner.headerBadge }}
                </span>
            </div>

            <!-- Time-limited badge (např. "pouze do neděle") -->
            <div
                v-if="banner.timeLimit"
                class="absolute top-4 right-4 z-10"
            >
                <span class="bg-pink-400 text-pink-900 text-xs font-semibold px-3 py-1.5 rounded-full">
                    {{ banner.timeLimit }}
                </span>
            </div>

            <!-- New product badge -->
            <div
                v-if="banner.isNew"
                class="absolute top-4 left-4 z-10"
            >
                <span class="bg-primary-700 text-white text-xs font-semibold px-3 py-1.5 rounded-full">
                    {{ banner.newLabel || 'novinka na Pilulce' }}
                </span>
            </div>

            <!-- Content area -->
            <div class="flex-1 px-5 pb-6 flex flex-col relative z-10">
                <!-- Special layout for text-only cards (like first card) -->
                <template v-if="!banner.image && !banner.icon && !banner.discount && !banner.price">
                    <!-- Small header badge for text-only cards -->
                    <div
                        v-if="banner.headerBadge"
                        class="mb-2"
                    >
                        <span
                            :class="[
                                'text-sm font-semibold',
                                getHeaderBadgeClass(banner.variant),
                            ]"
                        >
                            {{ banner.headerBadge }}
                        </span>
                    </div>
                    <h3
                        :class="[
                            'font-bold mb-4 text-3xl leading-tight',
                            getTitleClass(banner.variant),
                        ]"
                    >
                        {{ banner.title }}
                    </h3>
                    <p
                        v-if="banner.description"
                        :class="[
                            'text-base mb-8 flex-1 leading-relaxed',
                            getDescriptionClass(banner.variant),
                        ]"
                    >
                        <span v-html="formatDescription(banner.description)"></span>
                    </p>
                </template>

                <!-- Image or Icon layout -->
                <template v-else>
                    <div
                        v-if="banner.image || banner.icon"
                        :class="[
                            'relative mb-5 flex-shrink-0',
                            banner.variant === 'shipping' ? 'h-48' : 'h-72',
                        ]"
                    >
                        <img
                            v-if="banner.image"
                            :src="banner.image"
                            :alt="banner.title"
                            class="w-full h-full object-cover rounded-lg"
                        />
                        <div
                            v-else-if="banner.icon"
                            class="w-full h-full flex items-center justify-center relative"
                        >
                            <!-- Checkmark circle above icon -->
                            <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-2">
                                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <component
                                :is="banner.icon"
                                :class="[
                                    'w-28 h-28',
                                    getIconClass(banner.variant),
                                ]"
                            />
                        </div>

                        <!-- Price overlay - circular badge -->
                        <div
                            v-if="banner.price"
                            :class="[
                                'absolute bg-primary-700 text-white rounded-full px-4 py-2.5 min-w-[75px] flex flex-col items-center justify-center shadow-lg',
                                banner.priceOld ? 'bottom-3 right-3' : 'bottom-3 right-3',
                            ]"
                        >
                            <span class="text-sm font-bold leading-tight">{{ banner.price }}</span>
                            <span
                                v-if="banner.priceOld"
                                class="text-xs line-through opacity-75 mt-0.5"
                            >
                                {{ banner.priceOld }}
                            </span>
                        </div>
                    </div>

                    <!-- Title -->
                    <h3
                        :class="[
                            'font-bold mb-2 text-lg leading-tight',
                            getTitleClass(banner.variant),
                        ]"
                    >
                        {{ banner.title }}
                    </h3>

                    <!-- Description -->
                    <p
                        v-if="banner.description"
                        :class="[
                            'text-sm mb-4 flex-1 leading-relaxed',
                            getDescriptionClass(banner.variant),
                        ]"
                    >
                        {{ banner.description }}
                    </p>

                    <!-- Discount info -->
                    <div
                        v-if="banner.discount"
                        :class="[
                            'text-sm font-semibold mb-4',
                            getTitleClass(banner.variant),
                        ]"
                    >
                        {{ banner.discount }}
                    </div>
                </template>

                <!-- CTA Button -->
                <button
                    :class="[
                        'mt-auto w-full py-3 px-4 rounded-lg font-semibold text-sm transition-colors',
                        getButtonClass(banner.variant),
                    ]"
                >
                    {{ banner.buttonText || 'Objevit' }}
                </button>
            </div>
        </div>
    </Link>
</template>

<script setup>
const props = defineProps({
    banner: {
        type: Object,
        required: true,
    },
});

const getBackgroundClass = (variant) => {
    const variants = {
        'light-green': 'bg-primary-50',
        'dark-teal': 'bg-teal-800',
        'shipping': 'bg-teal-800',
        'product': 'bg-primary-50',
        'beige': 'bg-stone-50',
        'default': 'bg-primary-50',
    };
    return variants[variant] || variants.default;
};

const getTitleClass = (variant) => {
    const variants = {
        'light-green': 'text-primary-800',
        'dark-teal': 'text-white',
        'shipping': 'text-white',
        'product': 'text-primary-800',
        'beige': 'text-primary-800',
        'default': 'text-primary-800',
    };
    return variants[variant] || variants.default;
};

const getDescriptionClass = (variant) => {
    const variants = {
        'light-green': 'text-primary-700',
        'dark-teal': 'text-white opacity-90',
        'shipping': 'text-white opacity-90',
        'product': 'text-primary-700',
        'beige': 'text-primary-700',
        'default': 'text-primary-700',
    };
    return variants[variant] || variants.default;
};

const getHeaderBadgeClass = (variant) => {
    const variants = {
        'light-green': 'text-primary-800',
        'dark-teal': 'text-white',
        'shipping': 'text-white',
        'product': 'text-primary-800',
        'beige': 'text-primary-800',
        'default': 'text-primary-800',
    };
    return variants[variant] || variants.default;
};

const getButtonClass = (variant) => {
    const variants = {
        'light-green': 'bg-primary-700 hover:bg-primary-800 text-white',
        'dark-teal': 'bg-white hover:bg-gray-100 text-teal-800',
        'shipping': 'bg-white hover:bg-gray-100 text-teal-800',
        'product': 'bg-primary-700 hover:bg-primary-800 text-white',
        'beige': 'bg-primary-50 hover:bg-primary-100 text-primary-800',
        'default': 'bg-primary-700 hover:bg-primary-800 text-white',
    };
    return variants[variant] || variants.default;
};

const getIconClass = (variant) => {
    const variants = {
        'dark-teal': 'text-white',
        'shipping': 'text-white',
        'default': 'text-primary-700',
    };
    return variants[variant] || variants.default;
};

const formatDescription = (text) => {
    if (!text) return '';
    // Make "Black Friday" bold if present
    return text.replace(/(Black Friday)/gi, '<strong>$1</strong>');
};
</script>

<style scoped>
/* Polka dot pattern */
.polka-dot-pattern {
    background-image: radial-gradient(circle, rgba(255, 255, 255, 0.9) 2px, transparent 2px);
    background-size: 24px 24px;
    background-position: 0 0;
}
</style>

