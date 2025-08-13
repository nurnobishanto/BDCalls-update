<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManualPaymentGateway extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'number',
        'type',             // 'bank' or 'mobile'
        'logo',
        'color',
        'minimum_amount',
        'maximum_amount',
        'status',
        'details',          // HTML instructions
    ];

    protected $casts = [
        'status' => 'boolean',
        'minimum_amount' => 'decimal:2',
        'maximum_amount' => 'decimal:2',
    ];
}
