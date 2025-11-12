<template>
    <div class="testimonial-section">
        <div class="content">
            <div class="content-1">
                <h2>Our Teaching</h2>
                <h2>Philosophy</h2>
            </div>
            <div class="content-2">
                <ul>
                    <li> Learn around <strong class="gold">like-minded people</strong>, and from <strong
                            class="gold">like-minded
                            mentors</strong>.
                    </li>
                    <li> Teaching practical skills <strong class="gold">through action</strong>, not just textbooks.
                    </li>
                    <li> Guiding our students to gain real-world experience <strong class="gold">promptly and
                            effectively</strong>.
                    </li>
                </ul>
            </div>

        </div>

        <!-- Swiper Slider -->
        <div class="slider-container">
            <swiper :slides-per-view="slidesPerView" :space-between="20" :loop="true" :autoplay="{
                delay: 3000,
                disableOnInteraction: false
            }" :navigation="true" :modules="modules" class="swiper-container">

                <swiper-slide>
                    <div class="testimonial-card">
                        <img src="/images/slide-1.webp" alt="Testimonial 1" />
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="testimonial-card">
                        <img src="/images/slide-2.webp" alt="Testimonial 1" />
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="testimonial-card">
                        <img src="/images/slide-3.webp" alt="Testimonial 1" />
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="testimonial-card">
                        <img src="/images/slide-4.webp" alt="Testimonial 1" />
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="testimonial-card">
                        <img src="/images/slide-5.webp" alt="Testimonial 1" />
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="testimonial-card">
                        <img src="/images/slide-6.webp" alt="Testimonial 1" />
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="testimonial-card">
                        <img src="/images/slide-7.webp" alt="Testimonial 1" />
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="testimonial-card">
                        <img src="/images/slide-8.webp" alt="Testimonial 1" />
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="testimonial-card">
                        <img src="/images/slide-9.webp" alt="Testimonial 1" />
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="testimonial-card">
                        <img src="/images/slide-10.webp" alt="Testimonial 1" />
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="testimonial-card">
                        <img src="/images/slide-11.webp" alt="Testimonial 1" />
                    </div>
                </swiper-slide>

            </swiper>
        </div>

        <router-link to="/join-now"> <button class="join-btn">JOIN NOW</button></router-link>
    </div>
</template>

<script>

import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/navigation";
import { Autoplay, Navigation } from "swiper/modules";

export default {
    components: { Swiper, SwiperSlide },
    data() {
        return {
            modules: [Autoplay, Navigation],
            slidesPerView: window.innerWidth <= 700 ? 1 : 2,
        };
    },
    mounted() {
        window.addEventListener("resize", this.updateSlides);
    },
    beforeUnmount() {
        window.removeEventListener("resize", this.updateSlides);
    },
    methods: {
        updateSlides() {
            this.slidesPerView = window.innerWidth <= 700 ? 1 : 2;
        }
    },

};
</script>

<style scoped>
.content-2 ul {
    list-style: none;
    /* Default bullets hatane ke liye */
    padding: 0;
}

.content-2 li {
    position: relative;
    line-height: 1.3;
    margin: 0;
    padding-left: 25px;
}

@media(max-width:580px) {
    .content-2 li {
        font-size: 12px;
    }
}

.content-2 li::before {
    content: "🔹";
    position: absolute;
    left: 0;
    top: -4px;
    font-size: 1.2rem;
}

.content-1 h2 {
    margin: 0;
    text-align: left;
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

.testimonial-section {
    text-align: center;
    background: #08080d;
    color: white;
    padding: 40px 20px;
}

.content {
    max-width: 1025px;
    margin-left: auto;
    margin-right: auto;
    display: flex;
    flex-direction: row;
    justify-content: center;
}

@media(max-width:715px) {
    .content {
        flex-direction: column;
    }
}

.content h2 {
    font-size: 2.5rem;
    font-weight: bold;
}

@media(max-width:580px) {
    .content h2 {
        font-size: 1.2rem;
    }
}

.content-1 {
    display: flex;
    flex-direction: column;
    justify-content: center;
    width: 40%;
}

@media(max-width:715px) {
    .content-1 {
        text-align: center;
        margin-bottom: 10px;
        align-items: center;
        width: 100%;
    }
}

.content-2 {
    width: 40%;
}

@media(max-width:715px) {
    .content-2 {
        width: 100%;
    }
}


ul {
    list-style: none;
    padding: 0;
    text-align: left;
    max-width: 600px;
    margin: 0 auto;
}

ul li {
    font-size: 0.9rem;
    margin: 10px 0;
}

.slider-container {
    max-width: 855px;
    margin: 20px auto;
}

@media(max-width:725px) {
    .slider-container {
        max-width: 100%;
    }
}

.swiper-container {
    overflow: hidden;
}

.testimonial-card {
    width: 415px;
    text-align: center;
}

@media(max-width:865px) {
    .testimonial-card {
        width: 330px;
    }
}

@media(max-width:700px) {
    .testimonial-card {
        width: 100%;
    }
}

.testimonial-card img {
    /* width: 100px; */
    /* height: 100px; */
    width: 100%;
    /* border-radius: 50%; */
    object-fit: cover;
}

.join-btn {
    font-weight: 800 !important;
    border-radius: 50px;
    background-image: linear-gradient(310deg, #38B6FF, #4CCAFF);
    color: black;
    padding: 10px 20px;
    font-size: 0.7rem;
    border-radius: 20px;
    margin-top: 20px;
    cursor: pointer;
    border: none;
}
.gold{
    color: #4ACFF8;
}
</style>
