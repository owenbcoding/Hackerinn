<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChangelogEntry extends Model
{
    public const TYPE_SHIPPED = 'shipped';

    public const TYPE_LEARNED = 'learned';

    public const TYPE_NEXT = 'next';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'content',
        'logged_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'logged_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to only entries belonging to the given user.
     */
    public function scopeForUser($query, User $user): void
    {
        $query->where('user_id', $user->id);
    }

    /**
     * All valid entry types.
     *
     * @return array<string>
     */
    public static function types(): array
    {
        return [self::TYPE_SHIPPED, self::TYPE_LEARNED, self::TYPE_NEXT];
    }
}
