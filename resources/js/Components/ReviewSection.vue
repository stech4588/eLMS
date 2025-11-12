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
      <swiper-slide v-for="review in reviews" :key="review.id">
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
      required: true,
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