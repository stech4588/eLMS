<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click.self="$emit('close')">
        <div class="relative w-full max-w-2xl p-8 mx-4 bg-white rounded-lg shadow-xl dark:bg-gray-900 max-h-[95vh] overflow-y-auto">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ quiz.title }}</h3>
                    <p class="mt-2 text-md text-gray-600 dark:text-gray-400">{{ quiz.description }}</p>
                </div>
                <button @click="$emit('close')" class="text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg p-1.5 ml-auto inline-flex items-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>

            <!-- Progress Bar -->
            <div class="mb-6">
                <div class="flex justify-between mb-1">
                    <span class="text-base font-medium text-indigo-700 dark:text-white">Progress</span>
                    <span class="text-sm font-medium text-indigo-700 dark:text-white">{{ answeredQuestionsCount }} of {{ quiz.questions.length }} answered</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 overflow-hidden">
                    <div class="bg-indigo-600 h-2.5 rounded-full" :style="{ width: clampedProgress + '%' }"></div>
                </div>
            </div>

            <form @submit.prevent="submitQuiz">
                <div class="max-h-[50vh] overflow-y-auto pr-4 -mr-4">
                    <div v-for="(question, qIndex) in quiz.questions" :key="question.id" class="mb-6 last:mb-0">
                        <p class="font-semibold text-lg text-gray-900 dark:text-gray-200 mb-4">{{ qIndex + 1 }}. {{ question.question_text }}</p>
                        <div class="space-y-3">
                             <label v-for="answer in question.answers" :key="answer.id" 
                                   class="flex items-center p-4 rounded-lg border cursor-pointer transition-colors duration-200"
                                   :class="{
                                       'bg-indigo-100 dark:bg-indigo-900/50 border-indigo-500 dark:border-indigo-400': form.answers[question.id] === answer.id,
                                       'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700': form.answers[question.id] !== answer.id
                                   }">
                                <input type="radio" :name="'question_' + question.id" :value="answer.id" v-model="form.answers[question.id]" class="sr-only">
                                <span class="w-5 h-5 mr-4 border-2 rounded-full flex-shrink-0"
                                      :class="{
                                          'bg-indigo-600 border-indigo-600': form.answers[question.id] === answer.id,
                                          'border-gray-400': form.answers[question.id] !== answer.id
                                      }"></span>
                                <span class="text-gray-800 dark:text-gray-300">{{ answer.answer_text }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                     <button @click="$emit('close')" type="button" class="px-6 py-2.5 text-gray-700 bg-gray-200 dark:bg-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 font-semibold">
                        Close
                    </button>
                    <button type="submit" 
                            :disabled="form.processing"
                            class="inline-block bg-indigo-600 text-white font-bold py-2.5 px-6 rounded-lg hover:bg-indigo-700 transition-transform transform hover:scale-105 duration-300 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                        Submit Quiz
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { computed, reactive, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    show: Boolean,
    quiz: Object,
});

const emit = defineEmits(['close', 'completed']);

const form = reactive({
    processing: false,
    answers: {},
});

const answeredQuestionsCount = computed(() => {
    if (!props.quiz || !props.quiz.questions) return 0;
    return props.quiz.questions.reduce((acc, q) => acc + (form.answers[q.id] !== undefined && form.answers[q.id] !== null ? 1 : 0), 0);
});

const progressPercentage = computed(() => {
    if (!props.quiz || !props.quiz.questions || props.quiz.questions.length === 0) {
        return 0;
    }
    return (answeredQuestionsCount.value / props.quiz.questions.length) * 100;
});

const clampedProgress = computed(() => Math.max(0, Math.min(100, progressPercentage.value)));

// Reset answers whenever popup opens or quiz changes
watch(() => props.show, (isOpen) => {
    if (isOpen) {
        form.answers = {};
    }
});
watch(() => props.quiz && props.quiz.id, () => {
    form.answers = {};
});

const submitQuiz = async () => {
    if (!props.quiz) return;
    try {
        form.processing = true;
        const response = await axios.post(route('quiz.attempt', { quiz: props.quiz.id }), {
            answers: form.answers,
            return_json: true,
        }, {
            headers: { 'Accept': 'application/json' }
        });
        emit('completed', response.data);
        emit('close');
    } catch (e) {
        // Optionally handle/emit error
        console.error('Quiz submit failed', e?.response?.data || e?.message);
    } finally {
        form.processing = false;
    }
};
</script>
