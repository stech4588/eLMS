<template>
  <nav class="navbar">
    <div class="navbar-content">
      <Link href="/joinnow" class="join-now">JOIN NOW</Link>
      <a @click="scrollToSection('access')">ACCESS</a>
      <a @click="scrollToSection('education')">EDUCATION</a>
      <a @click="scrollToSection('result')">RESULTS</a>
      <a @click="scrollToSection('choice')">CHOICE</a>
      <Link href="/login" class="login-btn">LOG IN</Link>
      <div class="hamburger" @click="toggleMenu">
        <div :class="menuOpen ? 'bar cross-bar1' : 'bar'"></div>
        <div :class="menuOpen ? 'bar cross-bar2' : 'bar'"></div>
        <div :class="menuOpen ? 'bar cross-bar3' : 'bar'"></div>
      </div>
    </div>
    <transition name="dropdown">
      <div v-if="menuOpen" class="dropdown-menu">
        <Link href="/register" class="menu-item1">JOIN NOW</Link>
        <a @click="scrollToSection('access')" class="menu-item ">ACCESS</a>
        <a @click="scrollToSection('education')" class="menu-item ">EDUCATION</a>
        <a @click="scrollToSection('result')" class="menu-item ">RESULTS</a>
        <a @click="scrollToSection('choice')" class="menu-item ">CHOICE</a>
        <Link href="/login" class="menu-item ">LOG IN</Link>
      </div>
    </transition>
  </nav>
</template>

<script>
import { Link } from '@inertiajs/vue3';
export default {
  components: {
    Link,
  },
  data() {
    return {
      menuOpen: false,
    };
  },
  methods: {
    toggleMenu() {
      this.menuOpen = !this.menuOpen;
    },
    scrollToSection(sectionId) {
      const section = document.getElementById(sectionId);
      if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
      }
    },
  },
};
</script>

<style scoped>
.access {
  font-weight: 800 !important;
  -webkit-text-fill-color: #FFB54C;
}

/* Navbar Styles */
.navbar {
  padding: 20px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  /* position: sticky; */
  top: 0;
  z-index: 1000;
  color: rgb(0, 0, 0);
}

.navbar-content {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
}

.navbar-content a {
  padding: 8px 16px;
  cursor: pointer;
  text-decoration: none;
  color: rgb(0, 0, 0);
  font-weight: 500;
  transition: transform 0.4s ease-in-out, box-shadow 0.3s ease-in-out;
  position: relative;
}

.navbar-content a:hover {
  transform: scale(1.1) rotateX(10deg);
  /* Halki si tilt aur zoom-out effect */
  box-shadow: 0px 10px 20px rgba(255, 255, 255, 0.3);
  /* Shadow effect */
}

.join-now {
  font-weight: 800 !important;
  border-radius: 50px;
  background-image: linear-gradient(310deg, #38B6FF, #4CCAFF);
  color: black;
  padding: 5px;
  cursor: pointer;
}

.login-btn {
  font-weight: 800 !important;
  border-radius: 50px;
  border: 2px solid #08080D;
  color: rgb(0, 0, 0);
  padding: 5px;
  cursor: pointer;
}

.hamburger {
  display: none;
  flex-direction: column;
  cursor: pointer;
}

.bar {
  background-color: rgb(0, 0, 0);
  height: 3.5px;
  width: 25px;
  margin: 2px 0;
  transition: all 0.3s ease;
}

.cross-bar1 {
  transform: rotate(45deg) translate(5px, 5px);
}

.cross-bar2 {
  opacity: 0;
}

.cross-bar3 {
  transform: rotate(-45deg) translate(6px, -6px);
}

/* Dropdown Menu */
.dropdown-menu {
  display: flex;
  margin-top: 18px;
  flex-direction: column;
  background-color: #08080D;
  position: absolute;
  top: 60px;
  left: 0;
  width: 100%;
  z-index: 1000;
  padding: 0;
  transform: translateY(-20px);
}

.menu-item {
  padding: 14px 20px;
  font-weight: 800 !important;
  color: white;
  text-align: center;
  border-bottom: 1px solid #333;
  text-decoration: none;
  font-size: 1rem;
}

.menu-item:hover {
  background-color: #333;
}

.menu-item1 {
  text-decoration: none;
  padding: 14px 20px;
  color: #ffb543;
  text-align: center;
  border-bottom: 1px solid #333;
  font-size: 1rem;
  font-weight: 800 !important;
}

.menu-item:hover {
  background-color: #333;
}

/* Dropdown Slide Transition */
.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 0.4s ease;
}

.dropdown-enter {
  opacity: 0;
  transform: translateY(-20px);
}

.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}

/* Responsive Styles */
@media (max-width: 768px) {
  .navbar-content a {
    display: none;
  }

  .hamburger {
    display: flex;
  }
}
</style>