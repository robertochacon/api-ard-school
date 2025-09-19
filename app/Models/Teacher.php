<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'department',
        'hire_date',
        'salary',
        'qualification',
        'specialization',
        'office_location',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_email',
        'is_active',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the teacher.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the courses taught by the teacher.
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Get the grades given by the teacher.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the attendance records created by the teacher.
     */
    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}