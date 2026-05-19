<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'genres';

    /*
    |--------------------------------------------------------------------------
    | Primary Key
    |--------------------------------------------------------------------------
    */

    protected $primaryKey = 'genre_id';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Genre bisa dimiliki banyak game (many-to-many via game_genres)
    public function games()
    {
        return $this->belongsToMany(
            Game::class,
            'game_genres',    // pivot table
            'genre_id',       // FK di pivot pointing to Genre
            'game_id'         // FK di pivot pointing to Game
        );
    }
}
