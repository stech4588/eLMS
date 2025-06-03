<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordFieldType = ref('password');

const togglePasswordVisibility = () => {
    passwordFieldType.value = passwordFieldType.value === 'password' ? 'text' : 'password';
};

const form = useForm({
    name: '',
    // company_name: '',
    email: '',
    // num_employees: '',
    password: '',
    // phone_country_code: 'PK',
    phone_number: '',
    agree_to_terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="form-container">
            <h1 class="form-title">Empower Your Learning Journey</h1>
            <p class="form-subtitle">
                Welcome to LMS.360.pk! Please fill out the form below to start your free trial and begin learning today.
            </p>

            <form @submit.prevent="submit" class="form-body">
                <!-- Row 1: Name and Company Name -->
                <div class="form-row">
                    <div class="form-group input_box_signup">
                        <InputLabel for="name" value="Name" class="form-label" />
                        <TextInput
                            id="name"
                            type="text"
                            class="form-input input_box_outline"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                        />
                        <InputError class="form-error" :message="form.errors.name" />
                    </div>
                    <!-- <div class="form-group input_box_signup">
                        <InputLabel for="company_name" value="Company Name" class="form-label" />
                        <TextInput
                            id="company_name"
                            type="text"
                            class="form-input input_box_outline"
                            v-model="form.company_name"
                            required
                            autocomplete="organization"
                        />
                        <InputError class="form-error" :message="form.errors.company_name" />
                    </div> -->
                </div>

                <!-- Row 2: Email and Number of Employees -->
                <div class="form-row">
                    <div class="form-group input_box_signup">
                        <InputLabel for="email" value="Email" class="form-label" />
                        <TextInput
                            id="email"
                            type="email"
                            class="form-input input_box_outline"
                            v-model="form.email"
                            required
                            autocomplete="username"
                        />
                        <InputError class="form-error" :message="form.errors.email" />
                    </div>
                    <!-- <div class="form-group input_box_signup">
                        <InputLabel for="num_employees" value="Number of Employee's" class="form-label" />
                        <select
                            id="num_employees"
                            class="form-input form-select input_box_outline"
                            v-model="form.num_employees"
                            required
                        >
                            <option value="" disabled>Select an option</option>
                            <option value="1-10">1-10</option>
                            <option value="11-50">11-50</option>
                            <option value="51-200">51-200</option>
                            <option value="201-500">201-500</option>
                            <option value="500+">500+</option>
                        </select>
                        <InputError class="form-error" :message="form.errors.num_employees" />
                    </div> -->
                </div>

                <!-- Row 3: Password and Phone Number -->
                <div class="form-row">
                    <div class="form-group input_box_signup">
                        <InputLabel for="password" value="Password" class="form-label" />
                        <div style="position: relative;">
                            <TextInput
                                id="password"
                                :type="passwordFieldType"
                                class="form-input input_box_outline"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                            />
                            <span class="password-eye-icon" @click="togglePasswordVisibility"><img src="/images/view_icon.svg"/></span>
                        </div>
                        <InputError class="form-error" :message="form.errors.password" />
                    </div>
                    <div class="form-group input_box_signup">
                        <InputLabel for="phone_number" value="Phone Number" class="form-label" style="margin-bottom: 0px; margin-top: 0px;"/>
                        <div class="phone-input-group">
                            <select v-model="form.phone_country_code" class="form-input country-code-select">
                                <option value="PK">PK</option>
                                <!-- Add other countries as needed -->
                            </select>
                            <TextInput
                                id="phone_number"
                                type="tel"
                                class="form-input phone-number-input input_box_outline"
                                v-model="form.phone_number"
                                placeholder="0301 1234857"
                                required
                                autocomplete="tel-national"
                                style="border: none;"
                            />
                        </div>
                        <InputError class="form-error" :message="form.errors.phone_number" />
                    </div>
                </div>
                 <div class="password-rules">
                            <span><span class="rule-cross">✗</span> At least one uppercase letter</span>
                            <span><span class="rule-cross">✗</span> At least one uppercase letter</span>
                            <span><span class="rule-cross">✗</span> At least one uppercase letter</span>
                            <span><span class="rule-cross">✗</span> At least one uppercase letter</span>
                        </div>

                <!-- Terms and Conditions -->
                <div class="form-group terms-group">
                    <input
                        type="checkbox"
                        id="agree_terms"
                        v-model="form.agree_to_terms"
                        required
                        class="form-checkbox"
                    />
                    <label for="agree_terms" class="terms-label">
                        I agree to the <Link href="/privacy-policy" class="form-link">Privacy Policy</Link> & <Link href="/terms-of-services" class="form-link">Terms Of Services</Link>
                    </label>
                    <InputError class="form-error" :message="form.errors.agree_to_terms" />
                </div>

                <!-- Buttons -->
                <div class="form-actions">
                    <PrimaryButton
                        class="submit-button"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing || !form.agree_to_terms"
                        style="    background-color: #1898e5;"
                    >
                        Sign Up
                    </PrimaryButton>
                    <Link :href="route('login')" class="login-button-link">
                        Log In
                    </Link>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>

<style scoped>
.form-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px 40px; /* Added more horizontal padding */
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif; /* Modern sans-serif font stack */
    background-color: #fff;
    max-width: 900px; /* Increased max-width */
    margin: 2rem auto; /* Centering and margin */
}

.form-title {
    font-size: 28px; /* Adjusted as per image */
    font-weight: 600; /* Semi-bold */
    color: #111827; /* Darker gray */
    margin-bottom: 8px; /* Adjusted margin */
    text-align: left;
    width: 100%;
}

.form-subtitle {
    font-size: 14px;
    color: #6B7280; /* Medium gray */
    margin-bottom: 30px;
    text-align: left;
    width: 100%;
}

.form-body {
    width: 100%;
}

.form-row {
    display: flex;
    gap: 25px; /* Gap between columns */
    margin-bottom: 20px; /* Space between rows */
}

.form-group {
    flex: 1; /* Each group takes equal space in a row */
    display: flex;
    flex-direction: column;
}
.input_box_signup{
    border: 1px solid #D1D5DB;
    border-left: 4px solid #9CA3AF;
    border-radius: 6px;
    justify-content: center;
    
    display: flex;
}

.form-label {
    display: block;
    margin-bottom: 6px;
    margin-top: 1px;
    font-size: 14px;
    font-weight: 500;
    color: #555555; /* Slightly lighter than title */
    margin-left: 12px;
}

.form-input,
.form-select {
    width: 100%;
    padding: 10px 12px;
    /* border: 1px solid #D1D5DB; Light gray border */
    /* border-radius: 6px; */
    font-size: 14px;
    color: #111827;
    background-color: #fff;
    line-height: 1.5;
    /* border-left: 4px solid #9CA3AF; Prominent left border as in image */
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    border: none;
}

.form-input:focus,
.form-select:focus {
    border-color: #4F46E5; /* Indigo focus color */
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
}

.form-select {
    appearance: none; /* Remove default arrow */
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236B7280'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem; /* Make space for custom arrow */
}

.password-eye-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6B7280;
    cursor: pointer;
}

.password-rules {
    margin-top: 8px;
    font-size: 0.8rem; /* Smaller text for rules */
    color: #EF4444; /* Red color for errors/rules */
    display: grid; /* Use grid for two columns of rules if needed */
    grid-template-columns: repeat(2, 1fr); /* Two columns */
    gap: 4px 8px; /* Gap between rules */
}
.password-rules span {
    display: flex;
    align-items: center;
}
.rule-cross {
    margin-right: 4px;
    font-weight: bold;
}


.phone-input-group {
    display: flex;
    align-items: center;
}

.country-code-select {
    width: auto; /* Adjust width automatically */
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-right: none; /* Remove right border to merge with text input */
    padding-right: 0.5rem; /* Adjust padding for flag */
}

.phone-number-input {
    flex-grow: 1;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-left: 1px solid #D1D5DB; /* Restore left border as it was removed by general .form-input */
}
/* Specific override for phone number input's left border because of grouping */
.phone-input-group .form-input.phone-number-input {
    
}
.phone-input-group .form-input.country-code-select {
     /* border-left: 4px solid #9CA3AF; Ensure prominent left border on select too */
}


.terms-group {
    margin-top: 40px; /* Reduced top margin */
    margin-bottom: 40px; /* Space before buttons */
    flex-direction: row;
    align-items: center;
}

.form-checkbox {
    width: 16px;
    height: 16px;
    margin-right: 8px;
    border-radius: 4px;
    border: 1px solid #D1D5DB;
    accent-color: #000000; /* Indigo for checkbox */

}
.form-checkbox:checked {
    background-color: #000000; /* Indigo */
    border-color: #000000; /* Indigo */
    outline: none;
    box-shadow: none;
}
.form-checkbox[type="checkbox"]:focus,
.form-checkbox[type="checkbox"]:focus-visible {
    outline: none;
    border: none;
    box-shadow: none;
}

.terms-label {
    font-size: 14px;
    color: #374151;
    font-weight: normal; /* Normal weight for terms label */
}

.form-link {
    color: #4F46E5; /* Indigo */
    text-decoration: none;
}
.form-link:hover {
    text-decoration: underline;
}

.form-error {
    margin-top: 6px;
    font-size: 0.875em;
    color: #EF4444; /* Red for errors */
}

.form-actions {
    display: flex;
   gap: 20px; /* Space between buttons */
    align-items: center;
    margin-top: 10px;
}

.submit-button,
.login-button-link {
    padding: 12px 25px; /* Larger padding */
    font-size: 14px;
    font-weight: 500;
    border-radius: 6px;
    cursor: pointer;
    text-align: center;
    transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
}

.submit-button {
    background-color: #868e96; /* Muted gray from image */
    color: white;
    border: 1px solid #868e96; /* Matching border */
}

.submit-button:hover {
    background-color: #6c757d; /* Darker gray on hover */
    border-color: #6c757d;
}

.submit-button.opacity-25 {
    opacity: 0.5; /* More visible opacity for disabled */
    cursor: not-allowed;
}


.login-button-link {
    background-color: #FFFFFF; /* White background */
    color: #4B5563; /* Dark gray text */
    border: 1px solid #D1D5DB; /* Light gray border */
    text-decoration: none; /* Remove underline from Link */
        padding: 9px 35px;
}

.login-button-link:hover {
    background-color: #F9FAFB; /* Slightly off-white on hover */
    border-color: #9CA3AF; /* Slightly darker border on hover */
    color: #1F2937; /* Darker text on hover */
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .form-row {
        flex-direction: column; /* Stack columns on smaller screens */
        gap: 0; /* Remove gap when stacked, rely on form-group margin */
    }
    .form-group {
        margin-bottom: 20px; /* Add margin back for stacked items */
    }
    .form-row .form-group:last-child {
        margin-bottom: 0; /* No bottom margin for the last item in a stacked row */
    }
    .password-rules {
        grid-template-columns: 1fr; /* Single column for password rules on small screens */
    }
    .form-actions {
        /* Stack buttons */
        gap: 15px; /* Add gap between stacked buttons */
    }
    .submit-button,
    .login-button-link {
        width: 100%; /* Full width buttons when stacked */
    }
    .form-container {
        padding: 20px;
    }
}
.input_box_outline:focus {
    border: none; /* Indigo focus color */
    outline: 0;
    box-shadow: none;

}


</style>
