<template>
    <div class="trw-background">
        <div class="trw-container">
            <div class="trw-header">
                <!-- <div class="price-guarantee">PRICE GUARANTEE HAS EXPIRED</div> -->
                <img src="/images/MBM_Uni.png" alt="Logo" class="trw-logo">
                <h1 class="trw-title">CHOOSE YOUR PATH TO SUCCESS</h1>
                <p class="trw-subtitle">Join thousands crushing their competition and building enemies</p>
            </div>

            <div class="billing-cycle-switcher">
                <span>Monthly</span>
                <label class="switch">
                    <input type="checkbox" v-model="isYearly" @change="handleBillingCycleChange">
                    <span class="slider round"></span>
                </label>
                <span>Yearly</span>
            </div>
            <div class="save-yearly-banner">
                <img src="/images/key.svg" alt="Sparkle" style="filter: invert(75%) sepia(85%) saturate(849%) hue-rotate(359deg) brightness(101%) contrast(101%);">
                SAVE UP TO 17% WITH YEARLY
            </div>

            <div class="pricing-plans">
                <div class="plan" :class="{ 'selected': selectedPlan === 'earn' }" @click="selectPlan('earn')">
                    <h2 class="plan-title">EARN</h2>
                    <div class="plan-price">${{ getPrice('earn') }}<span style="font-size: 25px; font-weight: 700;">/{{ isYearly ? 'year' : 'month' }}</span></div>
                    <ul class="plan-features">
                        <li><img src="/images/tick.svg" alt="tick"> Choose 1 Business Model</li>
                        <li><img src="/images/tick.svg" alt="tick"> Daily Live Broadcasts</li>
                        <li><img src="/images/tick.svg" alt="tick"> Real-Time Course Updates</li>
                        <li><img src="/images/tick.svg" alt="tick"> 3 Connected Devices</li>
                        <li><img src="/images/tick.svg" alt="tick"> Community Access</li>
                    </ul>
                    <button class="join-button" :class="{ 'selected': selectedPlan === 'earn' }">
                        <img src="/images/p_box.svg" style="filter: invert(1); height:1rem" />
                        Join with Card
                    </button>
                </div>

                <div class="plan prosper" :class="{ 'selected': selectedPlan === 'prosper' }" @click="selectPlan('prosper')">
                    <h2 class="plan-title">PROSPER</h2>
                    <div class="plan-price">${{ getPrice('prosper') }}<span style="font-size: 25px; font-weight: 700;">/{{ isYearly ? 'year' : 'month' }}</span></div>
                    <ul class="plan-features">
                        <li><img src="/images/tick.svg" alt="tick"> Everything in Earn</li>
                        <li><img src="/images/tick.svg" alt="tick"> 1 Extra Business Model</li>
                        <li><img src="/images/tick.svg" alt="tick"> Priority Support</li>
                        <li><img src="/images/tick.svg" alt="tick"> 5 Connected Devices</li>
                        <li><img src="/images/tick.svg" alt="tick"> Exclusive Workshops</li>
                    </ul>
                    <button class="join-button" :class="{ 'selected': selectedPlan === 'prosper' }">
                        <img src="/images/p_box.svg" style="filter: invert(1); height:1rem" />
                        Join with Card
                    </button>
                </div>

                <div class="plan conquer" :class="{ 'selected': selectedPlan === 'conquer' }" @click="selectPlan('conquer')">
                    <h2 class="plan-title">CONQUER</h2>
                    <div class="plan-price">${{ getPrice('conquer') }}<span style="font-size: 25px; font-weight: 700;">/{{ isYearly ? 'year' : 'month' }}</span></div>
                    <ul class="plan-features">
                        <li><img src="/images/tick.svg" alt="tick"> Everything in Prosper</li>
                        <li><img src="/images/tick.svg" alt="tick"> 9+ Extra Business Models</li>
                        <li><img src="/images/tick.svg" alt="tick"> VIP Community Access</li>
                        <li><img src="/images/tick.svg" alt="tick"> 7 Connected Devices</li>
                        <li><img src="/images/tick.svg" alt="tick"> Early Access to New Content</li>
                    </ul>
                     <button class="join-button" :class="{ 'selected': selectedPlan === 'conquer' }">
                        <img src="/images/p_box.svg" style="filter: invert(1); height:1rem" />
                        Join with Card
                    </button>
                </div>
            </div>
            
            <div class="join-info">
                <div class="avatars">
                    <img src="/images/1.png" alt="Member Avatar">
                    <img src="/images/2.png" alt="Member Avatar">
                    <img src="/images/3.png" alt="Member Avatar">
                </div>
                <span>Join <b>120,000+</b> members already transforming their lives</span>
            </div>
            <p class="price-currency-note">*All Prices Are Presented In USD.</p>

            <div v-if="showPaymentForm" class="payment-form">
                <div class="form-section">
                    <h3 class="form-section-title">CARD INFORMATION</h3>
                     <div class="form-row">
                        <div class="form-group card-number">
                            <label for="card-number">Card number</label>
                            <div class="input-with-icon">
                                 <img src="/images/p_box.svg" style="filter: invert(0.5); position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); height: 20px;" />
                                <div id="card-number-element" class="stripe-element"></div>
                            </div>
                        </div>
                        <div class="form-group expiration-date">
                            <label for="expiration-date">Expiration Date</label>
                            <div id="card-expiry-element" class="stripe-element"></div>
                        </div>
                        <div class="form-group cvc">
                            <label for="cvc">CVC</label>
                           <div id="card-cvc-element" class="stripe-element"></div>
                        </div>
                    </div>
                    <div class="form-row">
                         <div class="form-group billing-address">
                            <label for="billing-address">Billing Address</label>
                            <input type="text" id="billing-address" placeholder="Billing Address" v-model="formData.billingAddress" class="stripe-input">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title">YOUR INFORMATION</h3>
                     <div class="form-row">
                        <div class="form-group email-address">
                            <label for="email">Email address</label>
                            <input type="email" id="email" placeholder="example@gmail.com" v-model="formData.email" class="stripe-input">
                            <div v-if="formErrors.email" class="text-red-500 mt-1 text-xs">{{ formErrors.email }}</div>
                        </div>
                    </div>
                     <div class="form-row">
                        <div class="form-group full-name">
                            <label for="full-name">Full Name</label>
                            <input type="text" id="full-name" placeholder="Full Name" v-model="formData.name" class="stripe-input">
                            <div v-if="formErrors.name" class="text-red-500 mt-1 text-xs">{{ formErrors.name }}</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group password">
                            <label for="password">Password</label>
                            <input type="password" id="password" placeholder="Password" v-model="formData.password" class="stripe-input">
                            <div v-if="formErrors.password" class="text-red-500 mt-1 text-xs">{{ formErrors.password }}</div>
                        </div>
                    </div>
                </div>

                <div class="total-due">
                    <p>Total Due:</p>
                    <p class="total-price">${{getTotalDue()}}<span v-if="!isYearly">/month</span></p>
                </div>

                <div class="terms-agreement">
                    <input type="checkbox" id="terms-checkbox" class="custom-checkbox" v-model="formData.terms">
                    <label for="terms-checkbox">I accept the <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>, and agree to pay ${{getTotalDue()}} USD every month until I cancel.</label>
                </div>
                <div v-if="formErrors.terms" class="text-red-500 text-sm mb-4">{{ formErrors.terms }}</div>

                <button @click.prevent="processPayment" class="submit-payment-btn" :disabled="paymentProcessing">
                    <span v-if="paymentProcessing">Processing...</span>
                    <span v-else class="flex items-center justify-center">
                         <img src="/images/p_box.svg" style="height:24px; filter: invert(0); margin-right: 10px;" alt="card icon" />
                        Enter MBM University
                    </span>
                </button>
                <div v-if="paymentError" class="text-red-500 mt-4 text-center">{{ paymentError }}</div>
                <p class="copyright">Copyright © 2025 MBM University</p>
            </div>
        </div>
    </div>
</template>

<script>
import { loadStripe } from '@stripe/stripe-js';
import axios from 'axios';

export default {
    name: 'JoinNowRedesigned',
    data() {
        return {
            pk: 'pk_test_51RZFkqPF8BzoPAVhNu7Lpqi40TnbQETCQGyiisJyPfztajSTaZdBOfXem930W375gIMhjyaLK9VAJOMk4mUb9NNc009zifzCDs',
            stripe: null,
            elements: null,
            cardNumber: null,
            cardExpiry: null,
            cardCvc: null,
            paymentProcessing: false,
            showPaymentForm: false,
            isYearly: false,
            selectedPlan: 'prosper',
            paymentError: null,
            formData: {
                name: '',
                email: '',
                password: '',
                billingAddress: '',
                terms: false,
            },
            formErrors: {
                name: '',
                email: '',
                password: '',
                terms: '',
            },
            plans: {
                earn: { monthly: 49, yearly: 492 }, 
                prosper: { monthly: 69, yearly: 699 },
                conquer: { monthly: 99, yearly: 999 },
            }
        };
    },
    async mounted() {
        this.stripe = await loadStripe(this.pk);
    },
    methods: {
        selectPlan(plan) {
            this.selectedPlan = plan;
            this.showPaymentForm = true;
            this.$nextTick(() => {
                this.initializeStripeElements();
            });
        },
        getPrice(plan) {
            if (this.isYearly) {
                return (this.plans[plan].yearly ).toFixed(0);
            }
            return this.plans[plan].monthly ;
        },
        getTotalDue() {
            let total = this.isYearly ? this.plans[this.selectedPlan].yearly : this.plans[this.selectedPlan].monthly;
            return (total ).toFixed(2);
        },
        getAmountInCents() {
            const plan = this.plans[this.selectedPlan];
            return this.isYearly ? Math.round(plan.yearly) : plan.monthly;
        },
        handleBillingCycleChange() {
            if(this.showPaymentForm) {
                 this.initializeStripeElements();
            }
        },
        initializeStripeElements() {
            this.elements = this.stripe.elements();
            const elementStyles = {
                base: {
                    color: '#fff',
                    fontFamily: '"Montserrat", sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#a0a0a0'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };

            this.cardNumber = this.elements.create('cardNumber', { style: elementStyles });
            this.cardNumber.mount('#card-number-element');

            this.cardExpiry = this.elements.create('cardExpiry', { style: elementStyles });
            this.cardExpiry.mount('#card-expiry-element');

            this.cardCvc = this.elements.create('cardCvc', { style: elementStyles });
            this.cardCvc.mount('#card-cvc-element');
        },
        validateForm() {
            this.formErrors = { name: '', email: '', password: '', terms: '' };
            this.paymentError = null;
            let hasError = false;
            if (!this.formData.name) {
                this.formErrors.name = 'Full Name is required.';
                hasError = true;
            }
            if (!this.formData.email) {
                this.formErrors.email = 'Email address is required.';
                hasError = true;
            }
            if (!this.formData.password) {
                this.formErrors.password = 'Password is required.';
                hasError = true;
            }
            if (!this.formData.terms) {
                this.formErrors.terms = 'You must accept the terms and conditions.';
                hasError = true;
            }
            return !hasError;
        },
        async processPayment() {
            if (!this.validateForm()) {
                return;
            }
            this.paymentProcessing = true;
            this.paymentError = null;
            
            try {
                const amount = this.getAmountInCents();
                const intentResponse = await axios.get(`/fetch-intent/${amount}`);
                const clientSecret = intentResponse.data.client_secret;

                const { paymentIntent, error } = await this.stripe.confirmCardPayment(
                    clientSecret, {
                        payment_method: {
                            card: this.cardNumber,
                            billing_details: {
                                name: this.formData.name,
                                email: this.formData.email,
                                address: {
                                    line1: this.formData.billingAddress,
                                },
                            },
                        },
                    }
                );

                if (error) {
                    throw error;
                }

                if (paymentIntent.status === 'succeeded') {
                    const postPaymentData = {
                        name: this.formData.name,
                        email: this.formData.email,
                        password: this.formData.password,
                        billingAddress: this.formData.billingAddress,
                        transaction_id: paymentIntent.id,
                        amount: this.getTotalDue(),
                        plan: this.selectedPlan,
                        billing_cycle: this.isYearly ? 'yearly' : 'monthly',
                        payment_method: paymentIntent.payment_method_types[0] || 'card',
                    };
                    this.$inertia.post('/register-from-payment', postPaymentData, {
                        onFinish: () => this.paymentProcessing = false,
                    });
                } else {
                    this.paymentError = "Payment was not successful. Please try again.";
                    this.paymentProcessing = false;
                }

            } catch (error) {
                console.error(error);
                this.paymentError = error.message || "An unexpected error occurred.";
                this.paymentProcessing = false;
            }
        }
    },
    beforeDestroy() {
        if (this.cardNumber) {
            this.cardNumber.destroy();
        }
        if (this.cardExpiry) {
            this.cardExpiry.destroy();
        }
        if (this.cardCvc) {
            this.cardCvc.destroy();
        }
    }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap');

/* Reset and base styles */
body {
    background-color: #0d1016;
    color: white;
    font-family: 'Montserrat', sans-serif;
}

.trw-background {
    background-color: #0D1016;
    background-image: 
        linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
    background-size: 40px 40px;
    min-height: 100vh;
    padding: 2rem;
}

.trw-container {
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
    text-align: center;
}

.trw-header {
    margin-bottom: 2rem;
    align-items: center;
    justify-content: center;
    display: flex;
    flex-direction: column;
}

.price-guarantee {
    background-color: #4a1c1c;
    color: #f7b1b1;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    display: inline-block;
    margin-bottom: 2rem;
    font-weight: 600;
    font-size: 0.9rem;
}

.trw-logo {
    height: 100px;
    margin-bottom: 2rem;
}

.trw-title {
    font-size: 3rem;
    font-weight: 900;
    margin-bottom: 0.5rem;
    color: #FFFFFF;
}

.trw-subtitle {
    font-size: 1.2rem;
    color: #a0a0a0;
}

/* Billing Cycle Switcher */
.billing-cycle-switcher {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 1rem;
    font-size: 1.2rem;
    font-weight: 600;
}

.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    margin: 0 1rem;
}

.switch input { 
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #E9B20A;
    transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
}

input:checked + .slider:before {
    transform: translateX(26px);
}

.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}

.save-yearly-banner {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background-color: rgba(233, 178, 10, 0.1);
    border: 1px solid #E9B20A;
    padding: 0.5rem 1.5rem;
    border-radius: 20px;
    color: #E9B20A;
    font-weight: 600;
    margin: 1rem 0 2rem 0;
}

/* Pricing Plans */
.pricing-plans {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.plan {
    background-color: #12151c;
    border: 2px solid #2a2d35;
    border-radius: 10px;
    padding: 2rem;
    text-align: left;
    cursor: pointer;
    transition: border-color 0.3s;
    display: flex;
    flex-direction: column;
    color: #FFFFFF;
}
.plan.prosper {
    border-color: #E9B20A;
}

.plan.conquer {
     border-color: #d9534f;
}

.plan:hover, .plan.selected {
    border-color: #E9B20A;
}

.plan-title {
    font-size: 1.5rem;
    font-weight: 700;
    text-align: center;
}

.plan-price {
    font-size: 2.5rem;
    font-weight: 700;
    text-align: center;
}

.plan-price span {
    font-size: 1rem;
    color: #a0a0a0;
    font-weight: 400;
}

.plan-features {
    list-style: none;
    padding: 0;
    margin: 1.5rem 0;
    flex-grow: 1;
}

.plan-features li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.plan-features li img {
    height: 16px;
}

.join-button {
    width: 100%;
    background-color: #2a2d35;
    color: white;
    border: 1px solid #444;
    padding: 1rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
}
.join-button.selected{
    border: 1px solid #ECC870;
    background: linear-gradient(109.78deg, rgba(255, 255, 255, 0.15) -13.37%, rgba(236, 200, 112, 0.15) 38.96%, rgba(134, 114, 64, 0.15) 138.03%);
}

/* Join Info */
.join-info {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}
.avatars {
    display: flex;
}
.avatars img {
    height: 32px;
    width: 32px;
    border-radius: 50%;
    border: 2px solid #0D1016;
    margin-left: -10px;
}
.avatars img:first-child {
    margin-left: 0;
}

.price-currency-note {
    color: #a0a0a0;
    font-size: 0.9rem;
    margin-bottom: 3rem;
}

/* Payment Form */
.payment-form {
    max-width: 900px;
    margin: 0 auto;
    text-align: left;
}

.form-section {
    margin-bottom: 2rem;
}

.form-section-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: #FFFFFF;
}

.form-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-group {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.form-group:last-child {
    margin-bottom: 0;
}


.form-group label {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    color: #a0a0a0;
}

.form-group input {
    background-color: #1B1E26;
    border: 1px solid #333;
    border-radius: 5px;
    padding: 1rem;
    color: white;
    font-size: 1rem;
    width: 100%;
}
.input-with-icon {
    position: relative;
    width: 100%;
}

.input-with-icon input {
    padding-left: 3rem;
}

.card-number { flex-basis: 50%; }
.expiration-date { flex-basis: 25%; }
.cvc { flex-basis: 25%; }
.billing-address { flex-basis: 100%; }
.email-address { flex-basis: 100%; }
.full-name { flex-basis: 100%; }

/* Total Due */
.total-due {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #0d1a25;
    border: 1px solid #3a4d5e;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}
.total-due p {
    margin: 0;
    font-size: 1.1rem;
}
.total-due .total-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #ecc870;
}

.total-due .total-price span {
    font-size: 1rem;
    font-weight: 400;
    color: #a0a0a0;
}

/* Terms Agreement */
.terms-agreement {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    font-size: 0.9rem;
    color: #a0a0a0;
}
.terms-agreement a {
    color: white;
    text-decoration: underline;
}

.custom-checkbox {
    appearance: none;
    background-color: transparent;
    border: 2px solid #555;
    padding: 0.5rem;
    border-radius: 4px;
    cursor: pointer;
    position: relative;
    width: 24px;
    height: 24px;
}
.custom-checkbox:checked {
    background-color: #E9B20A;
    border-color: #E9B20A;
}

/* Submit button */
.submit-payment-btn {
    width: 100%;
    background: linear-gradient(109.78deg, rgb(255, 255, 255) -13.37%, rgb(236, 200, 112) 38.96%, rgb(134, 114, 64) 138.03%);
    color: black;
    border: none;
    padding: 1.5rem;
    border-radius: 8px;
    font-size: 1.2rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    transition: background 0.3s;
}
.submit-payment-btn:hover{
    transform: scale(1.05);
    transition: transform 0.3s ease;
}
.submit-payment-btn img {
    filter: invert(1);
}

.copyright {
    margin-top: 2rem;
    font-size: 0.9rem;
    color: #a0a0a0;
}

@media (max-width: 992px) {
    .pricing-plans {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
        margin-bottom: 0;
    }
    .form-group {
        margin-bottom: 1rem;
    }
     .form-group:last-child {
        margin-bottom: 0;
    }
    .trw-title {
        font-size: 2rem;
    }
    .trw-subtitle {
        font-size: 1rem;
    }
     .trw-background {
        padding: 1rem;
    }
}

.stripe-element {
    background-color: #0d1a25;
    border: 1px solid #3a4d5e;
    border-radius: 5px;
    padding: 1rem;
    color: white;
    font-size: 1rem;
    width: 100%;
}
.input-with-icon .stripe-element{
    padding-left: 3.5rem;
}
.stripe-input {
    background-color: #0d1a25 !important;
    border: 1px solid #3a4d5e !important;
    border-radius: 5px;
    padding: 1rem;
    color: white;
    font-size: 1rem;
    width: 100%;
}
</style>