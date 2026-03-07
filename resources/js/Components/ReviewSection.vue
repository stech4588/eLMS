<template>
  <div class="main-review-section" id="testimonials">
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
        1036: {
          slidesPerView: 3,
        },
        720: {
          slidesPerView: 2,
        },
      },
      dummyReviews: [
        {
          id: 'dummy-1',
          user: {
            name: 'Ahmed Ali',
            profile_photo_url: 'https://ui-avatars.com/api/?name=Ahmed+Ali&size=200&background=0ea5e9&color=fff&bold=true&font-size=0.5',
          },
          rating: 5,
          comment: 'Excellent learning platform with comprehensive courses and experienced instructors. Highly recommended for professional development.',
        },
        {
          id: 'dummy-2',
          user: {
            name: 'Sara Khan',
            profile_photo_url: 'https://ui-avatars.com/api/?name=Sara+Khan&size=200&background=8b5cf6&color=fff&bold=true&font-size=0.5',
          },
          rating: 5,
          comment: 'The courses are well-structured and the interactive learning experience is amazing. Best decision I made for my career.',
        },
        {
          id: 'dummy-3',
          user: {
            name: 'Mohammad Hassan',
            profile_photo_url: 'https://ui-avatars.com/api/?name=Mohammad+Hassan&size=200&background=10b981&color=fff&bold=true&font-size=0.5',
          },
          rating: 4,
          comment: 'Great platform with quality content. The support team is very helpful and responsive to student needs.',
        },
        {
          id: 'dummy-4',
          user: {
            name: 'Fatima Sheikh',
            profile_photo_url: 'https://ui-avatars.com/api/?name=Fatima+Sheikh&size=200&background=ec4899&color=fff&bold=true&font-size=0.5',
          },
          rating: 5,
          comment: 'Outstanding educational experience with practical examples and real-world applications. The instructors are knowledgeable and supportive.',
        },
        {
          id: 'dummy-5',
          user: {
            name: 'Ali Raza',
            profile_photo_url: 'https://ui-avatars.com/api/?name=Ali+Raza&size=200&background=f59e0b&color=fff&bold=true&font-size=0.5',
          },
          rating: 5,
          comment: 'I have learned so much from this platform. The course materials are comprehensive and the learning path is well designed.',
        },
        {
          id: 'dummy-6',
          user: {
            name: 'Ayesha Malik',
            profile_photo_url: 'https://ui-avatars.com/api/?name=Ayesha+Malik&size=200&background=6366f1&color=fff&bold=true&font-size=0.5',
          },
          rating: 4,
          comment: 'Very satisfied with the course quality and delivery. The platform is user-friendly and the content is up-to-date with industry standards.',
        },
      ],
    };
  },
  computed: {
      user() {
          return this.$page.props.auth.user;
      },
      allReviews() {
          // Combine dynamic reviews with dummy reviews
          // Dynamic reviews come first, then dummy reviews
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
          if (words.length > 12) {
              return words.slice(0, 12).join(' ') + '...';
          }
          return comment;
      },
  }
};
</script>

<style scoped>
.mbm-view-now{
  margin-top: 20px;
}
::v-deep(.swiper-button-next),
::v-deep(.swiper-button-prev) {
    color: #87CEEB !important;
    width: 2px !important;
}

::v-deep(.swiper-button-next:hover),
::v-deep(.swiper-button-prev:hover) {
    color: #87CEEB !important;
}
::v-deep(.swiper-wrapper){
  margin-left: 30px;
  margin-right: 30px;
}
/* .reviewer-img img {
  width: 100px;
  height: 100px;
  border-radius: 50%;
}
.review-col-2-1 p {
  font-size: 18px;
} */
</style>