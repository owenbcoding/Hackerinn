<?php

namespace App\Http\Controllers;

use App\Models\ChangelogEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChangelogEntryController extends Controller
{
    /**
     * List changelog entries with optional type filter.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();
        $type = $request->input('type');

        $query = ChangelogEntry::forUser($user)->orderByDesc('logged_at');

        if ($type && in_array($type, ChangelogEntry::types(), true)) {
            $query->where('type', $type);
        }

        $entries = $query->limit(100)->get()->map(fn (ChangelogEntry $e) => [
            'id' => $e->id,
            'type' => $e->type,
            'content' => $e->content,
            'logged_at' => $e->logged_at->toIso8601String(),
        ]);

        return Inertia::render('changelog/Index', [
            'entries' => $entries,
            'filterType' => $type ?? null,
        ]);
    }

    /**
     * Store a new changelog entry.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'in:shipped,learned,next'],
            'content' => ['required', 'string', 'max:5000'],
        ]);

        $request->user()->changelogEntries()->create([
            'type' => $validated['type'],
            'content' => $validated['content'],
            'logged_at' => now(),
        ]);

        return redirect()->back();
    }

    /**
     * Update a changelog entry.
     */
    public function update(Request $request, ChangelogEntry $changelogEntry): RedirectResponse
    {
        if ($changelogEntry->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'type' => ['sometimes', 'string', 'in:shipped,learned,next'],
            'content' => ['sometimes', 'string', 'max:5000'],
        ]);

        $changelogEntry->update($validated);

        return redirect()->back();
    }

    /**
     * Delete a changelog entry.
     */
    public function destroy(Request $request, ChangelogEntry $changelogEntry): RedirectResponse
    {
        if ($changelogEntry->user_id !== $request->user()->id) {
            abort(403);
        }

        $changelogEntry->delete();

        return redirect()->back();
    }
}
