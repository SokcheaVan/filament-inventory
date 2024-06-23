<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory,
    SoftDeletes;

    protected $guarded = ['created_at', 'updated_at'];

    /**
     * Start Relationships
     */
    public function customer_bank_accounts()
    {
        return $this->hasMany(CustomerBankAccount::class);
    }
    /**
     * End Relationships
     */

    /**
     * Start Attribute
     */
    public function getProvinceLabelAttribute()
    {
        return __('translations.' . $this->province);
    }
    /**
     * End Attribute
     */
}
