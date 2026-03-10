<template>
    <Head title="Take Quiz" />

    <AuthenticatedLayout>
        <div class="py-12 bg-gray-100 dark:bg-gray-800 min-h-screen">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-8 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ quiz.title }}</h1>
                        <p class="text-md text-gray-600 dark:text-gray-400 mb-6">{{ quiz.description }}</p>

                        <!-- Progress Bar -->
                        <div class="mb-8">
                            <div class="flex justify-between mb-1">
                                <span class="text-base font-medium text-gray-900 dark:text-white">Progress</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ answeredQuestionsCount }} of {{ quiz.questions.length }} answered</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-[#1C355E] h-2.5 rounded-full" :style="{ width: progressPercentage + '%' }"></div>
                            </div>
                        </div>

                        <form @submit.prevent="submitQuiz">
                            <div v-for="(question, qIndex) in quiz.questions" :key="question.id" class="mb-8 p-6 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <p class="font-semibold text-lg text-gray-900 dark:text-gray-200 mb-4">{{ qIndex + 1 }}. {{ question.question_text }}</p>
                                <div class="space-y-3">
                                    <label v-for="answer in question.answers" :key="answer.id" 
                                           class="flex items-center p-4 rounded-lg border cursor-pointer transition-colors duration-200"
                                           :class="{
                                               'bg-green-50 dark:bg-green-900/30 border-green-500 dark:border-green-400': form.answers[question.id] === answer.id,
                                               'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700': form.answers[question.id] !== answer.id
                                           }">
                                        <input type="radio" :name="'question_' + question.id" :value="answer.id" v-model="form.answers[question.id]" class="hidden">
                                        <span class="w-5 h-5 mr-4 border-2 rounded-full flex-shrink-0"
                                              :class="{
                                                  'bg-[#1C355E] border-[#1C355E]': form.answers[question.id] === answer.id,
                                                  'border-gray-400': form.answers[question.id] !== answer.id
                                              }"></span>
                                        <span class="text-gray-800 dark:text-gray-300">{{ answer.answer_text }}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="mt-8 text-center">
                                <button type="submit" 
                                        :disabled="form.processing"
                                        class="inline-block bg-[#1C355E] text-white font-bold py-3 px-8 rounded-lg hover:bg-[#254a7a] transition-transform transform hover:scale-105 duration-300 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                    Submit Quiz
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { defineProps, computed } from 'vue';

const props = defineProps({
    quiz: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    answers: {},
});

const answeredQuestionsCount = computed(() => {
    return Object.values(form.answers).filter(val => val !== null && val !== undefined).length;
});

const progressPercentage = computed(() => {
    if (!props.quiz.questions || props.quiz.questions.length === 0) {
        return 0;
    }
    return (answeredQuestionsCount.value / props.quiz.questions.length) * 100;
});

const submitQuiz = () => {
    form.post(route('quiz.attempt', { quiz: props.quiz.id }));
};
</script>
