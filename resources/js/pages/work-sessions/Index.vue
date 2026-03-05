<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Trash2 } from 'lucide-vue-next';
import { ref, computed, watch, onUnmounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import { destroy, store, update } from '@/routes/work-sessions';

type ActiveSession = {
    id: number;
    started_at: string;
    planned_duration_minutes: number | null;
};

type Session = {
    id: number;
    started_at: string;
    ended_at: string | null;
    planned_duration_minutes: number | null;
    duration_seconds: number;
    note: string | null;
};

const props = defineProps<{
    activeSession: ActiveSession | null;
    sessions: Session[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Work Sessions', href: undefined },
];

const DURATION_PRESETS = [25, 50, 90] as const;
const selectedDuration = ref<number | null>(null);
const customDuration = ref<string>('');
const starting = ref(false);

const endDialogOpen = ref(false);
const endNote = ref('');
const endingSessionId = ref<number | null>(null);
const ending = ref(false);

const startedAt = computed(() =>
    props.activeSession ? new Date(props.activeSession.started_at).getTime() : 0,
);
const elapsedSeconds = ref(0);
let timerId: ReturnType<typeof setInterval> | null = null;

function formatDuration(totalSeconds: number): string {
    const h = Math.floor(totalSeconds / 3600);
    const m = Math.floor((totalSeconds % 3600) / 60);
    const s = totalSeconds % 60;
    if (h > 0) {
        return `${h}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
    }
    return `${m}:${s.toString().padStart(2, '0')}`;
}

const displayTime = computed(() => formatDuration(elapsedSeconds.value));

const remainingSeconds = computed(() => {
    if (!props.activeSession?.planned_duration_minutes) return null;
    const planned = props.activeSession.planned_duration_minutes * 60;
    const remaining = planned - elapsedSeconds.value;
    return Math.max(0, remaining);
});

const displayCountdown = computed(() =>
    remainingSeconds.value !== null ? formatDuration(remainingSeconds.value) : null,
);

function startSession() {
    const minutes =
        selectedDuration.value ??
        (customDuration.value ? parseInt(customDuration.value, 10) : null);
    if (minutes != null && (minutes < 1 || minutes > 480)) return;
    starting.value = true;
    router.post(store().url, { planned_duration_minutes: minutes ?? undefined }, {
        preserveScroll: true,
        onFinish: () => {
            starting.value = false;
        },
    });
}

function openEndDialog(sessionId: number) {
    endingSessionId.value = sessionId;
    endNote.value = '';
    endDialogOpen.value = true;
}

function submitEndSession() {
    const id = endingSessionId.value;
    if (id == null) return;
    ending.value = true;
    router.patch(update(id).url, { note: endNote.value || undefined }, {
        preserveScroll: true,
        onFinish: () => {
            ending.value = false;
            endDialogOpen.value = false;
            endingSessionId.value = null;
        },
    });
}

function removeSession(sessionId: number) {
    router.delete(destroy(sessionId).url, { preserveScroll: true });
}

function formatSessionDate(iso: string): string {
    const d = new Date(iso);
    const now = new Date();
    const isToday =
        d.getDate() === now.getDate() &&
        d.getMonth() === now.getMonth() &&
        d.getFullYear() === now.getFullYear();
    if (isToday) {
        return d.toLocaleTimeString(undefined, { hour: '2-digit', minute: '2-digit' });
    }
    return d.toLocaleDateString(undefined, {
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

function startTimer() {
    const tick = () => {
        elapsedSeconds.value = Math.floor(
            (Date.now() - startedAt.value) / 1000,
        );
    };
    tick();
    timerId = setInterval(tick, 1000);
}

function stopTimer() {
    if (timerId) {
        clearInterval(timerId);
        timerId = null;
    }
}

watch(
    () => props.activeSession,
    (active) => {
        stopTimer();
        if (active) startTimer();
    },
    { immediate: true },
);

onUnmounted(stopTimer);
</script>

<template>
    <Head title="Work Sessions" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4"
        >
            <!-- Active session: timer + end -->
            <div
                v-if="activeSession"
                class="rounded-xl border border-sidebar-border/70 bg-card p-8 dark:border-sidebar-border"
            >
                <div class="flex flex-col items-center gap-4">
                    <p class="text-sm font-medium text-muted-foreground">
                        Session in progress
                    </p>
                    <div class="text-5xl font-mono tabular-nums tracking-tight">
                        {{ displayTime }}
                    </div>
                    <p
                        v-if="displayCountdown !== null"
                        class="text-lg text-muted-foreground"
                    >
                        {{ displayCountdown }} left
                    </p>
                    <Button
                        variant="destructive"
                        size="lg"
                        @click="openEndDialog(activeSession.id)"
                    >
                        End session
                    </Button>
                </div>
            </div>

            <!-- No active session: start form -->
            <div
                v-else
                class="rounded-xl border border-sidebar-border/70 bg-card p-8 dark:border-sidebar-border"
            >
                <h2 class="text-lg font-semibold">Start a work session</h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    Time-box your deep work. Pick a duration or leave open-ended.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <Button
                        v-for="mins in DURATION_PRESETS"
                        :key="mins"
                        :variant="selectedDuration === mins ? 'default' : 'outline'"
                        @click="
                            selectedDuration = mins;
                            customDuration = '';
                        "
                    >
                        {{ mins }} min
                    </Button>
                    <div class="flex items-center gap-2">
                        <Input
                            v-model="customDuration"
                            type="number"
                            min="1"
                            max="480"
                            placeholder="Custom"
                            class="w-24"
                            @input="selectedDuration = null"
                        />
                        <span class="text-sm text-muted-foreground">min</span>
                    </div>
                </div>
                <Button
                    class="mt-4"
                    :disabled="starting"
                    @click="startSession"
                >
                    {{ starting ? 'Starting…' : 'Start session' }}
                </Button>
            </div>

            <!-- History -->
            <div>
                <h2 class="mb-3 text-lg font-semibold">Recent sessions</h2>
                <div
                    v-if="sessions.length === 0"
                    class="rounded-lg border border-dashed border-sidebar-border/70 py-8 text-center text-sm text-muted-foreground dark:border-sidebar-border"
                >
                    No sessions yet. Start one above.
                </div>
                <ul v-else class="space-y-2">
                    <li
                        v-for="session in sessions"
                        :key="session.id"
                        class="flex items-center justify-between gap-4 rounded-lg border border-sidebar-border/70 px-4 py-3 dark:border-sidebar-border"
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
                                class="mt-1 text-sm text-muted-foreground"
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
            </div>
        </div>

        <!-- End session dialog -->
        <Dialog v-model:open="endDialogOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>What did you ship?</DialogTitle>
                    <DialogDescription>
                        Optional note for this session. You can leave it blank.
                    </DialogDescription>
                </DialogHeader>
                <div class="grid gap-2 py-2">
                    <Label for="end-note">Note</Label>
                    <Input
                        id="end-note"
                        v-model="endNote"
                        type="text"
                        placeholder="e.g. Finished auth flow, fixed footer bug"
                        class="w-full"
                    />
                </div>
                <DialogFooter>
                    <Button
                        variant="outline"
                        :disabled="ending"
                        @click="endDialogOpen = false"
                    >
                        Cancel
                    </Button>
                    <Button :disabled="ending" @click="submitEndSession">
                        {{ ending ? 'Ending…' : 'End session' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
