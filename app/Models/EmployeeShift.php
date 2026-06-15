<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeShift extends Model
{
    //  
    protected $primaryKey = 'shift_id';
    protected $fillable = [
        'shift_name',
        'start_time',
        'end_time',
        'late_after_minutes',
        'early_leave_before_minutes',
        'working_hours',
        'status',
    ];
    
     protected $casts = [
        'working_hours' => 'decimal:2',
    ];

    public function employeeShifts()
    {
        return $this->hasMany(
            EmployeeShift::class,
            'shift_id',
            'shift_id'
        );
    }
}
