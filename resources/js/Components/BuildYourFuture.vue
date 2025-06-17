<template>
  <div class="main-future-container">
    <div class="future-row">
      <div class="future-col-1">
        <div class="future-head">
          <div class="future-head-row">
            <div class="future-head-col-1">
              <h1>Design the life</h1>
              <h1>You want</h1>
            </div>
            <div class="future-head-col-2">
               <a :href="user ? '/dashboard' : '/'">  
               <img src="/images/MBM_Uni.png" alt="MBM Logo">
             </a>
            </div>
          </div>
        </div>
        <div class="future-description">
          <p> MBM University students succeed because <span class="highlight">they take action.</span> We provide the
            tested path, and you shape your journey.</p>
          <p><span class="highlight">Business is a skill.</span> Like any other, it can be developed with dedication,
            the right coaches, and a supportive learning environment.</p>
          <p>Our expert coaches practice what they teach and stay ahead with cutting-edge strategies and technologies.
            They provide actionable insights to help you with your business.</p>
          <p>In life, <span class="highlight">you have two choices:</span> seize opportunities, take action, and live
            without regrets, or hesitate, let chances slip away, and live with "what ifs."</p>
          <p>Which path will you choose?</p>
        </div>
      </div>
      <div class="future-col-2-m">
        <div class="future-col-2">
          <div class="future-col-2-description">
            <h2>Get Full Access</h2>
            <h1><span class="old-price">${{ oldPrice }}</span> <span class="new-price">${{ newPrice }}</span>/month</h1>
            <p>Cancel membership at any time</p>
            <ul class="future-points">
              <li>
                <div class="tick">✔</div> &nbsp; Guided
                step-by-step lessons
              </li>
              <li>
                <div class="tick">✔</div> &nbsp; 19 modern
                business models
              </li>
              <li>
                <div class="tick">✔</div> &nbsp; Access to
                industry experts
              </li>
              <li>
                <div class="tick">✔</div> &nbsp; Community
                chat groups
              </li>
              <li>
                <div class="tick">✔</div> &nbsp; No
                experience needed
              </li>
              <li>
                <div class="tick">✔</div> &nbsp; Custom-made
                learning app
              </li>
              <li>
                <div class="tick"><img src="/images/money-icon.svg" alt="" srcset=""></div> &nbsp; 24/7
                customer support Monthly price locked
              </li>
            </ul>

          </div>

        </div>
        <Link href="/register"><button class="join-btn">JOIN NOW</button></Link>
      </div>

    </div>
  </div>
</template>
<script>
import apiClient from '@/Config/apiClient.js';
import { usePage, Link } from '@inertiajs/vue3';
export default {
  components: {
    Link,
  },
  computed: {
    user() {
      return usePage().props.auth?.user;
    }
  },
  data() {
    return {
      oldPrice: '',
      newPrice: '',
      features: [],
    };
  },
  mounted() {
    this.fetchPricingData();
  },
  methods: {
    async fetchPricingData() {
      try {
        const response = await apiClient.get('/api/pricing-plans');
        if (response.data.length > 0) {
          const plan = response.data[0]; // Fetch the first plan
          this.oldPrice = plan.old_price;
          this.newPrice = plan.new_price;
          this.features = plan.features;
        }
      } catch (error) {
        console.error('Error fetching pricing data:', error);
      }
    },
  },
};
</script>
<style scoped>
@media(max-width:580px) {
  .future-col-2-description h1 {
    font-size: 20px !important;
  }
}
@media(max-width:580px) {
  .future-col-2-description h2 {
    font-size: 20px !important;
  }
}

.future-col-2-description p {
  padding-bottom: 19px;
  border-bottom: 2px solid #fff;
}
@media(max-width:580px) {
  .future-col-2-description p {
    font-size: 15px !important;
  }
}
.future-col-2-m {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

.main-future-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #08080D;
  color: white;
  padding: 40px;
}

@media(max-width:580px) {
  .main-future-container {
    padding: 20px;
  }
}

.future-row {
  display: flex;
  max-width: 1200px;
  width: 100%;
  flex-direction: row;
  gap: 40px;
  justify-content: center;
  align-items: self-start;
}

@media(max-width:955px) {
  .future-row {
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
}

.future-col-1 {
  width: 50%;
}

@media(max-width:955px) {
  .future-col-1 {
    width: 100%;
  }
}

.future-head-row {
  display: flex;
  align-items: flex-start;
}

@media(max-width:580px) {
  .future-head-row {
    justify-content: center;
    align-items: center;
    flex-direction: column-reverse;
  }
}

.future-head-col-1 h1 {
  font-size: 3rem;
  font-weight: bold;
  margin: 0;
  text-align: left;
}

@media(max-width:580px) {
  .future-head-col-1 h1 {
    font-size: 2rem;
    text-align: center;
  }
}

.future-head-col-2 img {
  width: 130px;
  margin-left: 10px;
}

.future-description p {
  font-size: 1.1rem;
  text-align: left;
  line-height: 1.6;
  margin-bottom: 10px;
}

@media(max-width:580px) {
  .future-description p {
    text-align: center;
  }
}

.future-description .highlight {
  color: #87CEEB;
  font-weight: bold;
}

.future-col-2 {
  /* width: 21%; */
  background: #08080D;
  padding: 30px;
  border-radius: 12px;
  border: 2px solid #87CEEB;
  text-align: center;
  box-shadow: 0 0 15px rgba(97, 244, 232, 0.514);
}

.future-col-2 h2 {
  font-size: 2rem;
  margin-bottom: 10px;
}

.future-col-2 h1 {
  border-radius: 19px;
  font-size: 2.2rem;
  border: 2px solid #87CEEB;
  padding: 13px;
  margin-bottom: 10px;
}

.old-price {
  text-decoration: line-through;
  font-weight: bold;
}

.new-price {
  color: #87CEEB;
  font-weight: bold;
}

.future-points {
  list-style: none;
  padding: 0;
  margin: 20px 0;
  text-align: left;
}

.future-points li {
  font-size: 1.1rem;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
}
@media(max-width:580px){
  
.future-points li {
  font-size: 0.7rem;
}
}
.join-btn {
  border: none;
  margin-top: 30px;
  width: 121px;
  font-weight: 800 !important;
  border-radius: 50px;
  background-image: linear-gradient(310deg, #38B6FF, #4CCAFF);
  color: black;
  padding: 8px 0px;

}

/* .join-btn:hover {
    background-color: #e76f51;
    transform: scale(1.05);
  } */

@media (max-width: 768px) {
  .future-row {
    flex-direction: column;
    align-items: center;
  }

  .future-col-2 {
    width: 100%;
    max-width: 400px;
  }
}

@media (max-width: 580px) {

  .future-col-2 {
    width: auto;
    padding: 15px;
  }
}
</style>