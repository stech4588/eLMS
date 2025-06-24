<template>
    <div class="main-faq">
        <section class="faq-wrapper">
    <div class="faq-header">
      <h2>Frequently Asked Questions</h2>
    </div>
    <div class="faq-list">
      <div
        v-for="(faq, index) in faqs"
        :key="index"
        class="faq-item"
      >
        <button
          class="faq-toggle"
          @click="toggleFAQ(index)"
        >
          <span>{{ faq.question }}</span>
          <svg
            :class="{ rotate: activeIndex === index }"
            class="icon"
            viewBox="0 0 24 24"
          >
            <path
              fill="currentColor"
              d="M7,10L12,15L17,10H7Z"
            />
          </svg>
        </button>
        <transition name="slide-fade">
          <div
            v-show="activeIndex === index"
            class="faq-answer"
          >
            {{ faq.answer }}
          </div>
        </transition>
      </div>
    </div>
    <Link href="/register" class="cta">
      <button class="join-button">JOIN NOW</button>
    </Link>
  </section>
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
            " It depends how much you commit to MBM University. If you are dedicated and apply our expertise advice, it's typical to see return on your investment within the first few weeks. No profit is guaranteed. It's up to you to do the work.",
        },
        {
          question: "Do I need money once I join MBM University?",
          answer:
            " In MBM University, we provide training on modern wealth-building strategies like e-commerce, copywriting and client acquisitionect, which can be started with no money.",
        },
        {
          question: "Will you be able to access all the courses after joining?",
          answer:
            "Yes, when you join MBM University, you will get access to all of our courses, not just the one you choose.",
        },
        {
          question: "Can I cancel whenever I want?",
          answer:
            "Yes, can cancel your membership whenever you want. That said, most students in MBM University choose to renew, often earning enough to cover their next month's membership and beyond.",
        },
        {
          question: "Does my age truly matter?",
          answer:
            " Not at all! That said, if you're under 18, we recommend getting permission from a parent or guardian before joining MBM University. Why spend money on the latest video games only to lose interest in a week? Instead, become part of our community, launch your own business, and amaze your friends and family by being the kid who levels up in real life",
        },
        {
          question:
            " I have no experience with the skills you teach. Will that be an issue?",
          answer:
            "Not at all! Our program is designed to guide you from beginner to expert, making it completely beginner-friendly. Plus, if you already have a business, we provide valuable insights to help you scale and grow.",
        },
        {
          question: "I have a busy schedule—can I still join?",
          answer:
            "Absolutely! In MBM University, speed is key. Our methods are built for fast implementation, so even with just a few hours a day, you can start applying these skills and earning your first dollar online.",
        },
        {
          question: "Does it matter that I'm from X country?",
          answer:
            "No, it's not an issue at all! MBM University focuses on online income, so your location won’t hold you back.",
        },
      ],
    };
  },
  methods: {
    toggleFAQ(index) {
      this.activeIndex = this.activeIndex === index ? null : index;
    },
  },
};
</script>

<style scoped>
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

/* Smooth Transition Animation */
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

@media (max-width: 600px) {
  .faq-header h2 {
    font-size: 26px;
  }
  .faq-toggle {
    font-size: 16px;
  }
  .faq-answer {
    font-size: 14px;
  }
}
</style>