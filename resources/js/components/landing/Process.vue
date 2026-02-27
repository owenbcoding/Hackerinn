<script setup lang="ts">
const steps = [
    {
        number: '01',
        title: 'Check in daily',
        description:
            "Start your day with a quick check-in. Set your intention for what you're building today.",
    },
    {
        number: '02',
        title: 'Start a work session',
        description:
            'Hit the button, enter flow state. Your session is logged when you ship something real.',
    },
    {
        number: '03',
        title: 'Log your progress',
        description:
            "Document what you built, what you learned, and what's next. Build your personal changelog.",
    },
    {
        number: '04',
        title: 'Stack your streak',
        description:
            'Show up consistently. Watch your streak grow. Stay accountable to yourself.',
    },
];
</script>

<template>
    <section
        id="how-it-works"
        class="px-6 py-24 lg:px-8 lg:py-32"
    >
        <div class="mx-auto max-w-7xl">
            <div class="grid gap-16 lg:grid-cols-2">
                <div>
                    <span
                        class="rounded-sm bg-primary px-3 py-1 font-mono text-xs font-semibold text-primary-foreground tracking-wide"
                    >
                        PROCESS
                    </span>
                    <h2 class="mt-4 text-4xl font-bold sm:text-5xl">
                        Simple by design
                    </h2>
                    <p class="mt-4 max-w-xl text-muted-foreground">
                        No complex setup. No onboarding hell. Just open, check in,
                        and start building.
                    </p>
                    <img
                        src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800&q=80"
                        alt="Developer at desk with monitors"
                        class="mt-8 w-full rounded-lg grayscale"
                        width="800"
                        height="500"
                        loading="lazy"
                    />
                </div>

                <div class="flex flex-col gap-0 lg:pt-28">
                    <div
                        v-for="(step, i) in steps"
                        :key="step.number"
                        class="flex gap-6"
                    >
                        <div class="flex flex-col items-center">
                            <span
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-primary font-mono text-xs font-bold text-primary-foreground"
                            >
                                {{ step.number }}
                            </span>
                            <div
                                v-if="i < steps.length - 1"
                                class="mt-2 h-full w-px flex-1 bg-border"
                            />
                        </div>
                        <div class="pb-12">
                            <h3 class="text-lg font-bold text-foreground">
                                {{ step.title }}
                            </h3>
                            <p class="mt-2 text-sm leading-relaxed text-muted-foreground">
                                {{ step.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
