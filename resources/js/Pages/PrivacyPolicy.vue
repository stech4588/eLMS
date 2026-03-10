<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import FooterContainer from '@/Components/Footer-Container.vue';
</script>

<template>
  <GuestLayout>
    <Head title="Privacy Policy" />
    <div class="prose-container">
      <div class="prose">
        <h1>Privacy Policy</h1>
        <p>Last updated: [Date]</p>

        <h2>1. Introduction</h2>
        <p>Welcome to LMS.360.pk ("we", "our", "us"). We are committed to protecting your personal information and your right to privacy. If you have any questions or concerns about our policy, or our practices with regards to your personal information, please contact us.</p>

        <h2>2. Information We Collect</h2>
        <p>We collect personal information that you voluntarily provide to us when you register on the website, express an interest in obtaining information about us or our products and services, when you participate in activities on the website or otherwise when you contact us.</p>
        <p>The personal information that we collect depends on the context of your interactions with us and the website, the choices you make and the products and features you use. The personal information we collect may include the following: Name, Email Address, Phone Number, etc.</p>

        <h2>3. How We Use Your Information</h2>
        <p>We use personal information collected via our website for a variety of business purposes described below. We process your personal information for these purposes in reliance on our legitimate business interests, in order to enter into or perform a contract with you, with your consent, and/or for compliance with our legal obligations.</p>

        <h2>4. Will Your Information Be Shared With Anyone?</h2>
        <p>We only share information with your consent, to comply with laws, to provide you with services, to protect your rights, or to fulfill business obligations.</p>

        <h2>5. How Long Do We Keep Your Information?</h2>
        <p>We keep your information for as long as necessary to fulfill the purposes outlined in this privacy policy unless otherwise required by law.</p>

        <h2>6. How Do We Keep Your Information Safe?</h2>
        <p>We aim to protect your personal information through a system of organizational and technical security measures.</p>

        <h2>7. Contact Us</h2>
        <p>If you have questions or comments about this policy, you may email us at [Contact Email].</p>
      </div>
    </div>
    <FooterContainer />
  </GuestLayout>
</template>

<style scoped>
.prose-container {
    display: flex;
    justify-content: center;
    padding: 2rem;
    font-family: 'Inter', sans-serif;
    background-color: #f9fafb;
}
.prose {
    max-width: 800px;
    width: 100%;
    background: #fff;
    padding: 2rem 3rem;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    color: #374151;
    line-height: 1.7;
}
.prose h1 {
    font-size: 2.25rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: #111827;
}
.prose h2 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #111827;
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 0.5rem;
}
.prose p {
    margin-bottom: 1.25rem;
}
</style> 