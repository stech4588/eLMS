<template>
    <Link
        :href="href"
        class="block bg-transparent text-black/80 text-center justify-between border-none px-2 py-2 text-xs hover:bg-white/10 transition rounded-md"
    >
        <h3 class="text-xs font-semibold">{{ title }}</h3>
        <p class="text-[11px] opacity-70 mt-0.5">{{ description }}</p>
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    title: String,
    description: String,
    href: String,
})
</script>
