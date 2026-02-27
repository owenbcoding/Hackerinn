<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ArrowRight, Menu, Terminal, X } from 'lucide-vue-next';
import { dashboard, home, register } from '@/routes';

defineProps<{
    auth?: { user?: unknown };
    canRegister: boolean;
}>();
const open = ref(false);
</script>

<template>
    <header
        class="sticky top-0 z-50 border-b border-border bg-background/95 backdrop-blur-md"
    >
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-6 py-5 sm:gap-8 lg:px-8">
            <Link
                :href="home().url"
                class="flex shrink-0 items-center gap-2 font-bold text-foreground"
            >
                <span
                    class="flex h-9 w-9 items-center justify-center rounded-md bg-primary text-primary-foreground"
                >
                    <Terminal class="h-5 w-5" />
                </span>
                <span>HackerInn</span>
            </Link>

            <nav class="hidden flex-1 items-center justify-center gap-10 lg:flex">
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

            <div class="hidden shrink-0 lg:flex lg:items-center">
                <Link
                    v-if="auth?.user"
                    :href="dashboard().url"
                    class="inline-flex items-center gap-2 rounded-md bg-primary px-5 py-2.5 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                >
                    Dashboard
                    <ArrowRight class="h-4 w-4" />
                </Link>
                <Link
                    v-else
                    :href="canRegister ? register().url : '#waitlist'"
                    class="inline-flex items-center gap-2 rounded-md bg-primary px-5 py-2.5 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                >
                    Join Waitlist
                    <ArrowRight class="h-4 w-4" />
                </Link>
            </div>

            <button
                type="button"
                class="flex h-9 w-9 items-center justify-center rounded-md lg:hidden"
                aria-label="Toggle menu"
                @click="open = !open"
            >
                <Menu v-if="!open" class="h-5 w-5" />
                <X v-else class="h-5 w-5" />
            </button>
        </div>

        <div
            v-show="open"
            class="border-t border-border px-6 py-5 lg:hidden"
        >
            <nav class="flex flex-col gap-4">
                <a
                    href="#features"
                    class="text-sm text-muted-foreground"
                    @click="open = false"
                >
                    Features
                </a>
                <a
                    href="#how-it-works"
                    class="text-sm text-muted-foreground"
                    @click="open = false"
                >
                    How it works
                </a>
                <a
                    href="#builders"
                    class="text-sm text-muted-foreground"
                    @click="open = false"
                >
                    Builders
                </a>
                <Link
                    v-if="auth?.user"
                    :href="dashboard().url"
                    class="inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground"
                    @click="open = false"
                >
                    Dashboard
                    <ArrowRight class="h-4 w-4" />
                </Link>
                <Link
                    v-else
                    :href="canRegister ? register().url : '#waitlist'"
                    class="inline-flex w-fit items-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground"
                    @click="open = false"
                >
                    Join Waitlist
                    <ArrowRight class="h-4 w-4" />
                </Link>
            </nav>
        </div>
    </header>
</template>
