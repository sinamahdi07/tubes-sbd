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

    public function getEmbedUrlAttribute(): ?string
    {
        $url = trim((string) $this->url);

        if ($url === '') {
            return null;
        }

        $parts = parse_url($url);

        if ($parts === false || empty($parts['host'])) {
            return null;
        }

        $host = strtolower($parts['host']);
        $host = preg_replace('/^(www|m)\./', '', $host);
        $path = trim($parts['path'] ?? '', '/');
        $videoId = null;

        if ($host === 'youtu.be') {
            $videoId = explode('/', $path)[0] ?? null;
        }

        if ($videoId === null && ($host === 'youtube.com' || str_ends_with($host, '.youtube.com'))) {
            if ($path === 'watch') {
                parse_str($parts['query'] ?? '', $query);
                $videoId = $query['v'] ?? null;
            } elseif (str_starts_with($path, 'embed/')) {
                $videoId = explode('/', substr($path, 6))[0] ?? null;
            } elseif (str_starts_with($path, 'shorts/')) {
                $videoId = explode('/', substr($path, 7))[0] ?? null;
            }
        }

        if (! is_string($videoId) || ! preg_match('/^[A-Za-z0-9_-]{6,32}$/', $videoId)) {
            return null;
        }

        return 'https://www.youtube.com/embed/'.$videoId;
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }
}
