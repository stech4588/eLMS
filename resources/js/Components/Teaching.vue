<template>
    <!-- <div class="lms-mart" id="learn">
        <div class="lms-wrapper">
            <h2 class="lms-heading">
                Discover the <span class="lms-highlight">MBM Course Library</span><br />
                Built to guide every learner from enrollment to mastery.
            </h2>
            <p class="lms-desc">
                Our LMS unifies curriculum planning, live classrooms, and performance analytics so faculty can deliver
                consistent outcomes while students always understand their next milestone.
            </p>

            <div class="lms-box">
                <div class="lms-card">
                    <img src="/images/AI.svg" alt="AI Icon" class="lms-icon" />
                    <h3 class="lms-title">CURATED PROGRAM PATHS</h3>
                    <p class="lms-desc">
                        Browse undergraduate, professional, and skills-based courses mapped into clear, semester-ready sequences that keep learners progressing with confidence.
                    </p>
                </div>

                <div class="lms-card lms-border-x">
                    <img src="/images/cube.svg" alt="Coach icon" class="lms-icon" />
                    <h3 class="lms-title">MENTORS & LIVE SESSIONS</h3>
                    <p class="lms-desc">
                        Book office hours, join interactive cohorts, and receive instructor feedback without leaving the platform, ensuring personalized guidance at every stage.
                    </p>
                </div>

                <div class="lms-card">
                    <img src="/images/workforce.svg" alt="Community icon" class="lms-icon" />
                    <h3 class="lms-title">TOOLS & CAMPUS SERVICES</h3>
                    <p class="lms-desc">
                        Integrated library search, assessment builders, attendance reports, and AI study aides keep every class organized and every learner supported.
                    </p>
                </div>
            </div>
            <button @click="joinNowUrl" class="lms-button">➜ Explore Courses</button>
        </div>
    </div> -->

</template>

<script setup>
import { usePage, router } from '@inertiajs/vue3'

const user = usePage().props.auth.user

const joinNowUrl = () => {
    if (user) {
        if (usePage().props.auth.profile_incomplete) {
            router.get('/register/complete');
        } else {
            router.get('/dashboard');
        }
    } else {
        router.get('/joinnow');
    }
};
</script>
<style>
</style>
