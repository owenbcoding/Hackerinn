<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight, ChevronRight } from 'lucide-vue-next';
import { dashboard, register } from '@/routes';

defineProps<{
    auth?: { user?: unknown };
    canRegister: boolean;
}>();
</script>

<template>
    <section class="relative px-6 py-24 lg:px-8 lg:py-32">
        <div
            class="absolute left-1/2 top-0 h-[500px] w-[800px] -translate-x-1/2 rounded-full bg-primary/5 blur-3xl"
        />

        <div class="relative mx-auto max-w-7xl">
            <div class="mb-8 flex items-center gap-3">
                <span
                    class="rounded-sm bg-primary px-3 py-1 font-mono text-xs font-semibold text-primary-foreground tracking-wide"
                >
                    BUILD_MODE
                </span>
                <span class="font-mono text-sm text-muted-foreground">v1.0.0</span>
            </div>

            <h1
                class="text-5xl font-bold leading-tight tracking-tight sm:text-6xl lg:text-7xl"
            >
                Your home base<br />
                <span class="text-primary">for building.</span>
            </h1>

            <p class="mt-6 max-w-xl text-lg text-muted-foreground">
                Check in. Work with intent. Track real progress. No endless feeds.
                No fake productivity. Just builders, building.
            </p>

            <div class="mt-10 flex flex-col gap-4 sm:flex-row">
                <Link
                    v-if="auth?.user"
                    :href="dashboard().url"
                    class="inline-flex items-center justify-center gap-2 rounded-md bg-primary px-6 py-3.5 text-base font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                >
                    Go to Dashboard
                    <ArrowRight class="h-5 w-5" />
                </Link>
                <Link
                    v-else
                    :href="canRegister ? register().url : '#waitlist'"
                    class="inline-flex items-center justify-center gap-2 rounded-md bg-primary px-6 py-3.5 text-base font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                >
                    Join the Waitlist
                    <ArrowRight class="h-5 w-5" />
                </Link>
                <a
                    href="#features"
                    class="inline-flex items-center justify-center gap-2 rounded-md border border-border bg-secondary px-6 py-3.5 text-base font-medium text-secondary-foreground transition-colors hover:bg-secondary/80"
                >
                    See Features
                    <ChevronRight class="h-5 w-5" />
                </a>
            </div>

            <div
                class="mt-16 flex flex-col gap-8 border-t border-border pt-8 sm:flex-row sm:gap-12 lg:gap-16"
            >
                <div>
                    <div
                        class="font-mono text-3xl font-bold text-primary sm:text-4xl"
                    >
                        2.4k+
                    </div>
                    <div
                        class="mt-1 font-mono text-xs uppercase tracking-widest text-muted-foreground"
                    >
                        Builders waiting
                    </div>
                </div>
                <div>
                    <div
                        class="font-mono text-3xl font-bold text-primary sm:text-4xl"
                    >
                        89%
                    </div>
                    <div
                        class="mt-1 font-mono text-xs uppercase tracking-widest text-muted-foreground"
                    >
                        Ship rate
                    </div>
                </div>
                <div>
                    <div
                        class="font-mono text-3xl font-bold text-foreground sm:text-4xl"
                    >
                        0
                    </div>
                    <div
                        class="mt-1 font-mono text-xs uppercase tracking-widest text-muted-foreground"
                    >
                        Distractions
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
