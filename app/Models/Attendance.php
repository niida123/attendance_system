<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    //
    protected $table = 'attendance';
    protected $primaryKey = 'attendance_id';
    protected $fillable = [
        'employee_id',
        'holiday_id',
        'attendance_date',
        'check_in',
        'check_out',
        'working_hours',
        'late_minutes',
        'overtime_hours',
        'status',
        'remarks',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in' => 'string',
        'check_out' => 'string',
        'working_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function holiday()
    {
        return $this->belongsTo(Holiday::class, 'holiday_id', 'holiday_id');
    }
}
