<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameScreenshot extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'game_screenshots';

    /*
    |--------------------------------------------------------------------------
    | Primary Key
    |--------------------------------------------------------------------------
    */

    protected $primaryKey = 'screenshot_id';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'game_id',
        'url',
        'order',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Screenshot milik satu game
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }
}
