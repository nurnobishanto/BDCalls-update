<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DueBill extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'user_ip_number_id',
        'call_rate',
        'minutes',
        'service_charge',
        'month',
        'total',
        'payment_status',
    ];
    protected $attributes = [
        'minutes' => 0,
        'service_charge' => 0,
        'call_rate' => 0,
    ];
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function userIpNumber(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(UserIpNumber::class);
    }
}
