<template>
    <nav :class="['mb-6', customClass]">
        <ol class="flex items-center space-x-2 text-sm text-gray-500">
            <li v-for="(item, index) in items" :key="index">
                <Link
                    v-if="item.href"
                    :href="item.href"
                    class="hover:text-gray-700 transition-colors"
                >
                    {{ item.label }}
                </Link>
                <span v-else class="text-gray-900">{{ item.label }}</span>
                <span v-if="index < items.length - 1" class="ml-2">/</span>
            </li>
        </ol>
    </nav>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    items: {
        type: Array,
        required: true,
        default: () => [],
    },
    customClass: {
        type: String,
        default: "",
    },
});
</script>

