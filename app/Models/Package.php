<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'registration_number',
        'user',
        'call_channel',
        'call_channel_charges',
        'additional_extensions',
        'ivr_support',
        'web_space',
        'ram',
        'call_record',
        'voice_mail',
        'call_forward',
        'call_transfer',
        'data_backup',
        'recovery',
        'ring_group',
        'amber_blacklist',
        'call_charge_mobile_tnt',
        'pulse',
        'call_charges_ivr_number',
        'call_charges_own_network',
        'incoming_charges',
        'supported_devices',
        'spam_filter',
        'connection_type',
        'connection_method',
        'custom_configuration',
        'connection_charges',
        'uptime_guarantee',
        'control_panel',
        'account_will_remain_day',
        'automatic_termination_day',
        'price',
        'call_rate',
        'status',
    ];

    protected $casts = [
        'call_rate' => 'decimal:2',
        'price' => 'decimal:2',
        'status' => 'boolean',
    ];
}
