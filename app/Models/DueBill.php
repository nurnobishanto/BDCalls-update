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
    protected static function boot()
    {
        parent::boot();

        // Saving event runs on create & update
        static::saving(function ($model) {
            // Fetch call_rate & service_charge from the related package
            if ($model->userIpNumber && $model->userIpNumber->package) {
                $model->call_rate = $model->userIpNumber->package->call_rate ?? 0;
                $model->service_charge = $model->userIpNumber->package->price ?? 0;
            }

            // Calculate total
            $model->total = self::calculateTotal($model);
        });
    }

    /**
     * Calculate total = service_charge + (call_rate / 100 * minutes)
     */
    protected static function calculateTotal($model): float
    {
        $callRate = (float) ($model->call_rate ?? 0);
        $minutes = (float) ($model->minutes ?? 0);
        $serviceCharge = (float) ($model->service_charge ?? 0);

        return $serviceCharge + (($callRate / 100) * $minutes);
    }


}
