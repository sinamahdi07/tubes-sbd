<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameDetail extends Model
{
    use HasFactory;

    protected $table = 'game_details';

    protected $primaryKey = 'game_detail_id';

    protected $fillable = [
        'game_id',
        'appid',
        'discount',
        'short_description',
        'header_image',
        'website',
        'minimum_requirements',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }
}
