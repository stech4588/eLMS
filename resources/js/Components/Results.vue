<template>
    <section class="mbm-featured-resources" id="result">
        <div class="mbm-header">
            <h2 class="mbm-title">FEATURED RESOURCES</h2>
            <a href="/joinnow" class="mbm-view-all">→ VIEW ALL RESOURCES</a>
        </div>

        <div class="mbm-cards-wrapper">
            <div class="mbm-card" v-for="(card, index) in cards" :key="index">
                <div class="mbm-card-image">
                    <img :src="card.image" alt="card image" />
                    <div class="mbm-arrow-container">
                        <div class="mbm-curve-bg"></div>
                        <div class="mbm-arrow-circle">
                            <span class="mbm-arrow">↗</span>
                        </div>
                    </div>
                </div>
                <div class="mbm-card-content">
                    <span class="mbm-badge">{{ card.type }}</span>
                    <h3 class="mbm-card-title">{{ card.title }}</h3>
                    <p class="mbm-description">{{ card.description }}</p>
                    <a href="/joinnow" class="mbm-view-now">→ View now</a>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: 'FeaturedResources',
    data() {
        return {
            cards: [
                {
                    type: 'WHITEPAPERS',
                    title: 'Why Learning Analytics Is Your Competitive Edge',
                    description:
                        'Discover how top learning leaders from Partners, The Walt Disney Company, VML, and Seertech Solutions use data-driven insights to transform learning into strategic impact.',
                    image: '/images/card-1.jpg'
                },
                {
                    type: 'CASE STUDIES',
                    title: 'Red Hat – Centralizing Enterprise Learning',
                    description:
                        'Learn how Red Hat overcame fragmented learning systems to deliver a unified, scalable eLearning solution across their global workforce.',
                    image: '/images/card-2.jpg'
                },
                {
                    type: 'CASE STUDIES',
                    title: 'ICU Medical – Scaling Global Training Consistency',
                    description:
                        'See how ICU Medical ensured consistent and compliant training delivery across regions by leveraging a robust LMS solution.',
                    image: '/images/card-3.png'
                }
            ]
        };
    }
};
</script>

<style scoped>
</style>
