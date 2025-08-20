<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IpNumber extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'number',
        'price',
        'status',
    ];
    public function numberPurchases(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(NumberPurchase::class, 'ip_number_id');
    }
    public function getStatusAttribute()
    {
        // If has any NumberPurchase that is not rejected
        if (UserIpNumber::where('nummber',$this->attributes['number'])->exists()) {
            return 'sold_out';
        }
        elseif ($this->numberPurchases()
            ->where('status', '!=', NumberPurchase::STATUS_REJECT)
            ->exists()) {
            return 'in_process';
        }else{
            return 'available';
        }

    }
}
