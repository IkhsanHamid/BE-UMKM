<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasUuids;
    protected $fillable = ['name', 'owner_id'];

    /**
     * Get the user that owns the business.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the outlets for the business.
     */
    public function outlets()
    {
        return $this->hasMany(Outlet::class);
    }
}
