<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $primaryKey = 'office_id';

    protected $fillable = [
        'office_code',
        'office_name',
        'address',
        'latitude',
        'longitude',
        'allowed_radius',
        'office_ip',
        'office_wifi_name',
        'description',
        'status',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'allowed_radius' => 'integer',
    ];

    /**
     * One Office has many Employees
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'office_id', 'office_id');
    }

    /**
     * One Office has many Attendance Logs
     */
    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class, 'office_id', 'office_id');
    }
}