<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StreakService
{
    /**
     * Get the set of dates (Y-m-d) when the user was "active":
     * either had a check-in or completed at least one work session.
     *
     * @return array<string>
     */
    public function activeDaysForUser(User $user): array
    {
        $checkInDates = $user->checkIns()->get()->map(fn ($c) => $c->date->toDateString())->toArray();

        $sessionDates = $user->workSessions()
            ->whereNotNull('ended_at')
            ->get()
            ->map(fn ($s) => $s->ended_at->toDateString())
            ->unique()
            ->values()
            ->toArray();

        $all = array_unique(array_merge($checkInDates, $sessionDates));

        return array_values($all);
    }

    /**
     * Current streak: number of consecutive days ending today where each day
     * has at least one check-in or one completed work session.
     * If today is not active, returns 0.
     */
    public function currentStreak(User $user): int
    {
        $activeSet = array_fill_keys($this->activeDaysForUser($user), true);
        $today = Carbon::today()->toDateString();

        if (! isset($activeSet[$today])) {
            return 0;
        }

        $count = 0;
        $date = Carbon::today();

        while (isset($activeSet[$date->toDateString()])) {
            $count++;
            $date->subDay();
        }

        return $count;
    }
}
