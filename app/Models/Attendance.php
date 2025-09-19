<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = [
        'student_id',
        'course_id',
        'session_id',
        'teacher_id',
        'date',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the session that owns the attendance record.
     */
    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * Get the student that owns the attendance record.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the course that owns the attendance record.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the teacher that recorded the attendance.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}