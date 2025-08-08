<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MinuteBundle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'incoming_charge',
        'ip_number_charge',
        'extension_charge',
        'outgoing_call_charge',
        'pulse',
        'minutes',
        'validity',
        'price',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'price' => 'decimal:2',
        'minutes' => 'integer',
    ];
}
