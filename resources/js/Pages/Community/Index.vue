<template>
    <AuthenticatedLayout>
        <div class="elms-v3-community-bg transition-colors duration-300">
            <div class="container mx-auto p-4 max-w-4xl pt-8">
            <!-- Header -->
            <!-- <div class="flex justify-between items-center mb-6">
                 <h1 class="text-2xl font-bold dark:text-gray-200">Community</h1>
            </div> -->

            <!-- Simple Post Bar Trigger (New Design) -->
            <div class="elms-v3-simple-post-bar" @click="showPostModal = true">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span class="placeholder-text">Write something...</span>
            </div>

            <!-- Category Filters (New) -->
            <div class="elms-v3-category-filters">
                <div 
                    class="elms-v3-category-pill all" 
                    :class="{ active: selectedCategory === 'all' }"
                    @click="filterByCategory('all')"
                >
                    All
                </div>
                <div 
                    v-for="cat in categories" 
                    :key="cat.id"
                    class="elms-v3-category-pill"
                    :class="{ active: selectedCategory === cat.id }"
                    @click="filterByCategory(cat.id)"
                >
                    <i :class="cat.icon" class="text-xs mr-1 opacity-80"></i>
                    {{ cat.label }}
                </div>
                <!-- Filter Settings Icon (Mockup) -->
                <div class="elms-v3-category-pill" style="min-width: 44px; justify-content: center; padding: 0; width: 44px; height: 44px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                        <path d="M4 21v-7m0-4V3m8 21v-11m0-4V3m8 21v-9m0-4V3M1 14h6m2-7h6m2 9h6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <!-- Create Post Modal -->
            <div v-if="showPostModal" class="elms-v3-modal-overlay" @click.self="showPostModal = false">
                <div class="elms-v3-modal-content create-modal">
                    <div class="elms-v3-create-card overflow-hidden flex flex-col max-h-[90vh]" style="margin-bottom: 0;">
                        <div class="elms-v3-create-header flex-shrink-0">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 shadow-md border-2 border-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span>
                                <b>{{ $page.props.auth.user.name }}</b> posting in 
                                <span style="color: #009EE0; font-weight: 700;">ElevateU University</span>
                            </span>
                        </div>

                        <div class="overflow-y-auto custom-scrollbar flex-1 pr-2">


                        <input 
                            v-model="newPostTitle" 
                            class="elms-v3-input-title" 
                            placeholder="Title"
                            @focus="lastFocusedField = 'title'"
                        >
                        <div class="relative">
                            <div v-if="!newPostContent" class="absolute left-0 top-0 text-gray-400 pointer-events-none p-3 text-lg">Write something...</div>
                            <textarea 
                                ref="contentInput"
                                v-model="newPostContent" 
                                class="elms-v3-input-content" 
                                placeholder=""
                                rows="4"
                                @focus="lastFocusedField = 'content'"
                            ></textarea>
                        </div>


                        <!-- Poll Creation Section -->
                        <div v-if="showPoll" class="mt-4 p-4 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50/50 dark:bg-gray-800/30">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Poll</span>
                                <button @click="showPoll = false" class="text-xs font-bold text-gray-400 hover:text-red-500 uppercase tracking-wider">Remove</button>
                            </div>
                            
                            <div class="space-y-3">
                                <div v-for="(option, index) in pollOptions" :key="index" class="relative group">
                                    <input 
                                        v-model="pollOptions[index]"
                                        :placeholder="'Option ' + (index + 1)"
                                        class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm focus:border-blue-500 outline-none transition-all pr-10"
                                    >
                                    <button 
                                        v-if="pollOptions.length > 2"
                                        @click="removePollOption(index)" 
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500"
                                    >
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <button 
                                v-if="pollOptions.length < 5"
                                @click="addPollOption" 
                                class="mt-4 border border-gray-200 dark:border-gray-700 rounded-xl px-6 py-2.5 text-xs font-bold text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all uppercase"
                            >
                                <i class="fa-solid fa-plus mr-2"></i> Add option
                            </button>
                        </div>

                        <!-- Link Upload Section (Modified for Multiple Links) -->
                        <div v-if="showLinkInput" class="mt-4 p-4 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50/50 dark:bg-gray-800/30">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                    {{ linkInputType === 'youtube' ? 'Add YouTube Video' : 'Add Web Link' }}
                                </span>
                                <button @click="showLinkInput = false; tempLink = ''" class="text-xs font-bold text-gray-400 hover:text-red-500 uppercase tracking-wider">Close</button>
                            </div>
                            <div class="flex gap-2">
                                <input 
                                    v-model="tempLink"
                                    :placeholder="linkInputType === 'youtube' ? 'Paste YouTube URL...' : 'Paste Web URL (e.g. https://google.com)'"
                                    class="flex-1 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm focus:border-blue-500 outline-none transition-all"
                                    @keyup.enter="addLink"
                                >
                                <button @click="addLink" class="bg-blue-500 text-white px-4 rounded-xl font-bold text-xs uppercase transition-all hover:bg-blue-600 active:scale-95">Add</button>
                            </div>
                            <p v-if="tempLink && !isValidUrl(tempLink)" class="mt-2 text-[10px] text-red-500 font-bold">Please enter a valid working URL.</p>

                            <!-- Link Previews -->
                            <div v-if="addedLinks.length > 0" class="mt-4 space-y-3">
                                <template v-for="(link, lIdx) in addedLinks" :key="'link-' + lIdx">
                                    <!-- YouTube Preview -->
                                    <div v-if="isYoutubeUrl(link)" class="relative group">
                                        <div class="rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 shadow-md aspect-video bg-black relative">
                                            <img :src="getYoutubeThumbnail(link)" class="w-full h-full object-cover opacity-80">
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center shadow-lg">
                                                    <i class="fa-solid fa-play text-white text-lg ml-0.5"></i>
                                                </div>
                                            </div>
                                            <button @click="removeLink(lIdx)" class="absolute top-2 right-2 bg-black/50 text-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-red-500 transition-colors">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Generic Link UI (Simplified) -->
                                    <div v-else class="flex items-center justify-between p-3 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-xl shadow-sm">
                                        <div class="flex items-center gap-3 overflow-hidden">
                                             <div class="w-8 h-8 bg-blue-50 dark:bg-blue-900/20 rounded-lg flex items-center justify-center text-blue-500">
                                                <i class="fa-solid fa-link text-xs"></i>
                                            </div>
                                            <span class="text-xs font-bold text-gray-900 dark:text-gray-100 truncate max-w-[200px]">{{ link }}</span>
                                        </div>
                                        <button @click="removeLink(lIdx)" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors">
                                            <i class="fa-solid fa-xmark text-sm"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Attachment Previews -->
                        <div v-if="attachments.length > 0" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-4 max-h-[200px] overflow-y-auto p-1">
                            <div v-for="(file, index) in attachments" :key="index" class="relative group">
                                <img v-if="file.type.startsWith('image/')" :src="file.preview" class="rounded-lg w-full h-24 object-cover border dark:border-gray-700">
                                <div v-else class="flex flex-col items-center justify-center h-24 bg-gray-50 dark:bg-gray-800 border dark:border-gray-700 rounded-lg p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-[10px] truncate w-full text-center mt-1 dark:text-gray-400">{{ file.name }}</span>
                                </div>
                                <button @click="removeAttachment(index)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs shadow-md">×</button>
                            </div>
                        </div>

                        </div> <!-- End of overflow-y-auto -->

                        <div class="elms-v3-toolbar items-center justify-between flex-shrink-0">
                            <div class="flex items-center gap-2">
                                <div class="elms-v3-tool-btn" @click="triggerFileInput" title="Add Attachments">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13" /></svg>
                                </div>

                                <div class="elms-v3-tool-btn relative" @click="toggleEmojiPicker" title="Add Emoji">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><circle cx="12" cy="12" r="10" /><path d="M8 14s1.5 2 4 2 4-2 4-2" /><line x1="9" y1="9" x2="9.01" y2="9" /><line x1="15" y1="9" x2="15.01" y2="9" /></svg>
                                    
                                    <!-- Emoji Picker Popup -->
                                    <div v-if="showEmojiPicker" class="absolute bottom-full left-0 mb-4 z-50">
                                        <EmojiPicker :native="true" @select="onEmojiSelect" />
                                    </div>
                                </div>
                                <div class="elms-v3-tool-btn font-black text-sm relative" @click="togglePoll" :class="{ 'text-blue-500 bg-blue-50 dark:bg-blue-900/20': showPoll }" title="Add Poll">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6">
                                        <path d="M18 20V10M12 20V4M6 20v-6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="elms-v3-tool-btn relative" @click="toggleLinkPreview('generic')" :class="{ 'text-blue-500 bg-blue-50 dark:bg-blue-900/20': showLinkInput && linkInputType === 'generic' }" title="Add Generic Link">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6">
                                        <path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10.172 13.828a4 4 0 015.656 0l4-4a4 4 0 10-5.656-5.656l-1.102 1.101" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="elms-v3-tool-btn relative" @click="toggleLinkPreview('youtube')" :class="{ 'text-red-500 bg-red-50 dark:bg-red-900/20': showLinkInput && linkInputType === 'youtube' }" title="Add YouTube Link">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6">
                                        <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z" />
                                        <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="currentColor"/>
                                    </svg>
                                </div>
                                <!-- <div class="elms-v3-tool-btn font-black text-sm relative opacity-50 cursor-not-allowed" title="GIF (Coming Soon)">
                                    GIF
                                </div> -->
                                <!-- Hidden input -->
                                <input type="file" ref="fileInput" @change="handleFileChange" class="hidden" accept="image/*,video/*,application/pdf,.doc,.docx,.zip,.txt" multiple>

                                <!-- Category Dropdown -->
                                <div class="elms-v3-category-select-wrapper ml-2">
                                    <select v-model="postCategory" class="elms-v3-category-select">
                                        <option value="general">General discussion</option>
                                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                            {{ cat.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="elms-v3-create-actions flex items-center gap-4">
                                <span class="elms-v3-cancel-btn uppercase text-xs font-bold tracking-wider cursor-pointer hover:text-gray-900" @click="showPostModal = false">CANCEL</span>
                                <button 
                                    @click="submitPost" 
                                    class="elms-v3-post-btn uppercase" 
                                    :class="{ active: newPostTitle.trim() && (newPostContent.trim() || attachments.length > 0 || (showPoll && pollOptions.filter(o => o.trim()).length >= 2) || addedLinks.length > 0) }"
                                    :disabled="!newPostTitle.trim() || (!newPostContent.trim() && attachments.length === 0 && (!showPoll || pollOptions.filter(o => o.trim()).length < 2) && addedLinks.length === 0)"
                                >
                                    POST
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Posts List -->
            <div ref="scrollComponent" class="space-y-6">
                    <Post
                        v-for="post in posts"
                        :key="post.id"
                        :post="post"
                        @deleted="handlePostDeleted"
                        @restored="handlePostRestored"
                    />

                    <!-- Empty State (New) -->
                    <div v-if="!loading && posts.length === 0" class="flex flex-col items-center justify-center py-20 px-4 text-center">
                        <div class="bg-white dark:bg-gray-800 rounded-full w-24 h-24 flex items-center justify-center mb-6 shadow-sm border border-gray-100 dark:border-gray-700">
                            <i class="fa-solid fa-folder-open text-4xl text-gray-300 dark:text-gray-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">No conversations here yet</h3>
                        <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                            {{ selectedCategory === 'all' 
                                ? "The community is quiet... be the first to start a discussion!" 
                                : "There are no posts in this category yet. Be the first to share something!" 
                            }}
                        </p>
                        <button 
                            @click="showPostModal = true" 
                            class="mt-8 bg-[#1C355E] text-white px-10 py-3.5 rounded-xl font-bold hover:bg-[#2a4e8c] transition-all shadow-lg hover:shadow-xl active:scale-95"
                        >
                            <i class="fa-solid fa-plus mr-2"></i> Start a discussion
                        </button>
                    </div>
            </div>

             <div v-if="loading" class="text-center p-8 text-gray-500 dark:text-gray-300">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-500 border-t-transparent"></div>
            </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Post from '@/Components/Community/Post.vue';
import { ref, onMounted, onUnmounted, nextTick } from 'vue';
import axios from 'axios';
import { showToast } from '@/toast.js';
import EmojiPicker from 'vue3-emoji-picker';
import 'vue3-emoji-picker/css';
import { GiphyFetch } from '@giphy/js-fetch-api';

const gf = new GiphyFetch('dc6zaTOxFJmzC'); // Standard Giphy Public Beta Key

const posts = ref([]);
const page = ref(1);
const lastPage = ref(1);
const loading = ref(false);
const scrollComponent = ref(null);
const showPostModal = ref(false);
const newPostTitle = ref('');
const newPostContent = ref('');
const postCategory = ref('general');
const selectedCategory = ref('all');
const fileInput = ref(null);
const attachments = ref([]);
const showEmojiPicker = ref(false);
const showGifPicker = ref(false);
const gifSearch = ref('');
const gifs = ref([]);
const loadingGifs = ref(false);
const lastFocusedField = ref('content');

const showPoll = ref(false);
const pollOptions = ref(['', '']);
const showLinkInput = ref(false);
const linkInputType = ref('generic');
const tempLink = ref('');
const addedLinks = ref([]);

const toggleLinkPreview = (type) => {
    if (showLinkInput.value && linkInputType.value === type) {
        showLinkInput.value = false;
        tempLink.value = '';
    } else {
        showLinkInput.value = true;
        linkInputType.value = type;
    }
};

const addLink = () => {
    if (!tempLink.value.trim()) return;
    if (!isValidUrl(tempLink.value)) {
        showToast('Please enter a valid working URL.', 'error');
        return;
    }
    
    if (addedLinks.value.includes(tempLink.value.trim())) {
        showToast('Link already added.', 'warning');
        return;
    }

    addedLinks.value.push(tempLink.value.trim());
    tempLink.value = '';
    // Optional: close input after adding? USER probably wants to add more. Keep it open.
};

const removeLink = (index) => {
    addedLinks.value.splice(index, 1);
};

const isValidUrl = (string) => {
    try {
        new URL(string);
        return true;
    } catch (_) {
        return false;
    }
};

const isYoutubeUrl = (url) => {
    if (!url) return false;
    return url.match(/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+/);
};

const getYoutubeEmbedId = (url) => {
    if (!url) return '';
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : '';
};

const getYoutubeThumbnail = (url) => {
    const videoId = getYoutubeEmbedId(url);
    return videoId ? `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg` : '';
};

const togglePoll = () => {
    showPoll.value = !showPoll.value;
};

const addPollOption = () => {
    if (pollOptions.value.length < 5) {
        pollOptions.value.push('');
    }
};

const removePollOption = (index) => {
    if (pollOptions.value.length > 2) {
        pollOptions.value.splice(index, 1);
    }
};

const toggleEmojiPicker = () => {
    showEmojiPicker.value = !showEmojiPicker.value;
    showGifPicker.value = false;
};

/*
const toggleGifPicker = () => {
    showGifPicker.value = !showGifPicker.value;
    showEmojiPicker.value = false;
    if (showGifPicker.value && gifs.value.length === 0) {
        fetchTrendingGifs();
    }
};
*/

const onEmojiSelect = (emoji) => {
    if (lastFocusedField.value === 'title') {
        newPostTitle.value += emoji.i;
    } else {
        newPostContent.value += emoji.i;
    }
    showEmojiPicker.value = false;
};

/*
const fetchTrendingGifs = async () => {
    loadingGifs.value = true;
    try {
        const { data } = await gf.trending({ limit: 10 });
        gifs.value = data;
    } catch (err) {
        console.error(err);
    } finally {
        loadingGifs.value = false;
    }
};

const searchGifs = async () => {
    if (!gifSearch.value.trim()) {
        fetchTrendingGifs();
        return;
    }
    loadingGifs.value = true;
    try {
        const { data } = await gf.search(gifSearch.value, { limit: 10 });
        gifs.value = data;
    } catch (err) {
        console.error(err);
    } finally {
        loadingGifs.value = false;
    }
};

const selectGif = (gif) => {
    newPostContent.value += ` ${gif.images.fixed_height.url} `;
    showGifPicker.value = false;
};
*/


const categories = [
    { id: 'general', label: 'General discussion', icon: 'fa-solid fa-comments' },
    { id: 'new_member', label: 'New Member!', icon: 'fa-solid fa-user-plus' },
    { id: 'wins', label: 'Wins / Results!', icon: 'fa-solid fa-trophy' },
    { id: 'bonus', label: 'Bonus Content', icon: 'fa-solid fa-gift' },
    { id: 'questions', label: 'Ask Questions', icon: 'fa-solid fa-circle-question' },
    { id: 'announcements', label: 'Announcements', icon: 'fa-solid fa-bullhorn' },
];

const handlePostDeleted = (postId) => {
    posts.value = posts.value.filter(p => p.id !== postId);
};

const handlePostRestored = (post) => {
    // Add it back and sort by created_at (latest first)
    posts.value.push(post);
    posts.value.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
};

const fetchPosts = async () => {
    if (loading.value || (page.value > lastPage.value && page.value > 1)) return;
    loading.value = true;

    try {
        const response = await axios.get(`/api/community-posts?page=${page.value}&category=${selectedCategory.value}`);
        posts.value = [...posts.value, ...response.data.data];
        lastPage.value = response.data.last_page;
        page.value++;
    } catch (error) {
        console.error('Error fetching posts:', error);
    } finally {
        loading.value = false;
    }
};

const addNewPost = (newPost) => {
    posts.value.unshift(newPost);
};

const filterByCategory = (catId) => {
    selectedCategory.value = catId;
    posts.value = [];
    page.value = 1;
    lastPage.value = 1;
    fetchPosts();
};

const triggerFileInput = () => {
    fileInput.value.click();
};

const handleFileChange = (event) => {
    const files = Array.from(event.target.files);
    files.forEach(file => {
        if (file.type.startsWith('image/')) {
            file.preview = URL.createObjectURL(file);
        }
        attachments.value.push(file);
    });
    fileInput.value.value = '';
};

const removeAttachment = (index) => {
    const file = attachments.value[index];
    if (file.preview) {
        URL.revokeObjectURL(file.preview);
    }
    attachments.value.splice(index, 1);
};

const clearPost = () => {
    newPostTitle.value = '';
    newPostContent.value = '';
    postCategory.value = 'general';
    attachments.value.forEach(file => {
        if (file.preview) URL.revokeObjectURL(file.preview);
    });
    attachments.value = [];
    showPoll.value = false;
    pollOptions.value = ['', ''];
    showLinkInput.value = false;
    addedLinks.value = [];
    tempLink.value = '';
};

const submitPost = async () => {
    if (!newPostTitle.value.trim()) {
        showToast('The title field is required.', 'error');
        return;
    }
    if (!newPostContent.value.trim() && attachments.value.length === 0) {
        showToast('The content field is required.', 'error');
        return;
    }

    const formData = new FormData();
    formData.append('title', newPostTitle.value);
    formData.append('content', newPostContent.value);
    formData.append('category', postCategory.value);
    
    attachments.value.forEach(file => {
        formData.append('attachments[]', file);
    });

    if (addedLinks.value.length > 0) {
        addedLinks.value.forEach(link => {
            formData.append('links[]', link);
        });
    }

    if (showPoll.value) {
        const validOptions = pollOptions.value.filter(o => o.trim());
        if (validOptions.length >= 2) {
            validOptions.forEach(opt => {
                formData.append('poll_options[]', opt);
            });
            formData.append('poll_question', newPostTitle.value); // Use title as question or allow separate field? 
            // In the image, "Title" is prominent, so title is a good question.
        }
    }

    try {
        const response = await axios.post('/api/community-posts', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        addNewPost(response.data);
        clearPost();
        showPostModal.value = false;
        showToast('Post created successfully!');
    } catch (error) {
        console.error('Error submitting post:', error);
        const message = error.response?.data?.message || 'Error creating post';
        showToast(message, 'error');
    }
};
const handleScroll = () => {
    let element = scrollComponent.value;
    if (element && element.getBoundingClientRect().bottom < window.innerHeight) {
        fetchPosts();
    }
}

onMounted(() => {
    fetchPosts();
    window.addEventListener("scroll", handleScroll)
    // Realtime new posts
    if (window.Echo) {
        window.Echo.private('community')
            .listen('.CommunityPostCreated', (payload) => {
                if (!payload) return;
                addNewPost(payload);
            });
    }
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll)
    try {
        if (window.Echo) {
            window.Echo.leave('private-community');
        }
    } catch (e) {}
})

</script>
