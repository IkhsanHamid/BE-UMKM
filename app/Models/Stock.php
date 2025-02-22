<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasUuids;
    protected $fillable = [
        'product_id',
        'quantity',
        'outlet_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }
}
