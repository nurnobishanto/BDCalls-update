<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recharge extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'number',
        'amount',
        'payment_method',
        'screenshot',
        'bundle_id',
        'package_id',
        'status',
        'payment_status',
        'payment_response',
        'note',
    ];

    protected $casts = [
        'payment_request' => 'array',
        'payment_response' => 'array',
    ];

    // Relationships if needed:
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function minuteBundle()
    {
        return $this->belongsTo(MinuteBundle::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
