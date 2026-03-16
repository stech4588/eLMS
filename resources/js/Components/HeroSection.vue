<!-- 
<template>
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-container">
            <div class="hero-text">
                <h1>Welcome To<br> <span>ElevateU University</span></h1>
                <p>
                    Where ordinary people transform into global online entrepreneurs. 
                </p>
                <div class="hero-buttons">
                    <a :href="joinNowUrl" class="btn-primary">Browse Courses</a>
                    <a :href="loginUrl" class="btn-secondary">Login Now</a>
                </div>
            </div>

            <div class="mbm-right">
                <div class="mbm-chain">
                    <div ref="link1" class="mbm-link-piece mbm-top-link"></div>
                    <div ref="link2" class="mbm-link-piece mbm-mid-link"></div>
                    <div ref="link3" class="mbm-link-piece mbm-bottom-link"></div>
                </div>
            </div>
        </div>
    </section>
</template>
<script setup>
import { onMounted, ref,computed } from 'vue'
import { gsap } from 'gsap'
import { usePage } from '@inertiajs/vue3'

const link1 = ref(null)
const link2 = ref(null)
const link3 = ref(null)
const user = usePage().props.auth.user


const joinNowUrl = computed(() => {
  if (user) {
    if (usePage().props.auth.profile_incomplete) {
      return '/register/complete';
    }
    return '/dashboard';
  }
  return '/joinnow';
});

const loginUrl = computed(() => {

  if (user) {
    if (usePage().props.auth.profile_incomplete) {
      return '/register/complete';
    }
    return '/dashboard';
  }
  return '/login';
});

onMounted(() => {
  const pieces = [link1.value, link2.value, link3.value]

  const animateChain = () => {
    // Reset everything before starting
    gsap.set(pieces, {
      y: -200,
      opacity: 0,
      scale: 0.7,
      rotation: 0,
      filter: 'blur(0px)',
    })

    const tl = gsap.timeline({
      defaults: { ease: 'bounce.out' },
      onComplete: animateChain // loop forever
    })

    // Drop with bounce and stagger
    tl.to(pieces, {
      y: 0,
      opacity: 1,
      scale: 1,
      duration: 0.8,
      stagger: 0.2,
    })

    // Individual circular rotation
    tl.to(pieces, {
      rotation: 360,
      duration: 1.5,
      ease: 'power2.inOut',
      transformOrigin: '50% 50%',
      stagger: {
        each: 0.1,
        from: 'center',
      }
    }, '+=0.3')

    // Fade + blur as they disappear
    tl.to(pieces, {
      opacity: 0,
      scale: 0.5,
      duration: 0.6,
      ease: 'power1.in',
      filter: 'blur(6px)',
      stagger: 0.1,
    }, '+=0.2')
  }

  animateChain()
})
</script>
<style>

/* Right Side Chain */
.mbm-right {
  flex: 1 1 400px;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
}
@media(max-width:770px){
  .mbm-right{
    margin-top: -91px;
  }
}
.mbm-chain {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: -20px;
  margin-top: 30px;
}

.mbm-link-piece {
  width: 80px;
  height: 120px;
  border: 14px solid;
  border-radius: 50px;
  background-color: transparent;
  transform-origin: top center;
  box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
}

.mbm-top-link {
  border-color: #4dd6e8;
}

.mbm-mid-link {
  border-color: #0078d4;
  margin-top: -40px;
}

.mbm-bottom-link {
  border-color: #005bbb;
  margin-top: -40px;
}

/* Responsive */
@media (max-width: 768px) {
  .mbm-hero-container {
    flex-direction: column;
    text-align: center;
  }

  .mbm-title {
    font-size: 2rem;
  }

  .mbm-subtext {
    font-size: 1rem;
  }

  .mbm-buttons {
    justify-content: center;
  }

  .mbm-chain {
    margin-top: 40px;
  }

  .mbm-link-piece {
    width: 60px;
    height: 90px;
  }
}
.hero-section {
    position: relative;
    padding: 100px 20px;
    overflow: hidden;
    font-family: 'Segoe UI', sans-serif;
    background-color: transparent !important;
    height: 90vh;
    justify-content: center;
    align-items: center;
    display: flex;
}
@media(max-width:770px){
    .hero-section{
        height: 144vh;
    }
}
.hero-overlay {
    position: absolute;
    inset: 0;
    z-index: 0;
    background-color: transparent !important;
}

.hero-container {
    max-width: 1200px;
    display: flex;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}
@media(max-width:770px){
    .hero-container{
        flex-direction: column;
    }
}
.hero-text {
    text-align: center;
    margin-bottom: 60px;
}

.hero-text h1 {
    color: #fff;
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-align: left;
}

@media(min-width:1024px){
    .hero-text{
        width: 45rem;
    }
}

@media(max-width:550px) {
    .hero-text h1 {
        font-size: 2rem;
    }
}

.hero-text h1 span {
    color: #4ACFF8;
}

.hero-text p {
    font-size: 1.2rem;
    color: #fff;
    max-width: 700px;
    margin: 0 0 30px;
    line-height: 1.7;
    text-align: left;
}

@media(max-width:550px) {
    .hero-text p {
        font-size: 1rem;
    }
}

.hero-buttons {
    display: flex;
    flex-wrap: wrap;
    justify-content: left;
    gap: 15px;
    text-align: left;
}

.btn-primary {
    padding: 12px 28px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
    box-shadow: 0 4px 14px rgba(59, 130, 246, 0.4);
    background: linear-gradient(310deg, #38B6FF, #4CCAFF);
    color: #000000;
}

@media(max-width:550px) {
    .btn-primary {
        font-size: 12px;
    }
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(76, 202, 255, 0.4);
}

.btn-secondary {
    border: 2px solid #fff;
    color: #fff;
    padding: 12px 28px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
}

.btn-secondary:hover {
    background: #009ada;
}

.features {
    display: grid;
    grid-template-columns: 1fr;
    gap: 25px;
}

@media (min-width: 768px) {
    .features {
        grid-template-columns: repeat(3, 1fr);
    }
}

.feature-card {
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(10px);
    padding: 30px;
    border-radius: 20px;
    text-align: left;
    box-shadow: 0 6px 24px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
}

.icon-box {
    color: #1C355E;
    margin-bottom: 20px;
}

.icon-box svg {
    width: 50px;
    height: 50px;
}

.feature-card h3 {
    font-size: 1.3rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 10px;
}

.feature-card p {
    color: #4b5563;
    font-size: 0.95rem;
    line-height: 1.6;
}
</style>
-->

<!-- NEW REDESIGNED HERO SECTION -->
<template>
  <section class="video-hero">
    <!-- Background Video -->
    <video autoplay muted loop playsinline class="hero-video">
      <source src="https://www.seertechsolutions.com/wp-content/uploads/2025/05/Seertech-Sales-Video-NewVoice-1.mp4"
        type="video/mp4">
      Your browser does not support the video tag.
    </video>

    <!-- Black Overlay -->
    <div class="video-overlay"></div>

    <!-- Hero Content Center -->
    <div class="hero-content">
      <div class="hero-text-center">
        <h1>Welcome To<br> <span class="white-text">ElevateU University</span></h1>
        <p class="white-text">
          Where ordinary people transform into global online entrepreneurs.
        </p>
        <div class="hero-actions">
          <!-- <a :href="joinNowUrl" class="btn-outline-green">BROWSE COURSES</a> -->
          <a :href="loginUrl" class="btn-white-link login-btn">Login Now</a>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth.user);

const joinNowUrl = computed(() => {
  if (user.value) {
    if (page.props.auth.profile_incomplete) return '/register/complete';
    return '/dashboard';
  }
  return '/joinnow';
});

const loginUrl = computed(() => {
  if (user.value) {
    if (page.props.auth.profile_incomplete) return '/register/complete';
    return '/dashboard';
  }
  return '/login';
});
</script>

<style scoped>
.video-hero {
  position: relative;
  width: 100%;
  height: 80vh; /* Slightly reduced height further for compactness */
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  font-family: 'Inter', system-ui, sans-serif;
  background-color: #000;
}

.hero-video {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 100%;
  height: 100%;
  z-index: 0;
  transform: translate(-50%, -50%);
  object-fit: cover;
}

.video-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7); /* Lighter overlay for visibility */
  z-index: 0;
}

.hero-content {
  position: relative;
  z-index: 10;
  text-align: center;
  padding: 0 20px;
  max-width: 700px; /* Reduced max-width for better centering */
}

.hero-text-center h1 {
  color: #fff;
  font-size: clamp(1.6rem, 4.5vw, 2.6rem); /* Further reduced weight/size */
  font-weight: 800;
  line-height: 1.2;
  margin-bottom: 15px;
  text-transform: uppercase;
  letter-spacing: -0.5px;
}

.white-text {
  color: #ffffff !important;
}

.hero-text-center p {
  font-size: clamp(0.9rem, 2vw, 1.1rem);
  color: rgba(255, 255, 255, 0.9);
  margin-bottom: 35px;
  font-weight: 400;
  letter-spacing: 0.3px;
}

.hero-actions {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20px;
}

.btn-outline-green {
  display: inline-block;
  padding: 12px 35px;
  background: transparent;
  border: 2px solid #2ECC71;
  color: #2ECC71;
  text-decoration: none;
  font-weight: 700;
  font-size: 14px;
  letter-spacing: 1px;
  border-radius: 6px;
  transition: all 0.3s ease;
  text-transform: uppercase;
}

.btn-outline-green:hover {
  background: #2ECC71;
  color: #fff;
  box-shadow: 0 0 20px rgba(46, 204, 113, 0.4);
}

.btn-white-link {
  color: #fff;
  text-decoration: none;
  font-weight: 600;
  font-size: 14px;
  transition: opacity 0.2s;
  border-bottom: 1px solid transparent;
}

.btn-white-link:hover {
  opacity: 0.8;
  border-bottom: 1px solid #fff;
}
.login-btn{
  background: transparent;
  color: #fff;
  padding: 12px 35px;
  border-radius: 6px;
  text-decoration: none;
  font-size: 14px;
  letter-spacing: 1px;
  transition: all 0.3s ease;
  text-transform: uppercase;
  border: 2px solid #ffffff;
}
.login-btn:hover {
  background: #ffffff;
  color: #000000;
  border: 2px solid #ffffff;
}

@media (max-width: 600px) {
  .video-hero {
    height: 70vh;
  }
  .hero-actions {
    flex-direction: column;
    gap: 12px;
  }

  .btn-outline-green {
    width: 100%;
    padding: 12px 25px;
  }
}
</style>

