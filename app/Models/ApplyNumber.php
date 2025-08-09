<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplyNumber extends Model
{
    use SoftDeletes;

    // Fillable fields matching your schema
    protected $fillable = [
        'user_id',
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
    ];

    // Status constants for ease of use
    const STATUS_PENDING = 'pending';
    const STATUS_PROGRESS = 'progress';
    const STATUS_REJECT = 'reject';
    const STATUS_RESOLVED = 'resolved';

    /**
     * The attributes that should be cast.
     * Optional but good practice if you want to cast dates or enums.
     */
    protected $casts = [
        'status' => 'string',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: ApplyNumber belongs to a User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
