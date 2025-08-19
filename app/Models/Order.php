<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes,HasFactory;

    protected $fillable = [
        'invoice_no',
        'user_id',
        'payment_method',
        'transaction_id',
        'status',
        'total',
        'billing_details',
        'paid_at',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'billing_details' => 'array',
        'paid_at' => 'datetime',
    ];
    // In App\Models\Order.php

    protected static function booted()
    {
        static::created(function ($order) {
            if (empty($order->invoice_no)) {
                $order->invoice_no = self::generateInvoiceNo($order);
                $order->save();
            }
        });

        static::updating(function ($order) {
            if (empty($order->invoice_no)) {
                $order->invoice_no = self::generateInvoiceNo($order);
            }
        });
    }

    protected static function generateInvoiceNo($order)
    {
        $datePart = now()->format('Ym'); // e.g., 202505
        $randomPart = rand(1000, 9999);  // 4 digit random
        $idPart = str_pad($order->id, 5, '0', STR_PAD_LEFT);
        return "{$datePart}-{$randomPart}-{$idPart}";
    }

    /**
     * User who placed the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}
