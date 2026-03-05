<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import { Trash2 } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { type BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import {
    destroy,
    index as changelogIndex,
    store,
} from '@/routes/changelog';

type Entry = {
    id: number;
    type: string;
    content: string;
    logged_at: string;
};

const props = withDefaults(
    defineProps<{
        entries: Entry[];
        filterType: string | null;
    }>(),
    { entries: () => [], filterType: null },
);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Changelog', href: undefined },
];

const TYPE_OPTIONS = [
    { value: '', label: 'All' },
    { value: 'shipped', label: 'Shipped' },
    { value: 'learned', label: 'Learned' },
    { value: 'next', label: 'Next' },
] as const;

const form = useForm<{ type: string; content: string }>({
    type: 'shipped',
    content: '',
});

function submitEntry() {
    form.post(store().url, {
        preserveScroll: true,
        onSuccess: () => form.reset('content'),
    });
}

function setFilter(type: string) {
    const url = type
        ? changelogIndex().url + '?type=' + encodeURIComponent(type)
        : changelogIndex().url;
    router.get(url, {}, { preserveState: true });
}

function removeEntry(id: number) {
    router.delete(destroy(id).url, { preserveScroll: true });
}

function formatDate(iso: string): string {
    const d = new Date(iso);
    return d.toLocaleDateString(undefined, {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

function typeLabel(type: string): string {
    const o = TYPE_OPTIONS.find((x) => x.value === type);
    return o?.label ?? type;
}
</script>

<template>
    <Head title="Changelog" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4"
        >
            <section
                class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
            >
                <h1 class="text-lg font-semibold">Changelog</h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Document what you built, learned, and what's next.
                </p>

                <form
                    class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-end"
                    @submit.prevent="submitEntry"
                >
                    <div class="space-y-2">
                        <Label for="type">Type</Label>
                        <select
                            id="type"
                            v-model="form.type"
                            name="type"
                            class="border-input bg-transparent focus-visible:border-ring focus-visible:ring-ring/50 h-9 w-full rounded-md border px-3 py-1 text-sm outline-none focus-visible:ring-[3px] sm:w-[140px]"
                        >
                            <option value="shipped">Shipped</option>
                            <option value="learned">Learned</option>
                            <option value="next">Next</option>
                        </select>
                    </div>
                    <div class="min-w-0 flex-1 space-y-2">
                        <Label for="content">Entry</Label>
                        <Textarea
                            id="content"
                            v-model="form.content"
                            placeholder="What did you ship, learn, or plan?"
                            class="min-h-[80px] resize-y"
                            :disabled="form.processing"
                        />
                    </div>
                    <Button
                        type="submit"
                        :disabled="form.processing || !form.content.trim()"
                    >
                        Add
                    </Button>
                </form>
            </section>

            <section>
                <div class="mb-3 flex flex-wrap items-center gap-2">
                    <span class="text-sm font-medium text-muted-foreground"
                        >Filter:</span
                    >
                    <Button
                        v-for="opt in TYPE_OPTIONS"
                        :key="opt.value"
                        :variant="
                            (filterType ?? '') === opt.value
                                ? 'secondary'
                                : 'ghost'
                        "
                        size="sm"
                        @click="setFilter(opt.value)"
                    >
                        {{ opt.label }}
                    </Button>
                </div>

                <ul
                    v-if="entries.length > 0"
                    class="space-y-2"
                >
                    <li
                        v-for="entry of entries"
                        :key="entry.id"
                        class="flex items-start justify-between gap-2 rounded-lg border border-sidebar-border/70 px-4 py-3 dark:border-sidebar-border"
                    >
                        <div class="min-w-0 flex-1">
                            <span
                                class="inline-block rounded bg-muted px-2 py-0.5 text-xs font-medium"
                            >
                                {{ typeLabel(entry.type) }}
                            </span>
                            <p class="mt-2 text-sm whitespace-pre-wrap">{{
                                entry.content
                            }}</p>
                            <p class="mt-1 text-xs text-muted-foreground">
                                {{ formatDate(entry.logged_at) }}
                            </p>
                        </div>
                        <Button
                            variant="ghost"
                            size="icon"
                            class="shrink-0 text-muted-foreground hover:text-destructive"
                            aria-label="Remove entry"
                            @click="removeEntry(entry.id)"
                        >
                            <Trash2 class="h-4 w-4" />
                        </Button>
                    </li>
                </ul>

                <p
                    v-else
                    class="rounded-lg border border-sidebar-border/70 px-4 py-8 text-center text-sm text-muted-foreground dark:border-sidebar-border"
                >
                    No entries yet. Add one above.
                </p>
            </section>
        </div>
    </AppLayout>
</template>
