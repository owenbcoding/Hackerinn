<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';
import { Clock, Flame, Trash2 } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { type BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import { index as checkInsIndex, store as checkInsStore } from '@/routes/check-ins';
import { destroy, index as workSessionsIndex } from '@/routes/work-sessions';

type CheckInItem = {
    id: number;
    date: string;
    intention: string | null;
};

const props = withDefaults(
    defineProps<{
        recentSessions?: Array<{
            id: number;
            started_at: string;
            duration_seconds: number;
            note: string | null;
        }>;
        todayCheckIn?: CheckInItem | null;
        recentCheckIns?: CheckInItem[];
        currentStreak?: number;
    }>(),
    {
        recentSessions: () => [],
        todayCheckIn: null,
        recentCheckIns: () => [],
        currentStreak: 0,
    },
);

const checkInForm = useForm<{ intention: string }>({ intention: '' });

watch(
    () => props.todayCheckIn,
    (c) => {
        if (c) checkInForm.intention = c.intention ?? '';
    },
    { immediate: true },
);

function submitCheckIn() {
    checkInForm.post(checkInsStore().url, { preserveScroll: true });
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

function formatDuration(totalSeconds: number): string {
    const m = Math.floor(totalSeconds / 60);
    const s = totalSeconds % 60;
    return `${m}:${s.toString().padStart(2, '0')}`;
}

function formatSessionDate(iso: string): string {
    const d = new Date(iso);
    return d.toLocaleDateString(undefined, {
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

function formatCheckInDate(dateStr: string): string {
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

function removeSession(sessionId: number) {
    router.delete(destroy(sessionId).url, { preserveScroll: true });
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4"
        >
            <section
                v-if="currentStreak > 0"
                class="flex items-center gap-2 rounded-xl border border-sidebar-border/70 bg-card px-4 py-3 dark:border-sidebar-border"
            >
                <Flame class="h-5 w-5 text-amber-500" />
                <span class="text-sm font-medium">
                    {{ currentStreak }} day{{ currentStreak === 1 ? '' : 's' }} streak
                </span>
            </section>

            <section
                class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
            >
                <h2 class="text-lg font-semibold">Today's focus</h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    What are you working on today? Set your intention, then start
                    a work session.
                </p>
                <form
                    class="mt-4 space-y-3"
                    @submit.prevent="submitCheckIn"
                >
                    <Label for="intention" class="sr-only">Today's intention</Label>
                    <Textarea
                        id="intention"
                        v-model="checkInForm.intention"
                        :placeholder="
                            todayCheckIn?.intention
                                ? 'Update your intention...'
                                : 'e.g. Ship the login flow, fix dashboard bug...'
                        "
                        class="min-h-[80px] resize-y"
                        :disabled="checkInForm.processing"
                    />
                    <div class="flex flex-wrap items-center gap-2">
                        <Button
                            type="submit"
                            size="sm"
                            :disabled="checkInForm.processing"
                        >
                            {{ todayCheckIn ? 'Update intention' : 'Save intention' }}
                        </Button>
                        <Button as-child size="sm" variant="outline">
                            <Link :href="workSessionsIndex()">
                                <Clock class="mr-2 h-4 w-4" />
                                Start work session
                            </Link>
                        </Button>
                    </div>
                </form>
            </section>

            <section v-if="recentCheckIns.length > 0">
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Recent check-ins</h2>
                    <Button as-child variant="ghost" size="sm">
                        <Link :href="checkInsIndex()">View all</Link>
                    </Button>
                </div>
                <ul class="space-y-2">
                    <li
                        v-for="c of recentCheckIns"
                        :key="c.id"
                        class="rounded-lg border border-sidebar-border/70 px-4 py-3 dark:border-sidebar-border"
                    >
                        <p class="text-xs text-muted-foreground">
                            {{ formatCheckInDate(c.date) }}
                        </p>
                        <p
                            v-if="c.intention"
                            class="mt-1 truncate text-sm"
                        >
                            {{ c.intention }}
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

            <section v-if="recentSessions.length > 0">
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Recent sessions</h2>
                    <Button as-child variant="ghost" size="sm">
                        <Link :href="workSessionsIndex()">View all</Link>
                    </Button>
                </div>
                <ul class="space-y-2">
                    <li
                        v-for="session in recentSessions"
                        :key="session.id"
                        class="flex items-start justify-between gap-2 rounded-lg border border-sidebar-border/70 px-4 py-3 dark:border-sidebar-border"
                    >
                        <div class="min-w-0 flex-1">
                            <p class="font-mono text-sm tabular-nums">
                                {{ formatDuration(session.duration_seconds) }}
                            </p>
                            <p class="text-xs text-muted-foreground">
                                {{ formatSessionDate(session.started_at) }}
                            </p>
                            <p
                                v-if="session.note"
                                class="mt-1 truncate text-sm text-muted-foreground"
                            >
                                {{ session.note }}
                            </p>
                        </div>
                        <Button
                            variant="ghost"
                            size="icon"
                            class="shrink-0 text-muted-foreground hover:text-destructive"
                            aria-label="Remove session"
                            @click="removeSession(session.id)"
                        >
                            <Trash2 class="h-4 w-4" />
                        </Button>
                    </li>
                </ul>
            </section>
        </div>
    </AppLayout>
</template>
