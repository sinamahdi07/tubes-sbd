<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'games';

    /*
    |--------------------------------------------------------------------------
    | Primary Key
    |--------------------------------------------------------------------------
    */

    protected $primaryKey = 'game_id';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'title',
        'description',
        'price',
        'release_date',
        'thumbnail_url',
        'developer_id',
        'publisher_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'price'        => 'decimal:2',
        'release_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Relasi ke Developer (Many-to-One)
    public function developer()
    {
        return $this->belongsTo(
            Developer::class,
            'developer_id',
            'developer_id'
        );
    }

    // Relasi ke Publisher (Many-to-One)
    public function publisher()
    {
        return $this->belongsTo(
            Publisher::class,
            'publisher_id',
            'publisher_id'
        );
    }

    // Relasi ke Genre (Many-to-Many via game_genres)
    public function genres()
    {
        return $this->belongsToMany(
            Genre::class,
            'game_genres',  // pivot table
            'game_id',      // FK di pivot pointing to Game
            'genre_id'      // FK di pivot pointing to Genre
        );
    }

    // Relasi ke Screenshot (One-to-Many)
    public function screenshots()
    {
        return $this->hasMany(
            GameScreenshot::class,
            'game_id',
            'game_id'
        )->orderBy('order');
    }
}