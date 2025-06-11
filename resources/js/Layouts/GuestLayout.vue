<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-white-100 pt-6 sm:justify-center sm:pt-0"
    >
        <div style="width:100%; display: flex; justify-content: center; align-items: center;">
            <!-- <Link href="/">
                <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />
            </Link> -->
            <img src="/images/MBM_Uni.png" alt="logo" class="" style=" width: 100px; height: 100px;">
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 "
        >
            <slot />
        </div>
    </div>
</template>
