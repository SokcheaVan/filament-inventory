<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerBankAccount extends Model
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
    /**
     * End Relationships
     */
}
