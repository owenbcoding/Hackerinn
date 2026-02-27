<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';
</script>

<template>
    <footer class="border-t border-border">
        <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
            <div class="flex flex-col items-center gap-10 sm:flex-row sm:items-center sm:justify-between lg:gap-0">
                <div class="flex shrink-0 flex-col items-center sm:items-start text-center sm:text-left">
                    <Link
                        :href="home().url"
                        class="flex items-center gap-2 font-bold text-foreground"
                    >
                        <span
                            class="flex h-8 w-8 items-center justify-center rounded-md bg-primary text-xl font-bold text-primary-foreground"
                        >
                            &gt;
                        </span>
                        HackerInn
                    </Link>
                    <p class="mt-3 text-sm text-muted-foreground">
                        Just builders, building.
                    </p>
                </div>

                <nav class="flex flex-wrap items-center justify-center gap-5 sm:justify-start sm:gap-8">
                    <a
                        href="#features"
                        class="text-sm text-muted-foreground transition-colors hover:text-foreground"
                    >
                        Features
                    </a>
                    <a
                        href="#how-it-works"
                        class="text-sm text-muted-foreground transition-colors hover:text-foreground"
                    >
                        How it works
                    </a>
                    <a
                        href="#builders"
                        class="text-sm text-muted-foreground transition-colors hover:text-foreground"
                    >
                        Builders
                    </a>
                </nav>

                <div class="flex shrink-0 gap-3">
                    <a
                        href="https://github.com"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex h-10 w-10 items-center justify-center rounded-md border border-border text-muted-foreground transition-colors hover:border-primary/30 hover:text-foreground"
                        aria-label="GitHub"
                    >
                        <svg
                            class="h-5 w-5"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </a>
                </div>
            </div>

            <div
                class="mt-12 flex flex-col gap-4 border-t border-border pt-8 sm:flex-row sm:items-center sm:justify-between"
            >
                <p class="font-mono text-sm text-muted-foreground">
                    © 2026 HackerInn. All rights reserved.
                </p>
                <p class="text-sm text-muted-foreground">
                    Built with
                    <span class="text-primary">&lt;intent&gt;</span>
                </p>
            </div>
        </div>
    </footer>
</template>
