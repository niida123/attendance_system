<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeShift extends Model
{
    //  
    protected $primaryKey = 'employee_shift_id';
    protected $table = 'employee_shifts';
    protected $fillable = [
        'employee_id',
        'shift_id',
        'effective_from',
        'effective_to',
    ];

    // ← Add this
    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'shift_id');
    }

    // ← Add this
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function employeeShifts()
    {
        return $this->hasMany(
            EmployeeShift::class,
            'employee_shift_id',
            'employee_shift_id'
        );
    }
}
