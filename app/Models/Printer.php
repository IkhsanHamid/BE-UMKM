<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    //
    protected $fillable = [
        'name',
        'connection_type',
        'mac_address',
        'ip_address',
        'paper_width',
        'default',
        'outlet_id',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
