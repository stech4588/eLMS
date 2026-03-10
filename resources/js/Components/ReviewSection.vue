<template>
  <div class="main-review-section" id="testimonials">
    <!-- ===== ORIGINAL COMPONENT DESIGN (COMMENTED FOR BACKUP) =====
    <div class="review-context">
      <h2>What Our Clients Think Of <span class="animated-2">ElevateU University.</span></h2>
      <p>We take pride in being transparent about our expertise and — we’re thrilled that our clients appreciate it too.
      </p>
    </div>  
    <swiper ref="mySwiper" :slides-per-view="1" :space-between="10" :loop="true" :navigation="true" :autoplay="{
      delay: 2500,
      disableOnInteraction: false,
    }" :modules="modules" :breakpoints="breakpoints" class="mySwiper">
      <swiper-slide v-for="review in allReviews" :key="review.id">
        <div class="review">
          <div class="review-row">
            <div class="review-col-1">
              <div class="reviewer-img">
                <img :src="review.user.profile_photo_url ? review.user.profile_photo_url : '/images/profile_photo.jpg'" alt="reviewer image">
              </div>
              <div class="review-col-2-2">
                <h3 class="animated-3">{{ review.user.name }}</h3>
                <StarRating :rating="review.rating" />
              </div>
            </div>
            <div class="review-col-2">
              <div class="review-col-2-1">
                <p>{{ truncateComment(review.comment) }}</p>
              </div>
            </div>
          </div>
        </div>
      </swiper-slide>
    </swiper>
    <button @click="joinNowUrl" class="mbm-view-now">→ Join Now</button>
    -->

    <!-- NEW REDESIGNED UI -->
    <div class="review-header">
      <h2 class="review-title">What Our Clients Think Of <br><span class="highlight">ElevateU University.</span></h2>
      <p class="review-subtitle">We take pride in being transparent about our expertise — and our clients love the
        results.</p>
    </div>

    <div class="reviews-container">
      <div class="portrait-grid">
        <div v-for="review in allReviews.slice(0, 5)" :key="review.id" class="portrait-card">
          <div class="portrait-img-container">
            <img :src="review.user.profile_photo_url ? review.user.profile_photo_url : '/images/profile_photo.jpg'"
              class="portrait-img" alt="Client Portrait">
            <div class="portrait-fade-overlay"></div>
            <div class="portrait-content">
              <div class="stars-box">
                <StarRating :rating="review.rating" />
              </div>
              <p class="review-quote-caps">{{ truncateComment(review.comment) }}</p>
              <h4 class="client-name">{{ review.user.name }}</h4>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- <div class="review-actions">
      <button @click="joinNowUrl" class="btn-join-now">Join ElevateU University</button>
    </div> -->
  </div>
</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import { Autoplay, Navigation } from 'swiper/modules';
import { router } from '@inertiajs/vue3';
import StarRating from './StarRating.vue';

export default {
  components: {
    Swiper,
    SwiperSlide,
    StarRating,
  },
  props: {
    reviews: {
      type: Array,
      default: () => [],
    },
  },
  setup() {
    return {
      modules: [Autoplay, Navigation],
    };
  },
  data() {
    return {
      breakpoints: {
        1200: { slidesPerView: 4 },
        900: { slidesPerView: 3 },
        600: { slidesPerView: 2 },
        0: { slidesPerView: 1 },
      },
      dummyReviews: [
        {
          id: 'dummy-1',
          user: {
            name: 'Ahmed Ali',
            profile_photo_url: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=600&auto=format&fit=crop',
          },
          rating: 5,
          comment: 'Outstanding educational experience with practical examples and real-world applications.',
        },
        {
          id: 'dummy-2',
          user: {
            name: 'Sara Khan',
            profile_photo_url: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=600&auto=format&fit=crop',
          },
          rating: 5,
          comment: 'The courses are well-structured and the interactive learning experience is truly amazing.',
        },
        {
          id: 'dummy-3',
          user: {
            name: 'John Smith',
            profile_photo_url: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=600&auto=format&fit=crop',
          },
          rating: 4,
          comment: 'Great platform with quality content. The support team is very responsive.',
        },
        {
          id: 'dummy-4',
          user: {
            name: 'Ayesha Malik',
            profile_photo_url: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=600&auto=format&fit=crop',
          },
          rating: 5,
          comment: 'I have learned so much here. The course materials are comprehensive and deep.',
        },
        {
          id: 'dummy-5',
          user: {
            name: 'Michael Chen',
            profile_photo_url: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?q=80&w=600&auto=format&fit=crop',
          },
          rating: 5,
          comment: 'World-class information shared by real multimillionaires who have done it.',
        },
      ],
    };
  },
  computed: {
    user() {
      return this.$page.props.auth.user;
    },
    allReviews() {
      const combined = [...this.reviews, ...this.dummyReviews];
      return combined;
    },
  },
  methods: {
    joinNowUrl() {
      if (this.user) {
        if (this.$page.props.auth.profile_incomplete) {
          router.get('/register/complete');
        } else {
          router.get('/dashboard');
        }
      } else {
        router.get('/joinnow');
      }
    },
    truncateComment(comment) {
      if (!comment) return '';
      const words = comment.split(' ');
      if (words.length > 10) {
        return words.slice(0, 10).join(' ') + '...';
      }
      return comment;
    },
  }
};
</script>

<style scoped>
.main-review-section {
  padding: 80px 20px;
  background-color: #000;
  /* Dark background as per design */
  color: #fff;
  font-family: 'Inter', system-ui, sans-serif;
  text-align: center;
}

.review-header {
  margin-bottom: 50px;
}

.review-title {
  font-size: 32px;
  font-weight: 800;
  line-height: 1.2;
  margin-bottom: 15px;
}

.highlight {
  color: #2ecc71;
  /* Green highlight to match theme */
}

.review-subtitle {
  font-size: 15px;
  color: #aaa;
  max-width: 600px;
  margin: 0 auto;
}

.reviews-container {
  max-width: 1400px;
  margin: 0 auto;
}

.portrait-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 20px;
  justify-content: center;
}

.portrait-card {
  position: relative;
  height: 300px;
  border-radius: 12px;
  overflow: hidden;
  transition: transform 0.3s ease;
  background: #111;
}

.portrait-card:hover {
  transform: translateY(-5px);
}

.portrait-img-container {
  height: 100%;
  width: 100%;
  position: relative;
}

.portrait-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: grayscale(100%) contrast(1.1);
  /* Match the grayscale design */
  transition: filter 0.3s ease;
}

.portrait-card:hover .portrait-img {
  filter: grayscale(0%) contrast(1.1);
  /* Subtle color shift on hover */
}

.portrait-fade-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(to bottom, transparent 30%, rgba(0, 0, 0, 0.85) 90%);
}

.portrait-content {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 15px;
  z-index: 2;
}

.stars-box {
  margin-bottom: 8px;
  display: flex;
  justify-content: center;
}

.review-quote-caps {
  font-size: 11px;
  font-weight: 700;
  color: #fff;
  text-transform: uppercase;
  margin-bottom: 8px;
  line-height: 1.3;
  letter-spacing: 0.5px;
}

.client-name {
  font-size: 10px;
  font-weight: 400;
  color: #ccc;
  margin-top: 2px;
}

.review-actions {
  margin-top: 50px;
}

.btn-join-now {
  background-color: #2ecc71;
  color: #fff;
  padding: 14px 40px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 16px;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
}

.btn-join-now:hover {
  background-color: #27ae60;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
}

::v-deep(.swiper-button-next),
::v-deep(.swiper-button-prev) {
  color: #2ecc71 !important;
  background: rgba(255, 255, 255, 0.1);
  width: 50px;
  height: 50px;
  border-radius: 50%;
  backdrop-filter: blur(5px);
}

::v-deep(.swiper-button-next:after),
::v-deep(.swiper-button-prev:after) {
  font-size: 20px;
  font-weight: bold;
}

@media (max-width: 1100px) {
  .portrait-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 768px) {
  .portrait-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .review-title {
    font-size: 24px;
  }
}

@media (max-width: 480px) {
  .portrait-grid {
    grid-template-columns: 1fr;
  }
}
</style>