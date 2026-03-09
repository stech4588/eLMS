<!-- 
<template>
  <nav class="modern-navbar">
    <div class="navbar-container">
      <a :href="logoUrl" class="logo">
        <img src="/images/MBM_Uni.png" alt="" />
      </a>

      <ul class="nav-links" v-if="!menuOpen">
        <li><a @click="scrollToSection('access')">ACCESS</a></li>
        <li><a @click="scrollToSection('learn')">LEARN</a></li>
        <li><a @click="scrollToSection('education')">EDUCATION</a></li>
        <li><a @click="scrollToSection('why-us')">WHY US</a></li>
        <li><a @click="scrollToSection('testimonials')">TESTIMONIALS</a></li>

        <li><a @click="scrollToSection('pricing')">PRICING</a></li>
        <li><a @click="scrollToSection('faq')">FAQ</a></li>
      </ul>

      <div class="action-buttons" v-if="!menuOpen">
        <Link :href="joinNowUrl" class="join">JOIN NOW</Link>
        <Link :href="loginUrl" class="login">LOG IN</Link>
      </div>

      <div class="hamburger" @click="toggleMenu">
        <span :class="{ open: menuOpen }"></span>
        <span :class="{ open: menuOpen }"></span>
        <span :class="{ open: menuOpen }"></span>
      </div>
    </div>

    <transition name="dropdown">
      <div v-show="menuOpen" class="custom-dropdown">
        <Link :href="joinNowUrl">JOIN NOW</Link>
        <Link href="/login">LOG IN</Link>
        <a @click="scrollToSection('access')">ACCESS</a>
        <a @click="scrollToSection('learn')">LEARN</a>
        <a @click="scrollToSection('education')">EDUCATION</a>
        <a @click="scrollToSection('why-us')">WHY US</a>
        <a @click="scrollToSection('testimonials')">TESTIMONIALS</a>
        <a @click="scrollToSection('pricing')">PRICING</a>
        <a @click="scrollToSection('faq')">FAQ</a>
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
  computed: {
    user() {
      return this.$page.props.auth.user;
    },
    logoUrl() {
      if (this.user) {
        if (this.$page.props.auth.profile_incomplete && this.user.type === 'student') {
          return '/register/complete';
        }
        return '/dashboard';
      }
      return '/';
    },
    joinNowUrl() {
      if (this.user) {
        if (this.$page.props.auth.profile_incomplete && this.user.type === 'student') {
          return '/register/complete';
        }
        return '/dashboard';
      }
      return '/joinnow';
    },
    loginUrl() {
      if (this.user) {
        if (this.$page.props.auth.profile_incomplete && this.user.type === 'student') {
          return '/register/complete';
        }
        return '/dashboard';
      }
      return '/login';
    },
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
* {
  box-sizing: border-box;
}

.modern-navbar {
  font-family: 'Segoe UI', sans-serif;
  color: #fff;
  top: 0;
  width: 100%;
  z-index: 1000;
  padding: 0px 30px;
  background-color: transparent !important;
}

.navbar-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
}

.logo {
  width: 140px;
}

@media (max-width: 768px) {
  .logo {
    width: 140px;
    height: 80px;
  }
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
  background: linear-gradient(135deg, #29DFFD 0%, #245AF8 100%);
  color: #ffffff;
  padding: 10px 24px;
  font-size: 0.95rem;
  letter-spacing: 0.5px;
  position: relative;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(41, 223, 253, 0.3);
}

.join::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
  transition: left 0.5s;
}

.join:hover::before {
  left: 100%;
}

.join:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(41, 223, 253, 0.5);
  background: linear-gradient(135deg, #38E8FF 0%, #3565FF 100%);
}


.login {
  border: 2px solid #fff;
  color: #fff;
}

.login:hover {
  background-color: #009ada !important;
  color: #fff;
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

.custom-dropdown {
  background: rgba(30, 30, 47, 0.9);
  -webkit-backdrop-filter: blur(8px);
  backdrop-filter: blur(8px);
  border-top: 1px solid #333;
  display: flex;
  padding-bottom: 30px !important;
  flex-direction: column;
  gap: 15px;
  padding: 0 30px;
  border-radius: 0 0 16px 16px;
  overflow: hidden;
  position: absolute;
  z-index: 1037;
  width: 94%;
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

.custom-dropdown a:first-child {
  background: linear-gradient(135deg, #29DFFD 0%, #245AF8 100%);
  color: #ffffff;
  padding: 12px 20px;
  border-radius: 50px;
  text-align: center;
  border: none;
  margin-top: 10px;
  box-shadow: 0 4px 15px rgba(41, 223, 253, 0.3);
  font-weight: bold;
  letter-spacing: 0.5px;
}

.custom-dropdown a:first-child:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(41, 223, 253, 0.5);
  background: linear-gradient(135deg, #38E8FF 0%, #3565FF 100%);
  color: #ffffff;
}

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

@media (max-width: 1320px) {

  .nav-links,
  .action-buttons {
    display: none;
  }

  .hamburger {
    display: flex;
  }
}
</style>
-->

<!-- NEW REDESIGNED NAVBAR -->
<template>
  <nav class="simple-navbar">
    <div class="container">
      <div class="logo-side">
        <Link :href="logoUrl">
          <img src="/images/MBM_Uni.png" alt="Logo" class="navbar-logo" />
        </Link>
      </div>
      <div class="links-side">
        <div class="nav-links">
          <a @click="scrollToSection('access')">ACCESS</a>
          <a @click="scrollToSection('learn')">LEARN</a>
          <a @click="scrollToSection('education')">EDUCATION</a>
          <a @click="scrollToSection('why-us')">WHY US</a>
          <a @click="scrollToSection('testimonials')">TESTIMONIALS</a>
          <a @click="scrollToSection('pricing')">PRICING</a>
          <a @click="scrollToSection('faq')">FAQ</a>
          <Link :href="joinNowUrl">JOIN NOW</Link>
          <Link :href="loginUrl" class="login-link">LOG IN</Link>
        </div>
        <div class="mobile-menu-btn" @click="toggleMenu">
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
        </div>
      </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div v-if="menuOpen" class="mobile-overlay" @click="toggleMenu">
      <div class="mobile-menu" @click.stop>
        <a @click="scrollToSection('access')">ACCESS</a>
        <a @click="scrollToSection('learn')">LEARN</a>
        <a @click="scrollToSection('education')">EDUCATION</a>
        <a @click="scrollToSection('why-us')">WHY US</a>
        <a @click="scrollToSection('testimonials')">TESTIMONIALS</a>
        <a @click="scrollToSection('pricing')">PRICING</a>
        <a @click="scrollToSection('faq')">FAQ</a>
        <Link :href="joinNowUrl">JOIN NOW</Link>
        <Link :href="loginUrl">LOG IN</Link>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const menuOpen = ref(false);

const user = computed(() => page.props.auth.user);

const logoUrl = computed(() => {
  if (user.value) {
    if (page.props.auth.profile_incomplete && user.value.type === 'student') return '/register/complete';
    return '/dashboard';
  }
  return '/';
});

const joinNowUrl = computed(() => {
  if (user.value) {
    if (page.props.auth.profile_incomplete && user.value.type === 'student') return '/register/complete';
    return '/dashboard';
  }
  return '/joinnow';
});

const loginUrl = computed(() => {
  if (user.value) {
    if (page.props.auth.profile_incomplete && user.value.type === 'student') return '/register/complete';
    return '/dashboard';
  }
  return '/login';
});

const toggleMenu = () => {
  menuOpen.value = !menuOpen.value;
};

const scrollToSection = (id) => {
  const section = document.getElementById(id);
  if (section) section.scrollIntoView({ behavior: 'smooth' });
  menuOpen.value = false;
};
</script>

<style scoped>
.simple-navbar {
  background: #ffffff;
  height: 80px;
  width: 100%;
  display: flex;
  align-items: center;
  border-bottom: 1px solid #eaeaea;
  position: sticky;
  top: 0;
  z-index: 2000;
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.container {
  width: 100%;
  max-width: 1300px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 40px;
}

.navbar-logo {
  height: 100px;
  width: auto;
  filter: grayscale(100%) brightness(0);
  /* Make logo black if needed */
}

.nav-links {
  display: flex;
  align-items: center;
  gap: 35px;
}

.nav-links a,
.nav-links .login-link {
  color: #000000;
  text-decoration: none;
  font-size: 15px;
  font-weight: 500;
  cursor: pointer;
  transition: opacity 0.2s ease;
}

.nav-links a:hover {
  opacity: 0.7;
}

.mobile-menu-btn {
  display: none;
  flex-direction: column;
  gap: 6px;
  cursor: pointer;
}

.bar {
  width: 25px;
  height: 2px;
  background: #000;
}

.mobile-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: flex-end;
}

.mobile-menu {
  background: #fff;
  width: 250px;
  height: 100%;
  padding: 40px 20px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

@media (max-width: 991px) {
  .nav-links {
    display: none;
  }

  .mobile-menu-btn {
    display: flex;
  }
}
</style>
