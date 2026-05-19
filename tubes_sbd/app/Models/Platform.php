<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Platform extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'platform_id';
    protected $fillable = ['name', 'slug', 'icon'];

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_platforms', 'platform_id', 'game_id');
    }
}
