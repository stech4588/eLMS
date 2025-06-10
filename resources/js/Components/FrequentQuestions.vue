<template>
    <div class="parent-faq">
        <div class="faq-container">
            <h2 class="faq-title">Frequently Asked Questions</h2>
            <div v-for="(faq, index) in faqs" :key="index" class="faq-item">
                <a class="faq-question" @click="toggleFAQ(index)">
                    {{ faq.question }}
                    <span class="drop">
                        <div v-if="activeIndex === index"  alt="minus icon"
                            class="faq-icon" >+</div>
                        <div v-else src="@/assets/images/plus.svg" alt="plus icon" class="faq-icon" >+</div>
                    </span>
                </a>
                <div class="faq-answer" :class="{ open: activeIndex === index }">{{ faq.answer }}</div>
            </div>
            <Link href="/register">
                <div class="real-dsp-btns">
                    <button style="cursor: pointer;">JOIN NOW</button>
                </div>
                <div id="brxe-jkiusw" class="brxe-div button__circle"></div>
            </Link>

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
                    answer: " It depends how much you commit to MBM University. If you are dedicated and apply our expertise advice, it's typical to see return on your investment within the first few weeks. No profit is guaranteed. It's up to you to do the work."
                },
                {
                    question: "Do I need money once I join MBM University?",
                    answer: " In MBM University, we provide training on modern wealth-building strategies like e-commerce, copywriting and client acquisitionect, which can be started with no money."
                },
                {
                    question: "Will you be able to access all the courses after joining?",
                    answer: "Yes, when you join MBM University, you will get access to all of our courses, not just the one you choose."
                },
                {
                    question: "Can I cancel whenever I want?",
                    answer: "Yes, can cancel your membership whenever you want. That said, most students in MBM University choose to renew, often earning enough to cover their next month's membership and beyond."
                },
                {
                    question: "Does my age truly matter?",
                    answer: " Not at all! That said, if you're under 18, we recommend getting permission from a parent or guardian before joining MBM University.                                                                                                    Why spend money on the latest video games only to lose interest in a week? Instead, become part of our community, launch your own business, and amaze your friends and family by being the kid who levels up in real life"
                },
                {
                    question: " I have no experience with the skills you teach. Will that be an issue?",
                    answer: "Not at all! Our program is designed to guide you from beginner to expert, making it completely beginner-friendly.                                                                                              Plus, if you already have a business, we provide valuable insights to help you scale and grow."
                },
                {
                    question: "I have a busy schedule—can I still join?",
                    answer: "Absolutely! In MBM University, speed is key.                                                                                                            Our methods are built for fast implementation, so even with just a few hours a day, you can start applying these skills and earning your first dollar online."
                },
                {
                    question: "Does it matter that I'm from X country?",
                    answer: "No, it's not an issue at all!                                                    MBM University focuses on online income, so your location won’t hold you back."
                }
            ]
        };
    },
    methods: {
        toggleFAQ(index) {
            this.activeIndex = this.activeIndex === index ? null : index;
        }
    }
};
</script>

<style scoped>
a {
    text-decoration: none !important;
}

.real-dsp-btns button {
    margin-top: 10px;
    text-decoration: none;
    font-weight: 800 !important;
    border-radius: 50px;
    background-image: linear-gradient(310deg, #1f6893, #2d86ab);
    color: black;
    border: none;
    padding: 10px 20px;
}
.real-dsp-btns button:hover {
    background-image: linear-gradient(310deg, #38B6FF, #4CCAFF);
    color: white ;
    border: none;
    padding: 10px 30px;
    cursor: pointer;
    transition: padding 0.2s;
}
.button__circle {
    position: absolute;
    border-radius: 100%;
    background-color: rgba(255, 255, 255, 0.1);
    width: 0px;
    height: 0px;
    transition: width 0.2s ease-in, height 0.2s ease-in;
}
.button__circle:hover {
    width: 500px;
    height: 500px;
}

.parent-faq {
    background: #08080D;
}

.faq-container {
    max-width: 710px;
    margin: auto;
    padding: 20px;
    color: #fff;
    border-radius: 8px;
}

.faq-title {
    font-size: 40px;
    text-align: center;
    margin-bottom: 20px;
}

.faq-item {
    padding-top: 6px;
    padding-bottom: 6px;
    margin: 10px;
    border-radius: 12px;
    border: 2px solid #fff;
}

.faq-question {
    width: 97%;
    font-weight: bold;
    background: none;
    border: none;
    color: #fff;
    font-size: 18px;
    padding: 7px;
    text-align: left;
    display: flex;
    justify-content: space-between;
    cursor: pointer;
    align-items: center;
}

@media(max-width:430px) {
    .faq-question {
        font-size: 14px;
    }
}


.faq-icon {
    width: 20px;
    height: 20px;
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    text-align: left;
}

.faq-icon {
    width: 20px;
    height: 20px;
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    text-align: left;
    padding: 10px 15px;
    transition: max-height 0.9s ease;
    padding: 0 15px;
}

.faq-answer.open {
    max-height: 458px;
    text-align: left;
    padding-top: 0px !important;
    padding-bottom: 0px !important;
    transition: max-height 2s ease;
    padding: 10px 15px;
    border-radius: 0 0 8px 8px;
}

@media (max-width: 768px) {
    .faq-answer.open p {
        font-size: 15px;
    }
}

@media (max-width: 768px) {
    .faq-container {
        width: 90%;
    }
}

@media (max-width: 580px) {
    .faq-container {
        width: auto;
    }
}
.drop{
    color: skyblue;
    font-size: 24px;
}
</style>