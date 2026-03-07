<template>
  <section class="future-section" id="pricing">
    <!-- ===== ORIGINAL COMPONENT DESIGN (COMMENTED FOR BACKUP) =====
    <div class="animated-gradient-bg"></div>
    <div class="main-future-container">
      <div class="future-row">
        <div class="future-col-1">
          <div class="future-head">
            <div class="future-head-row">
              <div class="future-head-col-1">
                <h1>Design The Life</h1>
                <h1>You Want</h1>
              </div>
              <div class="future-head-col-2">
                <a :href="logoUrl">
                  <img src="/images/MBM_Uni.png" alt="MBM Logo" />
                </a>
              </div>
            </div>
          </div>
          <div class="future-description">
            <p>
              ElevateU University students succeed because
              <span class="highlight">they take action.</span> We provide the tested path, and you shape your journey.
            </p>
            <p>
              <span class="highlight">Business is a skill.</span> Like any other, it can be developed with dedication, the right coaches, and a supportive learning environment.
            </p>
            <p>
              Our expert coaches practice what they teach and stay ahead with cutting-edge strategies and technologies. They provide actionable insights to help you with your business.
            </p>
            <p>
              In life,
              <span class="highlight">you have two choices:</span> seize opportunities, take action, and live without regrets, or hesitate, let chances slip away, and live with "what ifs."
            </p>
            <p>Which path will you choose?</p>
          </div>
        </div>

        <div class="future-col-2-m">
          <div class="future-col-2 glass-effect">
            <div class="future-col-2-description">
              <h2>Get Full Access</h2>
              <h1><span class="old-price">$123</span> <span class="new-price">$55</span>/month</h1>
              <p>Cancel membership at any time</p>
              <ul class="future-points">
                <li><div class="tick">✔</div>&nbsp; Guided step-by-step lessons</li>
                <li><div class="tick">✔</div>&nbsp; 19 modern business models</li>
                <li><div class="tick">✔</div>&nbsp; Access to industry experts</li>
                <li><div class="tick">✔</div>&nbsp; Community chat groups</li>
                <li><div class="tick">✔</div>&nbsp; No experience needed</li>
                <li><div class="tick">✔</div>&nbsp; Custom-made learning app</li>
                <li>
                  <div class="tick">
                    <img src="/images/money-icon.svg" alt="" />
                  </div>
                  &nbsp; 24/7 customer support Monthly price locked
                </li>
              </ul>
            </div>
            <Link :href="joinNowUrl"><button class="join-btn">JOIN NOW</button></Link>
          </div>
        </div>
      </div>
    </div>
    -->

    <!-- NEW REDESIGNED UI -->
    <div class="future-wrapper">
      <div class="future-container">
        <div class="future-grid">
          <!-- Content Side -->
          <div class="content-side">
            <h2 class="main-heading">DESIGN THE LIFE <br><span class="emphasized">YOU WANT</span></h2>
            
            <div class="narrative">
              <p>ElevateU University students succeed because <span class="text-green">they take action.</span> We provide the tested path, and you shape your journey.</p>
              
              <p><span class="text-green">Business is a skill.</span> Like any other, it can be developed with dedication, the right coaches, and a supportive learning environment.</p>
              
              <p>Our expert coaches practice what they teach and stay ahead with cutting-edge strategies — providing actionable insights for your growth.</p>
              
              <p>In life, <span class="text-green">you have two choices:</span> seize opportunities and live without regrets, or hesitate and live with "what ifs."</p>
              
              <p class="final-question">Which path will you choose?</p>
            </div>
          </div>

          <!-- Pricing Side -->
          <div class="pricing-side">
            <div class="pricing-card">
              <div class="card-header">
                <h3 class="card-title">GET FULL ACCESS</h3>
                <div class="price-box">
                  <span class="price-strikethrough">$123</span>
                  <span class="price-current">$55<small>/month</small></span>
                </div>
                <p class="price-disclaimer">Cancel membership at any time</p>
              </div>

              <ul class="benefit-list">
                <li><span class="dash">—</span> Guided step-by-step lessons</li>
                <li><span class="dash">—</span> 19 modern business models</li>
                <li><span class="dash">—</span> Access to industry experts</li>
                <li><span class="dash">—</span> Community chat groups</li>
                <li><span class="dash">—</span> No experience needed</li>
                <li><span class="dash">—</span> Custom-made learning app</li>
                <li><span class="dash">—</span> 24/7 customer support</li>
              </ul>

              <div class="action-box">
                <Link :href="joinNowUrl" class="btn-join-premium">JOIN NOW</Link>
                <p class="lock-notice">Monthly price locked for life</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { usePage, Link } from '@inertiajs/vue3';
export default {
  components: { Link },
  computed: {
    user() {
      return usePage().props.auth?.user;
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
    logoUrl() {
      if (this.user) {
        if (this.$page.props.auth.profile_incomplete) {
          return '/register/complete';
        }
        return '/dashboard';
      }
      return '/';
    }
  },
};
</script>

<style scoped>
/* 
.future-head-row{
  display: flex;
  flex-direction: row;
}
@media(max-width:533px){
  .future-head-row{
    flex-direction: column;
    justify-content: center;
    text-align: center;
    align-items: center;
  }
}
* {
  font-family: 'Segoe UI', sans-serif;
  box-sizing: border-box;
}

.future-section {
  position: relative;
  overflow: hidden;
  padding: 60px 20px;
  background: #12121D;
  color: white;
}
.main-future-container {
  position: relative;
  z-index: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  max-width: 1200px;
  margin: auto;
}

.future-row {
  display: flex;
  flex-direction: row;
  gap: 40px;
  flex-wrap: wrap;
  justify-content: center;
  align-items: flex-start;
}

.future-col-1 {
  flex: 1 1 500px;
  min-width: 300px;
}

.future-head-col-1 h1 {
  font-size: 2.8rem;
  font-weight: bold;
  text-align: left;
  margin: 0;
}

.future-head-col-2 img {
  width: 153px;
  margin-left: 10px;
}

.future-description p {
  line-height: 1.7;
  font-family: 'Segoe UI', sans-serif;
  font-size: 1.2rem;
  margin-bottom: 12px;
  text-align: left;
}
@media(max-width:532px){
  .future-description p {
    text-align: center;
  }
}
.highlight {
  color: #87CEEB;
  font-weight: bold;
}

.future-col-2-m {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  flex: 1 1 360px;
  min-width: 300px;
}

.future-col-2.glass-effect {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid #87CEEB;
  border-radius: 16px;
  padding: 30px;
  text-align: center;
  backdrop-filter: blur(10px);
  box-shadow: 0 0 35px rgba(135, 206, 235, 0.3);
}

.future-col-2 h2 {
  font-size: 1.8rem;
  margin-bottom: 10px;
}

.future-col-2 h1 {
  font-size: 2rem;
  margin: 10px 0;
  padding: 10px;
  border: 1px solid #87CEEB;
  border-radius: 10px;
}

.old-price {
  text-decoration: line-through;
  color: #bbb;
  margin-right: 8px;
}

.new-price {
  color: #87CEEB;
  font-weight: bold;
}

.future-col-2-description p {
  border-bottom: 2px solid #fff;
  padding-bottom: 12px;
}

.future-points {
  list-style: none;
  padding: 0;
  margin: 20px 0 0 0;
  text-align: left;
}

.future-points li {
  display: flex;
  align-items: center;
  font-size: 1rem;
  margin-bottom: 10px;
}

.tick {
  color: #87CEEB;
  font-weight: bold;
  display: flex;
  align-items: center;
}

.tick img {
  width: 18px;
  height: 18px;
}

.join-btn {
  background-image: linear-gradient(135deg, #38B6FF, #4CCAFF);
  border: none;
  border-radius: 40px;
  padding: 12px 25px;
  color: black;
  font-weight: bold;
  margin-top: 20px;
  cursor: pointer;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.join-btn:hover {
  transform: scale(1.05);
  box-shadow: 0 8px 16px rgba(72, 202, 255, 0.3);
}

@media (max-width: 400px) {
  .future-head-col-1 h1 {
    font-size: 1.8rem;
    text-align: center;
  }
  .future-col-2 h2 {
    font-size: 1.5rem;
  }
  .future-col-2 h1 {
    font-size: 1.5rem;
  }
  .future-col-2-description p {
    font-size: 0.9rem;
  }
  .future-points li {
    font-size: 0.85rem;
  }
}
*/

/* NEW REDESIGNED STYLES (REFINED PROPORTIONS) */
.future-section {
  background-color: #272d34;
  color: #ffffff;
  padding: 80px 20px;
  font-family: 'Inter', system-ui, sans-serif;
}

.future-container {
  max-width: 1100px;
  margin: 0 auto;
}

.future-grid {
  display: flex;
  gap: 60px;
  align-items: center;
  flex-wrap: wrap;
  justify-content: center;
}

.content-side {
  flex: 1 1 500px;
  min-width: 300px;
}

.main-heading {
  font-size: 2.2rem;
  font-weight: 700;
  line-height: 1.2;
  margin-bottom: 25px;
  letter-spacing: -0.5px;
  text-align: left;
}

.emphasized {
  color: #2ecc71;
}

.narrative p {
  font-size: 1.05rem;
  line-height: 1.6;
  margin-bottom: 15px;
  color: #cbd5e1;
  text-align: left;
}

.text-green {
  color: #2ecc71;
  font-weight: 700;
}

.final-question {
  font-size: 1.15rem;
  font-weight: 700;
  color: #ffffff;
  margin-top: 25px;
  text-align: left;
}

/* Pricing Side (Compact Card) */
.pricing-side {
  flex: 0 1 420px;
  width: 100%;
}

.pricing-card {
  background: #1e252b;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.4);
  border: 1px solid rgba(255, 255, 255, 0.1);
  text-align: center;
}

.card-title {
  font-size: 1rem;
  font-weight: 700;
  color: #94a3b8;
  margin-bottom: 12px;
  letter-spacing: 1px;
}

.price-box {
  margin-bottom: 5px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.price-strikethrough {
  font-size: 1.4rem;
  text-decoration: line-through;
  color: #64748b;
  margin-right: 12px;
}

.price-current {
  font-size: 2.8rem;
  font-weight: 700;
  color: #ffffff;
}

.price-current small {
  font-size: 1.1rem;
  font-weight: 400;
  color: #94a3b8;
}

.price-disclaimer {
  font-size: 0.85rem;
  color: #64748b;
  margin-bottom: 25px;
}

.benefit-list {
  list-style: none;
  padding: 0;
  margin: 0 0 30px 0;
  text-align: left;
}

.benefit-list li {
  font-size: 0.9rem;
  color: #cbd5e1;
  padding: 8px 0;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.dash {
  color: #2ecc71;
  font-weight: 700;
  margin-right: 10px;
}

.btn-join-premium {
  display: block;
  background-color: #2ecc71;
  color: #ffffff;
  padding: 13px 0;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 700;
  text-decoration: none;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.btn-join-premium:hover {
  background-color: #27ae60;
  transform: translateY(-2px);
  box-shadow: 0 10px 15px rgba(46, 204, 113, 0.2);
}

.lock-notice {
  font-size: 11px;
  color: #64748b;
  margin-top: 15px;
}

@media (max-width: 1024px) {
  .main-heading, .narrative p, .final-question {
    text-align: center;
  }
}

@media (max-width: 640px) {
  .main-heading {
    font-size: 1.8rem;
  }
  .price-current {
    font-size: 2.2rem;
  }
}
</style>