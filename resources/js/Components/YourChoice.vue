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
<!-- <template>
    <div class="main-nbm-banner" id="choice">
        <div class="nbm-banner-row">
            <section class="mbm-banner">
                <div class="mbm-banner-content">
                    <h2 class="mbm-banner-heading">
                        The Old World Teaches Servival, ElevateU University Teaches Modern Wealth Creation.
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
</template> -->

<template>
    <div class="choice-redesign" id="choice">
        <div class="choice-container">
            <div class="choice-card">
                <div class="choice-tag">PREMIUM ACCESS</div>
                <h2 class="choice-heading">
                    The Old World Teaches <span class="text-gray">Survival</span>, <br>
                    ElevateU University Teaches <span class="text-green">Modern Wealth Creation.</span>
                </h2>
                <p class="choice-subtext">
                    Break free from limitations. Contact us today to see how we can help you level up!
                </p>
                <div class="choice-action">
                    <Link :href="joinNowUrl" class="btn-primary-green">
                    JOIN ELEVATEU UNIVERSITY NOW ➜
                    </Link>
                </div>
            </div>
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

<style scoped>
/* NEW REDESIGNED STYLES */
.choice-redesign {
    background-color: #ffffff;
    padding: 120px 20px;
    font-family: 'Inter', system-ui, sans-serif;
    border-bottom: none !important;
}

.choice-container {
    max-width: 1100px;
    margin: 0 auto;
}

.choice-card {
    text-align: center;
}

.choice-tag {
    font-size: 14px;
    font-weight: 700;
    color: #2ecc71;
    letter-spacing: 2px;
    margin-bottom: 20px;
}

.choice-heading {
    font-size: 3rem;
    font-weight: 700;
    line-height: 1.1;
    color: #000000;
    margin-bottom: 30px;
    letter-spacing: -1px;
}

.text-gray {
    color: #64748b;
    font-weight: 400;
}

.text-green {
    color: #2ecc71;
}

.choice-subtext {
    font-size: 1.2rem;
    color: #475569;
    max-width: 700px;
    margin: 0 auto 50px;
    line-height: 1.6;
}

.choice-action {
    display: flex;
    justify-content: center;
}

.btn-primary-green {
    background-color: #2ecc71;
    color: #ffffff;
    padding: 20px 40px;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px -5px rgba(46, 204, 113, 0.4);
    display: inline-block;
}

.btn-primary-green:hover {
    background-color: #27ae60;
    transform: translateY(-3px);
    box-shadow: 0 20px 30px -10px rgba(46, 204, 113, 0.5);
}

@media (max-width: 768px) {
    .choice-heading {
        font-size: 2.2rem;
    }

    .choice-subtext {
        font-size: 1.1rem;
    }

    .btn-primary-green {
        width: 100%;
        padding: 18px 20px;
    }
}
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
    background: linear-gradient(90deg, #1C355E, #254a7a);
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
    border-bottom: 2px solid #1C355E;
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

.pill-accent {
    background: linear-gradient(135deg, #1C355E, #254a7a);
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
