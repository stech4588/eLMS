<template>
    <section class="mbm-featured-resources">
        <div class="mbm-header">
            <h2 class="mbm-title">FEATURED RESOURCES</h2>
            <button class="mbm-view-all">→ VIEW ALL RESOURCES</button>
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
                    <button class="mbm-view-now">→ View now</button>
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
                    title: 'Why Learning Analytics is Your Weapon',
                    description:
                        'Featuring insights from learning leaders at Partners, A Walt Disney Company, VML, and Seertech Solutions As...',
                    image: '/images/card-1.jpg'
                },
                {
                    type: 'CASE STUDIES',
                    title: 'Red Hat Case Study',
                    description:
                        'Red Hat, a global leader in enterprise open-source software, struggled with fragmented learning technology that hindere...',
                    image: '/images/card-2.jpg'
                },
                {
                    type: 'CASE STUDIES',
                    title: 'ICU Medical Case Study',
                    description:
                        'ICU Medical, a global leader in medical device manufacturing, faced challenges in delivering consistent training across its...',
                    image: '/images/card-3.png'
                }
            ]
        };
    }
};
</script>

<style scoped>
</style>
