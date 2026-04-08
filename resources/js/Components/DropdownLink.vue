<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
        default: false,
    },
});

const classes = computed(() => ([
    'block w-full px-4 py-2 text-start text-sm leading-5 transition duration-150 ease-in-out focus:outline-none',
    props.active
        ? 'bg-gray-100 text-[#1C355E] font-semibold dark:bg-dark-bg-tertiary dark:text-[#93c5fd]'
        : 'text-gray-700 hover:bg-gray-100 focus:bg-gray-100 dark:text-dark-text-secondary dark:hover:bg-dark-bg-tertiary dark:focus:bg-dark-bg-tertiary',
].join(' ')));
</script>

<template>
    <Link
        :href="href"
        :class="classes"
    >
        <slot />
    </Link>
</template>
