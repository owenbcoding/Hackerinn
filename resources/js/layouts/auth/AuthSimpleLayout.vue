<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Terminal } from 'lucide-vue-next';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="hackerinn-landing min-h-svh bg-background">
        <div
            class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10"
        >
            <div class="w-full max-w-sm">
                <div class="flex flex-col gap-8">
                    <div class="flex flex-col items-center gap-4">
                        <Link
                            :href="home()"
                            class="flex flex-col items-center gap-3 font-medium text-foreground"
                        >
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-md bg-primary"
                            >
                                <Terminal class="size-5 text-primary-foreground" />
                            </div>
                            <span class="text-lg font-bold tracking-tight"
                                >HackerInn</span
                            >
                            <span class="sr-only">{{ title }}</span>
                        </Link>
                        <div class="space-y-2 text-center">
                            <h1 class="text-xl font-medium text-foreground">
                                {{ title }}
                            </h1>
                            <p
                                class="text-center text-sm text-muted-foreground"
                            >
                                {{ description }}
                            </p>
                        </div>
                    </div>
                    <div
                        class="rounded-xl border border-border bg-card px-6 py-6 shadow-xl [&_label]:text-card-foreground [&_input]:border-input [&_input]:bg-input [&_input]:text-foreground [&_input]:placeholder:text-muted-foreground [&_input]:focus-visible:ring-primary [&_button[type=submit]]:bg-primary [&_button[type=submit]]:text-primary-foreground"
                    >
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
