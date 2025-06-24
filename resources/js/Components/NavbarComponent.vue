<template>
  <nav class="modern-navbar">
    <div class="navbar-container">
      <div class="logo">
        <img src="/images/MBM_Uni.png" alt="" />
      </div>

      <ul class="nav-links" v-if="!menuOpen">
        <li><a @click="scrollToSection('access')">ACCESS</a></li>
        <li><a @click="scrollToSection('education')">EDUCATION</a></li>
        <li><a @click="scrollToSection('result')">RESULTS</a></li>
        <li><a @click="scrollToSection('choice')">CHOICE</a></li>
      </ul>

      <div class="action-buttons" v-if="!menuOpen">
        <Link href="/register" class="join">JOIN NOW</Link>
        <Link href="/login" class="login">LOG IN</Link>
      </div>

      <div class="hamburger" @click="toggleMenu">
        <span :class="{ open: menuOpen }"></span>
        <span :class="{ open: menuOpen }"></span>
        <span :class="{ open: menuOpen }"></span>
      </div>
    </div>

    <!-- Smooth Animated Dropdown -->
    <transition name="dropdown">
      <div v-show="menuOpen" class="custom-dropdown">
        <Link href="/register">JOIN NOW</Link>
        <a @click="scrollToSection('access')">ACCESS</a>
        <a @click="scrollToSection('education')">EDUCATION</a>
        <a @click="scrollToSection('result')">RESULTS</a>
        <a @click="scrollToSection('choice')">CHOICE</a>
        <Link href="/login">LOG IN</Link>
      </div>
    </transition>
  </nav>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
  components: { Link },
  data() {
    return {
      menuOpen: false,
    };
  },
  methods: {
    toggleMenu() {
      this.menuOpen = !this.menuOpen;
    },
    scrollToSection(id) {
      const section = document.getElementById(id);
      if (section) section.scrollIntoView({ behavior: 'smooth' });
      this.menuOpen = false;
    },
  },
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;700&display=swap');

* {
  box-sizing: border-box;
}

.modern-navbar {
  font-family: 'Segoe UI', sans-serif;
  background: #12121d;
  color: #fff;
  position: sticky;
  top: 0;
  width: 100%;
  z-index: 1000;
  padding: 0px 30px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.6);
}

.navbar-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
}

.logo {
  width: 90px;
}

.nav-links {
  display: flex;
  gap: 20px;
  list-style: none;
}

.nav-links a {
  position: relative;
  color: #ffffff;
  text-decoration: none;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  padding: 6px 10px;
  transition: color 0.3s ease;
}

.nav-links a::before,
.nav-links a::after {
  content: "";
  position: absolute;
  width: 0%;
  height: 2px;
  bottom: 0;
  background: #4ccaff;
  transition: all 0.3s ease;
}

.nav-links a::before {
  left: 0;
}

.nav-links a::after {
  right: 0;
}

.nav-links a:hover::before,
.nav-links a:hover::after {
  width: 100%;
}

.nav-links a:hover {
  color: #4ccaff;
  text-shadow: 0 0 5px #4ccaffaa;
}

.action-buttons {
  display: flex;
  gap: 10px;
}

.join,
.login {
  padding: 6px 14px;
  font-weight: bold;
  border-radius: 50px;
  text-decoration: none;
  transition: all 0.3s ease;
}

.join {
  background: linear-gradient(45deg, #38b6ff, #4ccaff);
  color: #000;
}

.login {
  border: 2px solid #4ccaff;
  color: #fff;
}

.login:hover {
  background-color: #4ccaff;
  color: #000;
}

.hamburger {
  display: none;
  flex-direction: column;
  gap: 5px;
  cursor: pointer;
}

.hamburger span {
  width: 25px;
  height: 3px;
  background-color: #ffffff;
  border-radius: 2px;
  transition: all 0.3s ease;
}

.hamburger span.open:nth-child(1) {
  transform: rotate(45deg) translate(5px, 5px);
}

.hamburger span.open:nth-child(2) {
  opacity: 0;
}

.hamburger span.open:nth-child(3) {
  transform: rotate(-45deg) translate(6px, -6px);
}

/* Smooth Dropdown Styling */
.custom-dropdown {
  background: rgba(30, 30, 47, 0.9);
  backdrop-filter: blur(8px);
  border-top: 1px solid #333;
  display: flex;
  padding-bottom:30px!important;
  flex-direction: column;
  gap: 15px;
  padding: 0 30px;
  border-radius: 0 0 16px 16px;
  overflow: hidden;
}

.custom-dropdown a {
  color: #ffffff;
  font-weight: 600;
  text-decoration: none;
  padding: 10px 0;
  border-bottom: 1px solid #333;
  transition: all 0.3s ease;
}

.custom-dropdown a:hover {
  color: #4ccaff;
  transform: translateX(5px);
}

/* Vue Transition Classes */
.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 0.4s ease;
  overflow: hidden;
}

.dropdown-enter-from,
.dropdown-leave-to {
  max-height: 0;
  opacity: 0;
  transform: scaleY(0.9);
}

.dropdown-enter-to,
.dropdown-leave-from {
  max-height: 500px;
  opacity: 1;
  transform: scaleY(1);
}

/* Responsive */
@media (max-width: 895px) {
  .nav-links,
  .action-buttons {
    display: none;
  }

  .hamburger {
    display: flex;
  }
}
</style>
