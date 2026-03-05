<?php

namespace App\Http\Controllers;

use App\Models\WorkSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkSessionController extends Controller
{
    /**
     * List work sessions and show active session if any.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();
        $activeSession = WorkSession::forUser($user)->whereNull('ended_at')->latest('started_at')->first();
        $sessions = WorkSession::forUser($user)
            ->whereNotNull('ended_at')
            ->orderByDesc('ended_at')
            ->limit(50)
            ->get();

        return Inertia::render('work-sessions/Index', [
            'activeSession' => $activeSession ? [
                'id' => $activeSession->id,
                'started_at' => $activeSession->started_at->toIso8601String(),
                'planned_duration_minutes' => $activeSession->planned_duration_minutes,
            ] : null,
            'sessions' => $sessions->map(fn (WorkSession $s) => [
                'id' => $s->id,
                'started_at' => $s->started_at->toIso8601String(),
                'ended_at' => $s->ended_at?->toIso8601String(),
                'planned_duration_minutes' => $s->planned_duration_minutes,
                'duration_seconds' => $s->duration_seconds,
                'note' => $s->note,
            ]),
        ]);
    }

    /**
     * Start a new work session.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $active = WorkSession::forUser($user)->whereNull('ended_at')->exists();
        if ($active) {
            return back()->withErrors(['session' => 'You already have an active session.']);
        }

        $validated = $request->validate([
            'planned_duration_minutes' => ['nullable', 'integer', 'min:1', 'max:480'],
        ]);

        WorkSession::create([
            'user_id' => $user->id,
            'started_at' => now(),
            'planned_duration_minutes' => $validated['planned_duration_minutes'] ?? null,
        ]);

        return redirect()->route('work-sessions.index');
    }

    /**
     * End a work session and optionally set note.
     */
    public function update(Request $request, WorkSession $workSession): RedirectResponse
    {
        if ($workSession->user_id !== $request->user()->id) {
            abort(403);
        }
        if ($workSession->ended_at !== null) {
            return back()->withErrors(['session' => 'This session is already ended.']);
        }

        $validated = $request->validate([
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        $workSession->update([
            'ended_at' => now(),
            'note' => $validated['note'] ?? $workSession->note,
        ]);

        return redirect()->route('work-sessions.index');
    }

    /**
     * Delete a work session.
     */
    public function destroy(Request $request, WorkSession $workSession): RedirectResponse
    {
        if ($workSession->user_id !== $request->user()->id) {
            abort(403);
        }

        $workSession->delete();

        return redirect()->back();
    }
}
