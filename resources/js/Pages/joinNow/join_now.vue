<template>
    
    <div class="main_joinNow">
        <div class="left_side_join">
            <div class="logo_with_ring">
                <img src="/images/logo_with_ring.svg">
            </div>
            <div class="title_key">
                <img src="/images/key.svg">
                <div style="margin-left: 10px;">UNLOCK ACCESS TO...</div>
            </div>
            <div style="margin-top: 20px;">
                <div class="text_tick">
                    <img src="/images/tick.svg">
                    <div style="margin-left: 10px;">Live calls and AMAs with Experts</div>
                </div>
                <div class="text_tick">
                    <img src="/images/tick.svg">
                    <div style="margin-left: 10px;">24/7 Support and on-demand guidance</div>
                </div>
                <div class="text_tick">
                    <img src="/images/tick.svg">
                    <div style="margin-left: 10px;">Over 18 Modern Wealth Creation Methods</div>
                </div>
                <div class="text_tick">
                    <img src="/images/tick.svg">
                    <div style="margin-left: 10px;">7+ Distinct Campuses</div>
                </div>
                <div class="text_tick">
                    <img src="/images/tick.svg">
                    <div style="margin-left: 10px;">1000+ Professionally made Video lessons</div>
                </div>
            </div>


            <div class="text_outtick">
                <div style="margin-left: 40px; margin-top: 20px;">
                    An active community of like-minded,
                    wealth-focused individuals.
                </div>
            </div>
        </div>
        <div class="right_side_join">
            <div class="right_header">
                <div class="inner_right_header">
                    <img src="/images/trw-logo.png">
                    <div class="right_header_text">
                        <p style="font-size: 27px; font-weight: 900;">JOIN THE REAL WORLD</p>
                        <p style="font-size: 21px; ">ESCAPE THE MATRIX</p>
                    </div>
                </div>
            </div>
            <form class="main_information_sec">
                <div class="plan_sec">
                    <div class="inf_title">
                        <img src="/images/check_icon.svg">
                        <p style="margin-left: 10px;">SELECT PLAN</p>
                    </div>
                    <div class="all_boxes">
                        <div class="main_plan_box" @click.prevent="showPaymentSection = true">
                            <div class="plan_box1">
                                <div class="inner_plan_box">
                                    <div class="main_header_box">
                                        <div class="prices_header">
                                            <div style="font-size: 27px; font-weight: 800; color: white;">$49.99&nbsp;
                                            </div>
                                            <div>/&nbsp;monthly</div>
                                        </div>
                                    </div>
                                    <div style="font-size: 24px; text-align: left; color: white;">Cadet</div>
                                    <div class="disc_plan_box">
                                        <div style="text-align: left;">A first step towards breaking free</div>
                                        <div class="stpes_plan">
                                            <div class="inner_stpes_plan">
                                                <img src="/images/tick.svg">

                                                <div style="margin-left: 5px;">Access to all TRW Campuses</div>
                                            </div>
                                            <div class="inner_stpes_plan">
                                                <img src="/images/tick.svg">

                                                <div style="margin-left: 5px;">Daily live broadcasts</div>
                                            </div>
                                            <div class="inner_stpes_plan">
                                                <img src="/images/tick.svg">

                                                <div style="margin-left: 5px;">Daily course updates</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="head_plan_btn">
                                    <div class="plan_btn">CHOOSE PLAN</div>
                                </button>
                                <button class="plan_arr_btn">
                                    <img style="filter: invert(1); height: 2rem;" src="/images/arrorw.svg">
                                </button>

                            </div>
                        </div>
                        <div class="main_plan_box" @click.prevent="showPaymentSection = true">
                            <div class="plan_box">
                                <button class="most_pop_btn">
                                    <div class="inner_pop_btn">MOST POPULAR</div>
                                </button>
                                <div class="inner_plan_box">
                                    <div class="main_header_box">
                                        <div class="prices_header">
                                            <div style="font-size: 27px; font-weight: 800; color: white;">$149&nbsp;
                                            </div>
                                            <div>/&nbsp;3 monthly</div>
                                        </div>
                                        <button class="most_pop_btn_none">
                                            <div class="inner_pop_btn">MOST POPULAR</div>
                                        </button>
                                    </div>
                                    <div style="font-size: 24px; text-align: left; color: white;">Challenger</div>
                                    <div class="disc_plan_box" style="text-align: left;">
                                        <div>Three months to harness your power.</div>
                                        <div class="stpes_plan">
                                            <div class="inner_stpes_plan">
                                                <img src="/images/tick.svg">

                                                <div style="margin-left: 5px;">All of Cadet</div>
                                            </div>
                                            <div class="inner_stpes_plan">
                                                <img src="/images/tick.svg">

                                                <div style="margin-left: 5px;">Daily coin bonus</div>
                                            </div>
                                            <div class="inner_stpes_plan">
                                                <img src="/images/tick.svg">

                                                <div style="margin-left: 5px;">Power level boost</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="head_plan_btn">
                                    <div class="plan_btn">CHOOSE PLAN</div>
                                </button>
                                <button class="plan_arr_btn">
                                    <img style="filter: invert(1); height: 2rem;" src="/images/arrorw.svg">
                                </button>

                            </div>
                        </div>
                        <div class="main_plan_box" @click.prevent="showPaymentSection = true">
                            <div class="plan_box">
                                <button class="most_save_btn">
                                    <div class="inner_save_btn">SAVE $103</div>
                                </button>
                                <div class="inner_plan_box">
                                    <div class="main_header_box">
                                        <div class="prices_header">
                                            <div style="font-size: 27px; font-weight: 800; color: white;">$497&nbsp;
                                            </div>
                                            <div>/&nbsp;1 year</div>
                                        </div>
                                        <button class="most_save_btn_none">
                                            <div class="inner_save_btn">SAVE $103</div>
                                        </button>
                                    </div>
                                    <div style="font-size: 24px; text-align:left; color: white;">Hero</div>
                                    <div class="disc_plan_box" style="text-align: left;">
                                        <div>One year of complete commitment</div>
                                        <div class="stpes_plan">
                                            <div class="inner_stpes_plan">
                                                <img src="/images/tick.svg">

                                                <div style="margin-left: 5px;">Maximum daily coin bonus</div>
                                            </div>
                                            <div class="inner_stpes_plan">
                                                <img src="/images/tick.svg">

                                                <div style="margin-left: 5px;">Big power level boost</div>
                                            </div>
                                            <div class="inner_stpes_plan">
                                                <img src="/images/tick.svg">

                                                <div style="margin-left: 5px;">Exclusive chats and lessons</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="head_plan_btn">
                                    <div class="plan_btn">CHOOSE PLAN</div>
                                </button>
                                <button class="plan_arr_btn">
                                    <img style="filter: invert(1); height: 2rem;" src="/images/arrorw.svg">
                                </button>

                            </div>
                        </div>
                    </div>

                </div>
                <div v-if="showPaymentSection" class="information_sec">
                    <div class="inf_title">
                        <img src="/images/check_icon.svg">
                        <p style="margin-left: 10px;">SELECT PAYMENT</p>
                    </div>
                    <div class="input_sec" style="color:white;">
                        <button class="payment_join"
                            @click.prevent="showCreditSection = true, showCheckoutSection = false">
                            <div class="left_pay_side">
                                <img style="filter: invert(1);" src="/images/p_box.svg">
                                <span style="margin-left: 10px;">Join with Credit/Debit Card </span>
                            </div>
                            <div class="right_pay_side">
                                <img src="/images/arrorw.svg">

                            </div>
                        </button>
                        <button class="payment_join"
                            @click.prevent="showCheckoutSection = true, showCreditSection = false"
                            style="margin-top: 10px;">
                            <div class="left_pay_side">
                                <img style="height: 2rem;" src="/images/pay-with-crypto.png">
                                <span style="margin-left: 10px;">Join with Crypto </span>
                            </div>
                            <div class="right_pay_side">
                                <img src="/images/arrorw.svg">

                            </div>
                        </button>

                    </div>
                </div>
                <div class="information_sec" id="checkout_id" v-if="showCheckoutSection">
                    <div class="inf_title">
                        <img src="/images/check_icon.svg">
                        <p style="margin-left: 10px;">CHECKOUT</p>
                    </div>

                    <div class="main_check_sec">
                        <div class="inner_main_check">
                            <div style="color: white; display: flex;">
                                <span style="font-size: 28px; font-weight: 700;">$149</span>
                                <span style="font-size: 28px; ">/ 3 Months</span>
                            </div>
                            <div style="margin-top: 10px; line-height: 25px;flex-direction: row; display: flex;">
                                <input type="checkbox" class="customer-checkbox">
                                <label style="color: rgb(183 183 183 / 1); margin-left: 10px;">I accept the
                                    <a>Terms and Conditions</a>
                                    <span>and <a>Privacy and Policy</a>.</span>
                                </label>
                            </div>
                        </div>
                        <div class="enter_btn">
                            <img src="/images/dollor_icon.svg">
                            <span style="font-size: 28px; font-weight: 700; text-align: center;">ENTER NOW</span>
                        </div>
                        <div class="enter_btn">
                            <img src="/images/T_icon.svg">
                            <span style="font-size: 28px; font-weight: 700; text-align: center;">ENTER NOW</span>
                        </div>
                    </div>

                </div>

                <div class="information_sec" v-if="showCreditSection">
                    <div class="inf_title">
                        <img src="/images/check_icon.svg">
                        <p style="margin-left: 10px;">ENTER CREDIT CARD</p>
                    </div>
                    <div class="input_sec">
                        <div id="payment-element" style="margin-bottom: 20px;"></div>
                        <div class="ticket-continue-btn-div">
                            <button class="ticket-continue-button bg-white px-4 py-2 rounded-md" @click="booking">
                                Pay 100
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div></div>

        </div>
    </div>
</template>

<script>
import { loadStripe } from '@stripe/stripe-js'

export default {
    name: 'join_now',
    data() {
        return {
            pk: 'pk_test_51RZFkqPF8BzoPAVhNu7Lpqi40TnbQETCQGyiisJyPfztajSTaZdBOfXem930W375gIMhjyaLK9VAJOMk4mUb9NNc009zifzCDs',
            showPaymentSection: false,
            showCreditSection: false,
            showCheckoutSection: false,
            stripe: null,
            cardElement: null,
            paymentProcessing: false,
            selectedPlan: null,
            plans: {
                cadet: { amount: 4999, name: 'Cadet', interval: 'monthly' },
                challenger: { amount: 14900, name: 'Challenger', interval: '3 months' },
                hero: { amount: 49700, name: 'Hero', interval: '1 year' }
            }
        };
    },
    async mounted() {
        // Initialize Stripe
        this.stripe = await loadStripe(this.pk);

        // Set up card element when credit section is shown
        this.$watch('showCreditSection', (newVal) => {
            if (newVal) {
                this.$nextTick(() => {
                    this.loadStripeDate();
                });
            }
        });
    },
    methods: {

        async booking() {
            const StripeResult = await this.stripe.confirmPayment({
                elements: this.elements,
                redirect: 'if_required'
            })

            if (!StripeResult.error) {
                // try {
                //     const response = await axios.post(/ticket/booking / ${ this.$userStore().user.id }, {
                //         cart: this.cart,
                //         payment: StripeResult.paymentIntent
                //     })
                //     if (response.data && response.data.message && response.data.bookingId) {
                //         this.$userStore().updateLoyalty_points(response.data.loyalty_points)
                //         this.$emit('completePayment', response.data.message, response.data.bookingId, true)
                //     }
                // } catch (error) {
                //     const errorMessage =
                //         error.response && error.response.data && error.response.data.message
                //             ? error.response.data.message
                //             : error.message
                //     this.$emit('completePayment', errorMessage, null, false)
                // } finally {
                //     this.isLoading = false
                // }
            } else {
                // console.log(StripeResult.error)
                // this.isLoading = false
                // toast('Card details are invalid!', {
                //     type: 'error',
                //     autoClose: 5000
                // })
            }
        },

        async loadStripeDate() {
            await axios
                .get('/fetch-intent/' + 100)
                .then((res) => {
                    this.elements = this.stripe.elements({ clientSecret: res.data.client_secret })
                    const paymentElement = this.elements.create('payment')
                    paymentElement.mount('#payment-element')
                })
        },

        setupStripeElements() {
            const elements = this.stripe.elements();
            this.cardElement = elements.create('card');
            this.cardElement.mount('#card-element');
        },

        async selectPlan(plan) {
            this.selectedPlan = plan;
            this.showPaymentSection = true;
        },

        async handleCreditCardPayment() {
            this.paymentProcessing = true;

            try {
                // Create payment intent
                const response = await axios.post('/api/stripe/create-payment-intent', {
                    amount: this.plans[this.selectedPlan].amount,
                    plan_type: this.selectedPlan
                });

                // Confirm payment
                const { paymentIntent, error } = await this.stripe.confirmCardPayment(
                    response.data.clientSecret, {
                    payment_method: {
                        card: this.cardElement,
                        billing_details: {
                            name: this.formData.firstName + ' ' + this.formData.lastName,
                            email: this.formData.email
                        }
                    }
                }
                );

                if (error) {
                    throw error;
                }

                // Payment succeeded
                this.$toast.success('Payment successful! Welcome to The Real World');
                // Redirect or show success message

            } catch (error) {
                console.error(error);
                this.$toast.error(error.message || 'Payment failed');
            } finally {
                this.paymentProcessing = false;
            }
        }
    }
}
</script>

<style>
.right_header_text {
    display: flex;
    flex-direction: column;
    text-align: left;
}

@media(max-width:1010px) {
    .right_header_text {
        text-align: center;
    }
}

.right_header_text p {
    margin: 0;
    margin-bottom: 13px;
}

.payment_join {
    color: white;
    border-color: hsl(var(--pc) / 0.4);
    width: 75%;
    flex-shrink: 1;
    height: 3rem;
    padding-left: 1rem;
    padding-right: 1rem;
    font-size: 1rem;
    line-height: 1.5rem;
    border-width: 1px;
    background-color: hsl(100 0 0 / 1);
    border-radius: 10px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

@media (max-width:1570px) {
    .payment_join {
        width: 90%;
    }
}

@media (max-width:342px) {
    .payment_join {
        height: 5rem;
    }
}

.left_pay_side {
    align-items: center;
    justify-content: center;
    display: flex;
}

.right_pay_side img {
    filter: invert(1);
    height: 2rem;
}

.inner_right_header {
    display: flex;
    align-items: center;
    color: white;
}

@media (max-width:1024px) {
    .inner_right_header {
        flex-direction: column;
        text-align: center;
    }
}

.inner_right_header img {
    height: 100px;


}

.right_side_join {
    padding-left: 23px;
    padding-right: 23px;
    padding-top: 30px;
    padding-bottom: 50px;
    width: 100%;
    overflow-y: scroll;
}

@media (max-width:1024px) {
    .right_side_join {
        width: 100%;
    }
}

.inf_title {
    display: flex;
    align-items: center;
    color: rgb(183 183 183 / 1);
    font-size: 18px;
    font-weight: 900;
}

@media (max-width:370px) {
    .inf_title {
        font-size: 13px;
    }
}

.inf_title img {
    height: 27px;
}

.input_join {
    border-color: hsl(var(--pc) / 0.4);
    width: 75%;
    flex-shrink: 1;
    height: 3rem;
    padding-left: 1rem;
    padding-right: 1rem;
    font-size: 1rem;
    line-height: 1.5rem;
    border-width: 1px;
    background-color: hsl(100 0 0 / 1);
    border-radius: 10px;
}

@media (max-width:1570px) {
    .input_join {
        width: 90%;
    }
}

@media (max-width:1535px) {
    .input_join {
        width: 85%;
    }
}

@media (max-width:460px) {
    .input_join {
        width: 80%;
    }
}

.input_sec {
    display: flex;
    flex-direction: column;
    padding-left: 36px;
}

@media (max-width:1570px) {
    .input_sec {
        width: 92%;
    }
}

@media (max-width:1010px) {
    .input_sec {
        width: 100%;
        display: flex;
        padding: 0px !important;
        align-items: center;
        text-align: left;
        justify-content: center;
        flex-direction: column;
    }
}

.lable_join {
    font-size: 14px;
    display: flex;
    user-select: none;
    align-items: center;
    justify-content: space-between;
    padding: .5rem .25rem;
}

.plan_box1 {
    padding-left: 20px;
    padding-right: 20px;
    padding-top: 16px;
    border: 1px solid rgb(27 30 38 / 1);
    padding-bottom: 16px;
    color: rgb(183 183 183 / 1);
    line-height: 30px;
    /* width: 200px; */
    background-color: rgb(27 30 38 / 1);
    align-items: center;
    height: 393.5px;
    justify-content: space-between;
    display: flex;
    flex-direction: column;
    transition: all .3s ease-in-out;
    border-radius: 20px;
}

.plan_box1:hover {
    border: 1px solid #e9b20a;
    border-radius: 20px;
}

@media (max-width:1535px) {
    .plan_box1 {
        align-items: flex-start;
        height: 180px;
        flex-direction: row;
        width: 100%;
        justify-content: flex-end;
    }
}

@media (max-width:1010px) {
    .plan_box1 {
        height: 100%;
    }
}

@media (max-width:370px) {
    .plan_box1 {
        width: 100%;
        align-items: center;
        justify-content: flex-start;
    }
}


.plan_box {
    padding-left: 20px;
    padding-right: 20px;
    padding-top: 16px;
    padding-bottom: 16px;
    color: rgb(183 183 183 / 1);
    line-height: 30px;
    /* width: 200px; */
    background-color: rgb(27 30 38 / 1);
    align-items: center;
    height: 393.5px;
    justify-content: space-between;
    border: 1px solid rgb(27 30 38 / 1);
    display: flex;
    flex-direction: column;
    transition: all .3s ease-in-out;
    border-radius: 20px;
}

.plan_box:hover {
    border: 1px solid #e9b20a;
    border-radius: 20px;
}

@media (max-width:1535px) {
    .plan_box {
        align-items: flex-start;
        height: 180px;
        flex-direction: row-reverse;
        width: 100%;
        justify-content: flex-end;
    }
}

@media (max-width:1535px) {
    .plan_box {

        flex-direction: row;

    }
}

@media (max-width:1010px) {
    .plan_box {
        height: 100%;
    }
}

@media (max-width:370px) {
    .plan_box {
        width: 85%;
        align-items: center;
        justify-content: flex-start;
    }
}

.prices_header {
    display: flex;
    align-items: center;
}

.inner_stpes_plan {
    flex-direction: row;
    align-items: center;
    display: flex;
}

@media(max-width:370px) {
    .inner_stpes_plan {
        font-size: 10px;
    }
}

.inner_stpes_plan img {
    height: 15px;
}

@media(max-width:370px) {
    .inner_stpes_plan img {
        height: 10px;
    }
}

.plan_btn {
    font-size: 1.25rem;
    line-height: 1.75rem;
    font-weight: 700;
    color: white;
    padding: 8px;
}

.head_plan_btn {
    margin-top: .25rem;
    background-color: rgb(57 60 69 /1);
    align-items: center;
    justify-content: center;
    display: flex;
    border-radius: 10px;
}

@media (max-width:1535px) {
    .head_plan_btn {
        display: none;
    }
}

.disc_plan_box {

    line-height: 30px;
    align-items: center;
    justify-content: center;
    /* display: flex; */
    flex-direction: column;
}

.all_boxes {
    display: flex;
    gap: 20px;
}

@media (max-width:1535px) {
    .all_boxes {
        flex-direction: column;
        padding-left: 37px;
        padding-right: 37px;
    }
}

@media (max-width:1010px) {
    .all_boxes {
        width: 92%;
    }
}

@media (max-width:510px) {
    .all_boxes {
        flex-direction: column;
        padding-left: 0px;
        padding-right: 0px;
        width: 90%;
    }
}

.most_pop_btn {
    background-color: rgb(57 60 69 /1);
    border-radius: 20px;

}

.most_pop_btn_none {
    display: none;
    border: none;
}

@media (max-width:1535px) {
    .most_pop_btn_none {
        display: flex;
        background-color: rgb(57 60 69 /1);
        border-radius: 20px;
    }
}

@media (max-width:1535px) {
    .most_pop_btn {
        display: none;


    }
}

@media (max-width:470px) {
    .most_pop_btn_none {
        letter-spacing: 0;
        align-items: center;
    }
}

@media (max-width:417px) {
    .most_pop_btn_none {
        height: 40px;
    }
}

.inner_pop_btn {
    padding: 5px;
    color: #f1ba13;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .1em;
}

@media (max-width:466px) {
    .inner_pop_btn {
        padding: 0px;
        font-size: 9px;
    }
}

@media (max-width:417px) {
    .inner_pop_btn {

        font-size: 7px;
    }
}

.most_save_btn {
    border-radius: 20px;
    background-image: linear-gradient(137deg, #FFB54C, #FF8838);
}

@media (max-width:1535px) {
    .most_save_btn {
        display: none;
    }
}

.most_save_btn_none {
    display: none;
    border: none;
}

@media (max-width:1535px) {
    .most_save_btn_none {
        display: flex;
        border-radius: 20px;
        background-image: linear-gradient(137deg, #FFB54C, #FF8838);
    }
}

@media (max-width:417px) {
    .most_save_btn_none {
        height: 23px;
    }
}

@media (max-width:360px) {
    .most_save_btn_none {
        height: 30px;
    }
}

.inner_save_btn {
    padding: 5px;
    border: none;
    color: black;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .1em;
}

@media (max-width:470px) {
    .inner_save_btn {
        align-items: center;
    }
}

@media (max-width:417px) {
    .inner_save_btn {
        font-size: 8px;
    }
}

.main_plan_box {
    align-items: flex-start;
    justify-content: flex-start;
    display: flex;
}


@media (max-width:1535px) {
    .main_header_box {
        flex-direction: row;
        display: flex;
        justify-content: space-between;
    }
}

@media (max-width:1535px) {
    .inner_plan_box {
        width: 100%;
    }
}

@media (max-width:1024px) {
    .right_header {
        align-items: center;
        justify-content: center;
        display: flex;
    }
}

@media (max-width:1024px) {
    .information_sec {
        align-items: center;
        justify-content: center;
        display: flex;
        flex-direction: column;
    }
}

@media (max-width:1024px) {
    .plan_sec {
        align-items: center;
        justify-content: center;
        display: flex;
        flex-direction: column;
    }
}

.plan_arr_btn {
    display: none;
}

@media (max-width:1535px) {
    .plan_arr_btn {
        margin-top: 140px;
        background-color: transparent;
        border-style: none;
        display: flex;
    }
}

.main_check_sec {
    padding-left: 35px;
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

@media (max-width: 1024px) {
    .main_check_sec {
        padding-left: 0px;
    }
}

.customer-checkbox {
    cursor: pointer;
    border: 2px solid white;
    height: 22px;
    width: 37px;
    background-color: transparent;
    appearance: none;
    border-radius: 5px;
}

.customer-checkbox:checked {
    background-color: #f1ba13;
}

.customer-checkbox:checked::after {
    content: "\2714";
    font-size: 1rem;
    color: white;
    position: relative;
    left: 16%;
    transform: translate(-50%, -50%);
    font-weight: bold;

}

.enter_btn {
    border-radius: 10px;
    background-color: #ffaa30;
    margin-top: 20px;
    text-align: center;
    height: 4rem;
    align-items: center;
    justify-content: center;
    display: flex;
}

.enter_btn {
    border-radius: 10px;
    background-color: #ffaa30;
    margin-top: 20px;
    height: 4rem;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 20px;
    /* Add padding for spacing */
    gap: 10px;
    /* Space between image and text */
    width: 75%;
}

@media (max-width: 1570px) {
    .enter_btn {
        width: 90%;
    }
}

.enter_btn img {
    height: 2.5rem;
}


.enter_btn span {
    flex: 1;
    text-align: center;
    font-size: 28px;
    font-weight: 700;
}

.main_joinNow {
    font-family: 'Montserrat', sans-serif;
    width: 100%;
    height: 100vh;
    flex-direction: row;
    display: flex;
    position: fixed;

    background-color: #06060C;
}

.left_side_join {
    width: 55%;
    /* height: 800px; */
    background-color: #0d1016b0;
    text-align: left;
    padding-left: 90px;
    padding-right: 90px;
    padding-top: 53px;
    padding-bottom: 54px;
    /* justify-content: center; */
    align-items: flex-start;
    display: flex;
    flex-direction: column;
    line-height: 39px;
}

@media (max-width:1633px) {
    .left_side_join {
        width: 75%;
    }
}

@media (max-width:1024px) {
    .left_side_join {
        display: none;
    }
}

.text_tick {
    display: flex;
    text-align: left;
    font-size: 17px;
    font-weight: 600;
    align-items: center;
    color: white;

}

.text_tick img {
    height: 17px;
    ;
}

.title_key {
    display: flex;
    font-size: 35px;
    color: white;
    font-weight: 600;
    align-items: center;
    margin-top: 30px;

}

@media (max-width:1311px) {
    .title_key {
        font-size: 27px;
    }
}

.title_key img {
    height: 27px;
}

.text_outtick {
    font-size: 25px;
    font-weight: 600;
    color: white;
}

.logo_with_ring img {
    height: 220px;
}
</style>