<template>
    <Head title="Quiz Result" />
    <QuizResultPopup :show="true" :attempt="attempt" @close="goBack" />
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import QuizResultPopup from '@/Components/QuizResultPopup.vue';

const props = defineProps({
    attempt: {
        type: Object,
        required: true,
    },
});

function goBack() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        router.get('/dashboard');
    }
}
</script>
