<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $primaryKey = 'leave_request_id';

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'approved_by',
        'approval_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approval_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(
            Employee::class,
            'employee_id',
            'employee_id'
        );
    }

    public function leaveType()
    {
        return $this->belongsTo(
            LeaveType::class,
            'leave_type_id',
            'leave_type_id'
        );
    }

    public function approver()
    {
        return $this->belongsTo(
            Employee::class,
            'approved_by',
            'employee_id'
        );
    }
}
