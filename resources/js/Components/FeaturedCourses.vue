<template>
  <section class="featured-courses" id="education">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">ElevateU University's complete learning environment</h2>
        <p class="section-subtitle">everything you need, all in one place:</p>
      </div>

      <div class="courses-grid">
        <ExpandableCourseCard v-for="(course, index) in courses" :key="index" :title="course.title"
          :instructor="course.instructor" :image="course.image" :learningPoints="course.learningPoints" />
      </div>
    </div>
  </section>
</template>

<script setup>
import ExpandableCourseCard from './ExpandableCourseCard.vue';

const courses = [
  {
    title: "E-Commerce",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "Setting up your online store",
      "Finding winning products",
      "Scaling to 6 and 7 figures",
      "Managing inventory and logistics"
    ]
  },
  {
    title: "Copywriting",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "The psychology of persuasion",
      "Writing high-converting sales letters",
      "Mastering email marketing",
      "Crafting compelling headlines"
    ]
  },
  {
    title: "Stocks",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "Fundamental and technical analysis",
      "Building a long-term portfolio",
      "Risk management strategies",
      "Understanding market cycles"
    ]
  },
  {
    title: "Crypto Investing",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1621761191319-c6fb62004040?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "Understanding blockchain technology",
      "Evaluating altcoins and projects",
      "Secure storage and security",
      "Long-term investment strategies"
    ]
  },
  {
    title: "Business & Finance",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "Managing business cash flow",
      "Tax strategies for entrepreneurs",
      "Building a scalable business model",
      "Financial planning for success"
    ]
  },
  {
    title: "Crypto Trading",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1624555130581-1d9cca783bc0?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "Day trading and swing trading basics",
      "Chart patterns and indicators",
      "Managing emotions in trading",
      "Position sizing and leverage"
    ]
  },
  {
    title: "Content Creation & AI",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "Leveraging AI for content generation",
      "Building a personal brand",
      "Mastering social media algorithms",
      "Scaling production with tools"
    ]
  },
  {
    title: "Client Acquisition & Social Media",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "Finding and closing high-ticket clients",
      "Optimizing social media profiles",
      "Outreach and lead generation",
      "Retention and client management"
    ]
  },
  {
    title: "Ads Mastery",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1533750349088-cd871a92f312?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "Facebook, Google, and TikTok ads",
      "Targeting and retargeting strategies",
      "Creative testing and optimization",
      "Scaling budgets effectively"
    ]
  },
  {
    title: "DeFi",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1639762681485-074b7f938ba0?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "Yield farming and liquidity mining",
      "Understanding decentralized exchanges",
      "Managing risks in DeFi protocols",
      "Navigating the ecosystem safely"
    ]
  },
  {
    title: "Digital Advertising",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "Multi-channel ad strategies",
      "Ad copywriting and design",
      "Analytics and tracking mastery",
      "Maximizing ROI on spend"
    ]
  },
  {
    title: "Sales",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "The art of the close",
      "Handling objections effectively",
      "Building rapport and trust",
      "Advanced negotiation techniques"
    ]
  },
  {
    title: "Airbnb",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "Rental arbitrage and management",
      "Optimizing listings for ranking",
      "Automating guest communication",
      "Scaling to multiple properties"
    ]
  },
  {
    title: "Influencer Network Management",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1611162617474-5b21e879e113?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "Building and managing a creator team",
      "Monetization through networks",
      "Campaign management and tracking",
      "Scaling through partnerships"
    ]
  },
  {
    title: "Digital Marketing",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1432888498266-38ffec3eaf0a?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "Comprehensive marketing strategies",
      "Funnel building and optimization",
      "Conversion rate optimization (CRO)",
      "Growth hacking techniques"
    ]
  },
  {
    title: "Lead Generation",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "B2B and B2C lead generation",
      "Cold outreach mastery",
      "Automating the sales funnel",
      "Qualifying and nurturing leads"
    ]
  },
  {
    title: "Credit Repair",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "Understanding credit reporting",
      "Strategies for credit improvement",
      "Leveraging credit for business",
      "Financial literacy and planning"
    ]
  },
  {
    title: "Drop Shipping",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1518133910546-b6c2fb7d79e3?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "Product research for dropshipping",
      "Building high-converting stores",
      "Managing suppliers and shipping",
      "Scaling through paid traffic"
    ]
  },
  {
    title: "Social Media Automation",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "Automating posting and engagement",
      "Building massive social reach",
      "Tooling and workflow efficiency",
      "Monetizing automated accounts"
    ]
  },
  {
    title: "SEO Consulting",
    instructor: "ELEVATEU EXPERT",
    image: "https://images.unsplash.com/photo-1571721795195-a2ca2d3370a9?auto=format&fit=crop&w=600&q=80",
    learningPoints: [
      "On-page and off-page SEO",
      "Keyword research and strategy",
      "Building a consulting business",
      "Technical SEO and auditing"
    ]
  }
];
</script>


<style scoped>
.featured-courses {
  padding: 100px 20px;
  background-color: #f9f9f9;
}

.container {
  max-width: 1300px;
  margin: 0 auto;
}

.section-header {
  text-align: center;
  margin-bottom: 60px;
}

.section-title {
  font-size: 36px;
  font-weight: 700;
  color: #000;
  margin-bottom: 15px;
}

.section-subtitle {
  font-size: 18px;
  color: #666;
}

.courses-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  justify-items: center;
  align-items: start;
  /* Prevents cards in the same row from stretching */
}

@media (max-width: 768px) {
  .section-title {
    font-size: 28px;
  }

  .courses-grid {
    grid-template-columns: 1fr;
  }
}
</style>
