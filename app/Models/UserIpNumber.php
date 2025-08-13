<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserIpNumber extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'package_id',
        'number',
        'note',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    // Relation to DueBills
    public function dueBills(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DueBill::class);
    }
}
