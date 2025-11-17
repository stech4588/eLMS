<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AiChatbot from '@/Components/AiChatbot.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const props = defineProps({
    supportTopics: Array,
});

const activeTopic = ref(props.supportTopics.length > 0 ? props.supportTopics[0] : null);
const isChatOpen = ref(false);

const filteredVideos = computed(() => {
    if (!activeTopic.value) return [];
    if (!user.value || !user.value.type) return [];
    
    return activeTopic.value.videos.filter(video => video.roles.includes(user.value.type));
});

const chatbotContext = computed(() => {
    if (activeTopic.value) {
        return {
            title: `Help Topic: ${activeTopic.value.title}`,
            description: activeTopic.value.description
        };
    }
    return {
        title: 'General Support',
        description: 'The user is on the general help and support page.'
    };
});

function selectTopic(topic) {
    activeTopic.value = topic;
}
</script>

<template>
    <Head title="Help" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-white dark:text-gray-200">
                Support Training Tutorials
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white dark:bg-[#0b1624] border border-gray-200 dark:border-[#1f2d40] shadow-sm sm:rounded-2xl">
                    <div class="p-6 sm:p-10 text-gray-900 dark:text-gray-100 bg-white dark:bg-[#1A2C38] transition-colors duration-200">
                        <div class="grid grid-cols-1 gap-8 md:grid-cols-4 transition-colors duration-200">
                            <!-- Sidebar -->
                            <div class="md:col-span-1">
                                <div class="p-4 sm:p-6 bg-gray-100 dark:bg-[#142233] border border-gray-200 dark:border-[#1f2d40] rounded-xl shadow-sm">
                                    <div class="flex flex-col sm:flex-row sm:items-center mb-4 gap-4">
                                         <img src="/images/MBM_Uni.png" alt="Logo" class="w-auto h-12 mr-4">
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">MBM University</h2>
                                    </div>
                                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-100">Contents</h3>
                                    <ul class="space-y-2">
                                        <li v-for="topic in supportTopics" :key="topic.title">
                                            <a href="#" @click.prevent="selectTopic(topic)"
                                               :class="[
                                                   'block px-3 py-2 rounded-lg transition-colors',
                                                   activeTopic && activeTopic.title === topic.title
                                                       ? 'font-semibold text-blue-600 dark:text-blue-300 bg-blue-50 dark:bg-blue-500/10'
                                                       : 'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-[#1f2d40]'
                                               ]">
                                                {{ topic.title }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="p-4 sm:p-6 mt-4 bg-gray-100 dark:bg-[#142233] border border-gray-200 dark:border-[#1f2d40] rounded-xl shadow-sm">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Support & Contact</h3>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">If you need any help, our support team is here to assist.</p>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Email: support@mbm-university.com</p>
                                </div>
                            </div>

                            <!-- Main Content -->
                            <div class="md:col-span-3">
                                <div v-if="activeTopic">
                                    <h3 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">{{ activeTopic.title }}</h3>
                                    <p class="mb-6 text-gray-600 dark:text-gray-300">{{ activeTopic.description }}</p>

                                    <div class="space-y-8">
                                        <div v-for="video in filteredVideos" :key="video.title">
                                            <h4 class="mb-2 text-xl font-semibold text-gray-800 dark:text-gray-100">{{ video.title }}</h4>
                                            <div class="overflow-hidden border-2 border-gray-200 dark:border-[#1f2d40] rounded-2xl shadow-sm">
                                                <video controls class="w-full bg-black rounded-2xl aspect-video">
                                                    <source :src="video.path" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            </div>
                                        </div>
                                        <div v-if="filteredVideos.length === 0" class="text-center py-8 bg-gray-50 dark:bg-[#142233] border border-gray-200 dark:border-[#1f2d40] rounded-xl">
                                            <p class="text-gray-600 dark:text-gray-300">You do not have permission to view these tutorials.</p>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="flex flex-col items-center justify-center h-full p-8 text-center bg-gray-50 dark:bg-[#142233] border border-gray-200 dark:border-[#1f2d40] rounded-xl">
                                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">Welcome to our Support Center</h3>
                                    <p class="mt-2 text-gray-600 dark:text-gray-300">Please select a tutorial from the list on the left to get started.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chatbot FAB -->
        <button @click="isChatOpen = true" class="fixed bottom-8 right-8 bg-blue-600 text-white p-4 rounded-full shadow-lg hover:bg-blue-500 transition z-40">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
        </button>

        <AiChatbot 
            :show="isChatOpen" 
            @close="isChatOpen = false" 
            :chat-context="chatbotContext"
            welcome-message="Hello! How can I help you? Feel free to ask about any of our support topics."
            placeholder="Ask about a support topic..."
        />
    </AuthenticatedLayout>
</template>
