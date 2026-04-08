<template>

  <Head title="Purchase Course" />

  <div class="purchase-redesign-wrapper">
    <div class="top-banner">
      <Link href="/">
      <img src="/images/MBM_Uni.png" alt="Logo" class="banner-logo" />
      </Link>
    </div>

    <div class="checkout-wrapper">
      <div class="checkout-container">

        <!-- LEFT SIDE: Course Details -->
        <div class="course-details-side">
          <div class="course-visual">
            <img v-if="displayCourse.thumbnail" :src="displayCourse.thumbnail" :alt="displayCourse.title"
              class="main-thumbnail" />
            <div v-else class="thumbnail-placeholder"></div>
          </div>

          <div class="course-text-content">
            <p class="instructor-tag">{{ displayCourse.instructor }}</p>
            <h1 class="main-title">{{ displayCourse.title }}</h1>

            <div class="course-infobox">
              <p v-if="displayCourse.description" class="deep-dive-text">
                {{ displayCourse.description }}
              </p>
              <p v-else class="deep-dive-text">
                Learn the steps, tools and set your goals to 10X YOUR INCOME in this powerful deep-dive LIVE TRAINING on
                increasing your income!
              </p>

              <div class="learning-points-section">
                <h3>What you'll get:</h3>
                <ul class="points-list">
                  <li v-for="(point, index) in displayCourse.learning_points" :key="index">
                    {{ point }}
                  </li>
                </ul>
                <p class="access-note">*Unlimited Anytime 24/7 On-Demand Access</p>
              </div>
            </div>
          </div>
        </div>

        <!-- RIGHT SIDE: Checkout Form -->
        <div class="checkout-form-side">
          <div class="payment-card">
            <div class="price-header">
              <span class="amount">${{ coursePrice }}</span>
            </div>

            <form @submit.prevent="processPayment" class="redesigned-form">
              <!-- Guest Information -->
              <div v-if="!authUser" class="form-sections-stack">
                <div class="input-group">
                  <input v-model="form.name" type="text" placeholder="Full Name" class="nice-input" />
                  <span v-if="formErrors.name" class="error-msg">{{ formErrors.name }}</span>
                </div>

                <div class="input-group">
                  <input v-model="form.email" type="email" placeholder="Email Address" class="nice-input" />
                  <span v-if="formErrors.email" class="error-msg">{{ formErrors.email }}</span>
                </div>

                <div class="input-group">
                  <input v-model="form.phone_number" type="tel" placeholder="Phone Number" class="nice-input" />
                  <span v-if="formErrors.phone_number" class="error-msg">{{ formErrors.phone_number }}</span>
                </div>

                <div class="input-group">
                  <div class="password-field">
                    <input
                      v-model="form.password"
                      :type="showPassword ? 'text' : 'password'"
                      placeholder="Create Password"
                      class="nice-input nice-input--with-icon"
                    />
                    <button
                      type="button"
                      class="password-eye-btn"
                      @click="showPassword = !showPassword"
                      :title="showPassword ? 'Hide password' : 'Show password'"
                      aria-label="Toggle password visibility"
                    >
                      <svg v-if="!showPassword" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M12 15a3 3 0 100-6 3 3 0 000 6Z" stroke="currentColor" stroke-width="2"/>
                      </svg>
                      <svg v-else viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M3 3l18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M10.6 10.6a2 2 0 102.8 2.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M9.9 5.1A10.6 10.6 0 0112 5c6.5 0 10 7 10 7a18.2 18.2 0 01-4.2 5.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M6.2 6.2C3.8 8.1 2 12 2 12s3.5 7 10 7c1 0 2-.2 2.9-.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                      </svg>
                    </button>
                  </div>
                  <span v-if="formErrors.password" class="error-msg">{{ formErrors.password }}</span>
                </div>

                <div class="input-group">
                  <div class="password-field">
                    <input
                      v-model="form.password_confirmation"
                      :type="showConfirmPassword ? 'text' : 'password'"
                      placeholder="Confirm Password"
                      class="nice-input nice-input--with-icon"
                    />
                    <button
                      type="button"
                      class="password-eye-btn"
                      @click="showConfirmPassword = !showConfirmPassword"
                      :title="showConfirmPassword ? 'Hide password' : 'Show password'"
                      aria-label="Toggle password visibility"
                    >
                      <svg v-if="!showConfirmPassword" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M12 15a3 3 0 100-6 3 3 0 000 6Z" stroke="currentColor" stroke-width="2"/>
                      </svg>
                      <svg v-else viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M3 3l18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M10.6 10.6a2 2 0 102.8 2.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M9.9 5.1A10.6 10.6 0 0112 5c6.5 0 10 7 10 7a18.2 18.2 0 01-4.2 5.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M6.2 6.2C3.8 8.1 2 12 2 12s3.5 7 10 7c1 0 2-.2 2.9-.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Stripe Card Elements -->
              <div class="card-element-wrapper">
                <div class="stripe-field-container">
                  <div id="card-number-element" class="stripe-el"></div>
                </div>
                <div class="stripe-row">
                  <div id="card-expiry-element" class="stripe-el half"></div>
                  <div id="card-cvc-element" class="stripe-el half"></div>
                </div>
              </div>

              <div v-if="paymentError" class="overall-error">{{ paymentError }}</div>

              <!-- Agreements -->
              <div class="agreement-checks">
                <label class="check-box-label">
                  <input type="checkbox" required />
                  <span>Store this card for future purchases <i class="info-icon">?</i></span>
                </label>
                <label class="check-box-label">
                  <input type="checkbox" required />
                  <span>I have read and agree to the terms and conditions of this page.</span>
                </label>
              </div>

              <button type="submit" class="btn-complete-purchase" :disabled="paymentProcessing">
                {{ paymentProcessing ? 'Processing...' : 'Complete my purchase' }}
              </button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- ORIGINAL CODE COMMENTED OUT BELOW -->
  <!-- 
  <div class="purchase-page">
    <div class="purchase-container">
      <div class="purchase-header">
        <Link :href="authUser ? route('dashboard') : '/'" class="back-link">← Back</Link>
        <h1 class="purchase-title">Purchase Course</h1>
      </div>

      <div class="course-summary">
        <img v-if="course.thumbnail" :src="course.thumbnail" :alt="course.title" class="course-thumb" />
        <div class="course-info">
          <h2>{{ course.title }}</h2>
          <p class="course-price">${{ coursePrice }}</p>
        </div>
      </div>

      <form @submit.prevent="processPayment" class="purchase-form">
        <template v-if="!authUser">
          <div class="form-section">
            <h3 class="form-section-title">Your information</h3>
            <div class="form-row">
              <div class="form-group full-width">
                <label for="name">Full Name</label>
                <input id="name" v-model="form.name" type="text" placeholder="Full Name" class="form-input" />
                <span v-if="formErrors.name" class="error-text">{{ formErrors.name }}</span>
              </div>
            </div>
            ... and so on ...
          </div>
        </template>
...
</form>
</div>
</div>
-->
</template>

<script>
import { Head, Link, router } from '@inertiajs/vue3';
import { loadStripe } from '@stripe/stripe-js';
import axios from 'axios';

export default {
  name: 'PurchaseCourse',
  components: { Head, Link },
  props: {
    course: { type: Object, required: true },
    authUser: { type: Object, default: null },
  },
  data() {
    return {
      stripe: null,
      stripePromise: null,
      elements: null,
      cardNumber: null,
      cardExpiry: null,
      cardCvc: null,
      paymentProcessing: false,
      paymentError: null,
      showPassword: false,
      showConfirmPassword: false,
      form: {
        name: '',
        email: '',
        phone_number: '',
        password: '',
        password_confirmation: '',
      },
      formErrors: {},
    };
  },
computed: {
  displayCourse() {
      if (typeof window === 'undefined') return this.course;
      const params = new URLSearchParams(window.location.search);

      const title = params.get('title') || this.course.title;
      const thumbnail = params.get('image') || this.course.thumbnail;
      const instructor = params.get('instructor') || this.course.instructor;
      const description = params.get('description') || this.course.description;

      let learning_points = this.course.learning_points;
      const pointsParam = params.get('points');
      if (pointsParam) {
        try {
          learning_points = JSON.parse(pointsParam);
        } catch (e) {
          console.error('Failed to parse points', e);
        }
      }

      return {
        ...this.course,
        title,
        thumbnail,
        instructor,
        learning_points,
        description,
      };
    },
    coursePrice() {
      const p = this.course?.price;
      // If price is 11 digit 0 we handle it
      return p != null ? Number(p).toFixed(0) : '0';
    },
  },
  async mounted() {
    try {
      await this.initializeStripe();
      this.$nextTick(() => this.initializeStripeElements());
    } catch (e) {
      console.error(e);
      this.paymentError = 'Payment is not available. Please try again later.';
    }
  },
  beforeUnmount() {
    this.destroyStripeElements();
  },
  methods: {
    async initializeStripe() {
      if (this.stripe) return this.stripe;
      const key = this.$page?.props?.stripe?.key;
      if (!key) throw new Error('Stripe not configured');
      if (!this.stripePromise) this.stripePromise = loadStripe(key);
      this.stripe = await this.stripePromise;
      return this.stripe;
    },
    initializeStripeElements() {
      if (!this.stripe) return;
      this.destroyStripeElements();
      this.elements = this.stripe.elements();
      const style = {
        base: {
          color: '#333',
          fontFamily: '"Inter", sans-serif',
          fontSize: '15px',
          '::placeholder': { color: '#999' },
          lineHeight: '24px'
        },
        invalid: { color: '#fa755a' },
      };

      this.cardNumber = this.elements.create('cardNumber', { style, placeholder: 'Card number' });
      this.cardNumber.mount('#card-number-element');

      this.cardExpiry = this.elements.create('cardExpiry', { style });
      this.cardExpiry.mount('#card-expiry-element');

      this.cardCvc = this.elements.create('cardCvc', { style });
      this.cardCvc.mount('#card-cvc-element');
    },
    destroyStripeElements() {
      [this.cardNumber, this.cardExpiry, this.cardCvc].forEach((el) => {
        if (el) el.destroy();
      });
      this.cardNumber = this.cardExpiry = this.cardCvc = null;
    },
    validateGuestForm() {
      this.formErrors = {};
      let ok = true;
      if (!this.form.name?.trim()) {
        this.formErrors.name = 'Full name is required.';
        ok = false;
      }
      if (!this.form.email?.trim()) {
        this.formErrors.email = 'Email is required.';
        ok = false;
      } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) {
        this.formErrors.email = 'Please enter a valid email.';
        ok = false;
      }
      if (!this.form.phone_number?.trim()) {
        this.formErrors.phone_number = 'Phone number is required.';
        ok = false;
      }
      if (!this.form.password) {
        this.formErrors.password = 'Password is required.';
        ok = false;
      } else if (this.form.password.length < 8) {
        this.formErrors.password = 'Password must be at least 8 characters.';
        ok = false;
      }
      return ok;
    },
    async checkEmailExists(email) {
      const { data } = await axios.post(route('purchase-course.checkEmail'), { email });
      return data.exists === true;
    },
    getAmountInCents() {
      const p = this.course?.price;
      return Math.round((p != null ? Number(p) : 0) * 100);
    },
    async processPayment() {
      this.paymentError = null;

      if (!this.authUser) {
        if (!this.validateGuestForm()) return;
        const emailExists = await this.checkEmailExists(this.form.email);
        if (emailExists) {
          this.formErrors.email = 'An account with this email already exists.';
          this.paymentError = 'An account with this email already exists.';
          return;
        }
      }

      if (!this.cardNumber) {
        this.paymentError = 'Payment form loading...';
        return;
      }

      const amountInCents = this.getAmountInCents();
      if (amountInCents <= 0) {
        this.paymentError = 'Invalid course price.';
        return;
      }

      this.paymentProcessing = true;

      try {
        const intentRes = await axios.get(`/fetch-intent/${amountInCents}`);
        const clientSecret = intentRes.data.client_secret;

        const billingDetails = {};
        if (this.authUser) {
          billingDetails.name = this.authUser.name;
          billingDetails.email = this.authUser.email;
        } else {
          billingDetails.name = this.form.name;
          billingDetails.email = this.form.email;
        }

        const { paymentIntent, error } = await this.stripe.confirmCardPayment(clientSecret, {
          payment_method: {
            card: this.cardNumber,
            billing_details: billingDetails,
          },
        });

        if (error) {
          this.paymentError = error.message;
          this.paymentProcessing = false;
          return;
        }

        if (paymentIntent.status !== 'succeeded') {
          this.paymentError = 'Payment failed.';
          this.paymentProcessing = false;
          return;
        }

        const payload = {
          course_id: this.course.id,
          transaction_id: paymentIntent.id,
          payment_method: paymentIntent.payment_method_types?.[0] || 'card',
        };

        if (!this.authUser) {
          payload.name = this.form.name;
          payload.email = this.form.email;
          payload.phone_number = this.form.phone_number;
          payload.password = this.form.password;
          payload.password_confirmation = this.form.password_confirmation;
        }

        router.post(route('purchase-course.store'), payload, {
          onFinish: () => { this.paymentProcessing = false; },
        });
      } catch (err) {
        this.paymentError = err?.response?.data?.message || err?.message;
        this.paymentProcessing = false;
      }
    },
  },
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

.purchase-redesign-wrapper {
  background-color: #ffffff;
  min-height: 100vh;
  font-family: 'Inter', sans-serif;
  color: #1a1a1a;
}

.top-banner {
  width: 100%;
  height: 200px;
  background-image: url('https://kajabi-app-assets.kajabi-cdn.com/assets/checkout/default-banner-124023e59e3864fac6c19e42c240c1635474a351500cd56fdcb1051b0c62c252.jpg');
  background-size: cover;
  background-position: center;
  display: flex;
  justify-content: center;
  align-items: center;
}

.banner-logo {
  height: 130px;
  width: auto;
  filter: brightness(0) invert(1);
}

.checkout-wrapper {
  padding: 80px 40px;
}

.checkout-container {
  max-width: 950px;
  margin: 0 auto;
  display: flex;
  gap: 50px;
  align-items: flex-start;
}

/* Left Side */
.course-details-side {
  flex: 1;
  max-width: 450px;
  width: 100%;
}

.main-thumbnail {
  width: 100%;
  max-width: 450px;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  margin-bottom: 40px;
}

.instructor-tag {
  color: #666;
  font-weight: 600;
  font-size: 14px;
  letter-spacing: 1px;
  margin-bottom: 12px;
  text-transform: uppercase;
}

.main-title {
  font-size: 40px;
  font-weight: 800;
  line-height: 1.1;
  margin-bottom: 30px;
  color: #000;
}

.deep-dive-text {
  font-size: 18px;
  line-height: 1.5;
  color: #333;
  margin-bottom: 30px;
  font-weight: 500;
}

.learning-points-section h3 {
  font-size: 15px;
  font-weight: 600;
  color: #000;
  margin-bottom: 20px;
}

.points-list {
  list-style: none;
  padding: 0;
  margin-bottom: 25px;
}

.points-list li {
  position: relative;
  padding-left: 25px;
  margin-bottom: 15px;
  font-size: 15px;
  color: #444;
}

.points-list li::before {
  content: "•";
  position: absolute;
  left: 0;
  color: #333;
  font-weight: 900;
}

.access-note {
  font-size: 14px;
  font-weight: 700;
  color: #000;
}

/* Right Side Checkout Card */
.checkout-form-side {
  flex: 1;
  max-width: 450px;
  width: 100%;
  position: sticky;
  top: 100px;
}

.payment-card {
  background: #fff;
  border-radius: 4px;
  box-shadow: 0 4px 50px rgba(0, 0, 0, 0.08);
  border: 1px solid #efefef;
  width: 100%;
}

.price-header {
  padding: 15px 30px;
  border-bottom: 1px solid #f2f2f2;
}

.currency {
  font-size: 40px;
  font-weight: 600;
  vertical-align: top;
  margin-top: 4px;
  display: inline-block;
}

.amount {
  font-size: 40px;
  font-weight: 600;
  color: #000;
}

.redesigned-form {
  padding: 30px;
}

.form-sections-stack {
  display: flex;
  flex-direction: column;
  gap: 15px;
  margin-bottom: 20px;
}

.nice-input {
  width: 100%;
  padding: 8px 15px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 15px;
  transition: border-color 0.2s;
}

.nice-input--with-icon{
  padding-right: 48px;
}

.password-field{
  position: relative;
}

.password-eye-btn{
  position: absolute;
  top: 50%;
  right: 10px;
  transform: translateY(-50%);
  width: 34px;
  height: 34px;
  border-radius: 10px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #6b7280;
  background: transparent;
  border: 1px solid transparent;
  cursor: pointer;
  transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
}

.password-eye-btn svg{
  width: 18px;
  height: 18px;
}

.password-eye-btn:hover{
  background: rgba(0,0,0,0.04);
  border-color: rgba(0,0,0,0.08);
  color: #111827;
}

.nice-input:focus {
  outline: none;
  border-color: #2ecc71;
}

.error-msg {
  font-size: 12px;
  color: #ff4757;
  margin-top: 4px;
  display: block;
}

/* Stripe Fields */
.card-element-wrapper {
  margin-bottom: 20px;
}

.stripe-field-container {
  margin-bottom: 12px;
}

.stripe-el {
  padding: 12px 15px;
  border: 1px solid #ddd;
  border-radius: 4px;
  background-color: #fff;
}

.stripe-row {
  display: flex;
  gap: 12px;
}

.half {
  flex: 1;
}

.overall-error {
  color: #ff4757;
  font-size: 14px;
  margin-bottom: 15px;
  text-align: center;
}

/* Agreements */
.agreement-checks {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-bottom: 30px;
}

.check-box-label {
  display: flex;
  gap: 12px;
  font-size: 13px;
  line-height: 1.4;
  color: #555;
  cursor: pointer;
}

.check-box-label input {
  margin-top: 2px;
}

.info-icon {
  display: inline-block;
  width: 16px;
  height: 16px;
  border: 1px solid #999;
  border-radius: 50%;
  text-align: center;
  font-size: 10px;
  line-height: 14px;
  margin-left: 4px;
  font-style: normal;
}

/* CTA Button */
.btn-complete-purchase {
  width: 100%;
  background-color: #2ecc71;
  color: #fff;
  border: none;
  padding: 16px;
  border-radius: 4px;
  font-size: 16px;
  font-weight: 700;
  cursor: pointer;
  transition: background-color 0.2s;
}

.btn-complete-purchase:hover {
  background-color: #27ae60;
}

.btn-complete-purchase:disabled {
  background-color: #a8e6cf;
  cursor: not-allowed;
}

/* Responsive */
@media (max-width: 991px) {
  .checkout-container {
    flex-direction: column;
    padding: 20px;
  }

  .checkout-wrapper {
    padding: 40px 10px;
  }

  .checkout-form-side {
    position: static;
    width: 100%;
  }
}
</style>
