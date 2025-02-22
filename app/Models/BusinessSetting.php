<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessSetting extends Model
{
    //
    protected $fillable = [
        'business_id',
        'name',
        'charge_type',
        'type',
        'value',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
