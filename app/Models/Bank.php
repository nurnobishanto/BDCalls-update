<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bank_name',
        'account_name',
        'account_no',
        'branch_name',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
