<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Friendship extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_ACCEPTED = 'accepted';

    protected $fillable = [
        'requester_id',
        'addressee_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'requester_id' => 'integer',
            'addressee_id' => 'integer',
        ];
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function addressee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'addressee_id');
    }

    public function scopeBetween(Builder $query, int $firstUserId, int $secondUserId): Builder
    {
        return $query->where(function (Builder $query) use ($firstUserId, $secondUserId) {
            $query->where(function (Builder $query) use ($firstUserId, $secondUserId) {
                $query->where('requester_id', $firstUserId)
                    ->where('addressee_id', $secondUserId);
            })->orWhere(function (Builder $query) use ($firstUserId, $secondUserId) {
                $query->where('requester_id', $secondUserId)
                    ->where('addressee_id', $firstUserId);
            });
        });
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where(function (Builder $query) use ($userId) {
            $query->where('requester_id', $userId)
                ->orWhere('addressee_id', $userId);
        });
    }

    public function otherUser(User $user): ?User
    {
        if ($this->requester_id === $user->id) {
            return $this->addressee;
        }

        if ($this->addressee_id === $user->id) {
            return $this->requester;
        }

        return null;
    }
}
