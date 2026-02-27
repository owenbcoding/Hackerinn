<script setup lang="ts">
import {
    Clock,
    Code,
    GitBranch,
    Target,
    Users,
    Zap,
} from 'lucide-vue-next';

const features = [
    {
        icon: Clock,
        label: 'FOCUS',
        title: 'Work Sessions',
        description:
            'Time-box your deep work. Start a session, hit flow state, and log it when you ship.',
    },
    {
        icon: Target,
        label: 'TRACK',
        title: 'Project Tracking',
        description:
            'Track what you are building. One place for projects, goals, and progress.',
    },
    {
        icon: Zap,
        label: 'STREAK',
        title: 'Streak System',
        description:
            'Show up daily. Build streaks. Stay accountable to yourself.',
    },
    {
        icon: GitBranch,
        label: 'LOG',
        title: 'Clean History',
        description:
            'Document what you built, learned, and shipped. Your personal changelog.',
    },
    {
        icon: Users,
        label: 'TEAM',
        title: 'Hackathon Mode',
        description:
            'Team check-ins without daily standups. Built for hackathon sprints.',
    },
    {
        icon: Code,
        label: 'MINIMAL',
        title: 'Zero Bloat',
        description:
            'No gamification. No endless features. Just clean tracking.',
    },
];
</script>

<template>
    <section
        id="features"
        class="px-6 py-24 lg:px-8 lg:py-32"
    >
        <div class="mx-auto max-w-7xl">
            <span
                class="rounded-sm bg-primary px-3 py-1 font-mono text-xs font-semibold text-primary-foreground tracking-wide"
            >
                FEATURES
            </span>
            <h2 class="mt-4 text-4xl font-bold sm:text-5xl">
                Built for builders
            </h2>
            <p class="mt-4 max-w-2xl text-muted-foreground">
                Everything you need to stay accountable. Nothing you don't.
            </p>

            <div class="mt-16 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="feature in features"
                    :key="feature.label"
                    :class="[
                        'rounded-lg border border-border bg-card p-6 transition-colors hover:border-primary/30 lg:p-8',
                        feature.label === 'MINIMAL' && 'lg:col-span-2',
                    ]"
                >
                    <div class="flex items-start justify-between">
                        <span
                            class="flex h-10 w-10 items-center justify-center rounded-lg border border-border bg-secondary text-primary"
                        >
                            <component
                                :is="feature.icon"
                                class="h-5 w-5"
                            />
                        </span>
                        <span
                            class="font-mono text-xs text-muted-foreground tracking-wider"
                        >
                            {{ feature.label }}
                        </span>
                    </div>
                    <h3 class="mt-6 text-lg font-bold text-foreground">
                        {{ feature.title }}
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted-foreground">
                        {{ feature.description }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
