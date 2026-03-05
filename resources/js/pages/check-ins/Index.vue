<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';

type CheckInItem = {
    id: number;
    date: string;
    intention: string | null;
};

defineProps<{
    checkIns: CheckInItem[];
    from: string;
    to: string;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Check-ins', href: undefined },
];

function formatDate(dateStr: string): string {
    const d = new Date(dateStr);
    const today = new Date();
    if (d.toDateString() === today.toDateString()) return 'Today';
    const yesterday = new Date(today);
    yesterday.setDate(yesterday.getDate() - 1);
    if (d.toDateString() === yesterday.toDateString()) return 'Yesterday';
    return d.toLocaleDateString(undefined, {
        weekday: 'short',
        month: 'short',
        day: 'numeric',
    });
}
</script>

<template>
    <Head title="Check-ins" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4"
        >
            <section
                class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
            >
                <h1 class="text-lg font-semibold">Check-ins</h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Your daily intentions from {{ from }} to {{ to }}. Set
                    today's focus on the
                    <a
                        :href="dashboard().url"
                        class="text-primary underline underline-offset-4"
                    >
                        Dashboard</a
                    >.
                </p>
            </section>

            <section v-if="checkIns.length > 0">
                <ul class="space-y-2">
                    <li
                        v-for="checkIn of checkIns"
                        :key="checkIn.id"
                        class="rounded-lg border border-sidebar-border/70 px-4 py-3 dark:border-sidebar-border"
                    >
                        <p class="text-sm font-medium text-muted-foreground">
                            {{ formatDate(checkIn.date) }}
                        </p>
                        <p
                            v-if="checkIn.intention"
                            class="mt-1 text-sm"
                        >
                            {{ checkIn.intention }}
                        </p>
                        <p
                            v-else
                            class="mt-1 text-sm italic text-muted-foreground"
                        >
                            No intention set
                        </p>
                    </li>
                </ul>
            </section>

            <p
                v-else
                class="text-sm text-muted-foreground"
            >
                No check-ins in this range. Set your first intention on the
                Dashboard.
            </p>
        </div>
    </AppLayout>
</template>
