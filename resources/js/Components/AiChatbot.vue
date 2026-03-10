<template>
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="transform opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        enter-to-class="transform opacity-100 translate-y-0 sm:scale-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="transform opacity-100 translate-y-0 sm:scale-100"
        leave-to-class="transform opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        <div v-if="show" class="fixed bottom-0 right-0 mb-4 mr-4 w-full max-w-lg z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl flex flex-col h-[70vh] border dark:border-gray-700">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 rounded-t-lg bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex items-center space-x-3">
                        <div class="p-1.5 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zM2 10a8 8 0 1116 0 8 8 0 01-16 0zm9.333-3.047a.75.75 0 00-1.48-.314l-2.5 6.333a.75.75 0 001.314.65l.613-1.54A.75.75 0 0110 11.5h.047a.75.75 0 01.625.333l.613 1.54a.75.75 0 001.314-.65l-2.5-6.333zM9.414 9.5H10.5a.75.75 0 01.6.3l.375.938a.75.75 0 01-1.424.124L9.414 9.5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">AI Assistant</h3>
                    </div>
                    <button @click="closeChat" class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Messages -->
                <div ref="messagesContainer" class="flex-1 p-6 overflow-y-auto space-y-6">
                    <div v-for="(message, index) in messages" :key="index" class="flex items-start space-x-3" :class="message.isUser ? 'flex-row-reverse space-x-reverse' : ''">
                         <img :src="message.isUser ? authUser.profile_photo_url : '/images/ai-avatar.jpg'" class="w-8 h-8 rounded-full object-cover">
                        <div class="p-3 rounded-lg max-w-md prose dark:prose-invert" :class="message.isUser ? 'bg-gray-800 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white'">
                            <div v-html="message.text"></div>
                        </div>
                    </div>
                    <div v-if="isLoading" class="flex items-start space-x-3">
                        <img src="/images/ai-avatar.png" class="w-8 h-8 rounded-full object-cover">
                        <div class="p-3 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-gray-500 rounded-full animate-pulse"></div>
                                <div class="w-2 h-2 bg-gray-500 rounded-full animate-pulse delay-75"></div>
                                <div class="w-2 h-2 bg-gray-500 rounded-full animate-pulse delay-150"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50">
                    <div class="relative">
                        <textarea
                            v-model="newMessage"
                            @keyup.enter="sendMessageOnEnter"
                            ref="inputBox"
                            rows="1"
                            :placeholder="placeholder"
                            class="w-full px-4 py-3 pr-16 text-gray-800 bg-gray-100 border-2 border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white resize-none"
                        ></textarea>
                        <button @click="sendMessage" :disabled="isLoading || !newMessage.trim()" class="absolute inset-y-0 right-0 flex items-center justify-center w-12 h-12 text-white bg-[#1C355E] rounded-full transition-transform duration-200 transform hover:scale-110 disabled:bg-gray-400 dark:disabled:bg-gray-600 disabled:scale-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style>
.prose ul {
    list-style-type: disc;
    padding-left: 1.5rem;
}
.prose ol {
    list-style-type: decimal;
    padding-left: 1.5rem;
}
.prose code {
    background-color: rgba(0,0,0,0.1);
    padding: 0.2em 0.4em;
    margin: 0;
    font-size: 85%;
    border-radius: 3px;
}
.dark .prose code {
     background-color: rgba(255,255,255,0.1);
}
</style>

<script setup>
import { ref, watch, nextTick } from 'vue';
import axios from 'axios';
import { marked } from 'marked';
import { usePage } from '@inertiajs/vue3';

const authUser = usePage().props.auth.user;

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    chatContext: {
        type: Object,
        required: true
    },
    welcomeMessage: {
        type: String,
        default: "Hello! How can I help you today?"
    },
    placeholder: {
        type: String,
        default: "Ask me anything..."
    }
});

const emit = defineEmits(['close']);

const messages = ref([]);
const newMessage = ref('');
const isLoading = ref(false);
const messagesContainer = ref(null);
const inputBox = ref(null);

watch(() => props.show, (newValue) => {
    if (newValue) {
        messages.value = [{ text: props.welcomeMessage, isUser: false }];
    }
});

watch(messages, async () => {
    await nextTick();
    if (messagesContainer.value) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
}, { deep: true });

watch(newMessage, async () => {
    await nextTick();
    if (inputBox.value) {
        inputBox.value.style.height = 'auto';
        inputBox.value.style.height = `${inputBox.value.scrollHeight}px`;
    }
});

const sendMessageOnEnter = (event) => {
    if (event.shiftKey) return;
    sendMessage();
};

const sendMessage = async () => {
    if (!newMessage.value.trim() || isLoading.value) return;

    const userMessage = newMessage.value;
    messages.value.push({ text: userMessage, isUser: true });
    newMessage.value = '';
    isLoading.value = true;

    try {
        const response = await axios.post(route('ai.chat'), {
            message: userMessage,
            context_title: props.chatContext.title,
            context_description: props.chatContext.description
        });
        
        const formattedHtml = marked(response.data.reply);
        messages.value.push({ text: formattedHtml, isUser: false });

    } catch (error) {
        console.error('Error fetching AI response:', error);
        messages.value.push({ text: 'Sorry, I encountered an error. Please try again.', isUser: false });
    } finally {
        isLoading.value = false;
    }
};

const closeChat = () => {
    emit('close');
};
</script>
