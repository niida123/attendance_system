<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $primaryKey = 'employee_id';
    protected $fillable = [
        'employee_code',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'address',
        'photo',
        'department_id',
        'position_id',
        'hire_date',
        'basic_salary',
        'status',
    ];
     protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'basic_salary' => 'decimal:2',
    ];

    public function department()
    {
        return $this->belongsTo(
            Department::class,
            'department_id'
        );
    }

    public function position()
    {
        return $this->belongsTo(
            Position::class,
            'position_id'
        );
    }

    public function user()
    {
        return $this->hasOne(
            User::class,
            'employee_id',
            'employee_id'
        );
    }

    public function attendances()
    {
        return $this->hasMany(
            Attendance::class,
            'employee_id',
            'employee_id'
        );
    }

    public function attendanceLogs()
    {
        return $this->hasMany(
            AttendanceLog::class,
            'employee_id',
            'employee_id'
        );
    }

    public function leaveRequests()
    {
        return $this->hasMany(
            LeaveRequest::class,
            'employee_id',
            'employee_id'
        );
    }

}
