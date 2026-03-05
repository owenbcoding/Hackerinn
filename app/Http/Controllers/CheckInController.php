<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CheckInController extends Controller
{
    /**
     * List check-ins for a date range (default: last 14 days including today).
     */
    public function index(Request $request): Response
    {
        $user = $request->user();
        $from = $request->input('from', now()->subDays(13)->toDateString());
        $to = $request->input('to', now()->toDateString());

        $checkIns = CheckIn::forUser($user)
            ->whereBetween('date', [$from, $to])
            ->orderByDesc('date')
            ->get()
            ->map(fn (CheckIn $c) => [
                'id' => $c->id,
                'date' => $c->date->toDateString(),
                'intention' => $c->intention,
            ]);

        return Inertia::render('check-ins/Index', [
            'checkIns' => $checkIns,
            'from' => $from,
            'to' => $to,
        ]);
    }

    /**
     * Create or update today's check-in (upsert by date).
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $today = Carbon::today();

        $validated = $request->validate([
            'intention' => ['nullable', 'string', 'max:2000'],
        ]);

        CheckIn::updateOrCreate(
            [
                'user_id' => $user->id,
                'date' => $today,
            ],
            ['intention' => $validated['intention'] ?? '']
        );

        return redirect()->back();
    }

    /**
     * Update a check-in (same-day only for simplicity).
     */
    public function update(Request $request, CheckIn $checkIn): RedirectResponse
    {
        if ($checkIn->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'intention' => ['nullable', 'string', 'max:2000'],
        ]);

        $checkIn->update(['intention' => $validated['intention'] ?? $checkIn->intention]);

        return redirect()->back();
    }
}
