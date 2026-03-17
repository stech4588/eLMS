<template>
    <div class="expandable-card">
        <!-- Top Image Section -->
        <div class="card-image" :style="{ backgroundImage: `url(${cardImage})` }">
            <!-- Gradient overlay to ensure text/icons look good if added later -->
            <div class="image-overlay"></div>
        </div>

        <!-- Content Section -->
        <div class="card-content">
            <h2 class="course-title">{{ title }}</h2>
            <p class="instructor-name">DELIVERED BY {{ instructor }}</p>

            <!-- Toggle Button -->
            <button @click="isExpanded = !isExpanded" class="toggle-btn" :class="{ 'active': isExpanded }">
                What you'll learn
                <span class="arrow" :class="{ 'rotated': isExpanded }">▶</span>
            </button>

            <!-- Expandable Details -->
            <transition name="expand">
                <div v-if="isExpanded" class="expanded-details">
                    <ul class="learning-list">
                        <li v-for="(point, index) in learningPoints" :key="index">
                            {{ point }}
                        </li>
                    </ul>
                </div>
            </transition>

            <!-- CTA Button -->
            <div class="card-footer">
                <Link :href="route('purchase-course.show', { course_id: id, title: title, image: cardImage, instructor: instructor, points: JSON.stringify(learningPoints) })" class="btn-get-access">
                    GET ACCESS NOW!
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    id: {
        type: [Number, String],
        required: true
    },
    title: {
        type: String,
        required: true
    },
    instructor: {
        type: String,
        default: 'GRANT CARDONE'
    },
    image: {
        type: String,
        default: '/images/skill_section_thumbnail.svg'
    },
    learningPoints: {
        type: Array,
        default: () => [
            "Learn WHY you don't have the income you need",
            "How to create more",
            "How to establish the right thought process",
            "How to take the right level of action",
            "Identify the lies that are holding you back",
            "Define your 10X income goals",
            "Kick the excuses holding you back to the curb"
        ]
    }
});

const isExpanded = ref(false);

// Get course image based on title - using computed property directly
const cardImage = computed(() => {
    const titleLower = props.title.toLowerCase().trim();
    
    // Get the image path based on keyword matching
    let imagePath = props.image; // default
    
    if (titleLower.includes('crypto investing')) imagePath = '/course-card-img/Crypto Investing.jpg';
    else if (titleLower.includes('crypto trading')) imagePath = '/course-card-img/Crypto Trading.png';
    else if (titleLower.includes('business & finance')) imagePath = '/course-card-img/Business & Finance.jpg';
    else if (titleLower.includes('client acquisition')) imagePath = '/course-card-img/Client Acquisition & Social Media.jpg';
    else if (titleLower.includes('content creation')) imagePath = '/course-card-img/Content Creation & AI.jpg';
    else if (titleLower.includes('ads mastery')) imagePath = '/course-card-img/Ads Mastery.jpg';
    else if (titleLower.includes('digital advertising')) imagePath = '/course-card-img/Digital Advertising.jpg';
    else if (titleLower.includes('digital marketing')) imagePath = '/course-card-img/Digital Marketing.jpg';
    else if (titleLower.includes('influencer network')) imagePath = '/course-card-img/Influencer Network Management.jpg';
    else if (titleLower.includes('lead generation')) imagePath = '/course-card-img/Lead Generation.jfif';
    else if (titleLower.includes('credit repair')) imagePath = '/course-card-img/Credit Repair.jpg';
    else if (titleLower.includes('drop shipping') || titleLower.includes('dropshipping')) imagePath = '/course-card-img/Drop Shipping.jpg';
    else if (titleLower.includes('social media automation')) imagePath = '/course-card-img/Social Media Automation.jpg';
    else if (titleLower.includes('seo consulting')) imagePath = '/course-card-img/SEO Consulting.jpg';
    else if (titleLower.includes('real estate')) imagePath = '/course-card-img/Real Estate Investing.jpg';
    else if (titleLower.includes('e-commerce') || titleLower.includes('ecommerce')) imagePath = '/course-card-img/E-Commerce.jpg';
    else if (titleLower.includes('copywriting') || titleLower.includes('copy writing')) imagePath = '/course-card-img/copy writing.jfif';
    else if (titleLower.includes('stocks')) imagePath = '/course-card-img/stocks.jpg';
    else if (titleLower.includes('defi')) imagePath = '/course-card-img/DeFi.jpg';
    else if (titleLower.includes('sales')) imagePath = '/course-card-img/sales.jpg';
    else if (titleLower.includes('airbnb')) imagePath = '/course-card-img/Airbnb.jpg';
    
    // Encode the URL to handle spaces and special characters
    return encodeURI(imagePath);
});
</script>

<style scoped>
.expandable-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    max-width: 380px;
    width: 100%;
    display: flex;
    flex-direction: column;
    font-family: 'Inter', system-ui, sans-serif;
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
}

.card-image {
    height: 220px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.1));
}

.card-content {
    padding: 30px 25px;
    text-align: center;
}

.course-title {
    font-size: 20px;
    font-weight: 700;
    color: #000000;
    line-height: 1.2;
    margin-bottom: 8px;
    text-transform: none;
}

.instructor-name {
    font-size: 13px;
    color: #666;
    font-weight: 500;
    letter-spacing: 0.5px;
    margin-bottom: 25px;
}

.toggle-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 12px;
    border: 2px solid #e0e0e0;
    background: white;
    border-radius: 8px;
    font-weight: 500;
    color: #55606e;
    font-size: 16px;
    cursor: pointer;
    gap: 12px;
    transition: all 0.3s ease;
    margin-bottom: 20px;
}

.toggle-btn.active {
    background: #2ecc71;
    border-color: #2ecc71;
    color: white;
}

.arrow {
    font-size: 14px;
    transition: transform 0.3s ease;
    color: inherit;
}

.arrow.rotated {
    transform: rotate(90deg);
}

.expanded-details {
    overflow: hidden;
    text-align: left;
    margin-bottom: 25px;
}

.learning-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.learning-list li {
    position: relative;
    padding-left: 20px;
    margin-bottom: 15px;
    color: #5d6775;
    font-size: 15px;
    line-height: 1.4;
}

.learning-list li::before {
    content: "•";
    position: absolute;
    left: 0;
    color: #5d6775;
    font-weight: bold;
}

.btn-get-access {
    display: inline-block;
    width: 100%;
    padding: 12px;
    border: 2px solid #2ecc71;
    background: transparent;
    color: #2ecc71;
    border-radius: 8px;
    font-weight: 500;
    font-size: 16px;
    text-decoration: none;
    text-transform: uppercase;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(46, 204, 113, 0.1);
}

.btn-get-access:hover {
    background: #2ecc71;
    color: white;
    box-shadow: 0 6px 15px rgba(46, 204, 113, 0.3);
}

/* Base expand transition */
.expand-enter-active,
.expand-leave-active {
    transition: all 0.3s ease;
    max-height: 500px;
}

.expand-enter-from,
.expand-leave-to {
    max-height: 0;
    opacity: 0;
    margin-bottom: 0;
}

@media (max-width: 480px) {
    .course-title {
        font-size: 20px;
    }

    .toggle-btn,
    .btn-get-access {
        font-size: 16px;
    }
}
</style>
