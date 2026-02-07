<template>
    <div>
        <!-- Filter Button -->
        <button
            @click="showFilterModal = true"
            :class="buttonClass"
        >
            <svg
                class="w-5 h-5 text-gray-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
                />
            </svg>
            <span class="text-sm font-medium text-gray-700">{{ buttonLabel }}</span>
            <span
                v-if="activeFiltersCount > 0"
                class="ml-1 px-2 py-0.5 text-xs font-semibold bg-primary-600 text-white rounded-full"
            >
                {{ activeFiltersCount }}
            </span>
        </button>

        <!-- Filter Modal -->
        <Modal
            v-model="showFilterModal"
            :title="modalTitle"
        >
            <div class="space-y-6">
                <!-- Dynamic filters from backend -->
                <div
                    v-for="filter in filters"
                    :key="filter.key"
                    class="space-y-2"
                >
                    <label class="block text-sm font-medium text-gray-700">
                        {{ filter.label }}
                    </label>
                    <select
                        v-if="filter.type === 'select'"
                        v-model="tempFilters[filter.key]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                        <option value="">Všechny</option>
                        <option
                            v-for="option in filter.options"
                            :key="option.value"
                            :value="option.value"
                        >
                            {{ option.label }}
                        </option>
                    </select>
                    <input
                        v-else-if="filter.type === 'range'"
                        type="number"
                        v-model.number="tempFilters[filter.key]"
                        :placeholder="filter.placeholder"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    />
                    <div
                        v-else-if="filter.type === 'checkbox'"
                        class="space-y-2"
                    >
                        <label
                            v-for="option in filter.options"
                            :key="option.value"
                            class="flex items-center gap-2"
                        >
                            <input
                                type="checkbox"
                                :value="option.value"
                                :checked="isCheckboxChecked(filter.key, option.value)"
                                @change="handleCheckboxChange(filter.key, option.value, $event)"
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                            />
                            <span class="text-sm text-gray-700">{{
                                option.label
                            }}</span>
                        </label>
                    </div>
                </div>

                <!-- Default filters if none provided -->
                <div v-if="filters.length === 0" class="text-sm text-gray-500">
                    {{ emptyMessage }}
                </div>
            </div>

            <template #footer>
                <div class="flex justify-end gap-3">
                    <button
                        @click="resetFilters"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                    >
                        {{ resetLabel }}
                    </button>
                    <button
                        @click="applyFilters"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors"
                    >
                        {{ applyLabel }}
                    </button>
                </div>
            </template>
        </Modal>
    </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { router } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";

const props = defineProps({
    filters: {
        type: Array,
        default: () => [],
    },
    currentFilters: {
        type: Object,
        default: () => ({}),
    },
    baseUrl: {
        type: String,
        required: true,
    },
    queryParams: {
        type: Object,
        default: () => ({}),
    },
    buttonLabel: {
        type: String,
        default: "Filtry",
    },
    buttonClass: {
        type: String,
        default:
            "flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors",
    },
    modalTitle: {
        type: String,
        default: "Filtry",
    },
    emptyMessage: {
        type: String,
        default: "Žádné filtry nejsou k dispozici.",
    },
    resetLabel: {
        type: String,
        default: "Resetovat",
    },
    applyLabel: {
        type: String,
        default: "Použít filtry",
    },
});

const emit = defineEmits(["apply", "reset"]);

const showFilterModal = ref(false);
const tempFilters = ref({});
const activeFilters = ref({ ...props.currentFilters });

// Computed: Active filters count
const activeFiltersCount = computed(() => {
    return Object.values(activeFilters.value).filter(
        (val) => val !== "" && val !== null && val !== undefined
    ).length;
});

// Watch: Initialize tempFilters when modal opens
watch(showFilterModal, (newValue) => {
    if (newValue) {
        tempFilters.value = { ...activeFilters.value };
        // Initialize checkbox filters as arrays
        props.filters.forEach((filter) => {
            if (filter.type === "checkbox") {
                if (
                    !tempFilters.value[filter.key] ||
                    !Array.isArray(tempFilters.value[filter.key])
                ) {
                    tempFilters.value[filter.key] = [];
                }
            }
        });
    }
});

// Methods: Check if checkbox is checked
const isCheckboxChecked = (key, value) => {
    if (!tempFilters.value[key] || !Array.isArray(tempFilters.value[key])) {
        return false;
    }
    return tempFilters.value[key].includes(value);
};

// Methods: Handle checkbox change
const handleCheckboxChange = (key, value, event) => {
    if (!tempFilters.value[key] || !Array.isArray(tempFilters.value[key])) {
        tempFilters.value[key] = [];
    }

    if (event.target.checked) {
        if (!tempFilters.value[key].includes(value)) {
            tempFilters.value[key].push(value);
        }
    } else {
        const index = tempFilters.value[key].indexOf(value);
        if (index > -1) {
            tempFilters.value[key].splice(index, 1);
        }
    }
};

// Methods: Apply filters
const applyFilters = () => {
    activeFilters.value = { ...tempFilters.value };
    showFilterModal.value = false;

    // Build params - copy queryParams but remove existing filter params
    const params = {};
    Object.keys(props.queryParams).forEach((key) => {
        // Skip filter params, we'll add them fresh
        if (!key.startsWith("filters[")) {
            params[key] = props.queryParams[key];
        }
    });

    // Add filters
    Object.keys(activeFilters.value).forEach((key) => {
        const value = activeFilters.value[key];
        if (value !== "" && value !== null && value !== undefined) {
            if (Array.isArray(value)) {
                params[`filters[${key}]`] = value;
            } else {
                params[`filters[${key}]`] = value;
            }
        }
    });

    // Emit event
    emit("apply", activeFilters.value);

    // Navigate using Inertia
    router.get(props.baseUrl, params, {
        preserveState: true,
        preserveScroll: true,
    });
};

// Methods: Reset filters
const resetFilters = () => {
    activeFilters.value = {};
    tempFilters.value = {};
    showFilterModal.value = false;

    // Build params without filters - copy queryParams but remove filter params
    const params = {};
    Object.keys(props.queryParams).forEach((key) => {
        // Skip filter params
        if (!key.startsWith("filters[")) {
            params[key] = props.queryParams[key];
        }
    });

    // Emit event
    emit("reset");

    // Navigate using Inertia
    router.get(props.baseUrl, params, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

