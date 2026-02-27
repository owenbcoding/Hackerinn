<script setup lang="ts">
const testimonials = [
    {
        initials: 'SC',
        name: 'Sarah Chen',
        role: 'Indie Hacker',
        quote:
            'Finally, a productivity tool that does not try to gamify my life. Just clean tracking and real progress.',
    },
    {
        initials: 'MR',
        name: 'Marcus Rivera',
        role: 'Full-stack Dev',
        quote:
            "I have shipped more in 2 months with HackerInn than I did all last year. The streak system is addictive.",
    },
    {
        initials: 'AK',
        name: 'Alex Kim',
        role: 'Startup Founder',
        quote:
            'Used it for our hackathon sprint. The team check-ins kept everyone accountable without daily standups.',
    },
];
</script>

<template>
    <section
        id="builders"
        class="px-6 py-24 lg:px-8 lg:py-32"
    >
        <div class="mx-auto max-w-7xl">
            <div class="text-center">
                <span
                    class="rounded-sm bg-primary px-3 py-1 font-mono text-xs font-semibold text-primary-foreground tracking-wide"
                >
                    BUILDERS
                </span>
                <h2 class="mt-4 text-4xl font-bold sm:text-5xl">
                    From the community
                </h2>
            </div>

            <div class="mt-16 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="t in testimonials"
                    :key="t.initials"
                    class="rounded-lg border border-border bg-card p-6 lg:p-8"
                >
                    <p class="text-muted-foreground">{{ t.quote }}</p>
                    <div class="mt-6 flex items-center gap-3">
                        <span
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-primary font-mono text-xs font-bold text-primary-foreground"
                        >
                            {{ t.initials }}
                        </span>
                        <div>
                            <div class="font-medium text-foreground">
                                {{ t.name }}
                            </div>
                            <div class="text-sm text-muted-foreground">
                                {{ t.role }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
