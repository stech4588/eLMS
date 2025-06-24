<template>
    <Head :title="topic.name" />

    <AuthenticatedLayout>
        <div class="dark:bg-dark-bg-primary p-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-10">
                <div class="section_box dark:bg-dark-bg-secondary dark:text-white">
                    <h1 class="text-2xl font-bold mb-6">Courses in {{ topic.name }}</h1>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        <div v-for="course in courses" :key="course.id" class="course-card-container">
                            <CourseCard :course="course" @toggle-favorite="toggleFavorite" />
                        </div>
                         <div v-if="courses.length === 0" class="text-center col-span-full">
                            <p>No courses found for this topic.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import CourseCard from '@/Components/CourseCard.vue';

const props = defineProps({
    topic: Object,
    courses: Array,
});

const toggleFavorite = async (course) => {
    const originalIsFavorited = course.is_favorited;
    course.is_favorited = !course.is_favorited;

    try {
        await router.post(route('courses.toggleFavorite', { course: course.id }), {}, {
            preserveScroll: true,
            preserveState: true,
            onError: (errors) => {
                course.is_favorited = originalIsFavorited;
                console.error('Error toggling favorite:', errors);
            },
        });
    } catch (error) {
        course.is_favorited = originalIsFavorited;
        console.error('Failed to send favorite toggle request:', error);
    }
};
</script>

<style scoped>
.section_box {
    background-color: white;
    padding: 20px;
    border-radius: 16px;
    text-align: start;
}
.course-card-container {
    display: flex;
}
.dark .section_box {
    background-color: #1f2937;
    color: white;
}
</style> 