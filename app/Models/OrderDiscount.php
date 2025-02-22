<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class OrderDiscount extends Model
{
    use HasUuids;
    protected $fillable = [
        'order_id',
        'discount_id',

    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function discount()
    {
        return $this->belongsTo(BusinessSetting::class, 'discount_id');
    }
}
