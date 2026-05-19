<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use HasFactory, SoftDeletes;

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

    // Relasi ke Category (Many-to-Many via game_categories)
    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'game_categories',
            'game_id',
            'category_id'
        );
    }

    // Relasi ke Platform (Many-to-Many via game_platforms)
    public function platforms()
    {
        return $this->belongsToMany(
            Platform::class,
            'game_platforms',
            'game_id',
            'platform_id'
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

    // Relasi ke Trailer (One-to-Many)
    public function trailers()
    {
        return $this->hasMany(
            GameTrailer::class,
            'game_id',
            'game_id'
        )->orderBy('order');
    }

    // Relasi ke Cart (One-to-Many)
    public function carts()
    {
        return $this->hasMany(Cart::class, 'game_id', 'game_id');
    }

    public function paymentItems()
    {
        return $this->hasMany(PaymentItem::class, 'game_id', 'game_id');
    }

    public function scopeWithPaidPurchasesCount($query)
    {
        return $query->withCount([
            'paymentItems as paid_purchases_count' => function ($paymentItems) {
                $paymentItems->whereHas('payment', function ($payment) {
                    $payment->where('status', Payment::STATUS_PAID);
                });
            },
        ]);
    }

    public function reviews()
    {
        return $this->hasMany(GameReview::class, 'game_id', 'game_id');
    }

    public function getRouteKeyName()
    {
        return 'game_id';
    }

    // Relasi ke GameDetail (One-to-One)
    public function detail()
    {
        return $this->hasOne(GameDetail::class, 'game_id', 'game_id');
    }

    public function getDiscountPercentAttribute(): int
    {
        return min(100, max(0, (int) ($this->detail?->discount ?? 0)));
    }

    public function getFinalPriceAttribute(): float
    {
        $price = (float) ($this->price ?? 0);

        return max(0, $price * (1 - ($this->discount_percent / 100)));
    }
}
