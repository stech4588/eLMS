<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import FooterContainer from '@/Components/Footer-Container.vue';
</script>

<template>
  <GuestLayout>
    <Head title="Terms of Service" />
    <div class="prose-container">
      <div class="prose">
        <h1>Terms of Service</h1>
        <p>Last updated: [Date]</p>

        <h2>1. Agreement to Terms</h2>
        <p>By using our services, you agree to be bound by these Terms. If you do not agree to these Terms, do not use the services.</p>

        <h2>2. Privacy Policy</h2>
        <p>Our Privacy Policy explains how we collect, use, and share your personal information. You agree to our data practices, including the collection, use, and sharing of your information as described in our Privacy Policy.</p>

        <h2>3. Your Account</h2>
        <p>You are responsible for safeguarding your account, and you agree not to disclose your password to any third party. You must notify us immediately upon becoming aware of any breach of security or unauthorized use of your account.</p>

        <h2>4. Content</h2>
        <p>Our Service allows you to post, link, store, share and otherwise make available certain information, text, graphics, videos, or other material ("Content"). You are responsible for the Content that you post on or through the Service, including its legality, reliability, and appropriateness.</p>

        <h2>5. Prohibited Activities</h2>
        <p>You agree not to engage in any of the following prohibited activities: (i) copying, distributing, or disclosing any part of the service in any medium; (ii) using any automated system to access the service; (iii) transmitting spam, chain letters, or other unsolicited email.</p>

        <h2>6. Termination</h2>
        <p>We may terminate or suspend your account and bar access to the service immediately, without prior notice or liability, under our sole discretion, for any reason whatsoever and without limitation, including but not to a breach of the Terms.</p>

        <h2>7. Contact Us</h2>
        <p>If you have any questions about these Terms, please contact us at [Contact Email].</p>
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