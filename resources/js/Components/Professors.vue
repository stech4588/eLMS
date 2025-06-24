<template>
  <section class="meet-section">
    <div class="meet-container">
      <div class="meet-content">
        <h2 class="meet-title">Meet The Professors</h2>
        <p class="meet-paragraph">
          We call our instructors “Professors”, but their teachings come from
          <strong class="highlight">MBM University experience</strong>, not just theory.
        </p>
        <p class="meet-paragraph">
          All of our professors are hand-picked by MBM and reached
          <strong class="highlight">seven-figure success</strong> using the methods they teach inside MBM University.
        </p>
        <p class="meet-paragraph">
          Their mission is to <strong class="highlight">guide, teach, and mentor</strong> you throughout your business journey every single day.
        </p>
        <Link href="/register" class="join-button-wrapper">
          <button class="join-button">JOIN NOW</button>
        </Link>
      </div>

      <div class="meet-image-wrapper">
        <img src="/images/meet-the-professors.png" alt="Meet the Professors" class="meet-image" />
      </div>
    </div>
  </section>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
  name: 'MeetProfessors',
  components: {
    Link
  }
};
</script>

<style scoped>
/* Font family applied to everything in the section */
.meet-section {
  background-color: #12121D;
  font-family: 'Segoe UI', sans-serif;
  padding: 60px 20px;
  color: #ffffff;
}

.meet-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1200px;
  margin: 0 auto;
  gap: 40px;
  flex-wrap: wrap;
}

.meet-content {
  flex: 1 1 500px;
  text-align: left;
}

.meet-title {
  font-size: 48px;
  font-weight: 800;
  margin-bottom: 20px;
}

.meet-paragraph {
  font-size: 18px;
  line-height: 1.7;
  margin-bottom: 16px;
}

.highlight {
  color: #38B6FF;
  font-weight: 700;
}

.join-button-wrapper {
  margin-top: 20px;
}

.join-button {
  background-image: linear-gradient(310deg, #38B6FF, #4CCAFF);
  color: #000;
  font-weight: 700;
  font-size: 16px;
  padding: 12px 30px;
  border-radius: 30px;
  border: none;
  cursor: pointer;
  transition: transform 0.3s ease;
}

.join-button:hover {
  transform: scale(1.05);
}

.meet-image-wrapper {
  flex: 1 1 400px;
  display: flex;
  justify-content: center;
}

.meet-image {
  width: 100%;
  max-width: 450px;
  border-radius: 12px;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
  .meet-container {
    flex-direction: column;
    text-align: center;
  }

  .meet-content {
    text-align: center;
  }

  .meet-title {
    font-size: 32px;
  }

  .meet-paragraph {
    font-size: 16px;
  }

  .join-button {
    font-size: 14px;
    padding: 10px 24px;
  }
}
</style>
