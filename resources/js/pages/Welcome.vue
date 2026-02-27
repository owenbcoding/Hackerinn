<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import LandingNavbar from '@/components/landing/Navbar.vue';
import LandingHero from '@/components/landing/Hero.vue';
import LandingFeatures from '@/components/landing/Features.vue';
import LandingProcess from '@/components/landing/Process.vue';
import LandingTestimonials from '@/components/landing/Testimonials.vue';
import LandingCTA from '@/components/landing/CTA.vue';
import LandingFooter from '@/components/landing/Footer.vue';

withDefaults(
    defineProps<{
        canRegister?: boolean;
    }>(),
    { canRegister: true },
);

const page = usePage();
const auth = page.props.auth as { user?: unknown } | undefined;
</script>

<template>
    <Head title="HackerInn - Your home base for building">
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous" />
        <link
            href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&family=Geist+Mono:wght@400;500;600&family=Space+Grotesk:wght@400;500;600;700&display=swap"
            rel="stylesheet"
        />
    </Head>
    <div class="hackerinn-landing flex min-h-screen flex-col">
        <LandingNavbar :auth="auth" :can-register="canRegister" />
        <main class="flex-1">
            <LandingHero :auth="auth" :can-register="canRegister" />
            <LandingFeatures />
            <LandingProcess />
            <LandingTestimonials />
            <LandingCTA />
        </main>
        <LandingFooter />
    </div>
</template>
