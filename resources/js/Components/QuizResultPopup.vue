<template>
    <div v-if="show && attempt" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click.self="$emit('close')">
        <div class="relative w-full max-w-3xl p-6 sm:p-8 mx-4 bg-white rounded-lg shadow-xl dark:bg-gray-900 max-h-[95vh] overflow-y-auto">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Quiz Result: {{ attempt.quiz.title }}</h3>
                    <p class="mt-2 text-md text-gray-600 dark:text-gray-400">Here's how you performed on the quiz.</p>
                </div>
                <button @click="$emit('close')" class="text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg p-1.5 ml-auto inline-flex items-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>

            <div class="text-center p-6 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg mb-6">
                <p class="text-lg text-gray-700 dark:text-gray-300 mb-2">Your Score</p>
                <p class="text-5xl sm:text-6xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ attempt.score }} / {{ attempt.quiz.questions.length }}</p>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mt-4">
                    <div class="bg-indigo-600 dark:bg-indigo-400 h-2.5 rounded-full" :style="{ width: scorePercentage + '%' }"></div>
                </div>
                <p class="text-xl sm:text-2xl font-semibold mt-3" :class="scorePercentage >= 50 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400'">
                    {{ scorePercentage.toFixed(1) }}%
                </p>
            </div>

            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Review Your Answers</h2>
                <div v-for="(question, qIndex) in attempt.quiz.questions" :key="question.id"
                     class="mb-5 border rounded-xl p-4 sm:p-5"
                     :class="wasQuestionAnsweredCorrectly(question) ? 'border-green-300 bg-green-50 dark:border-green-600 dark:bg-green-900/20' : 'border-red-300 bg-red-50 dark:border-red-600 dark:bg-red-900/20'">
                    <p class="font-semibold text-lg text-gray-900 dark:text-gray-200 mb-3 break-words">{{ qIndex + 1 }}. {{ question.question_text }}</p>
                    <ul class="space-y-3">
                        <li v-for="answer in question.answers" :key="answer.id" class="flex items-center p-3 rounded-lg transition-colors duration-200"
                            :class="{
                                'bg-green-200 dark:bg-green-500/30 text-green-900 dark:text-green-200 font-semibold': answer.is_correct == 1,
                                'bg-red-200 dark:bg-red-500/30 text-red-900 dark:text-red-200': isUserAnswer(question.id, answer.id) && answer.is_correct != 1,
                                'bg-gray-100 dark:bg-gray-700/30 text-gray-800 dark:text-gray-300': !isUserAnswer(question.id, answer.id) && answer.is_correct != 1,
                            }">
                            <div class="w-6 h-6 mr-4 flex-shrink-0">
                                <svg v-if="answer.is_correct == 1" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-700 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <svg v-else-if="isUserAnswer(question.id, answer.id) && answer.is_correct != 1" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-700 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span :class="{'line-through': isUserAnswer(question.id, answer.id) && answer.is_correct != 1}">{{ answer.answer_text }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-6 text-right">
                <button @click="$emit('close')" class="inline-block bg-indigo-600 dark:bg-indigo-500 text-white font-bold py-2.5 px-6 rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-transform transform hover:scale-105 duration-300 shadow-lg">
                    Close
                </button>
            </div>
        </div>
    </div>
    
</template>

<script setup>
import { defineProps, computed } from 'vue';

const props = defineProps({
    show: Boolean,
    attempt: Object,
});

const scorePercentage = computed(() => {
    if (!props.attempt || !props.attempt.quiz || !props.attempt.quiz.questions || props.attempt.quiz.questions.length === 0) {
        return 0;
    }
    return (props.attempt.score / props.attempt.quiz.questions.length) * 100;
});

const getUserAnswerForQuestion = (questionId) => {
    if (!props.attempt || !props.attempt.answers) return null;
    return props.attempt.answers.find(a => a.question_id == questionId);
};

const isUserAnswer = (questionId, answerId) => {
    const userAnswer = getUserAnswerForQuestion(questionId);
    return userAnswer && userAnswer.answer_id == answerId;
};

const wasQuestionAnsweredCorrectly = (question) => {
    const userAnswer = getUserAnswerForQuestion(question.id);
    if (!userAnswer) return false;
    const correctAnswer = question.answers.find(answer => answer.is_correct == 1);
    return !!correctAnswer && userAnswer.answer_id === correctAnswer.id;
};
</script>


