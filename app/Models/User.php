<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Employee;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['username', 'email', 'password', 'last_login'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use Notifiable, HasRoles, HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'employee_id',
        'username',
        'email',
        'status',
        'avatar',
        'password',
        'last_login',
        'activity_log',
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
            'activity_log' => 'array',
        ];
    }

    protected $guard_name = 'web';

    protected $appends = ['role', 'name'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    // Fakes a singular "role" so your existing UI (role.name, role.id) keeps working,
    // backed by Spatie's actual roles() relationship under the hood
    public function getRoleAttribute()
    {
        $role = $this->roles->first();
        return $role ? ['id' => $role->id, 'name' => $role->name] : null;
    }

    public function getNameAttribute()
    {
        if ($this->employee) {
            return $this->employee->first_name . ' ' . $this->employee->last_name;
        }
        return $this->username;
    }

    public function adminlte_image()
    {
        // Check if the user has an associated employee with a photo
        if (
            $this->employee &&
            $this->employee->photo &&
            file_exists(storage_path('app/public/' . $this->employee->photo))
        ) {
            return asset('storage/' . $this->employee->photo);
        }

        // Fallback to a generated avatar based on the user's name
        return 'https://ui-avatars.com/api/?name='
            . urlencode($this->name)
            . '&background=4f46e5&color=fff';
    }

    public function adminlte_desc()
    {
        return $this->email;
    }
}
