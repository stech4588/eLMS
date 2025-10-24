<template>
    <Head title="Quiz Result" />

    <AuthenticatedLayout>
        <div class="py-12 bg-gray-100 dark:bg-gray-800 min-h-screen">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Result Summary Card -->
                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg mb-8">
                    <div class="p-8 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Quiz Result: {{ attempt.quiz.title }}</h1>
                        <p class="text-md text-gray-600 dark:text-gray-400 mb-6">Here's how you performed on the quiz.</p>
                        
                        <div class="text-center p-8 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                            <p class="text-lg text-gray-700 dark:text-gray-300 mb-2">Your Score</p>
                            <p class="text-6xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ attempt.score }} / {{ attempt.quiz.questions.length }}</p>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mt-4">
                                <div class="bg-indigo-600 dark:bg-indigo-400 h-2.5 rounded-full" :style="{ width: scorePercentage + '%' }"></div>
                            </div>
                            <p class="text-2xl font-semibold mt-3" :class="scorePercentage >= 50 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400'">
                                {{ scorePercentage.toFixed(1) }}%
                            </p>
                            <p v-if="scorePercentage >= 80" class="mt-4 text-lg text-gray-800 dark:text-gray-300">Excellent work! You've mastered this topic.</p>
                            <p v-else-if="scorePercentage >= 50" class="mt-4 text-lg text-gray-800 dark:text-gray-300">Good job! A little more practice will make perfect.</p>
                            <p v-else class="mt-4 text-lg text-gray-800 dark:text-gray-300">Keep trying! Review the answers below to improve.</p>
                        </div>
                    </div>
                </div>

                <!-- Review Answers Section -->
                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-8 bg-white dark:bg-gray-900">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Review Your Answers</h2>
                        
                        <div v-for="(question, qIndex) in attempt.quiz.questions" :key="question.id" 
                             class="mb-6 border rounded-xl p-5"
                             :class="wasQuestionAnsweredCorrectly(question) ? 'border-green-300 bg-green-50 dark:border-green-600 dark:bg-green-900/20' : 'border-red-300 bg-red-50 dark:border-red-600 dark:bg-red-900/20'">
                            
                            <p class="font-semibold text-lg text-gray-900 dark:text-gray-200 mb-4">{{ qIndex + 1 }}. {{ question.question_text }}</p>
                            
                            <ul class="space-y-3">
                                <li v-for="answer in question.answers" :key="answer.id" class="flex items-center p-3 rounded-lg transition-colors duration-200"
                                    :class="{
                                        'bg-green-200 dark:bg-green-500/30 text-green-900 dark:text-green-200 font-semibold': answer.is_correct == 1,
                                        'bg-red-200 dark:bg-red-500/30 text-red-900 dark:text-red-200': isUserAnswer(question.id, answer.id) && answer.is_correct != 1,
                                        'bg-gray-100 dark:bg-gray-700/30 text-gray-800 dark:text-gray-300': !isUserAnswer(question.id, answer.id) && answer.is_correct != 1,
                                    }">
                                    
                                    <div class="w-6 h-6 mr-4 flex-shrink-0">
                                        <!-- Correct Answer Icon -->
                                        <svg v-if="answer.is_correct == 1" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-700 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <!-- User's Incorrect Answer Icon -->
                                        <svg v-else-if="isUserAnswer(question.id, answer.id) && answer.is_correct != 1" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-700 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <!-- Other Options Icon -->
                                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>

                                    <span :class="{'line-through': isUserAnswer(question.id, answer.id) && answer.is_correct != 1}">
                                        {{ answer.answer_text }}
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <div class="mt-8 text-center">
                            <Link :href="route('career.journey')" class="inline-block bg-indigo-600 dark:bg-indigo-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-transform transform hover:scale-105 duration-300 shadow-lg">
                                Back to My Career Journey
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { defineProps, computed } from 'vue';

const props = defineProps({
    attempt: {
        type: Object,
        required: true,
    },
});

const scorePercentage = computed(() => {
    if (!props.attempt.quiz.questions || props.attempt.quiz.questions.length === 0) {
        return 0;
    }
    return (props.attempt.score / props.attempt.quiz.questions.length) * 100;
});

const getUserAnswerForQuestion = (questionId) => {
    if (!props.attempt.answers) return null;
    return props.attempt.answers.find(a => a.question_id === questionId);
};

const isUserAnswer = (questionId, answerId) => {
    const userAnswer = getUserAnswerForQuestion(questionId);
    return userAnswer && userAnswer.answer_id === answerId;
};

const wasQuestionAnsweredCorrectly = (question) => {
    const userAnswer = getUserAnswerForQuestion(question.id);
    if (!userAnswer) {
        return false; // Question was not answered
    }
    const correctAnswer = question.answers.find(answer => answer.is_correct == 1);
    return correctAnswer && userAnswer.answer_id === correctAnswer.id;
};
</script>
