<template>
  <section class="future-section" id="pricing">
    <div class="animated-gradient-bg"></div>
    <div class="main-future-container">
      <div class="future-row">
        <!-- Left Column -->
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

        <!-- Right Column -->
        <div class="future-col-2-m">
          <div class="future-col-2 glass-effect">
            <div class="future-col-2-description">
              <h2>Get Full Access</h2>
              <!-- <h1><span class="old-price">${{ oldPrice }}</span> <span class="new-price">${{ newPrice }}</span>/month</h1> -->
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
</style>