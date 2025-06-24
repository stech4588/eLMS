<template>
  <div class="main-footer">
    <footer class="footer dark:bg-dark-bg-primary">
      <div class="footer-container">
        <!-- Newsletter Signup -->
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

        <!-- Quick Links -->
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

        <!-- Office Information -->
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
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
const user = usePage().props.auth?.user
</script>

<style scoped>
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
</style>
