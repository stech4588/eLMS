<!-- <template>
  <div class="main-footer">
    <footer class="footer dark:bg-dark-bg-primary">
      <div class="footer-container">
        <div class="newsletter">
          <Link :href="user ? '/dashboard' : '/'">
            <img src="/images/MBM_Uni.png" alt="MBM Logo" class="logo dark:brightness-90" />
          </Link>
          <h2 class="newsletter-title dark:text-dark-text-primary">Newsletter Signup</h2>
          <label for="email" class="newsletter-label dark:text-dark-text-secondary">Enter Email</label>
          <input type="email" id="email" placeholder="Enter your email"
                 class="newsletter-input dark:bg-dark-bg-secondary dark:text-dark-text-primary dark:border-dark-border-primary" />
          <button class="subscribe-btn dark:bg-dark-accent-primary dark:hover:bg-dark-accent-secondary">Subscribe</button>
        </div>

        <div class="footer-section">
          <h3 class="section-title dark:text-dark-text-primary">Quick Links</h3>
          <ul>
            <li><Link href="/login" class="footer-link dark:text-dark-text-primary dark:hover:text-dark-accent-primary">Log In</Link></li>
            <li><Link href="/download" class="footer-link dark:text-dark-text-primary dark:hover:text-dark-accent-primary">Download</Link></li>
            <li><Link href="/terms" class="footer-link dark:text-dark-text-primary dark:hover:text-dark-accent-primary">Terms & Conditions</Link></li>
            <li><Link href="/privacypolicy" class="footer-link dark:text-dark-text-primary dark:hover:text-dark-accent-primary">Privacy Policy</Link></li>
            <li><Link href="/about" class="footer-link dark:text-dark-text-primary dark:hover:text-dark-accent-primary">About Us</Link></li>
            <li><Link href="/contact" class="footer-link dark:text-dark-text-primary dark:hover:text-dark-accent-primary">Need help? <span class="highlight dark:text-dark-accent-primary">Contact us</span></Link></li>
            <li><a href="mailto:support@mbmuniversity.com" class="footer-email dark:text-dark-text-primary dark:hover:text-dark-accent-primary">support@mbmuniversity.com</a></li>
          </ul>
        </div>

        <div class="footer-section">
          <h3 class="section-title dark:text-dark-text-primary">Office</h3>
          <p class="footer-text dark:text-dark-text-secondary">Owned and Managed by New Era Learning LLC</p>
          <p class="footer-text dark:text-dark-text-secondary">Distributed by our partners:</p>
          <p class="footer-text dark:text-dark-text-primary"><strong>Thrifty Consulting LLC</strong></p>
          <p class="footer-text dark:text-dark-text-secondary">800 North State St. Ste 403, Dover, DE 19901</p>
          <p class="footer-text dark:text-dark-text-primary"><strong>Legendary Courses, Inc</strong></p>
          <p class="footer-text dark:text-dark-text-secondary">221 N. Broad St. Middletown, DE 19709</p>
        </div>
      </div>

      <hr class="divider dark:border-dark-border-primary" />
      <div class="footer-bottom">
        <p class="bottom-text">Everything taught within MBM University is for education purposes only. It is up to each student to implement and do the work.</p>
        <p class="bottom-text">MBM University team does not guarantee any specific outcomes or success. Results may vary based on individual effort and circumstances.</p>
        <p class="bottom-text">© {{ new Date().getFullYear() }} MBM University. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template> -->
<template>
  <div class="mbm-main-footer">
    <footer class="mbm-footer">
      <div class="mbm-footer-container">
        <div class="mbm-footer-columns">
          <a :href="logoUrl" class="mbm-footer-logo">
            <img src="/images/MBM_Uni.png" alt="" class="mbm-footer-logo" />
          </a>
          <!-- Column 1: Logo + Quick Links -->
          <div class="mbm-footer-row">
            <div class="mbm-footer-column">
              <h3 class="mbm-footer-heading">QUICK LINKS</h3>
              <ul class="mbm-footer-links">
                <li><a @click="scrollToSection('why-us')" style="cursor: pointer;">Why Us</a></li>
                <li><a @click="scrollToSection('learn')" style="cursor: pointer;">Learn</a></li>
                <li><a @click="scrollToSection('result')" style="cursor: pointer;">Result</a></li>
                <li><a @click="scrollToSection('pricing')" style="cursor: pointer;">Pricing</a></li>
              </ul>
              <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a :href="joinNowUrl" class="mbm-footer-button">JOIN NOW ➜</a>
                <a href="/contact" class="mbm-footer-button">CONTACT US ➜</a>
              </div>
            </div>

            <!-- Column 2: Asia Pacific Inquiries -->
            <div class="mbm-footer-column">
              <h3 class="mbm-footer-heading">Popular Courses</h3>
              <ul class="mbm-footer-links">
                <li><a href="/dashboard" style="cursor: pointer;">E-Commerce</a></li>
                <li><a href="/dashboard" style="cursor: pointer;">Copywriting</a></li>
                <li><a href="/dashboard" style="cursor: pointer;">Stocks</a></li>
                <li><a href="/dashboard" style="cursor: pointer;">Crypto Investing</a></li>
              </ul>
            </div>

            <!-- Column 3: USA Inquiries -->
            <div class="mbm-footer-column">
              <h3 class="mbm-footer-heading">Top Learning Categories</h3>
              <ul class="mbm-footer-links">
                <li><a href="/dashboard" style="cursor: pointer;">Business & Finance</a></li>
                <li><a href="/dashboard" style="cursor: pointer;">Crypto Trading</a></li>
                <li><a href="/dashboard" style="cursor: pointer;">Content Creation & AI</a></li>
                <li><a href="/dashboard" style="cursor: pointer;">Client Acquisition & Social Media</a></li>
              </ul>
            </div>

            <!-- Column 4: EMEA Inquiries -->
            <div class="mbm-footer-column">
              <h3 class="mbm-footer-heading">Explore Our Courses</h3>
              <ul class="mbm-footer-links">
                <li><a href="/dashboard" style="cursor: pointer;">Ads Mastery</a></li>
                <li><a href="/dashboard" style="cursor: pointer;">DeFi</a></li>
                <li><a href="/dashboard" style="cursor: pointer;">Digital Advertising</a></li>
                <li><a href="/dashboard" style="cursor: pointer;">Sales</a></li>
              </ul>
            </div>
          </div>

        </div>

        <!-- Badges Row -->
        <!-- <div class="mbm-badges">
          <img src="/images/footer-img1.png" alt="" class="mbm-footer-img" />
          <img src="/images/footer-img2.svg" alt="" class="mbm-footer-img" />
          <img src="/images/footer-img3.png" alt="" class="mbm-footer-img" />
          <img src="/images/footer-img4.png" alt="" class="mbm-footer-img" />
          <img src="/images/footer-img5.png" alt="" class="mbm-footer-img" />
          <img src="/images/footer-img6.svg" alt="" class="mbm-footer-img" />
          <img src="/images/footer-img7.svg" alt="" class="mbm-footer-img" />
        </div> -->

        <!-- Bottom Bar -->
        <div class="mbm-footer-bottom">
          <p>© MBM University 2025</p>
          <p>
            <a href="#">Privacy Policy</a> |
            <a href="#">Terms of Service</a>
          </p>
          <div class="mbm-social-icons">
            <a href="/dashboard" aria-label="Dashboard" class="mbm-social-icon" title="Home">
              <img src="/images/home_icon.svg" alt="Dashboard" class="mbm-social-icon-img" />
            </a>
            <a href="/community" aria-label="Community" class="mbm-social-icon" title="Community">
              <img src="/images/community_icon.svg" alt="Community" class="mbm-social-icon-img" />
            </a>
            <a href="/groups" aria-label="Groups" class="mbm-social-icon" title="Groups">
              <img src="/images/groups_icon.svg" alt="Groups" class="mbm-social-icon-img" />
            </a>
            <a href="/jobs" aria-label="Jobs" class="mbm-social-icon" title="Jobs">
              <img src="/images/jobs_icon.svg" alt="Jobs" class="mbm-social-icon-img" />
            </a>
            <a href="/help" aria-label="Help" class="mbm-social-icon" title="Help">
              <img src="/images/help_icon.svg" alt="Help" class="mbm-social-icon-img" />
            </a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>
<script>
import { Link, usePage } from '@inertiajs/vue3'

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
        return '/dashboard';
        if (this.$page.props.auth.profile_incomplete) {
          return '/register/complete';
        }
      }
      return '/';
    }
  },
  methods: {
    scrollToSection(id) {
      const section = document.getElementById(id);
      if (section) section.scrollIntoView({ behavior: 'smooth' });
    },
  },
};
</script>

<style scoped>
.mbm-social-icons .mbm-social-icon-img {
  filter: brightness(0) invert(1);
}</style>
<!-- <style scoped>
.footer {
  background-color: #12121D;
  color: white;
  padding: 60px 20px;
  transition: all 0.3s ease-in-out;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.footer-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 40px;
  max-width: 1300px;
  margin: auto;
}

.newsletter {
  display: flex;
  text-align:left;
  flex-direction: column;
}

.logo {
  width: 200px;
  margin-bottom: 20px;
}

.newsletter-title {
  font-size: 22px;
  margin-bottom: 8px;
}

.newsletter-label {
  font-size: 13px;
  margin-bottom: 5px;
}

.newsletter-input {
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
  margin-bottom: 10px;
  width: 100%;
  transition: border 0.3s;
}

.newsletter-input:focus {
  border: 1px solid #03BFD7;
  outline: none;
}

.subscribe-btn {
  background-color: #03BFD7;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 6px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.3s;
}

.subscribe-btn:hover {
  background-color: #02a8be;
}

.footer-section h3 {
  font-size: 20px;
  margin-bottom: 12px;
  font-weight: 600;
}

.footer-section ul {
  list-style: none;
  padding: 0;
}

.footer-section li {
  margin-bottom: 8px;
}

.footer-section li a {
  text-decoration: none;
  color: #ccc;
  transition: color 0.3s ease;
}

.footer-section li a:hover {
  color: #03BFD7;
}

.footer-text {
  font-size: 14px;
  color: #ccc;
  margin-bottom: 6px;
}

.divider {
  margin: 40px 0 30px;
  border: none;
  height: 1px;
  background: rgba(255, 255, 255, 0.2);
}

.footer-bottom {
  text-align: center;
  font-size: 13px;
  color: #aaa;
  max-width: 1000px;
  margin: 0 auto;
  line-height: 1.6;
}

.bottom-text {
  margin-bottom: 10px;
}

/* Dark mode enhancements */
.dark .footer-section li a,
.dark .footer-text,
.dark .footer-bottom {
  color: #bbb;
}

.dark .footer-section li a:hover {
  color: #00bcd4;
}

/* Mobile */
@media (max-width: 768px) {
  .footer {
    padding: 40px 15px;
  }

  .logo {
    width: 160px;
  }

  .footer-bottom {
    padding-top: 20px;
  }
}
</style> -->
