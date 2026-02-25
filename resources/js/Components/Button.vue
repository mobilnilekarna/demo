<template>
    <button
        :type="type"
        :class="buttonClasses"
        :disabled="disabled"
        @click="$emit('click', $event)"
    >
        <slot />
    </button>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    type: {
        type: String,
        default: "button",
    },
    variant: {
        type: String,
        default: "primary",
        validator: (value) =>
            ["primary", "secondary", "danger"].includes(value),
    },
    size: {
        type: String,
        default: "md",
        validator: (value) => ["sm", "md", "lg"].includes(value),
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["click"]);

const buttonClasses = computed(() => {
    const baseClasses = "btn";

    const variantClasses = {
        primary: "btn-primary",
        secondary: "btn-secondary",
        danger: "btn-danger",
    };

    const sizeClasses = {
        sm: "px-3 py-1.5 text-sm",
        md: "px-4 py-2 text-base",
        lg: "px-6 py-3 text-lg",
    };

    const disabledClasses = props.disabled
        ? "opacity-50 cursor-not-allowed"
        : "";

    return [
        baseClasses,
        variantClasses[props.variant],
        sizeClasses[props.size],
        disabledClasses,
    ].join(" ");
});
</script>
