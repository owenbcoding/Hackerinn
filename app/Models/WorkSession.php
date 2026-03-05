<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkSession extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'started_at',
        'ended_at',
        'planned_duration_minutes',
        'note',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the work session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to only sessions belonging to the given user.
     */
    public function scopeForUser($query, User $user): void
    {
        $query->where('user_id', $user->id);
    }

    /**
     * Whether this session is still active (not ended).
     */
    public function isActive(): bool
    {
        return $this->ended_at === null;
    }

    /**
     * Duration in seconds (from started_at to ended_at or now if active).
     */
    public function getDurationSecondsAttribute(): int
    {
        $end = $this->ended_at ?? now();
        return (int) $this->started_at->diffInSeconds($end);
    }
}
