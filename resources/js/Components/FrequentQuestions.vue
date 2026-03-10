<template>
  <div class="main-faq" id="faq">
    <!-- ===== ORIGINAL COMPONENT DESIGN (COMMENTED FOR BACKUP) =====
    <section class="faq-wrapper">
      <div class="faq-header">
        <h2>Frequently Asked Questions</h2>
      </div>
      <div class="faq-list">
        <div v-for="(faq, index) in faqs" :key="index" class="faq-item">
          <button class="faq-toggle" @click="toggleFAQ(index)">
            <span>{{ faq.question }}</span>
            <svg :class="{ rotate: activeIndex === index }" class="icon" viewBox="0 0 24 24">
              <path fill="currentColor" d="M7,10L12,15L17,10H7Z" />
            </svg>
          </button>
          <transition name="slide-fade">
            <div v-show="activeIndex === index" class="faq-answer">
              {{ faq.answer }}
            </div>
          </transition>
        </div>
      </div>
      <Link :href="joinNowUrl" class="cta">
      <button :href="joinNowUrl" class="join-btn">JOIN NOW</button>
      </Link>
    </section>
    -->

    <!-- NEW REDESIGNED UI -->
    <div class="faq-container">
      <div class="faq-inner">
        <h2 class="faq-title">FREQUENTLY ASKED <span class="text-black">QUESTIONS</span></h2>

        <div class="faq-grid">
          <div v-for="(faq, index) in faqs" :key="index" class="faq-box"
            :class="{ 'faq-box-active': activeIndex === index }">
            <button class="faq-trigger" @click="toggleFAQ(index)">
              <span class="faq-q-text">{{ faq.question }}</span>
              <div class="faq-icon-wrap">
                <span class="faq-plus-minus">{{ activeIndex === index ? '−' : '+' }}</span>
              </div>
            </button>

            <transition name="faq-slide">
              <div v-show="activeIndex === index" class="faq-content">
                <div class="faq-divider"></div>
                <p class="faq-a-text">{{ faq.answer }}</p>
              </div>
            </transition>
          </div>
        </div>

        <div class="faq-action">
          <Link :href="joinNowUrl" class="btn-faq-join">JOIN NOW</Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
export default {
  components: {
    Link,
  },
  data() {
    return {
      activeIndex: null,
      faqs: [
        {
          question: "How fast do I get my money back?",
          answer:
            "It depends how much you commit to ElevateU University. If you are dedicated and apply our expertise advice, it's typical to see return on your investment within the first few weeks. No profit is guaranteed. It's up to you to do the work.",
        },
        {
          question: "Do I need money once I join ElevateU University?",
          answer:
            "In ElevateU University, we provide training on modern wealth-building strategies like e-commerce, copywriting and client acquisition, which can be started with no money.",
        },
        {
          question: "Will you be able to access all the courses after joining?",
          answer:
            "Yes, when you join ElevateU University, you will get access to all of our courses, not just the one you choose.",
        },
        {
          question: "Can I cancel whenever I want?",
          answer:
            "Yes, can cancel your membership whenever you want. That said, most students in ElevateU University choose to renew, often earning enough to cover their next month's membership and beyond.",
        },
        {
          question: "Does my age truly matter?",
          answer:
            "Not at all! That said, if you're under 18, we recommend getting permission from a parent or guardian before joining ElevateU University. Why spend money on the latest video games only to lose interest in a week? Instead, become part of our community, launch your own business, and amaze your friends and family by being the kid who levels up in real life.",
        },
        {
          question:
            "I have no experience with the skills you teach. Will that be an issue?",
          answer:
            "Not at all! Our program is designed to guide you from beginner to expert, making it completely beginner-friendly. Plus, if you already have a business, we provide valuable insights to help you scale and grow.",
        },
        {
          question: "I have a busy schedule—can I still join?",
          answer:
            "Absolutely! In ElevateU University, speed is key. Our methods are built for fast implementation, so even with just a few hours a day, you can start applying these skills and earning your first dollar online.",
        },
        {
          question: "Does it matter that I'm from X country?",
          answer:
            "No, it's not an issue at all! ElevateU University focuses on online income, so your location won't hold you back.",
        },
      ],
    };
  },
  computed: {
    user() {
      return this.$page.props.auth.user;
    },
    joinNowUrl() {
      if (this.user) {
        if (this.$page.props.auth.profile_incomplete) {
          return '/register/complete';
        }
        return '/dashboard';
      }
      return '/joinnow';
    },
  },
  methods: {
    toggleFAQ(index) {
      this.activeIndex = this.activeIndex === index ? null : index;
    },
  },
};
</script>

<style scoped>
/* 
.join-btn {
  margin-top: 26px;
  padding: 12px 32px;
  background: linear-gradient(310deg, #38B6FF, #4CCAFF);
  color: black;
  border: none;
  border-radius: 30px;
  font-weight: 800;
  font-size: 15px;
  cursor: pointer;
  transition: 0.3s;
  align-self: flex-start;
}

.join-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(76, 202, 255, 0.4);
}

.main-faq {
    background:#E3F0FF;
    padding: 100px 0;
}
.faq-wrapper {
  background: linear-gradient(135deg, #122229, #0c2933, #00293b);
  padding: 60px 20px;
  color: #fff;
  border-radius: 20px;
  max-width: 900px;
  margin: auto;
  font-family: 'Segoe UI', sans-serif;
}
@media(max-width:900px){
    .faq-wrapper{
        border-radius: 0;
    }
}
.faq-header h2 {
  font-size: 36px;
  text-align: center;
  margin-bottom: 40px;
  font-weight: 800;
}

.faq-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.faq-item {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 14px;
  overflow: hidden;
  transition: all 0.4s ease;
}

.faq-toggle {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  padding: 16px 20px;
  font-size: 18px;
  color: #fff;
  text-align: left;
  font-weight: 600;
  background: transparent;
  border: none;
  cursor: pointer;
  transition: background 0.3s;
}

.faq-toggle:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.faq-answer {
  padding:20px;
  font-size: 16px;
  color: #ddd;
  line-height: 1.6;
  background-color: rgba(255, 255, 255, 0.03);
  overflow: hidden;
}

.icon {
  width: 20px;
  height: 20px;
  fill: #00c6ff;
  transition: transform 0.3s;
}

.rotate {
  transform: rotate(180deg);
}

.join-button {
  margin: 40px auto 0;
  display: block;
  background: linear-gradient(135deg, #00c6ff, #0072ff);
  padding: 14px 30px;
  font-size: 16px;
  font-weight: 800;
  border-radius: 50px;
  color: white;
  border: none;
  cursor: pointer;
  transition: transform 0.2s, background 0.4s;
}

.join-button:hover {
  background: linear-gradient(135deg, #38b6ff, #4ccaff);
  transform: scale(1.05);
}

.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all 0.4s ease;
  max-height: 1000px;
  opacity: 1;
}
.slide-fade-enter-from,
.slide-fade-leave-to {
  max-height: 0;
  opacity: 0;
  padding-top: 0;
  padding-bottom: 0;
}
*/

/* NEW REDESIGNED STYLES */
.main-faq {
  padding: 100px 20px;
  font-family: 'Inter', system-ui, sans-serif;
}

.faq-container {
  background-color: #272d34;
  padding: 60px 20px;
  border-radius: 20px;
  max-width: 900px;
  margin: 0 auto;
}

.faq-title {
  font-size: 2.2rem;
  font-weight: 700;
  color: #bdbbbb;
  text-align: center;
  margin-bottom: 60px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.text-black {
  color: #2ecc71;
}

.faq-grid {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.faq-box {
  background-color: #f8fafc;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: 1px solid #e2e8f0;
}

.faq-trigger {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 30px;
  background: transparent;
  border: none;
  cursor: pointer;
  text-align: left;
}

.faq-q-text {
  font-size: 1.1rem;
  font-weight: 600;
  color: #1e293b;
  transition: color 0.3s ease;
}

.faq-icon-wrap {
  font-size: 1.5rem;
  font-weight: 300;
  color: #94a3b8;
  transition: color 0.3s ease;
}

/* ACTIVE STATE LOGIC */
.faq-box-active {
  background-color: #000000 !important;
  border-color: #000000;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
}

.faq-box-active .faq-q-text {
  color: #ffffff;
}

.faq-box-active .faq-icon-wrap {
  color: #2ecc71;
}


.faq-content {
  padding: 0 30px 30px 30px;
}

.faq-divider {
  height: 1px;
  background-color: rgba(255, 255, 255, 0.1);
  margin-bottom: 20px;
}

.faq-a-text {
  font-size: 1rem;
  line-height: 1.6;
  color: #cbd5e1;
}

/* Transitions */
.faq-slide-enter-active,
.faq-slide-leave-active {
  transition: all 0.3s ease-out;
  max-height: 500px;
}

.faq-slide-enter-from,
.faq-slide-leave-to {
  max-height: 0;
  opacity: 0;
  overflow: hidden;
}

/* Action Button */
.faq-action {
  margin-top: 60px;
  text-align: center;
}

.btn-faq-join {
  display: inline-block;
  background-color: #2ecc71;
  color: #ffffff;
  padding: 16px 45px;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 700;
  text-decoration: none;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 1px;
  box-shadow: 0 4px 14px rgba(46, 204, 113, 0.3);
}

.btn-faq-join:hover {
  background-color: #27ae60;
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(46, 204, 113, 0.4);
}

@media (max-width: 640px) {
  .faq-title {
    font-size: 1.8rem;
  }

  .faq-q-text {
    font-size: 1rem;
  }

  .faq-trigger {
    padding: 20px;
  }
}
</style>