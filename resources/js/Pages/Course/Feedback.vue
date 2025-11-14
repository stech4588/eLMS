<template>
    <Head :title="'Feedback for ' + course.title" />

    <AuthenticatedLayout>
        <ReviewDetailModal :show="showModal" :review="selectedReview" @close="closeModal" />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 dark:bg-dark-bg-secondary dark:text-white">
                        <h1 class="text-3xl font-bold mb-4">Feedback for {{ course.title }}</h1>
                        
                        <div v-if="reviews.data.length > 0" class="mt-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comment</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-[#293E4C]">
                                        <tr v-for="review in reviews.data" :key="review.id">
                                            <td class="px-6 py-4 whitespace-nowrap">{{ review.user.name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ review.rating }} / 5</td>
                                            <td class="px-6 py-4">{{ truncate(review.comment, 10) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ formatDateTime(review.reviewed_at) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button @click="openModal(review)" class="text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-4 flex justify-between items-center">
                                <Link v-if="reviews.prev_page_url" :href="reviews.prev_page_url" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 rounded">Previous</Link>
                                <div class="text-sm text-gray-700 dark:text-gray-300">
                                    Page {{ reviews.current_page }} of {{ reviews.last_page }}
                                </div>
                                <Link v-if="reviews.next_page_url" :href="reviews.next_page_url" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 rounded">Next</Link>
                            </div>
                        </div>

                        <div v-else>
                            <p class="text-lg text-gray-500">There are no reviews for this course yet.</p>
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
import { ref } from 'vue';
import ReviewDetailModal from '@/Components/ReviewDetailModal.vue';

const props = defineProps({
    course: Object,
    reviews: Object, // Paginated reviews object
});

const showModal = ref(false);
const selectedReview = ref(null);

function openModal(review) {
    selectedReview.value = review;
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    selectedReview.value = null;
}

function truncate(text, wordCount) {
    if (!text) return '';
    const words = text.split(' ');
    if (words.length <= wordCount) {
        return text;
    }
    return words.slice(0, wordCount).join(' ') + '...';
}

function formatDateTime(dateTimeString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true };
    return new Date(dateTimeString).toLocaleString(undefined, options);
}
</script>
