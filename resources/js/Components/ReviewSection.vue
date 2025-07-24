<template>
  <div class="main-review-section" id="testimonials">
    <div class="review-context">
      <h2>What our clients think of <span class="animated-2">MBM University.</span></h2>
      <p>We take pride in being transparent about our expertise and — we’re thrilled that our clients appreciate it too.
      </p>
    </div>
    <swiper ref="mySwiper" :slides-per-view="1" :space-between="10" :loop="true" :navigation="true" :autoplay="{
      delay: 2500,
      disableOnInteraction: false,
    }" :modules="modules" :breakpoints="breakpoints" class="mySwiper">
      <swiper-slide v-for="(review, index) in reviews" :key="index">
        <div class="review">
          <div class="review-row">
            <div class="review-col-1">
              <div class="reviewer-img">
                <img :src="review.image" alt="reviewer image">
              </div>
              <div class="review-col-2-2">
                <h3 class="animated-3">{{ review.name }}</h3>
                <p>{{ review.role }}</p>
              </div>
            </div>
            <div class="review-col-2">
              <div class="review-col-2-1">
                <p>{{ review.text }}</p>
              </div>
              
            </div>
          </div>
        </div>
      </swiper-slide>
    </swiper>
    <button @click="joinNowUrl" class="mbm-view-now">→ Join Now</button>
  </div>
</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import { Autoplay, Navigation } from 'swiper/modules';
import { router } from '@inertiajs/vue3';
export default {
  components: {
    Swiper,
    SwiperSlide,
  },
  setup() {
      return {
        modules: [Autoplay, Navigation],
      };
    },
  data() {
    return {
      reviews: [
        {
          image: '/images/profile_photo.jpg',
          text: " The automated organization and secure cloud storage save me a lot       of time each week, allowing me to focus more on providing strategic advice to my clients. Its a very useful tool for any bookkeeper.",
          name: "Mark Devis",
          role: "Professional User"
        },
        {
          image: '/images/profile_photo.jpg',
          text: " The automated organization and secure cloud storage save me a lot       of time each week, allowing me to focus more on providing strategic advice to my clients. Its a very useful tool for any bookkeeper.",
          name: "Sarah Johnson",
          role: "Business Analyst"
        },
        {
          image: '/images/profile_photo.jpg',
          text: " The automated organization and secure cloud storage save me a lot       of time each week, allowing me to focus more on providing strategic advice to my clients. Its a very useful tool for any bookkeeper.",
          name: "Emily Clarke",
          role: "Freelancer"
        },
        {
          image: '/images/profile_photo.jpg',
          text: " The automated organization and secure cloud storage save me a lot       of time each week, allowing me to focus more on providing strategic advice to my clients. Its a very useful tool for any bookkeeper.",
          name: "Mark Devis",
          role: "Professional User"
        },
        {
          image: '/images/profile_photo.jpg',
          text: " The automated organization and secure cloud storage save me a lot       of time each week, allowing me to focus more on providing strategic advice to my clients. Its a very useful tool for any bookkeeper.",
          name: "Sarah Johnson",
          role: "Business Analyst"
        },
        {
          image: '/images/profile_photo.jpg',
          text: " The automated organization and secure cloud storage save me a lot       of time each week, allowing me to focus more on providing strategic advice to my clients. Its a very useful tool for any bookkeeper.",
          name: "Emily Clarke",
          role: "Freelancer"
        },
        {
          image: '/images/profile_photo.jpg',
          text: " The automated organization and secure cloud storage save me a lot       of time each week, allowing me to focus more on providing strategic advice to my clients. Its a very useful tool for any bookkeeper.",
          name: "Mark Devis",
          role: "Professional User"
        },
        {
          image: '/images/profile_photo.jpg',
          text: " The automated organization and secure cloud storage save me a lot       of time each week, allowing me to focus more on providing strategic advice to my clients. Its a very useful tool for any bookkeeper.",
          name: "Sarah Johnson",
          role: "Business Analyst"
        },
        {
          image: '/images/profile_photo.jpg',
          text: " The automated organization and secure cloud storage save me a lot       of time each week, allowing me to focus more on providing strategic advice to my clients. Its a very useful tool for any bookkeeper.",
          name: "Emily Clarke",
          role: "Freelancer"
        },
        // Add more reviews as needed
      ],
      breakpoints: {
        1036: {
          slidesPerView: 3,
        },
        720: {
          slidesPerView: 2,
        },
      },
    };
  },
  computed: {
      user() {
          return this.$page.props.auth.user;
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