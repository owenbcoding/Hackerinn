<?php

/**
 * Copyright (c) 2026 Owencodes
 * Part of Hackerinn. See LICENSE file.
 */

use App\Http\Controllers\ChangelogEntryController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\WorkSessionController;
use App\Services\StreakService;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::post('newsletter', [NewsletterController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('newsletter.store');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    $user = auth()->user();
    $recentSessions = $user
        ? \App\Models\WorkSession::forUser($user)
            ->whereNotNull('ended_at')
            ->orderByDesc('ended_at')
            ->limit(5)
            ->get()
            ->map(fn ($s) => [
                'id' => $s->id,
                'started_at' => $s->started_at->toIso8601String(),
                'duration_seconds' => $s->duration_seconds,
                'note' => $s->note,
            ])
        : [];
    $todayCheckIn = $user
        ? \App\Models\CheckIn::forUser($user)->whereDate('date', now())->first()
        : null;
    $recentCheckIns = $user
        ? \App\Models\CheckIn::forUser($user)
            ->orderByDesc('date')
            ->limit(5)
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'date' => $c->date->toDateString(),
                'intention' => $c->intention,
            ])
        : [];
    $streakService = app(StreakService::class);
    $currentStreak = $user ? $streakService->currentStreak($user) : 0;

    return Inertia::render('Dashboard', [
        'recentSessions' => $recentSessions,
        'todayCheckIn' => $todayCheckIn ? [
            'id' => $todayCheckIn->id,
            'date' => $todayCheckIn->date->toDateString(),
            'intention' => $todayCheckIn->intention,
        ] : null,
        'recentCheckIns' => $recentCheckIns,
        'currentStreak' => $currentStreak,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('check-ins', [CheckInController::class, 'index'])->name('check-ins.index');
    Route::post('check-ins', [CheckInController::class, 'store'])->name('check-ins.store');
    Route::patch('check-ins/{checkIn}', [CheckInController::class, 'update'])->name('check-ins.update');

    Route::get('changelog', [ChangelogEntryController::class, 'index'])->name('changelog.index');
    Route::post('changelog', [ChangelogEntryController::class, 'store'])->name('changelog.store');
    Route::patch('changelog/{changelogEntry}', [ChangelogEntryController::class, 'update'])->name('changelog.update');
    Route::delete('changelog/{changelogEntry}', [ChangelogEntryController::class, 'destroy'])->name('changelog.destroy');

    Route::get('work-sessions', [WorkSessionController::class, 'index'])->name('work-sessions.index');
    Route::post('work-sessions', [WorkSessionController::class, 'store'])->name('work-sessions.store');
    Route::patch('work-sessions/{workSession}', [WorkSessionController::class, 'update'])->name('work-sessions.update');
    Route::delete('work-sessions/{workSession}', [WorkSessionController::class, 'destroy'])->name('work-sessions.destroy');
});

require __DIR__.'/settings.php';
