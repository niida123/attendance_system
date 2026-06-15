<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
     protected $primaryKey = 'holiday_id';

    protected $fillable = [
        'holiday_name',
        'start_date',
        'end_date',
        'description',
        'is_paid',
        'status',
    ];

    public function attendances()
    {
        return $this->hasMany(
            Attendance::class,
            'holiday_id',
            'holiday_id'
        );
    }
}
