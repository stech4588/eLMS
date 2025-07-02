<template>
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-container">
            <div class="hero-text">
                <h1>Welcome<br> <span>to MBM University</span></h1>
                <p>
                    Your gateway to quality education and professional development.
                    Discover courses that will help you achieve your goals.
                </p>
                <div class="hero-buttons">
                    <a href="/courses" class="btn-primary">Browse Courses</a>
                    <a href="/about" class="btn-secondary">Learn More</a>
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
import { onMounted, ref } from 'vue'
import { gsap } from 'gsap'

const link1 = ref(null)
const link2 = ref(null)
const link3 = ref(null)

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
    /* background: linear-gradient(120deg, #f0f4ff, #e5eaff); */
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
    /* background: linear-gradient(115deg, #102548 30%, #004c8d 65%, #009ada 100%) !important; */
    /* opacity: 0.3; */
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
    background: #789b4a;
    color: white;
    padding: 12px 28px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
    box-shadow: 0 4px 14px rgba(59, 130, 246, 0.4);
}

@media(max-width:550px) {
    .btn-primary {
        font-size: 12px;
    }
}

.btn-primary:hover {
    background: #009ada;
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
    color: #3b82f6;
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
