<template>
    <Head :title="course ? course.title : 'Course Detail'" />

    <AuthenticatedLayout>
        <div v-if="unavailableMessage" class="course-detail-unavailable">
            <div class="course-detail-unavailable-box">
                <p class="course-detail-unavailable-title">{{ unavailableMessage }}</p>
                <p class="course-detail-unavailable-text">Please check back later or contact the instructor if you believe this is a mistake.</p>
                <Link
                    :href="((user && user.type === 'student') || !user) ? route('dashboard') : route('coursess')"
                    class="course-detail-unavailable-btn"
                >
                    {{ ((user && user.type === 'student') || !user) ? 'Go to Dashboard' : 'Go to My Courses' }}
                </Link>
            </div>
        </div>

        <template v-else-if="course">
            <!-- Hero Section -->
            <div class="course-detail-hero">
                <h1 class="course-detail-hero-title">{{ course.title }}</h1>
                <Link
                    v-if="hasAnyVideos"
                    :href="route('courses.play', { course: course.id, video: firstVideoId })"
                    class="course-detail-hero-btn"
                >
                    Start Course
                </Link>
                <span v-else class="course-detail-hero-btn course-detail-hero-btn-disabled">Start Course</span>
            </div>

            <!-- Main Content: two columns -->
            <div class="course-detail-main">
                <!-- Left Column: Sections & Videos -->
                <div class="course-detail-left">
                    <template v-if="course.sections && course.sections.length > 0">
                        <section v-for="(section, sIndex) in course.sections" :key="section.id" class="course-detail-section">
                            <h2 class="course-detail-section-title">{{ romanNumeral(sIndex + 1) }}. {{ section.title }}</h2>
                            <div class="course-detail-lesson-list">
                                <Link
                                    v-for="video in section.videos"
                                    :key="video.id"
                                    :href="route('courses.play', { course: course.id, video: video.id })"
                                    class="course-detail-lesson-item"
                                >
                                    <img
                                        :src="video.thumbnail_url || '/images/skill_section_thumbnail.svg'"
                                        :alt="video.title"
                                        class="course-detail-lesson-thumb"
                                    />
                                    <span class="course-detail-lesson-name">{{ video.title }}</span>
                                </Link>
                            </div>
                        </section>
                    </template>
                    <template v-else-if="course.videos && course.videos.length > 0">
                        <section class="course-detail-section">
                            <h2 class="course-detail-section-title">Lessons</h2>
                            <div class="course-detail-lesson-list">
                                <Link
                                    v-for="video in course.videos"
                                    :key="video.id"
                                    :href="route('courses.play', { course: course.id, video: video.id })"
                                    class="course-detail-lesson-item"
                                >
                                    <img
                                        :src="video.thumbnail_url || '/images/skill_section_thumbnail.svg'"
                                        :alt="video.title"
                                        class="course-detail-lesson-thumb"
                                    />
                                    <span class="course-detail-lesson-name">{{ video.title }}</span>
                                </Link>
                            </div>
                        </section>
                    </template>
                    <p v-else class="course-detail-no-videos">No videos available for this course.</p>
                </div>

                <!-- Right Column: Thumbnail, Progress, Instructor -->
                <div class="course-detail-right">
                    <div class="course-detail-preview-card">
                        <img
                            v-if="course.first_video_thumbnail_url"
                            :src="course.first_video_thumbnail_url"
                            alt="Course preview"
                            class="course-detail-preview-img"
                        />
                        <div v-else class="course-detail-preview-placeholder">No preview</div>
                        <p class="course-detail-progress-text">{{ completedCount }} of {{ course.lessons_count || totalVideosCount }} Lessons Completed</p>
                    </div>
                    <div class="course-detail-instructor">
                        <h3 class="course-detail-instructor-heading">Instructor</h3>
                        <div v-if="course.instructor" class="course-detail-instructor-card">
                            <div class="course-detail-instructor-avatar-initials">
                                {{ getInitials(course.instructor.name) }}
                            </div>
                            <div class="course-detail-instructor-info">
                                <p class="course-detail-instructor-name">{{ course.instructor.name }}</p>
                                <p class="course-detail-instructor-role">Instructor</p>
                                <p class="course-detail-instructor-bio">Course creator and instructor.</p>
                            </div>
                        </div>
                        <div v-else class="course-detail-instructor-card">
                            <p class="course-detail-instructor-name">{{ course.author || 'Instructor' }}</p>
                            <p class="course-detail-instructor-role">Instructor</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Optional: Feedback link for course owner -->
            <div v-if="user && user.id === course.user_id" class="course-detail-footer-actions">
                <Link :href="route('courses.feedback', { course: course.id })" class="course-detail-feedback-link">View Feedback</Link>
            </div>
        </template>

        <div v-else class="course-detail-loading">
            <p>Loading course details or course not found...</p>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    course: Object,
    isPurchased: Boolean,
    completedCount: { type: Number, default: 0 },
    unavailableMessage: { type: String, default: null },
});

const page = usePage();
const user = computed(() => page.props.auth?.user);

const getInitials = (name) => {
    if (!name || typeof name !== 'string') return '';
    const parts = name.trim().split(/\s+/).filter(Boolean);
    if (!parts.length) return '';
    return parts
        .slice(0, 2)
        .map((p) => p.charAt(0).toUpperCase())
        .join('');
};

const hasAnyVideos = computed(() => {
    if (!props.course) return false;
    if (props.course.sections?.length) return props.course.sections.some(s => s.videos?.length);
    return props.course.videos?.length > 0;
});

const firstVideoId = computed(() => {
    if (!props.course) return null;
    if (props.course.sections?.length) {
        for (const s of props.course.sections) {
            if (s.videos?.length) return s.videos[0].id;
        }
    }
    return props.course.videos?.[0]?.id ?? null;
});

const totalVideosCount = computed(() => {
    if (!props.course) return 0;
    if (props.course.lessons_count != null) return props.course.lessons_count;
    if (props.course.sections?.length) return props.course.sections.reduce((sum, s) => sum + (s.videos?.length || 0), 0);
    return props.course.videos?.length || 0;
});

// Live completed count: seed from server prop, then poll every 15s for real-time updates
const liveCompletedCount = ref(props.completedCount ?? 0);
let progressPollTimer = null;

const fetchCompletedCount = async () => {
    if (!props.course?.id || !user.value) return;
    try {
        const { data } = await axios.get(route('courses.completedCount', { course: props.course.id }));
        if (typeof data.completed_count === 'number') {
            liveCompletedCount.value = data.completed_count;
        }
    } catch {
        // silent – keep last known value
    }
};

onMounted(() => {
    progressPollTimer = setInterval(fetchCompletedCount, 15000);
});

onUnmounted(() => {
    if (progressPollTimer) clearInterval(progressPollTimer);
});

const completedCount = computed(() => liveCompletedCount.value);

const romanNumeral = (n) => {
    const map = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'XIV', 'XV', 'XVI', 'XVII', 'XVIII', 'XIX', 'XX'];
    return map[n - 1] || String(n);
};
</script>

<style scoped>
.course-detail-unavailable {
    padding: 2rem;
    max-width: 42rem;
    margin: 0 auto;
}
.course-detail-unavailable-box {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
}
.course-detail-unavailable-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #b91c1c;
}
.course-detail-unavailable-text {
    margin-top: 0.5rem;
    color: #4b5563;
}
.course-detail-unavailable-btn {
    display: inline-block;
    margin-top: 1rem;
    padding: 0.5rem 1.25rem;
    background: #1C355E;
    color: #fff;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
}
.course-detail-unavailable-btn:hover {
    background: #254a7a;
}

/* Hero */
.course-detail-hero {
    background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
    background-size: cover;
    background-position: center;
    padding: 3rem 1.5rem;
    text-align: center;
}
.course-detail-hero-title {
    font-size: 2rem;
    font-weight: 700;
    color: #fff;
    margin: 0 0 1.5rem;
}
.course-detail-hero-btn {
    display: inline-block;
    padding: 0.75rem 2rem;
    background: #1C355E;
    color: #fff;
    font-weight: 600;
    border-radius: 6px;
    text-decoration: none;
    transition: background 0.2s;
}
.course-detail-hero-btn:hover {
    background: #254a7a;
}
.course-detail-hero-btn-disabled {
    background: #9ca3af;
    cursor: not-allowed;
    pointer-events: none;
}

/* Main two columns */
.course-detail-main {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 4rem;
    background: #fff;
}
@media (max-width: 900px) {
    .course-detail-main {
        grid-template-columns: 1fr;
    }
}

/* Left: sections & videos */
.course-detail-left {
    min-width: 0;
}
.course-detail-section {
    margin-bottom: 2rem;
    padding: 16px;
    border-radius: 12px;
    box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1);
}
.course-detail-section-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.75rem;
}
.course-detail-lesson-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.course-detail-lesson-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f3f4f6;
    text-decoration: none;
    color: #111827;
    transition: background 0.15s;
}
.course-detail-lesson-item:hover {
    background: #f9fafb;
}
.course-detail-lesson-thumb {
    width: 160px;
    height: 90px;
    object-fit: cover;
    border-radius: 6px;
    flex-shrink: 0;
}
.course-detail-lesson-name {
    font-weight: 500;
    font-size: 0.9375rem;
}
.course-detail-no-videos {
    color: #6b7280;
    margin: 0;
}

/* Right: preview + instructor */
.course-detail-right {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.course-detail-preview-card {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    background: #f9fafb;
}
.course-detail-preview-img {
    width: 100%;
    aspect-ratio: 16/10;
    object-fit: cover;
    display: block;
}
.course-detail-preview-placeholder {
    width: 100%;
    aspect-ratio: 16/10;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    font-size: 0.875rem;
}
.course-detail-progress-text {
    padding: 0.75rem 1rem;
    margin: 0;
    font-size: 0.875rem;
    font-weight: 500;
    color: #111827;
    border-top: 1px solid #e5e7eb;
}
.course-detail-instructor {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1rem;
    background: #fff;
}
.course-detail-instructor-heading {
    font-size: 1rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.75rem;
}
.course-detail-instructor-card {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}
.course-detail-instructor-avatar-wrap {
    flex-shrink: 0;
}
.course-detail-instructor-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.course-detail-instructor-avatar-initials {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #1C355E;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    letter-spacing: 0.03em;
    flex-shrink: 0;
}
.course-detail-instructor-info {
    min-width: 0;
}
.course-detail-instructor-name {
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.25rem;
    font-size: 1rem;
}
.course-detail-instructor-role {
    font-size: 0.8125rem;
    color: #6b7280;
    margin: 0 0 0.5rem;
}
.course-detail-instructor-bio {
    font-size: 0.875rem;
    color: #4b5563;
    line-height: 1.5;
    margin: 0;
}
.course-detail-footer-actions {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem 2rem;
    text-align: right;
}
.course-detail-feedback-link {
    font-size: 0.875rem;
    color: #1C355E;
    font-weight: 600;
    text-decoration: none;
}
.course-detail-feedback-link:hover {
    text-decoration: underline;
}
.course-detail-loading {
    padding: 3rem;
    text-align: center;
    color: #6b7280;
}

.dark .course-detail-hero-title { color: #fff; }
.dark .course-detail-main { background: #111827; }
.dark .course-detail-section-title,
.dark .course-detail-lesson-name,
.dark .course-detail-progress-text,
.dark .course-detail-instructor-heading,
.dark .course-detail-instructor-name { color: #f9fafb; }
.dark .course-detail-lesson-item { color: #e5e7eb; border-color: #374151; }
.dark .course-detail-lesson-item:hover { background: #1f2937; }
.dark .course-detail-preview-card,
.dark .course-detail-instructor { border-color: #374151; background: #1f2937; }
.dark .course-detail-instructor-role,
.dark .course-detail-instructor-bio { color: #9ca3af; }
</style>
