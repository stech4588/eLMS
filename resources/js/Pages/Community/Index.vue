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
                                class="w-full p-2 border rounded bg-gray-50 dark:bg-dark-bg dark:text-gray-200 dark:border-gray-600"
                                rows="3"
                                placeholder="What's on your mind?"
                            ></textarea>
                            <div class="flex justify-end mt-2">
                                <button
                                    @click="submitPost"
                                    :disabled="!newPostContent.trim()"
                                    class="bg-[#148ad9] text-white px-4 py-2 rounded-lg hover:bg-blue-600 disabled:bg-blue-300"
                                >
                                    Post
                                </button>
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

const submitPost = async () => {
    if (!newPostContent.value.trim()) return;

    try {
        const response = await axios.post('/api/community-posts', {
            content: newPostContent.value,
        });
        addNewPost(response.data);
        newPostContent.value = '';
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
