<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NumberPurchase extends Model
{
    use HasFactory, SoftDeletes;

    // Fillable fields matching your schema
    protected $fillable = [
        'user_id',
        'ip_number_id',
        'account_type',
        'name',
        'company_name',
        'email',
        'phone',
        'phone_country_code',
        'whatsapp_country_code',
        'whatsapp_number',
        'ip_number',
        'enather_ip_number',
        'nid_font_image',
        'nid_back_image',
        'trade_license',
        'selfie_photo',
        'status',
        'payment_status',
        'payment_method',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROGRESS = 'progress';
    const STATUS_REJECT = 'reject';
    const STATUS_RESOLVED = 'resolved';

    // Payment status constants
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_FAILED = 'failed';
    const PAYMENT_CANCELED = 'canceled';

    /**
     * Attribute casting
     */
    protected $casts = [
        'status' => 'string',
        'payment_status' => 'string',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: NumberPurchase belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: NumberPurchase belongs to an IP Number
     */
    public function ipNumber()
    {
        return $this->belongsTo(UserIpNumber::class, 'ip_number_id');
    }

    /**
     * Helper: Check if status is pending
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Helper: Check if payment is paid
     */
    public function isPaid()
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    /**
     * Helper: Check if payment is pending
     */
    public function isPaymentPending()
    {
        return $this->payment_status === self::PAYMENT_PENDING;
    }
}
