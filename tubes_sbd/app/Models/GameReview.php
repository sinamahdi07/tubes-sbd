<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameReview extends Model
{
    protected $fillable = [
        'game_id',
        'user_id',
        'is_recommended',
        'body',
    ];

    protected $casts = [
        'is_recommended' => 'boolean',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
