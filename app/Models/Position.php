<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $primaryKey = 'position_id';

    protected $fillable = [
        'position_name',
        'description',
        'status',
        'department_id',
    ];

    public function employees()
    {
        return $this->hasMany(
            Employee::class,
            'position_id',
            'position_id'
        );
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }
}
