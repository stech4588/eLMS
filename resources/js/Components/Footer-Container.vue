<template>
  <div class="main-footer">
    <footer class="footer dark:bg-dark-bg-primary transition-colors duration-200">
      <div class="footer-container">
        <!-- Newsletter Signup -->
        <div class="newsletter">
          <Link :href="user ? '/dashboard' : '/'">
            <img src="/images/MBM_Uni.png" alt="CFO Logo" class="logo dark:brightness-90" />
          </Link>
          <h2 class="dark:text-dark-text-primary">NewsLetter Signup</h2>
          <label for="email" class="dark:text-dark-text-secondary">Enter Email</label>
          <input type="email" id="email" placeholder="Enter your email" class="dark:bg-dark-bg-secondary dark:text-dark-text-primary dark:border-dark-border-primary" />
          <button class="subscribe-btn dark:bg-dark-accent-primary dark:hover:bg-dark-accent-secondary">Subscribe</button>
        </div>

        <!-- Quick Links -->
        <div class="footer-section">
          <h3 class="text-xl font-bold text-light-text-primary dark:text-dark-text-primary">Quick Links</h3>
          <ul>
            <li><router-link to="/login" class="footer-link dark:text-dark-text-primary dark:hover:text-dark-accent-primary">Log In</router-link></li>
            <li><router-link to="/download" class="footer-link dark:text-dark-text-primary dark:hover:text-dark-accent-primary">Download</router-link></li>
            <li><router-link to="/terms" class="footer-link dark:text-dark-text-primary dark:hover:text-dark-accent-primary">Terms & Conditions</router-link></li>
            <li><router-link to="/privacypolicy" class="footer-link dark:text-dark-text-primary dark:hover:text-dark-accent-primary">Privacy Policy</router-link></li>
            <li><router-link to="/about" class="footer-link dark:text-dark-text-primary dark:hover:text-dark-accent-primary">About Us</router-link></li>
            <li><router-link to="/contact" class="footer-link dark:text-dark-text-primary dark:hover:text-dark-accent-primary">Need help? <span class="highlight dark:text-dark-accent-primary">Contact us</span></router-link></li>
            <li><a href="mailto:support@therealworldportal.com" class="footer-email dark:text-dark-text-primary dark:hover:text-dark-accent-primary">support@therealworldportal.com</a></li>
          </ul>
        </div>

        <!-- Office Information -->
        <div class="footer-section">
          <h3 class="text-xl font-bold text-light-text-primary dark:text-dark-text-primary">Office</h3>
          <p class="dark:text-dark-text-secondary">Owned and Managed by New Era Learning LLC</p>
          <p class="dark:text-dark-text-secondary">Distributed by our partners:</p>
          <p class="dark:text-dark-text-primary"><strong>Thrifty Consulting LLC</strong></p>
          <p class="dark:text-dark-text-secondary">800 North State St. Ste 403, Dover, DE 19901</p>
          <p class="dark:text-dark-text-primary"><strong>Legendary Courses, Inc</strong></p>
          <p class="dark:text-dark-text-secondary">221 N. Broad St. Middletown, DE 19709</p>
        </div>
      </div>

      <hr class="dark:border-dark-border-primary" />
      <div class="footer-bottom">
        <p class="dark:text-dark-text-secondary">Everything taught within MBM University is for education purposes only. It is up to each student to implement and do the work.</p>
        <p class="dark:text-dark-text-secondary">MBM University team does not guarantee any specific outcomes or success. Results may vary based on individual effort and circumstances.</p>
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
  background-color: #08080D;
  color: white;
  padding: 40px 20px;
  transition: all 0.3s ease;
}

.footer-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  gap: 90px;
  max-width: 1420px;
  margin: auto;
}

.newsletter {
  flex: 1 1 250px;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.logo {
  width: 220px;
  margin-bottom: 15px;
  transition: all 0.3s ease;
}

.newsletter h2 {
  font-size: 20px;
  margin-bottom: 10px;
  transition: all 0.3s ease;
}

.newsletter label {
  font-size: 12px;
  margin-bottom: 5px;
  display: block;
  transition: all 0.3s ease;
}

.newsletter input {
  padding: 8px;
  margin-top: 5px;
  width: 80%;
  margin-bottom: 10px;
  border: none;
  border-radius: 4px;
  transition: all 0.3s ease;
}

.subscribe-btn {
  background-color: #03BFD7;
  border: none;
  color: white;
  padding: 10px 16px;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.subscribe-btn:hover {
  background-color: #02a8be;
}

.footer-section {
  flex: 1 1 200px;
}

.footer-section h3 {
  font-size: 18px;
  margin-bottom: 10px;
}

.footer-section ul {
  list-style: none;
  padding: 0px;
}

.footer-section ul li {
  text-align: left;
  margin-bottom: 6px;
}

.footer-section ul li a {
  color: white;
  text-decoration: none;
  transition: all 0.3s ease;
}

.footer-section ul li a:hover {
  text-decoration: underline;
}

.footer-section p {
  margin-bottom: 6px;
  text-align: left;
  font-size: 14px;
  transition: all 0.3s ease;
}

.social-icons a {
  margin-right: 10px;
  text-decoration: none;
  color: white;
  font-weight: bold;
  font-size: 14px;
  transition: all 0.3s ease;
}

hr {
  margin: 40px 0 20px;
  border: 0;
  border-top: 1px solid #ffffff30;
  transition: all 0.3s ease;
}

.footer-bottom {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-wrap: wrap;
  font-size: 15px;
  max-width: 1200px;
  margin: auto;
}

.footer-bottom p {
  margin: 0px;
  transition: all 0.3s ease;
}

/* Responsive */
@media (max-width: 768px) {
  .footer-container {
    flex-direction: column;
    gap: 40px;
  }

  .footer-bottom {
    flex-direction: column;
    text-align: center;
    gap: 10px;
  }
}
</style>