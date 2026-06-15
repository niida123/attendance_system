<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['username', 'email', 'password', 'last_login'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
     use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'employee_id',
        'role_id',
        'username',
        'email',
        'status',
        'password',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login' => 'datetime',
        ];
    }

    /*
    |-------------------------
    | Relationships
    |-------------------------
    */

    // User belongs to Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    // User belongs to Employee (optional)
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
    // Option A: Add a 'name' accessor that returns username
    public function getNameAttribute()
    {
         if ($this->employee) {
            return $this->employee->first_name . ' ' . $this->employee->last_name;
        }
        return $this->username;
    }
}
