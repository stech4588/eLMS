<template>
    <section class="why-lms-section" id="education">
        <div class="container">
            <div class="section-header">
                <h2>Why Choose ElevateU University?</h2>
                <!-- <p>
                    We’re the only platform that empowers both learner-centric and organization-driven outcomes through a scalable Learning Enablement Platform (LEP).
                </p> -->
            </div>

            <div class="features-grid">
                <div class="feature-card" v-for="(feature, index) in features" :key="index">
                    <div class="icon-box" v-html="feature.icon"></div>
                    <h3>{{ feature.title }}</h3>
                    <p>{{ feature.desc }}</p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const features = [
    {
        title: 'Content Creation & AI',
        desc: 'Leverage the power of Artificial Intelligence to personalize your learning.',
        icon: `<img src="/images/topicsLogo/AI-logo.png" alt="AI-Powered Learning" class="feature-icon-course w-20 h-20" />`
    },
    {
        title: 'Community Support',
        desc: 'Connect with peers and instructors in a supportive learning environment.',
        icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>`
    },
    {
        title: 'Copywriting',
        desc: 'Learn the art of persuasive writing to create compelling content that converts.',
        icon: `<img src="/images/topicsLogo/copywriting-logo.png" alt="Master Copywriting" class="feature-icon-course w-20 h-20" />`
    },
    {
        title: 'E-Commerce',
        desc: 'Build and scale your own successful online store with our expert-led courses.',
        icon: `<img src="/images/topicsLogo/e-commerce-logo.png" alt="E-Commerce Mastery" class="feature-icon-course w-20 h-20" />`
    },
    {
        title: 'Lead Generation',
        desc: 'Master the strategies to attract and convert high-quality leads for your business.',
        icon: `<img src="/images/topicsLogo/lead-logo.png" alt="Lead Generation" class="feature-icon-course w-20 h-20" />`
    },
    {
        title: 'Stocks',
        desc: 'Navigate the stock market with confidence and learn strategies for long-term growth.',
        icon: `<img src="/images/topicsLogo/stocks-logo.png" alt="Stock Market Investing" class="feature-icon-course w-20 h-20" />`
    }
]
</script>

<style scoped>


.why-lms-section {
    background: linear-gradient(135deg, #f0f4ff, #e2e8f0);
    padding: 30px 20px;
    font-family: 'Segoe UI', sans-serif;
    padding-top: 10px;
}

.container {
    max-width: 1200px;
    margin: auto;
}

.section-header {
    text-align: center;
    margin-bottom: 30px;
}

.section-header h2 {
    background: linear-gradient(90deg, #4f46e5, #3b82f6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 12px;
}

.section-header p {
    font-size: 1.125rem;
    color: #4b5563;
    max-width: 600px;
    margin: auto;
}

.features-grid {
    display: grid;
    gap: 30px;
    grid-template-columns: 1fr;
}

@media (min-width: 768px) {
    .features-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .features-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.feature-card {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    padding: 28px;
    border-radius: 16px;
    text-align: left;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding-top: 6px;
    padding-bottom: 8px;
}

.feature-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(59, 130, 246, 0.2);
}

.icon-box {
    color: #2563eb;
    margin-bottom: 6px;
}

.icon-box svg {
    width: 48px;
    height: 48px;
    transition: transform 0.4s;
}

.feature-card:hover .icon-box svg {
    transform: scale(1.15);
}

.feature-card h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 10px;
}

.feature-card p {
    color: #4b5563;
    font-size: 0.95rem;
    line-height: 1.6;
}
</style>
