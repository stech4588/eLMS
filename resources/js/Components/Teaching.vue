<template>
    <div class="lms-mart" id="learn">
        <div class="lms-wrapper">
            <h2 class="lms-heading">
                The most <span class="lms-highlight">Configurable and Personalized</span><br />
                Built for the future of MBM University
            </h2>

            <div class="lms-box">
                <!-- Card 1 -->
                <div class="lms-card">
                    <img src="/images/AI.svg" alt="AI Icon" class="lms-icon" />
                    <h3 class="lms-title">PERSONALISED BY AI AUTOMATION</h3>
                    <p class="lms-desc">
                        Harness AI and machine learning to grow your people faster with personalized career pathways.
                    </p>
                    <a :href="joinNowUrl" class="lms-button">➜ Learn more</a>
                </div>

                <!-- Card 2 -->
                <div class="lms-card lms-border-x">
                    <img src="/images/cube.svg" alt="Cube Icon" class="lms-icon" />
                    <h3 class="lms-title">EXTENDED ENTERPRISE/ ECOMMERCE</h3>
                    <p class="lms-desc">
                        Expand learning to external partners and grow your revenue at global scale.
                    </p>
                    <a :href="joinNowUrl" class="lms-button">➜ Learn more</a>
                </div>

                <!-- Card 3 -->
                <div class="lms-card">
                    <img src="/images/workforce.svg" alt="Workforce Icon" class="lms-icon" />
                    <h3 class="lms-title">WORKFORCE ENABLEMENT</h3>
                    <p class="lms-desc">
                        Accelerate workforce capabilities, manage compliance requirements, and drive operational
                        outcomes.
                    </p>
                    <a :href="joinNowUrl" class="lms-button">➜ Learn more</a>
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
<style>
</style>
