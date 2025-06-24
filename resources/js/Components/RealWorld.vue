<template>
  <section class="mbm-section">
    <div class="mbm-container">
      <div class="mbm-content">
        <div class="mbm-text">
          <div class="mbm-heading">
            <h1>What is MBM</h1>
            <h1>University?</h1>
          </div>
          <div class="mbm-logo">
            <a :href="user ? '/dashboard' : '/'">
              <img src="/images/MBM_Uni.png" alt="MBM Logo" />
            </a>
          </div>
        </div>

        <div class="mbm-description">
          <p>
            <span class="silver">MBM University</span> is a
            <span class="gold">global community</span>
            <span class="silver"></span> where like-minded individuals work toward achieving their personal
            goals.
          </p>
          <p>
            Every member receives
            <span class="gold">advanced training and mentoring</span> from our team of experienced industry specialists.
          </p>
          <p>
            Develop essential business skills with the help of our coaches through
            <span class="gold">our comprehensive education system.</span>
          </p>
          <Link href="/register">
            <button class="join-btn">JOIN NOW</button>
          </Link>
        </div>
      </div>

      <div class="mbm-image">
        <img src="/images/img1.png" alt="MBM Showcase" />
      </div>
    </div>
  </section>
</template>

<script>
import { usePage, Link } from '@inertiajs/vue3'

export default {
  components: {
    Link,
  },
  computed: {
    user() {
      return usePage().props.auth?.user
    },
  },
}
</script>

<style scoped>
.mbm-section {
  background: linear-gradient(145deg, #0a0a12, #141420);
  padding: 80px 20px;
  color: white;
  font-family: 'Segoe UI', sans-serif;
}

.mbm-container {
  max-width: 1200px;
  margin: auto;
  display: flex;
  flex-wrap: wrap;
  gap: 40px;
  align-items: center;
  justify-content: space-between;
}

.mbm-content {
  flex: 1 1 500px;
  display: flex;
  flex-direction: column;
  gap: 24px;
}
@media(max-width:408px){
    .mbm-content{
        justify-content: center;
    }
}
.mbm-text {
  display: flex;
  align-items: center;
  gap: 20px;
  flex-wrap: wrap;
  justify-content: flex-start;
}
@media(max-width:408px){
    .mbm-text {
        justify-content: center;
    }
}
.mbm-heading h1 {
  font-size: 40px;
  font-weight: 900;
  margin: 0;
  line-height: 1.2;
}

@media (max-width: 600px) {
  .mbm-heading h1 {
    font-size: 26px;
    text-align: center;
  }
}

.mbm-logo img {
  width: 150px;
  border-radius: 12px;
  transition: transform 0.3s ease;
}

.mbm-logo img:hover {
  transform: scale(1.05);
}

.mbm-description {
  text-align: left;
  display: flex;
  flex-direction: column;
  gap: 16px;
}
@media(max-width:407px){
    .mbm-description {
        text-align: center;
    }
}
.mbm-description p {
  line-height: 1.9;
  font-size: 17px;
  color: #e3e3e3;
  margin: 0;
}

.silver {
  font-weight: 800;
  color: #cfcfcf;
}

.gold {
  color: #87CEEB;
  font-weight: 900;
}

.join-btn {
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

.mbm-image {
  flex: 1 1 400px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.mbm-image img {
  width: 100%;
  max-width: 480px;
  border-radius: 16px;
  border: 2px solid #fff;
  object-fit: cover;
}

@media (max-width: 950px) {
  .mbm-container {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .join-btn {
    align-self: center;
  }
}
</style>
