<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seller extends Model
{
    use HasFactory,
    SoftDeletes;

    protected $guarded = ['created_at', 'updated_at'];

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
