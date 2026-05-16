<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'category_id';
    protected $fillable = ['name'];

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_categories', 'category_id', 'game_id');
    }
}
