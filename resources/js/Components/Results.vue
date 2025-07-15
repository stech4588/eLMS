<template>
    <section class="mbm-featured-resources" id="result">
        <div class="mbm-header">
            <h2 class="mbm-title">FEATURED RESOURCES</h2>
            <!-- <a :href="joinNowUrl" class="mbm-view-all">→ VIEW ALL RESOURCES</a> -->
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
                    <a :href="joinNowUrl" class="mbm-view-now">→ Join Now</a>
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
                    type: 'CASE STUDY',
                    title: 'Building a 7-Figure E-Commerce Empire',
                    description: 'Explore the strategies top entrepreneurs used to scale their online stores from zero to seven figures. Learn about product sourcing, marketing funnels, and customer retention.',
                    image: '/images/card-1.jpg'
                },
                {
                    type: 'GUIDE',
                    title: 'Navigating the World of Crypto Investing',
                    description: 'This guide breaks down the fundamentals of cryptocurrency investing, from understanding blockchain technology to identifying promising assets and managing risk.',
                    image: '/images/card-2.jpg'
                },
                {
                    type: 'BLUEPRINT',
                    title: 'The Ultimate Digital Marketing Blueprint',
                    description: 'Unlock the secrets to a successful online presence. This blueprint covers SEO, social media marketing, email campaigns, and content strategy to boost your brand.',
                    image: '/images/card-3.png'
                }
            ]
        };
    },
    computed: {
    user() {
      return this.$page.props.auth.user;
    },
    joinNowUrl() {
      if (this.user) {
        if (this.$page.props.auth.profile_incomplete) {
          return '/register/complete';
        }
        return '/dashboard';
      }
      return '/joinnow';
    },
  },
};
</script>

<style scoped>
</style>
