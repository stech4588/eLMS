<template>
    <Head :title="group.name + ' Chat'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center w-full">
                <div class="flex items-center space-x-2 sm:space-x-4 flex-1 min-w-0">
                    <Link :href="route('groups.index')" class="p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </Link>
                    <img :src="group.profile_picture_url" alt="Group Avatar" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover">
                    <h2 class="font-semibold text-lg sm:text-xl text-gray-800 dark:text-gray-200 leading-tight truncate">{{ group.name }}</h2>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-3 flex-shrink-0">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden sm:inline-block">Email Notifications</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" v-model="emailNotifications" @change="updateNotificationSetting" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>
            </div>
        </template>

        <div class="">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex flex-col h-[calc(100vh-180px)]">
                        <!-- Message Display Area -->
                        <div ref="messageContainer" class="flex-1 p-6 space-y-6 overflow-y-auto">
                            <div v-for="message in messages" :key="message.id" class="flex items-start gap-3" :class="isCurrentUser(message.user.id) ? 'flex-row-reverse' : ''">
                                <!-- Avatar -->
                                <img :src="message.user.profile_photo_url || '/images/default-avatar.png'" alt="Avatar" class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex-shrink-0 object-cover">
                                
                                <div class="flex flex-col" :class="isCurrentUser(message.user.id) ? 'items-end' : 'items-start'">
                                    <div class="font-semibold text-sm text-gray-900 dark:text-gray-100">
                                        {{ message.user.name }}
                                    </div>
                                    <div v-if="!message.group_event" class="p-3 rounded-lg mt-1 max-w-lg" :class="isCurrentUser(message.user.id) ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-200 rounded-bl-none'">
                                        <p v-if="message.content" class="text-sm whitespace-pre-wrap">{{ message.content }}</p>
                                        
                                        <div v-if="message.file_path" class="mt-2">
                                            <img v-if="message.file_type === 'image'" :src="'/' + message.file_path" alt="Attachment" class="max-w-xs rounded-lg cursor-pointer" @click="openAttachmentPreview('/' + message.file_path)">
                                            <video v-else-if="message.file_type === 'video'" :src="'/' + message.file_path" controls class="max-w-xs rounded-lg"></video>
                                            <a v-else :href="'/' + message.file_path" target="_blank" class="text-indigo-300 hover:underline flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                  <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                                Download Attached File
                                            </a>
                                        </div>
                                    </div>
                                    <div v-else @click="openEventDetails(message.group_event)" class="mt-1 p-4 rounded-lg bg-gray-100 dark:bg-gray-900/50 border dark:border-gray-700 w-full max-w-lg cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                        <div class="flex items-center gap-3">
                                            <div class="bg-indigo-100 dark:bg-indigo-900 p-2 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ message.group_event.name }}</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ formatEventTime(message.group_event.start_time) }}</p>
                                            </div>
                                        </div>
                                        <p class="mt-3 text-sm text-gray-800 dark:text-gray-200">{{ message.content }}</p>
                                    </div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ formatTimestamp(message.created_at) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Message Input Area -->
                        <div class="p-4 bg-gray-50 dark:bg-gray-900/50 border-t dark:border-gray-700 relative">
                             <!-- Attachment Preview -->
                            <div v-if="selectedFile" class="p-2 mb-2 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-between">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ selectedFile.name }}</span>
                                <button @click="removeSelectedFile" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">&times;</button>
                            </div>
                            
                            <div class="relative" ref="attachmentContainer">
                                <!-- Attachment Menu -->
                               <div v-if="showAttachmentMenu" class="absolute bottom-full left-0 mb-2 w-60 bg-light-bg-secondary dark:bg-dark-bg-secondary text-light-text-primary dark:text-dark-text-primary rounded-lg shadow-lg dark:shadow-dark z-20">
                                   <ul class="py-1">
                                       <li @click="handleAttachmentClick" class="flex items-center px-4 py-2 hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary cursor-pointer">
                                           <svg class="w-6 h-6 text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                           <span class="ml-3">Document</span>
                                       </li>
                                       <li @click="handleEventClick" class="flex items-center px-4 py-2 hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary cursor-pointer">
                                           <svg class="w-6 h-6 text-pink-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0h18M8.25 12h7.5" /></svg>
                                           <span class="ml-3">Event</span>
                                       </li>
                                   </ul>
                               </div>

                               <form @submit.prevent="sendMessage">
                                   <div class="flex items-center space-x-1 sm:space-x-2">
                                       <input type="file" ref="fileInput" @change="handleFileSelect" class="hidden">
                                       <button type="button" @click="toggleAttachmentMenu" class="p-2 text-gray-500 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
                                           <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                       </button>
                                       <input type="text" v-model="newMessage" class="flex-1 p-2 sm:p-3 border rounded-full dark:bg-gray-800 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Type a message...">
                                       <button type="button" @click="showEmojiPicker = !showEmojiPicker" class="p-2 text-gray-500 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
                                          <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                       </button>
                                       <button type="submit" class="bg-indigo-600 text-white rounded-full hover:bg-indigo-700 disabled:opacity-50 p-2.5 sm:px-4 sm:py-2" :disabled="!newMessage.trim() && !selectedFile">
                                           <span class="hidden sm:inline text-sm">Send</span>
                                           <svg class="h-5 w-5 sm:hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                             <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                           </svg>
                                       </button>
                                   </div>
                               </form>
                           </div>
                            <div v-if="showEmojiPicker" class="absolute bottom-20 right-4">
                                <EmojiPicker @select="onSelectEmoji" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="attachmentPreviewUrl" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50" @click="attachmentPreviewUrl = null">
            <img :src="attachmentPreviewUrl" class="max-w-screen-lg max-h-screen-lg">
        </div>
        <CreateEventModal v-if="showCreateEventModal" :group-id="group.id" @close="showCreateEventModal = false" @event-created="fetchMessages" />
        <EventDetailsModal v-if="showEventDetailsModal" :event="selectedEvent" @close="showEventDetailsModal = false" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import { ref, onMounted, nextTick, computed, watch, onUnmounted } from 'vue';
import axios from 'axios';
import EmojiPicker from 'vue3-emoji-picker';
import 'vue3-emoji-picker/css';
import { formatDistanceToNow } from 'date-fns';
import CreateEventModal from './CreateEventModal.vue';
import EventDetailsModal from './EventDetailsModal.vue';

const props = defineProps({
    group: Object,
});

const page = usePage();
const currentUser = computed(() => page.props.auth.user);
const messages = ref([]);
const newMessage = ref('');
const messageContainer = ref(null);
const fileInput = ref(null);
const selectedFile = ref(null);
const showEmojiPicker = ref(false);
const attachmentPreviewUrl = ref(null);
const showCreateEventModal = ref(false);
const showEventDetailsModal = ref(null);
const selectedEvent = ref(null);
const emailNotifications = ref(false);
const showAttachmentMenu = ref(false);
const attachmentContainer = ref(null);

const toggleAttachmentMenu = () => {
    showAttachmentMenu.value = !showAttachmentMenu.value;
};

const closeAttachmentMenu = () => {
    showAttachmentMenu.value = false;
};

const handleClickOutside = (event) => {
    if (attachmentContainer.value && !attachmentContainer.value.contains(event.target)) {
        closeAttachmentMenu();
    }
};

const handleAttachmentClick = () => {
    triggerFileInput();
    closeAttachmentMenu();
};

const handleEventClick = () => {
    showCreateEventModal.value = true;
    closeAttachmentMenu();
};

const currentUserMembership = computed(() => {
    if (!props.group || !currentUser.value) return null;
    return props.group.members.find(m => m.id === currentUser.value.id);
});

watch(currentUserMembership, (newVal) => {
    if (newVal && newVal.pivot) {
        emailNotifications.value = !!newVal.pivot.receive_email_notifications;
    }
}, { immediate: true });

const updateNotificationSetting = async () => {
    if (!currentUserMembership.value) return;
    try {
        await axios.post(route('groups.settings.notifications', { group: props.group.id }), {
            receive_email_notifications: emailNotifications.value,
        });
    } catch (error) {
        console.error('Error updating notification settings:', error);
        // Revert the toggle on error
        emailNotifications.value = !emailNotifications.value;
    }
};

const isCurrentUser = (userId) => {
    return currentUser.value && currentUser.value.id === userId;
};

const formatTimestamp = (timestamp) => {
    return formatDistanceToNow(new Date(timestamp), { addSuffix: true });
};

const formatEventTime = (timestamp) => {
    return new Date(timestamp).toLocaleString([], { dateStyle: 'medium', timeStyle: 'short' });
};

const openAttachmentPreview = (url) => {
    attachmentPreviewUrl.value = url;
};

const openEventDetails = (event) => {
    selectedEvent.value = event;
    showEventDetailsModal.value = true;
};

const onSelectEmoji = (emoji) => {
    newMessage.value += emoji.i;
    showEmojiPicker.value = false;
};

const fetchMessages = async () => {
    try {
        const response = await axios.get(route('api.groups.messages.index', props.group.id));
        messages.value = response.data.data.reverse();
        scrollToBottom();
    } catch (error) {
        console.error('Error fetching messages:', error);
    }
};

const triggerFileInput = () => {
    fileInput.value.click();
};

const handleFileSelect = (event) => {
    selectedFile.value = event.target.files[0];
};

const removeSelectedFile = () => {
    selectedFile.value = null;
    fileInput.value.value = '';
};

const sendMessage = async () => {
    if (!newMessage.value.trim() && !selectedFile.value) return;

    const formData = new FormData();
    formData.append('content', newMessage.value);
    if (selectedFile.value) {
        formData.append('attachment', selectedFile.value);
    }

    try {
        const response = await axios.post(route('api.groups.messages.store', props.group.id), formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        messages.value.push(response.data);
        newMessage.value = '';
        removeSelectedFile();
        scrollToBottom();
    } catch (error) {
        console.error('Error sending message:', error);
    }
};

const scrollToBottom = () => {
    nextTick(() => {
        if (messageContainer.value) {
            messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
        }
    });
};

onMounted(() => {
    fetchMessages();
    document.addEventListener('mousedown', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('mousedown', handleClickOutside);
});
</script>
