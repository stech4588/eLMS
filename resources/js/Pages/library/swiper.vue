<template>

<div>
        <swiper
  :slides-per-view="5"
  :space-between="30"
  :navigation="true"
  :mousewheel="true"
  :effect="'coverflow'"
  :modules="modules"  
  :coverflow-effect="{
    rotate: 50,
    stretch: 0,
    depth: 100,
    modifier: 1,
    slideShadows: true
  }"
  class="mySwiper"
>
  <swiper-slide v-for="(slide, index) in slides" :key="index">
    <div class="p-4 bg-white rounded-lg shadow text-center">
      {{ slide.title }}
    </div>
  </swiper-slide>
</swiper>
</div>
     

</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/effect-coverflow';
import 'swiper/css/mousewheel';

import { Navigation, Mousewheel, EffectCoverflow } from 'swiper/modules';

export default {
  components: {
    Swiper,
    SwiperSlide,
  },
  data() {
    return {
      slides: [
        { title: 'Slide 1' },
        { title: 'Slide 2' },
        { title: 'Slide 3' },
        { title: 'Slide 4' },
        { title: 'Slide 5' },
        { title: 'Slide 6' },
        { title: 'Slide 1' },
        { title: 'Slide 2' },
        { title: 'Slide 3' },
        { title: 'Slide 4' },
        { title: 'Slide 5' },
        { title: 'Slide 6' },
      ],
      modules: [Navigation, Mousewheel] // Add this line
    };
  },
};
</script>




<style>
.section_box {
    background-color: white;
    padding: 20px;
    border-radius: 16px;
}

.home_page_style {
    background-color: #acacac;
}

.skill_buttons {
    font-weight: 600;
    border-color: black;
}

.left_right_button {
    border: 1px solid #000000;
    border-radius: 50%;
    padding: 5px 10px;
}

@media (max-width: 430px) {
    .left_right_button {
        width: 35px;
    }
}

.main_skill_buttons {
    margin-top: 16px;
    margin-bottom: 35px;
}

.release_top_section {
    margin-bottom: 35px;
}

@media (max-width: 430px) {
    .main_left_right_button {
        display: flex;
    }
}
.mySwiper {
  width: 100%;
  padding: 40px 0;
}
.swiper-slide {
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  font-weight: bold;
  border-radius: 10px;
}




</style>
