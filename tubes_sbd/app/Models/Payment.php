<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public const STATUS_PAID = 'paid';
    public const STATUS_PENDING = 'pending';

    protected $fillable = [
        'user_id',
        'payment_code',
        'method',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PaymentItem::class);
    }

    public function getDisplaySubtotalAttribute(): float
    {
        if (array_key_exists('subtotal', $this->attributes) && $this->attributes['subtotal'] !== null) {
            return (float) $this->attributes['subtotal'];
        }

        if ($this->relationLoaded('items')) {
            return (float) $this->items->sum('line_subtotal');
        }

        return 0.0;
    }

    public function getDisplayDiscountTotalAttribute(): float
    {
        if (array_key_exists('discount_total', $this->attributes) && $this->attributes['discount_total'] !== null) {
            return (float) $this->attributes['discount_total'];
        }

        if ($this->relationLoaded('items')) {
            return (float) $this->items->sum('line_discount');
        }

        return max(0, $this->display_subtotal - $this->display_total);
    }

    public function getDisplayTotalAttribute(): float
    {
        if (array_key_exists('total', $this->attributes) && $this->attributes['total'] !== null) {
            return (float) $this->attributes['total'];
        }

        if (array_key_exists('items_line_total', $this->attributes) && $this->attributes['items_line_total'] !== null) {
            return (float) $this->attributes['items_line_total'];
        }

        if ($this->relationLoaded('items')) {
            return (float) $this->items->sum('line_total');
        }

        return 0.0;
    }

    public function getSubtotalAttribute($value): float
    {
        return (float) ($value ?? $this->display_subtotal);
    }

    public function getDiscountTotalAttribute($value): float
    {
        return (float) ($value ?? $this->display_discount_total);
    }

    public function getTotalAttribute($value): float
    {
        return (float) ($value ?? $this->display_total);
    }
}
