<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guard_name',
        'description',
        'status',
    ];

    // Renamed from users() to assignedUsers() to avoid conflict with Spatie
    public function assignedUsers()
    {
        return $this->morphedByMany(
            \App\Models\User::class,
            'model',
            'model_has_roles',
            'role_id',
            'model_id'
        );
    }
}