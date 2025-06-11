<template>
    <div class="main-hero-section">
        <div class="hero-logo">
            <img src="/images/MBM_Uni.png" alt="">
        </div>
        <div class="hero-description">
            <h1>Welcome to  <span class="real">MBM University</span></h1>
        </div>
        <div class="hero-video">
            <div v-if="isPlaying" class="video-container">
                <iframe width="100%" height="100%" :src="videoUrl" title="YouTube video player" frameborder="0"
                    allow="autoplay; encrypted-media" allowfullscreen>
                </iframe>
            </div>
            <div v-else class="video-thumbnail" @click="playVideo">
                <img src="/images/placeholder.webp" alt="">
                <div class="overlay">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100" fill="none">
                        <g clip-path="url(#clip0_713_490)">
                            <path
                                d="M50 0C22.3859 0 0 22.3859 0 50C0 77.6141 22.3859 100 50 100C77.616 100 100 77.6141 100 50C100 22.3859 77.616 0 50 0ZM50 90C27.9439 90 10 72.0561 10 50C10 27.9439 27.9439 10 50 10C72.0561 10 90 27.9439 90 50C90 72.0561 72.0561 90 50 90Z"
                                fill="url(#paint0_linear_713_490)"></path>
                            <path
                                d="M68.185 46.5008L43.9369 32.5008C40.6029 30.5768 37.875 32.1508 37.875 36.0008V64.0008C37.875 67.8508 40.6029 69.4268 43.9369 67.5008L68.185 53.5008C71.5189 51.5768 71.5189 48.4249 68.185 46.5008Z"
                                fill="url(#paint1_linear_713_490)"></path>
                        </g>
                        <defs>
                            <linearGradient id="paint0_linear_713_490" x1="50" y1="0" x2="50" y2="100"
                                gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FF9B01"></stop>
                                <stop offset="1" stop-color="#FFC468"></stop>
                            </linearGradient>
                            <linearGradient id="paint1_linear_713_490" x1="54.2802" y1="31.6797" x2="54.2802"
                                y2="68.3228" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FF9B01"></stop>
                                <stop offset="1" stop-color="#FFC468"></stop>
                            </linearGradient>
                            <clipPath id="clip0_713_490">
                                <rect width="100" height="100" fill="white"></rect>
                            </clipPath>
                        </defs>
                    </svg>
                </div>
            </div>
        </div>

        <div class="hero-advantage">
            <div class="advantage-container">
                <p><span class="gold">278,254+</span> have already taken advantage of this opportunity.</p>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            isPlaying: false,
            videoUrl: "https://www.youtube.com/embed/dQw4w9WgXcQ"
        };
    },
    methods: {
        playVideo() {
            this.isPlaying = true;
        }
    }
};
</script>
<style>

.video-container {
    width: 100%;
    height: 395px;
    border-radius: 37px;
    overflow: hidden;
}

.video-thumbnail {
    position: relative;
    cursor: pointer;
}

.video-thumbnail img {
    width: 100%;
    border-radius: 37px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
    border: 2px solid #fff;
}

.overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    justify-content: center;
    align-items: center;
}

.overlay svg {
    height: 100px;
    width: 100px;
    fill: #fff;
    filter: drop-shadow(1px 0 6px rgba(0, 0, 0, .3));
}

@media(max-width:768px) {
    .hero-video {
        width: 494px;
    }
    .video-container {
        height: 280px;
    }
    .overlay svg {
        height: 63px;
    }
}

@media(max-width:570px) {
    .hero-video {
        width: 362px;
    }
    .video-container {
        height: 220px;
    }
    .overlay svg {
        height: 45px;
    }
}

@media(max-width:430px) {
    .hero-video {
        width: 265px;
    }
    .video-container {
        height: 160px;
    }
}
.advantage-container p {
    font-size: 20px;
    margin: 9px;
}

@media(max-width:768px) {
    .advantage-container p {
        font-size: 13px;
    }
}

@media(max-width:570px) {
    .advantage-container p {
        margin: 0px;
    }
}

@media(max-width:430px) {
    .advantage-container p {
        font-size: 10px;
    }
}

.gold {
    color:  #87CEEB;
    font-weight: 900;
}

.advantage-container {
    margin-top: 30px;
    margin-bottom: 40px;
    border: 3px solid  #87CEEB;
    background-color: #f1f1f1;
    border-radius: 14px;
    padding: 10px 20px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
}

@media(max-width:390px) {
    .advantage-container {
        margin: 30px;
    }
}

.overlay svg {
    height: 100px;
    width: 100px;
    fill: #fff;
    filter: drop-shadow(1px 0 6px rgba(0, 0, 0, .3));
}

@media(max-width:768px) {
    .overlay svg {
        height: 63px;
    }
}

@media(max-width:570px) {
    .overlay svg {
        height: 45px;
    }
}

.overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    /* Ensure it spans the entire container */
    height: 100%;
    /* Same as above */
}



.hero-video {
    position: relative;
    /* Ensure relative positioning for absolute child */
    padding-top: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
    /* Center content inside */
    width: 702px;
}

@media(max-width:768px) {
    .hero-video {
        width: 494px;
    }
}

@media(max-width:570px) {
    .hero-video {
        width: 362px;
    }
}

@media(max-width:430px) {
    .hero-video {
        width: 265px;
    }
}


.hero-video img {
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
    border: 2px solid #fff;
    width: 100%;
    border-radius: 37px;
}

.main-hero-section {
    background-image: url(/images/hero-bg.webp);
    background-position: center center;
    background-size: cover;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.real {
    background-image: linear-gradient(310deg, #38B6FF, #4CCAFF);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-logo {
    margin-top: 40px;
    display: flex;
    justify-content: center;
}

@media(max-width:570px) {
    .hero-logo {
        margin-top: 4px;
    }
}

.hero-logo img {
    width: 163px;
    height: auto;
}

@media(max-width:768px) {
    .hero-logo img {
        width: 120px;
    }
}

@media(max-width:570px) {
    .hero-logo img {
        width: 80px;
    }
}

.hero-description h1 {
    font-size: 55px;
    font-weight: 700;
    margin-top: 2px;
    margin-bottom: 2px;
    line-height: 85px;
    letter-spacing: 3px;
    color: #08080D;
}

@media(max-width:768px) {
    .hero-description h1 {
        font-size: 36px;
    }
}

@media(max-width:570px) {
    .hero-description h1 {
        font-size: 23px;
        line-height: 45px;
    }
}

.hero-description h2 {
    font-size: 40px;
    margin-top: 2px;
    margin-bottom: 2px;
    color: #08080D;
    letter-spacing: 3px;
}

@media(max-width:768px) {
    .hero-description h2 {
        margin-top: -5px;
        font-size: 30px;
    }
}

@media(max-width:570px) {
    .hero-description h2 {
        margin-top: -5px;
        font-size: 16px;
    }
}
</style>