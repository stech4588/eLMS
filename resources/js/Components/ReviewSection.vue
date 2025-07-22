<template>
  <div class="main-review-section" id="testimonials">
    <div class="review-context">
      <h2>What our clients think of <span class="animated-2">MBM University.</span></h2>
      <p>We take pride in being transparent about our expertise and — we’re thrilled that our clients appreciate it too.
      </p>
    </div>
    <swiper ref="mySwiper" :slides-per-view="1" :space-between="10" :loop="true" :autoplay="{
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
            </div>
            <div class="review-col-2">
              <div class="review-col-2-1">
                <p>{{ review.text }}</p>
              </div>
              <div class="review-col-2-2">
                <h3 class="animated-3">{{ review.name }}</h3>
                <p>{{ review.role }}</p>
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
import { Autoplay} from 'swiper/modules';
export default {
  components: {
    Swiper,
    SwiperSlide,
  },
  setup() {
      return {
        // modules: [Autoplay],
      };
    },
  data() {
    return {
      reviews: [
        {
          image: '/images/profile_photo.jpg',
          text: "“ The automated organization and secure cloud storage save me a lot       of time each week, allowing me to focus more on providing strategic advice to my clients. Its a very useful tool for any bookkeeper.”",
          name: "Mark Devis",
          role: "Professional User"
        },
        {
          image: '/images/profile_photo.jpg',
          text: "“ The automated organization and secure cloud storage save me a lot       of time each week, allowing me to focus more on providing strategic advice to my clients. Its a very useful tool for any bookkeeper.”",
          name: "Sarah Johnson",
          role: "Business Analyst"
        },
        {
          image: '/images/profile_photo.jpg',
          text: "“ The automated organization and secure cloud storage save me a lot       of time each week, allowing me to focus more on providing strategic advice to my clients. Its a very useful tool for any bookkeeper.”",
          name: "Emily Clarke",
          role: "Freelancer"
        },
        {
          image: '/images/profile_photo.jpg',
          text: "“ The automated organization and secure cloud storage save me a lot       of time each week, allowing me to focus more on providing strategic advice to my clients. Its a very useful tool for any bookkeeper.”",
          name: "Mark Devis",
          role: "Professional User"
        },
        {
          image: '/images/profile_photo.jpg',
          text: "“ The automated organization and secure cloud storage save me a lot       of time each week, allowing me to focus more on providing strategic advice to my clients. Its a very useful tool for any bookkeeper.”",
          name: "Sarah Johnson",
          role: "Business Analyst"
        },
        {
          image: '/images/profile_photo.jpg',
          text: "“ The automated organization and secure cloud storage save me a lot       of time each week, allowing me to focus more on providing strategic advice to my clients. Its a very useful tool for any bookkeeper.”",
          name: "Emily Clarke",
          role: "Freelancer"
        },
        {
          image: '/images/profile_photo.jpg',
          text: "“ The automated organization and secure cloud storage save me a lot       of time each week, allowing me to focus more on providing strategic advice to my clients. Its a very useful tool for any bookkeeper.”",
          name: "Mark Devis",
          role: "Professional User"
        },
        {
          image: '/images/profile_photo.jpg',
          text: "“ The automated organization and secure cloud storage save me a lot       of time each week, allowing me to focus more on providing strategic advice to my clients. Its a very useful tool for any bookkeeper.”",
          name: "Sarah Johnson",
          role: "Business Analyst"
        },
        {
          image: '/images/profile_photo.jpg',
          text: "“ The automated organization and secure cloud storage save me a lot       of time each week, allowing me to focus more on providing strategic advice to my clients. Its a very useful tool for any bookkeeper.”",
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
};
</script>

<style scoped>
.mbm-view-now{
  margin-top: 20px;
}
</style>