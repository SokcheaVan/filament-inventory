<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    /**
     * Start Relationships
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    /**
     * End Relationships
     */
}
