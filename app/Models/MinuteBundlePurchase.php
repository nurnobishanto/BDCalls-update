<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MinuteBundlePurchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'minute_bundle_id',
        'user_id',
        'user_ip_number_id',
        'price',
        'status',
        'payment_status',
        'payment_method',
    ];

    // Optional: default attribute values
    protected $attributes = [
        'status' => 'pending',
        'payment_status' => 'pending',
    ];

    // Relationships
    public function minuteBundle()
    {
        return $this->belongsTo(MinuteBundle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userIpNumber()
    {
        return $this->belongsTo(UserIpNumber::class);
    }

    // Helper methods
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function markAsPaid()
    {
        $this->update([
            'payment_status' => 'paid',
            'status' => 'resolved',
        ]);
    }

    public function markAsFailed()
    {
        $this->update([
            'payment_status' => 'failed',
            'status' => 'reject',
        ]);
    }
}
