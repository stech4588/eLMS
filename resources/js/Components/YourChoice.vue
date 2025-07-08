<!-- <template>
    <div class="hero-section-modern" id="choice">
        <div class="floating-bg"></div>
        <div class="hero-wrapper">
            <div class="hero-left">
                <h1 class="hero-heading">You get to <span class="gradient-text">Choose</span></h1>
                <p>
                    We have opened up a mass portal to give you the tools to break free from
                    <strong>modern-day limitations</strong> and succeed.
                </p>
                <p>
                    We've spent years developing our fully independent and modern-day educational platform and we
                    continue to
                    <strong>innovate and improve daily.</strong>
                </p>
                <p>
                    Become <strong>prosperous, strong, and well-connected</strong> in this world. With your effort and
                    our guidance,
                    success is within reach.
                </p>
                <div class="cta-section">
                    <h3>Do you want to <span class="underline">take control of your future?</span></h3>
                </div>
            </div>
            <div class="hero-right">
                <div class="video-box">
                    <iframe v-if="isPlaying" :src="videoUrl" frameborder="0" allowfullscreen
                        allow="autoplay; encrypted-media"></iframe>
                    <div v-else class="video-placeholder" @click="playVideo">
                        <img src="/images/placeholder.webp" alt="Play">
                        <div class="video-play-icon">▶</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template> -->
<template>
    <div class="main-nbm-banner" id="choice">
        <div class="nbm-banner-row">
            <section class="mbm-banner">
                <div class="mbm-banner-content">
                    <h2 class="mbm-banner-heading">
                        Don’t let outdated systems <span class="mbm-nowrap">limit the learning potential</span> at MBM University.
                    </h2>
                    <div class="subtext">
                        <p class="mbm-banner-subtext">
                            Contact us today to see how we can help you!&nbsp;&nbsp;
                        </p><a :href="joinNowUrl" class="mbm-banner-button">➜ JOIN NOW</a>
                    </div>


                </div>
            </section>
        </div>
    </div>
</template>
<script setup>
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const user = usePage().props.auth.user

const joinNowUrl = computed(() => {
    if (user) {
        if (usePage().props.auth.profile_incomplete) {
            return '/register/complete';
        }
        return '/dashboard';
    }
    return '/joinnow';
});

</script>

<style>
</style>
<!-- <style scoped>
* {
    font-family: 'Segoe UI', sans-serif!important;
}

.hero-section-modern {
    position: relative;
    padding: 80px 20px;
    background: linear-gradient(135deg, #E6EAFF, #f8f9ff);
    overflow: hidden;
}

.floating-bg {
    position: absolute;
    top: -20%;
    left: -10%;
    width: 150%;
    height: 150%;
    background: radial-gradient(circle, rgba(187, 224, 255, 0.4) 20%, transparent 70%);
    animation: float 12s ease-in-out infinite;
    z-index: 0;
}

@keyframes float {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(20px);
    }
}

.hero-wrapper {
    position: relative;
    z-index: 1;
    max-width: 1200px;
    margin: auto;
    display: flex;
    gap: 40px;
    align-items: center;
    justify-content: center;
}
@media(max-width:960px){
    .hero-wrapper{
        flex-direction: column;
    }
}
.hero-left {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    min-width: 320px;
    text-align: left;
}
@media(max-width:960px){
    .hero-left{
        padding: 23px;
        margin-top: -20px!important;
        align-items: center;
        text-align: center;
    }
}
.hero-right {
    flex: 1;
    display: flex;
    justify-content: center;
}

.hero-heading {
    font-size: 42px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 20px;
}

.gradient-text {
    background: linear-gradient(90deg, #4f46e5, #3b82f6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-left p {
    font-family: 'Segoe UI', sans-serif;
    font-size: 1.2rem;
    color: #374151;
    max-width: 700px;
    margin: 0 auto 30px;
}

/* .underline {
    border-bottom: 2px solid #3b82f6;
} */

.cta-section {
    margin-top: 30px;
}

.cta-options {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.pill {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    border-radius: 20px;
    color: #fff;
    width: 200px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    transform: translateY(0);
}

/* .pill:hover {
    transform: translateY(-6px) scale(1.04);
} */

.pill img {
    width: 64px;
    margin-bottom: 12px;
}

.pill-red {
    background: linear-gradient(135deg, #f43f5e, #fb7185);
}

.pill-blue {
    background: linear-gradient(135deg, #2563eb, #38bdf8);
}

.video-box {
    position: relative;
    width: 100%;
    aspect-ratio: 11 / 9;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
}
@media (max-width: 960px) {
    .video-box{
        margin: 20px;
    }
}
.video-box iframe {
    width: 100%;
    height: 100%;
}

.video-placeholder {
    width: 100%;
    height: 100%;
    position: relative;
    cursor: pointer;
}

.video-placeholder img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 20px;
}

.video-play-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 28px;
    background: rgba(0, 0, 0, 0.6);
    color: #fff;
    padding: 18px 26px;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    transition: background 0.3s;
}

.video-play-icon:hover {
    background: rgba(0, 0, 0, 0.75);
}

@media (max-width: 768px) {
    .hero-heading {
        font-size: 42px;
    }

    .pill {
        width: 100%;
    }
}
@media(max-width: 576px) {
    .hero-heading {
        font-size: 32px;
    }
}
</style> -->
