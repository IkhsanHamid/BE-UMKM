<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasUuids;
    protected $fillable = ['name', 'business_id', 'address', 'phone', 'description'];

    /**
     * Get the business that owns the outlet.
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
