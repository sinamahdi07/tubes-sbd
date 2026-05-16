<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentItem extends Model
{
    protected $fillable = [
        'payment_id',
        'game_id',
        'title',
        'price',
        'discount_percent',
        'quantity',
        'line_total',
    ];

    protected $casts = [
        'price'            => 'decimal:2',
        'discount_percent' => 'integer',
        'quantity'         => 'integer',
        'line_total'       => 'decimal:2',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }
}
