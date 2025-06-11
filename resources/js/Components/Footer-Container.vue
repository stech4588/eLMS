<template>
  <div class="main-footer">
    <footer class="footer">
      <div class="footer-container">
        <!-- Newsletter Signup -->
        <div class="newsletter">
          <Link :href="user ? '/dashboard' : '/'">
          <img src="/images/MBM_Uni.png" alt="CFO Logo" class="logo" />
          </Link>
          <h2>NewsLetter Signup</h2>
          <label for="email">Enter Email</label>
          <input type="email" id="email" placeholder="Enter your email" />
          <button class="subscribe-btn">Subscribe</button>
        </div>

        <!-- Quick Links -->
        <div class="footer-section">
          <h3></h3>
          <ul>
            <li><router-link to="/login" class="footer-link">Log In</router-link></li>
            <li><router-link to="/download" class="footer-link">Download</router-link></li>
            <li><router-link to="/terms" class="footer-link">Terms & Conditions</router-link></li>
            <li><router-link to="/privacypolicy" class="footer-link">Privacy Policy</router-link></li>
            <li><router-link to="/about" class="footer-link">About Us</router-link></li>
            <li><router-link to="/contact" class="footer-link">Need help? <span class="highlight">Contact
                  us</span></router-link></li>
            <li><a href="mailto:support@therealworldportal.com" class="footer-email">support@therealworldportal.com</a>
            </li>
          </ul>
        </div>

        <!-- Pakistan Office -->
        <div class="footer-section">
          <h3></h3>
          <p>Owned and Managed by New Era Learning LLC</p>
          <p>Distributed by our partners:</p>
          <p><strong>Thrifty Consulting LLC</strong></p>
          <p>800 North State St. Ste 403, Dover, DE 19901</p>
          <p><strong>Legendary Courses, Inc</strong></p>
          <p>221 N. Broad St. Middletown, DE 19709</p>
        </div>
      </div>

      <hr />
      <div class="footer-bottom">
        <p>Everything taught within MBM University is for education purposes only. It is up to each student to implement
          and do the work.</p>
        <p>MBM University team does not guarantee any specific outcomes or success. Results may vary based on individual
          effort and circumstances.</p>
      </div>
    </footer>

  </div>
  <!-- <footer class="footer">
    <div class="footer-container">
      <div class="footer-links">
        <router-link to="/login" class="footer-link">Log In</router-link>
        <router-link to="/download" class="footer-link">Download</router-link>
        <router-link to="/terms" class="footer-link">Terms & Conditions</router-link>
        <router-link to="/privacypolicy" class="footer-link">Privacy Policy</router-link>
        <router-link to="/about" class="footer-link">About Us</router-link>
        <router-link to="/contact" class="footer-link">Need help? <span class="highlight">Contact
            us</span></router-link>
        <a href="mailto:support@therealworldportal.com" class="footer-email">support@therealworldportal.com</a>
      </div>

      <div class="footer-info">
        <p>Owned and Managed by New Era Learning LLC</p>
        <p>Distributed by our partners:</p>
        <p><strong>Thrifty Consulting LLC</strong></p>
        <p>800 North State St. Ste 403, Dover, DE 19901</p>
        <p><strong>Legendary Courses, Inc</strong></p>
        <p>221 N. Broad St. Middletown, DE 19709</p>
      </div>
    </div>

    <div class="footer-bottom">
      <p>Everything taught within MBM University is for education purposes only. It is up to each student to implement
        and do the work.</p>
      <p>MBM University team does not guarantee any specific outcomes or success. Results may vary based on individual
        effort and circumstances.</p>
    </div>
  </footer> -->
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
}

.newsletter h2 {
  font-size: 20px;
  margin-bottom: 10px;
}

.newsletter label {
  font-size: 12px;
  margin-bottom: 5px;
  display: block;
}

.newsletter input {
  padding: 8px;
  margin-top: 5px;
  width: 80%;
  margin-bottom: 10px;
  border: none;
  border-radius: 4px;
}

.subscribe-btn {
  background-color: #03BFD7;
  border: none;
  color: white;
  padding: 10px 16px;
  border-radius: 4px;
  cursor: pointer;
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
}

.footer-section ul li a:hover {
  text-decoration: underline;
}

.footer-section p {
  margin-bottom: 6px;
  text-align: left;
  font-size: 14px;
}

.social-icons a {
  margin-right: 10px;
  text-decoration: none;
  color: white;
  font-weight: bold;
  font-size: 14px;
}

hr {
  margin: 40px 0 20px;
  border: 0;
  border-top: 1px solid #ffffff30;
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
}

.scroll-top {
  background-color: #03BFD7;
  color: white;
  text-decoration: none;
  padding: 10px 14px;
  border-radius: 50%;
  font-size: 18px;
}

/* Responsive */
@media (max-width: 768px) {
  .footer-container {
    flex-direction: column;
  }

  .footer-bottom {
    flex-direction: column;
    text-align: center;
    gap: 10px;
  }
}
</style>