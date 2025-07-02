<template>
  <section class="mbm-hero">
    <div class="mbm-hero-container">
      <!-- LEFT SIDE CONTENT -->
      <div class="mbm-left">
        <h1 class="mbm-title">
          <span class="mbm-bold">THE</span> <span class="mbm-blue">MOST</span><br />
          CONFIGURABLE<br />
          LEARNING<br />
          SOLUTION<br />
          <span class="mbm-bold">ON THE MARKET</span>
        </h1>
        <p class="mbm-subtext">
          We replace an average of <span class="mbm-bold">7 different systems</span> with one powerful platform
        </p>
        <div class="mbm-buttons">
          <button class="mbm-request-btn">➜ Request Demo</button>
          <button class="mbm-learn-btn">➜ Learn more</button>
        </div>
      </div>

      <!-- RIGHT SIDE CHAIN -->
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


<style scoped>
/* Layout */
.mbm-hero {
  background: linear-gradient(to right, #0b1f3a, #027ac1);
  color: white;
  padding: 60px 20px;
  border-bottom-left-radius: 40px;
  border-bottom-right-radius: 40px;
  font-family: 'Segoe UI', sans-serif;
}

.mbm-hero-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  max-width: 1200px;
  margin: auto;
}

/* Left Side */
.mbm-left {
  flex: 1 1 500px;
  padding: 20px;
}

.mbm-title {
  font-size: 2.8rem;
  line-height: 1.3;
}

.mbm-blue {
  color: #2ebeff;
}

.mbm-bold {
  font-weight: bold;
}

.mbm-subtext {
  margin-top: 20px;
  font-size: 1.1rem;
  color: #ffffffcc;
}

.mbm-buttons {
  margin-top: 30px;
  display: flex;
  gap: 16px;
  flex-wrap: wrap;
}

.mbm-request-btn {
  background: #92d050;
  color: white;
  padding: 12px 24px;
  border-radius: 25px;
  border: none;
  font-weight: 600;
  cursor: pointer;
}

.mbm-learn-btn {
  background: transparent;
  color: white;
  border: 2px solid white;
  padding: 12px 24px;
  border-radius: 25px;
  font-weight: 600;
  cursor: pointer;
}

/* Right Side Chain */
.mbm-right {
  flex: 1 1 400px;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
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
</style>
