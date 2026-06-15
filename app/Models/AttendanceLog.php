<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    //
    protected $primaryKey = 'log_id';
    protected $fillable = [
        'employee_id',
        'log_datetime',
        'log_type',
        'device_name',
        'ip_address',
        'gps_location',
    ];

    protected $casts = [
        'log_datetime' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(
            Employee::class,
            'employee_id',
            'employee_id'
        );
    }

}
