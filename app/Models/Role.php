<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory,
    SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'permissions'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'permissions' => 'array'
        ];
    }

    public function getPermissionLabelsAttribute()
    {
        return collect($this->permissions)->map(function($permission) {
            $arr = explode('-', $permission);

            return __('labels.' . $arr[0]) . ' - ' . __('translations.' . $arr[1]);
        });
    }
}
