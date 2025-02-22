<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    //
    // $table->foreignId('stock_id')->constrained('stocks');
    //         $table->integer('quantity');
    //         //current stock
    //         $table->integer('current_stock');
    //         $table->string('type');
    //         $table->string('reference');
    //         $table->string('user');
    //         $table->string('note')->nullable();

    protected $fillable = [
        'stock_id',
        'quantity',
        'current_stock',
        'type',
        'reference',
        'user',
        'note'
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
