<template>
    <div class="flex items-center gap-3">
        <label v-if="showLabel" class="text-sm text-gray-600">
            {{ label }}
        </label>
        <select
            :value="currentSort"
            @change="handleSortChange"
            :class="selectClass"
        >
            <option
                v-for="option in options"
                :key="option.value"
                :value="option.value"
            >
                {{ option.label }}
            </option>
        </select>
    </div>
</template>

<script setup>
import { router } from "@inertiajs/vue3";

const props = defineProps({
    currentSort: {
        type: String,
        default: "default",
    },
    options: {
        type: Array,
        required: true,
        validator: (options) => {
            return options.every(
                (opt) => opt.hasOwnProperty("value") && opt.hasOwnProperty("label")
            );
        },
    },
    baseUrl: {
        type: String,
        required: true,
    },
    queryParams: {
        type: Object,
        default: () => ({}),
    },
    label: {
        type: String,
        default: "Řadit podle:",
    },
    showLabel: {
        type: Boolean,
        default: true,
    },
    selectClass: {
        type: String,
        default:
            "px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent",
    },
});

const emit = defineEmits(["change"]);

// Methods: Handle sort change
const handleSortChange = (event) => {
    const newSort = event.target.value;

    // Emit event for parent component handling
    emit("change", newSort);

    // Build params preserving existing query params
    const params = { ...props.queryParams };

    // Add sort if not default
    if (newSort && newSort !== "default") {
        params.sort = newSort;
    } else {
        // Remove sort if default
        delete params.sort;
    }

    // Navigate using Inertia
    router.get(props.baseUrl, params, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

