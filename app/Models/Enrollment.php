<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'session_id',
        'enrollment_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
    ];

    /**
     * Get the session that owns the enrollment.
     */
    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * Get the student that owns the enrollment.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the course that owns the enrollment.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}