<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'year',
        'teacher_id',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'year' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the teacher that owns the session.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the enrollments for the session.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the grades for the session.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the attendance records for the session.
     */
    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the courses associated with the session through enrollments.
     */
    public function courses()
    {
        return $this->hasManyThrough(Course::class, Enrollment::class, 'session_id', 'id', 'id', 'course_id');
    }

    /**
     * Get the students associated with the session through enrollments.
     */
    public function students()
    {
        return $this->hasManyThrough(Student::class, Enrollment::class, 'session_id', 'id', 'id', 'student_id');
    }
}
