<template>
  <section class="teaching-philosophy">
    <div class="tp-container">
      <!-- Left content -->
      <div class="tp-text">
        <h2>Our Teaching</h2>
        <h2>Philosophy</h2>
        <ul>
          <li>
            Learn around <span class="gold">like-minded people</span>, and from
            <span class="gold">like-minded mentors</span>.
          </li>
          <li>
            Teaching practical skills <span class="gold">through action</span>, not just textbooks.
          </li>
          <li>
            Guiding our students to gain real-world experience
            <span class="gold">promptly and effectively</span>.
          </li>
        </ul>
      </div>

      <!-- Slider content -->
      <div class="tp-slider">
        <swiper
          :slides-per-view="slidesPerView"
          :space-between="20"
          :loop="true"
          :autoplay="{ delay: 3000, disableOnInteraction: false }"
          :navigation="true"
          :modules="modules"
          class="swiper-container"
        >
          <swiper-slide v-for="n in 11" :key="n">
            <div class="slide-card">
              <img :src="`/images/slide-${n}.webp`" :alt="`Slide ${n}`" />
            </div>
          </swiper-slide>
        </swiper>
      </div>

      <!-- Join Button -->
      <Link href="/register">
        <button class="tp-button">JOIN NOW</button>
      </Link>
    </div>
  </section>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'
import 'swiper/css/navigation'
import { Autoplay, Navigation } from 'swiper/modules'

export default {
  components: { Swiper, SwiperSlide, Link },
  data() {
    return {
      modules: [Autoplay, Navigation],
      slidesPerView: window.innerWidth <= 700 ? 1 : 2
    }
  },
  mounted() {
    window.addEventListener('resize', this.updateSlides)
  },
  beforeUnmount() {
    window.removeEventListener('resize', this.updateSlides)
  },
  methods: {
    updateSlides() {
      this.slidesPerView = window.innerWidth <= 700 ? 1 : 2
    }
  }
}
</script>

<style scoped>
/* Make arrows visible and styled properly */
::v-deep(.swiper-button-prev) {
  color: #38b6ff;
  border-radius: 50%;
  width: 44px;
  height: 44px;
  top: 55%;
  transform: translateY(-50%);
  z-index: 10;
  display: flex;
  left: -10px;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}
/* Make arrows visible and styled properly */
::v-deep(.swiper-button-next) {
  color: #38b6ff;
  border-radius: 50%;
  width: 44px;
  height: 44px;
  top: 55%;
  right: -10px;
  transform: translateY(-50%);
  z-index: 10;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

/* Make arrow icons look nicer */
::v-deep(.swiper-button-next::after),
::v-deep(.swiper-button-prev::after) {
  font-size: 33px;
  font-weight: bold;
}

.teaching-philosophy {
  background-color: #12121d;
  color: #ffffff;
  font-family: 'Inter', sans-serif;
  padding: 60px 20px;
  text-align: center;
}

.tp-container {
  max-width: 1200px;
  margin: 0 auto;
}

.tp-text {
  max-width: 900px;
  margin: 0 auto 50px auto;
  text-align: left;
}

.tp-text h2 {
  font-size: 2.8rem;
  font-weight: 700;
  margin: 0;
  line-height: 1.2;
}

@media (max-width: 600px) {
  .tp-text h2 {
    font-size: 1.6rem;
    text-align: center;
  }
}

.tp-text ul {
  margin-top: 20px;
  padding-left: 0;
  list-style: none;
}

.tp-text li {
  font-size: 1rem;
  margin-bottom: 15px;
  padding-left: 24px;
  position: relative;
  line-height: 1.6;
}

.tp-text li::before {
  content: '✔';
  color: #38b6ff;
  position: absolute;
  left: 0;
  font-size: 1.1rem;
  top: 2px;
}

.gold {
  color: #87CEEB;
  font-weight: 700;
}

.tp-slider {
  max-width: 900px;
  margin: 0 auto 30px auto;
}

.swiper-container {
  overflow: hidden;
}

.slide-card {
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 18px rgba(0, 0, 0, 0.4);
}

.slide-card img {
  width: 100%;
  height: auto;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.slide-card:hover img {
  transform: scale(1.02);
}

.tp-button {
  font-weight: 800;
  border-radius: 30px;
  background-image: linear-gradient(310deg, #38b6ff, #4ccaff);
  color: black;
  padding: 12px 30px;
  font-size: 0.9rem;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease-in-out;
}

.tp-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(72, 192, 255, 0.3);
}
</style>
