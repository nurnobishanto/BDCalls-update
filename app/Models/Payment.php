<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'order_id',
        'transaction_id',
        'amount',
        'payment_method',
        'status',
        'request',
        'response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'request' => 'array',
        'response' => 'array',
    ];
    protected static function booted()
    {
        static::created(function ($payment) {
            if (empty($payment->transaction_id)) {
                $payment->transaction_id = self::generateTransactionId($payment);
                $payment->save(); // save after creation
            }
        });

        static::updating(function ($payment) {
            if (empty($payment->transaction_id)) {
                $payment->transaction_id = self::generateTransactionId($payment);
            }
        });
    }

    protected static function generateTransactionId($payment)
    {
        $prefix = strtoupper(substr($payment->payment_method, 0, 1)); // First char, capitalized
        $idPart = str_pad($payment->id, 5, '0', STR_PAD_LEFT);

        return "TRX{$prefix}T{$idPart}";
    }

    /**
     * Payment belongs to a user.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Payment belongs to an order.
     */
    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
