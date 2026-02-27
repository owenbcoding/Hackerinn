<script setup lang="ts">
import { ref } from 'vue';
import { CheckCircle2 } from 'lucide-vue-next';

const email = ref('');
const submitted = ref(false);

function handleSubmit(e: Event) {
    e.preventDefault();
    if (email.value.trim()) {
        submitted.value = true;
    }
}
</script>

<template>
    <section
        id="waitlist"
        class="relative px-6 py-24 lg:px-8 lg:py-32"
    >
        <div
            class="absolute left-1/2 top-1/2 h-[400px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary/5 blur-3xl"
        />

        <div class="relative mx-auto max-w-7xl text-center">
            <span
                class="rounded-sm bg-primary px-3 py-1 font-mono text-xs font-semibold text-primary-foreground tracking-wide"
            >
                EARLY_ACCESS
            </span>
            <h2 class="mt-4 text-4xl font-bold sm:text-5xl">
                Ready to build?
            </h2>
            <p class="mx-auto mt-4 max-w-xl text-muted-foreground">
                Join the waitlist and be first to access HackerInn when we launch.
                No spam. Just one email when it is time.
            </p>

            <div v-if="submitted" class="mt-8 flex flex-col items-center gap-4">
                <div
                    class="flex items-center gap-2 rounded-lg border border-primary/30 bg-primary/5 px-6 py-4"
                >
                    <CheckCircle2 class="h-6 w-6 text-primary" />
                    <span class="font-medium text-foreground">
                        You are on the list. We will be in touch.
                    </span>
                </div>
            </div>

            <form
                v-else
                class="mx-auto mt-8 flex max-w-md flex-col gap-4 sm:flex-row"
                @submit="handleSubmit"
            >
                <input
                    v-model="email"
                    type="email"
                    required
                    placeholder="you@builder.dev"
                    class="flex-1 rounded-md border border-border bg-input px-4 py-3 text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                />
                <button
                    type="submit"
                    class="rounded-md bg-primary px-6 py-3 font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                >
                    Join Waitlist
                </button>
            </form>

            <p class="mt-6 flex items-center justify-center gap-2 text-sm text-muted-foreground">
                <CheckCircle2 class="h-4 w-4 text-primary" />
                2,400+ builders already waiting
            </p>
        </div>
    </section>
</template>
