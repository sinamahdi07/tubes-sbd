<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'category_id';
    protected $fillable = ['name'];

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_categories', 'category_id', 'game_id');
    }
}
