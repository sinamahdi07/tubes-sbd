<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentItem extends Model
{
    protected $fillable = [
        'payment_id',
        'game_id',
        'title',
        'unit_price',
        'discount_percent',
        'quantity',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'discount_percent' => 'integer',
        'quantity' => 'integer',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }

    public function getPriceAttribute($value): float
    {
        return (float) ($value ?? $this->attributes['unit_price'] ?? 0);
    }

    public function getLineSubtotalAttribute(): float
    {
        return $this->price * max(1, (int) ($this->quantity ?? 1));
    }

    public function getLineDiscountAttribute(): float
    {
        return $this->line_subtotal * (min(100, max(0, (int) $this->discount_percent)) / 100);
    }

    public function getLineTotalAttribute($value): float
    {
        if ($value !== null) {
            return (float) $value;
        }

        return max(0, $this->line_subtotal - $this->line_discount);
    }
}
