<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameTrailer extends Model
{
    use SoftDeletes;

    protected $table = 'game_trailers';
    protected $primaryKey = 'trailer_id';
    protected $fillable = ['game_id', 'title', 'url', 'order'];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }
}
