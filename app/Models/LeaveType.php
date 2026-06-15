<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $primaryKey = 'leave_type_id';

    protected $fillable = [
        'leave_name',
        'max_days_per_year',
        'description',
        'status',
    ];

    public function leaveRequests()
    {
        return $this->hasMany(
            LeaveRequest::class,
            'leave_type_id',
            'leave_type_id'
        );
    }
}
