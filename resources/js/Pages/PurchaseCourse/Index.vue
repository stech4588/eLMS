<template>
  <Head title="Purchase Course" />
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
        <!-- Guest: full signup + card -->
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
            <div class="form-row">
              <div class="form-group full-width">
                <label for="email">Email</label>
                <input id="email" v-model="form.email" type="email" placeholder="Email" class="form-input" />
                <span v-if="formErrors.email" class="error-text">{{ formErrors.email }}</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group full-width">
                <label for="phone">Phone Number</label>
                <input id="phone" v-model="form.phone_number" type="tel" placeholder="Phone Number" class="form-input" />
                <span v-if="formErrors.phone_number" class="error-text">{{ formErrors.phone_number }}</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group full-width">
                <label for="password">Password</label>
                <div class="password-wrap">
                  <input
                    id="password"
                    v-model="form.password"
                    :type="showPassword ? 'text' : 'password'"
                    placeholder="Password"
                    class="form-input"
                  />
                  <button type="button" class="toggle-pw" @click="showPassword = !showPassword">
                    {{ showPassword ? 'Hide' : 'Show' }}
                  </button>
                </div>
                <span v-if="formErrors.password" class="error-text">{{ formErrors.password }}</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group full-width">
                <label for="password_confirmation">Confirm Password</label>
                <input
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  type="password"
                  placeholder="Confirm Password"
                  class="form-input"
                />
              </div>
            </div>
          </div>
        </template>

        <!-- Card (both guest and auth) -->
        <div class="form-section">
          <h3 class="form-section-title">Card information</h3>
          <div class="form-row card-row">
            <div class="form-group card-number">
              <label>Card number</label>
              <div id="card-number-element" class="stripe-element"></div>
            </div>
            <div class="form-group expiry">
              <label>Expiry date</label>
              <div id="card-expiry-element" class="stripe-element"></div>
            </div>
            <div class="form-group cvc">
              <label>CVV</label>
              <div id="card-cvc-element" class="stripe-element"></div>
            </div>
          </div>
        </div>

        <div v-if="paymentError" class="error-banner">{{ paymentError }}</div>

        <div class="total-row">
          <span>Total</span>
          <span class="total-amount">${{ coursePrice }}</span>
        </div>

        <button type="submit" class="submit-btn" :disabled="paymentProcessing">
          <span v-if="paymentProcessing">Processing...</span>
          <span v-else>{{ authUser ? 'Purchase' : 'Purchase & Create Account' }}</span>
        </button>
      </form>
    </div>
  </div>
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
    coursePrice() {
      const p = this.course?.price;
      return p != null ? Number(p).toFixed(2) : '0.00';
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
        base: { color: '#000', fontFamily: '"Montserrat", sans-serif', fontSize: '16px', '::placeholder': { color: '#a0a0a0' } },
        invalid: { color: '#fa755a' },
      };
      const el = document.getElementById('card-number-element');
      if (!el) return;
      this.cardNumber = this.elements.create('cardNumber', { style });
      this.cardNumber.mount('#card-number-element');
      this.cardExpiry = this.elements.create('cardExpiry', { style });
      this.cardExpiry.mount('#card-expiry-element');
      this.cardCvc = this.elements.create('cardCvc', { style });
      this.cardCvc.mount('#card-cvc-element');
    },
    destroyStripeElements() {
      [this.cardNumber, this.cardExpiry, this.cardCvc].forEach((el) => {
        if (el) {
          el.destroy();
        }
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
      } else if (this.form.password !== this.form.password_confirmation) {
        this.formErrors.password = 'Passwords do not match.';
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
          this.formErrors.email = 'An account with this email already exists. Please log in to purchase.';
          this.paymentError = 'An account with this email already exists. Please log in to purchase.';
          return;
        }
      }

      if (!this.cardNumber) {
        this.paymentError = 'Please wait for the payment form to load.';
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
          this.paymentError = error.message || 'Payment failed.';
          this.paymentProcessing = false;
          return;
        }

        if (paymentIntent.status !== 'succeeded') {
          this.paymentError = 'Payment was not successful. Please try again.';
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
          preserveScroll: true,
        });
      } catch (err) {
        this.paymentError = err?.response?.data?.message || err?.message || 'An error occurred. Please try again.';
        this.paymentProcessing = false;
      }
    },
  },
};
</script>

<style scoped>
.purchase-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #0d1016 0%, #1a2332 50%, #0d1016 100%);
  padding: 2rem 1rem;
  font-family: 'Montserrat', sans-serif;
  color: #fff;
}
.purchase-container {
  max-width: 560px;
  margin: 0 auto;
}
.purchase-header {
  margin-bottom: 1.5rem;
}
.back-link {
  color: #1897e5;
  text-decoration: none;
  font-size: 0.95rem;
  margin-bottom: 0.5rem;
  display: inline-block;
}
.back-link:hover {
  text-decoration: underline;
}
.purchase-title {
  font-size: 1.75rem;
  font-weight: 700;
  margin: 0;
}
.course-summary {
  display: flex;
  gap: 1rem;
  align-items: center;
  padding: 1rem;
  background: rgba(255,255,255,0.06);
  border-radius: 10px;
  margin-bottom: 1.5rem;
}
.course-thumb {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 8px;
}
.course-info h2 {
  font-size: 1.1rem;
  margin: 0 0 0.25rem 0;
}
.course-price {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1897e5;
  margin: 0;
}
.purchase-form {
  background: rgba(255,255,255,0.04);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 12px;
  padding: 1.5rem;
}
.form-section {
  margin-bottom: 1.5rem;
}
.form-section-title {
  font-size: 1rem;
  font-weight: 600;
  margin: 0 0 1rem 0;
  color: #e0e0e0;
}
.form-row {
  display: flex;
  gap: 1rem;
  margin-bottom: 1rem;
}
.form-group {
  flex: 1;
  display: flex;
  flex-direction: column;
}
.form-group.full-width { flex: 1 1 100%; }
.form-group.card-number { flex: 2; }
.form-group.expiry,
.form-group.cvc { flex: 1; }
.form-group label {
  font-size: 0.875rem;
  color: #a0a0a0;
  margin-bottom: 0.35rem;
}
.form-input {
  background: #fff;
  border: 1px solid #ccc;
  border-radius: 6px;
  padding: 0.75rem 1rem;
  color: #000;
  font-size: 1rem;
}
.form-input::placeholder {
  color: #888;
}
.password-wrap {
  position: relative;
}
.password-wrap .form-input { padding-right: 5rem; }
.toggle-pw {
  position: absolute;
  right: 0.5rem;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #1897e5;
  cursor: pointer;
  font-size: 0.85rem;
}
.error-text {
  font-size: 0.8rem;
  color: #f87171;
  margin-top: 0.25rem;
}
.stripe-element {
  background: #fff;
  border: 1px solid #ccc;
  border-radius: 6px;
  padding: 0.75rem 1rem;
  color: #000;
}
.card-row {
  display: flex;
  flex-wrap: wrap;
}
.error-banner {
  background: rgba(248,113,113,0.15);
  border: 1px solid #f87171;
  color: #fca5a5;
  padding: 0.75rem 1rem;
  border-radius: 8px;
  margin-bottom: 1rem;
  font-size: 0.9rem;
}
.total-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 0;
  font-size: 1.1rem;
}
.total-amount {
  font-weight: 700;
  color: #1897e5;
  font-size: 1.25rem;
}
.submit-btn {
  width: 100%;
  background: linear-gradient(109.78deg, #fff -13.37%, #1897e5 38.96%, #0e64a5 138.03%);
  color: #000;
  border: none;
  padding: 1rem 1.5rem;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 700;
  cursor: pointer;
}
.submit-btn:hover:not(:disabled) {
  opacity: 0.95;
}
.submit-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
@media (max-width: 600px) {
  .card-row { flex-direction: column; }
  .form-group.card-number,
  .form-group.expiry,
  .form-group.cvc { flex: 1 1 100%; }
}
</style>
