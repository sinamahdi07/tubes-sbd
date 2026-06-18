<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Screenshot extends Model
{
    protected $table = 'screenshots';

    protected $primaryKey = 'screenshot_id';

    protected $fillable = [
        'game_id',
        'image_url',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    public function game()
    {
        return $this->belongsTo(
            Game::class,
            'game_id',
            'game_id'
        );
    }
}
