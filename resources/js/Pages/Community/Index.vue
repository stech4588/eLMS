<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                 <h1 class="text-2xl font-bold dark:text-gray-200">Community</h1>
            </div>

            <!-- Main container for posts -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
                <!-- Create Post Section -->
                <div class="p-4 border-b dark:border-gray-600">
                    <div class="flex items-start">
                        <img :src="$page.props.auth.user.profile_photo_url" alt="My profile picture" class="w-10 h-10 rounded-full mr-3">
                        <div class="flex-1">
                            <textarea
                                v-model="newPostContent"
                                class="w-full p-2 border rounded bg-gray-50 dark:bg-dark-bg dark:text-gray-200 dark:border-gray-600 dark:bg-dark-bg-secondary"
                                rows="3"
                                placeholder="What's on your mind?"
                            ></textarea>
                            <div class="mt-2 flex justify-between items-center">
                                <div>
                                    <button @click="triggerFileInput" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.415a6 6 0 108.485 8.485L17 13" />
                                        </svg>
                                    </button>
                                    <input type="file" ref="fileInput" @change="handleFileChange" class="hidden" accept="image/*,application/pdf,.doc,.docx,.zip,.txt" multiple>
                                </div>
                                <button
                                    @click="submitPost"
                                    :disabled="!newPostContent.trim() && attachments.length === 0"
                                    class="bg-[#148ad9] text-white px-4 py-2 rounded-lg hover:bg-blue-600 disabled:bg-blue-300"
                                >
                                    Post
                                </button>
                            </div>
                            <div v-if="attachments.length > 0" class="mt-2 grid grid-cols-3 gap-2">
                                <div v-for="(file, index) in attachments" :key="index" class="relative">
                                    <img v-if="file.type.startsWith('image/')" :src="file.preview" class="rounded-lg w-full h-24 object-cover">
                                    <div v-else class="flex items-center justify-center h-24 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                        <span class="text-sm text-gray-500 dark:text-gray-400 p-2 text-center">{{ file.name }}</span>
                                    </div>
                                    <button @click="removeAttachment(index)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 text-xs w-[1.5rem]">X</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Posts -->
                <div ref="scrollComponent">
                    <Post
                        v-for="post in posts"
                        :key="post.id"
                        :post="post"
                    />
                </div>
            </div>

             <div v-if="loading" class="text-center p-4">
                Loading...
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Post from '@/Components/Community/Post.vue';
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const posts = ref([]);
const page = ref(1);
const lastPage = ref(1);
const loading = ref(false);
const scrollComponent = ref(null);
const newPostContent = ref('');
const fileInput = ref(null);
const attachments = ref([]);

const fetchPosts = async () => {
    if (loading.value || (page.value > lastPage.value && page.value > 1)) return;
    loading.value = true;

    try {
        const response = await axios.get(`/api/community-posts?page=${page.value}`);
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

const submitPost = async () => {
    if (!newPostContent.value.trim() && attachments.value.length === 0) return;

    const formData = new FormData();
    formData.append('content', newPostContent.value);
    attachments.value.forEach(file => {
        formData.append('attachments[]', file);
    });

    try {
        const response = await axios.post('/api/community-posts', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        addNewPost(response.data);
        newPostContent.value = '';
        attachments.value = [];
    } catch (error) {
        console.error('Error submitting post:', error);
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
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll)
})

</script>
