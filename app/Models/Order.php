<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    /**
     * Start Relationships
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sellers()
    {
        return $this->BelongsToMany(Seller::class);
    }

    public function order_products()
    {
        return $this->hasMany(OrderProduct::class);
    }
    /**
     * End Relationships
     */

    /**
     * Start Attribute
     */
    public function getOrderDateAttribute()
    {
        return Carbon::parse($this->order_at)->format('Y-m-d');
    }
    /**
     * End Attribute
     */
}
