<template>
    <section class="why-lms-section">
        <div class="container">
            <div class="section-header">
                <h2>Why Choose LMS?</h2>
                <p>
                    Discover the features that make our learning platform stand out from the rest.
                </p>
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
        title: 'Customizable Learning',
        desc: 'Tailor your learning experience to match your goals and preferences.',
        icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>`
    },
    {
        title: 'Community Support',
        desc: 'Connect with peers and instructors in a supportive learning environment.',
        icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>`
    },
    {
        title: 'Quality Content',
        desc: 'Access high-quality, up-to-date content curated by industry experts.',
        icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>`
    },
    {
        title: 'Fast Learning',
        desc: 'Accelerate your learning with our efficient and effective teaching methods.',
        icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>`
    },
    {
        title: 'Time Management',
        desc: 'Learn at your own pace with flexible scheduling options.',
        icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
    },
    {
        title: 'Resource Library',
        desc: 'Access a vast collection of learning resources and materials.',
        icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>`
    }
]
</script>

<style scoped>
.why-lms-section {
    background: linear-gradient(135deg, #f0f4ff, #e2e8f0);
    padding: 80px 20px;
    font-family: 'Segoe UI', sans-serif;
}

.container {
    max-width: 1200px;
    margin: auto;
}

.section-header {
    text-align: center;
    margin-bottom: 50px;
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
}

.feature-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(59, 130, 246, 0.2);
}

.icon-box {
    color: #2563eb;
    margin-bottom: 18px;
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
