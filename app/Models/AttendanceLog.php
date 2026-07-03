<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    //
    protected $primaryKey = 'log_id';
    protected $fillable = [
        'employee_id',
        'office_id',
        'log_datetime',
        'log_type',
        'device_name',
        'ip_address',
        'gps_location',
        'latitude',
        'longitude',
        'distance_from_office',
        'is_verified',
    ];

    protected $casts = [
        'log_datetime' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'distance_from_office' => 'decimal:2',
        'is_verified' => 'boolean',
    ];


    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id', 'office_id');
    }
    public function employee()
    {
        return $this->belongsTo(
            Employee::class,
            'employee_id',
            'employee_id'
        );
    }

}
