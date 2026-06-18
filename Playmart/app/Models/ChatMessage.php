<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'friendship_id',
        'message',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'sender_id' => 'integer',
            'receiver_id' => 'integer',
            'friendship_id' => 'integer',
            'read_at' => 'datetime',
        ];
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function scopeBetween(Builder $query, int $firstUserId, int $secondUserId): Builder
    {
        return $query->where(function (Builder $query) use ($firstUserId, $secondUserId) {
            $query->where(function (Builder $query) use ($firstUserId, $secondUserId) {
                $query->where('sender_id', $firstUserId)
                    ->where('receiver_id', $secondUserId);
            })->orWhere(function (Builder $query) use ($firstUserId, $secondUserId) {
                $query->where('sender_id', $secondUserId)
                    ->where('receiver_id', $firstUserId);
            });
        });
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where(function (Builder $query) use ($userId) {
            $query->where('sender_id', $userId)
                ->orWhere('receiver_id', $userId);
        });
    }

    public function otherUserId(int $userId): ?int
    {
        if ($this->sender_id === $userId) {
            return $this->receiver_id;
        }

        if ($this->receiver_id === $userId) {
            return $this->sender_id;
        }

        return null;
    }

    public function sentAtLabel(): string
    {
        if (! $this->created_at) {
            return '';
        }

        return $this->created_at
            ->copy()
            ->timezone('Asia/Jakarta')
            ->locale('id')
            ->translatedFormat('d M Y H.i').' WIB';
    }

    public function sentTimeLabel(): string
    {
        if (! $this->created_at) {
            return '';
        }

        return $this->created_at
            ->copy()
            ->timezone('Asia/Jakarta')
            ->format('H.i');
    }

    public function sentDateLabel(): string
    {
        if (! $this->created_at) {
            return '';
        }

        $sentAt = $this->created_at
            ->copy()
            ->timezone('Asia/Jakarta')
            ->locale('id');

        if ($sentAt->isToday()) {
            return 'Hari ini';
        }

        if ($sentAt->isYesterday()) {
            return 'Kemarin';
        }

        return $sentAt->translatedFormat('d M Y');
    }
}
