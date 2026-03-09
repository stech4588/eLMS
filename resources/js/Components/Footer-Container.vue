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
            <li><Link href="/terms-of-services" class="footer-link dark:text-dark-text-primary dark:hover:text-dark-accent-primary">Terms &amp; Conditions</Link></li>
            <li><Link href="/privacy-policy" class="footer-link dark:text-dark-text-primary dark:hover:text-dark-accent-primary">Privacy Policy</Link></li>
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
        <p class="bottom-text">Everything taught within ElevateU University is for education purposes only. It is up to each student to implement and do the work.</p>
        <p class="bottom-text">ElevateU University team does not guarantee any specific outcomes or success. Results may vary based on individual effort and circumstances.</p>
        <p class="bottom-text">© {{ new Date().getFullYear() }} ElevateU University. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template> -->
<!-- <template>
  <div class="mbm-main-footer">
    <footer class="mbm-footer">
      <div class="mbm-footer-container">
        <div class="mbm-footer-columns">
          <a :href="logoUrl" class="mbm-footer-logo">
            <img src="/images/MBM_Uni.png" alt="" class="mbm-footer-logo" />
          </a>
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

            <div class="mbm-footer-column">
              <h3 class="mbm-footer-heading">Popular Courses</h3>
              <ul class="mbm-footer-links">
                <li><a href="/dashboard" style="cursor: pointer;">E-Commerce</a></li>
                <li><a href="/dashboard" style="cursor: pointer;">Copywriting</a></li>
                <li><a href="/dashboard" style="cursor: pointer;">Stocks</a></li>
                <li><a href="/dashboard" style="cursor: pointer;">Crypto Investing</a></li>
              </ul>
            </div>

            <div class="mbm-footer-column">
              <h3 class="mbm-footer-heading">Top Learning Categories</h3>
              <ul class="mbm-footer-links">
                <li><a href="/dashboard" style="cursor: pointer;">Business & Finance</a></li>
                <li><a href="/dashboard" style="cursor: pointer;">Crypto Trading</a></li>
                <li><a href="/dashboard" style="cursor: pointer;">Content Creation & AI</a></li>
                <li><a href="/dashboard" style="cursor: pointer;">Client Acquisition & Social Media</a></li>
              </ul>
            </div>

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

        <div class="mbm-footer-bottom">
          <p>© ElevateU University 2025</p>
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
</template> -->

<template>
  <footer class="footer-simple" id="footer">
    <div class="footer-container">
      <div class="footer-content">
        <!-- Left Side: Copyright and Socials -->
        <div class="footer-left">
          <p class="copyright">© 2026 ELEVATEU UNIVERSITY. ALL RIGHTS RESERVED.</p>
          <div class="footer-socials">
            <a href="#" aria-label="Facebook" class="social-icon">
              <svg fill="currentColor" viewBox="0 0 24 24" class="icon">
                <path
                  d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
              </svg>
            </a>
            <a href="#" aria-label="X (Twitter)" class="social-icon">
              <svg fill="currentColor" viewBox="0 0 24 24" class="icon">
                <path
                  d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z" />
              </svg>
            </a>
            <a href="#" aria-label="Instagram" class="social-icon">
              <svg fill="currentColor" viewBox="0 0 24 24" class="icon">
                <path fill-rule="evenodd"
                  d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                  clip-rule="evenodd" />
              </svg>
            </a>
            <a href="#" aria-label="YouTube" class="social-icon">
              <svg fill="currentColor" viewBox="0 0 24 24" class="icon">
                <path
                  d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
              </svg>
            </a>
            <a href="#" aria-label="LinkedIn" class="social-icon">
              <svg fill="currentColor" viewBox="0 0 24 24" class="icon">
                <path
                  d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
              </svg>
            </a>
          </div>
        </div>

        <!-- Right Side: Links -->
        <div class="footer-right">
          <Link href="/contact" class="footer-link">Customer Support</Link>
          <Link href="/privacy-policy" class="footer-link">Privacy Policy</Link>
          <Link href="/terms-of-services" class="footer-link">Terms and Conditions</Link>
        </div>
      </div>
    </div>
  </footer>
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

/*
<style scoped>
.mbm-social-icons .mbm-social-icon-img {
  filter: brightness(0) invert(1);
}
</style> */

<style scoped>
/* NEW REDESIGNED FOOTER STYLES */
.footer-simple {
  background-color: #262c33;
  color: #ffffff;
  padding: 60px 20px;
  font-family: 'Inter', system-ui, sans-serif;
}

.footer-container {
  max-width: 1200px;
  margin: 0 auto;
}

.footer-content {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  /* Aligning strictly to the top */
  flex-wrap: wrap;
  gap: 20px;
}

.footer-left {
  display: flex;
  flex-direction: column;
  gap: 40px;
  /* Adds space between copyright and socials */
}

.copyright {
  font-size: 14px;
  color: #ffffff;
  margin: 0;
  line-height: 1;
}

.footer-socials {
  display: flex;
  gap: 15px;
}

.social-icon {
  color: #ffffff;
  transition: opacity 0.3s ease;
}

.social-icon:hover {
  opacity: 0.8;
}

.icon {
  width: 20px;
  height: 20px;
}

.footer-right {
  display: flex;
  gap: 30px;
  flex-wrap: wrap;
}

.footer-link {
  color: #ffffff;
  text-decoration: none;
  font-size: 14px;
  transition: opacity 0.3s ease;
  line-height: 1;
}

.footer-link:hover {
  opacity: 0.8;
}

@media (max-width: 768px) {
  .footer-content {
    flex-direction: column;
    text-align: center;
    gap: 30px;
  }

  .footer-left {
    align-items: center;
  }

  .footer-right {
    justify-content: center;
  }
}
</style>
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
