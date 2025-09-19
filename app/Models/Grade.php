<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'session_id',
        'teacher_id',
        'assignment_name',
        'grade_value',
        'max_grade',
        'grade_type',
        'date_given',
        'comments',
    ];

    protected $casts = [
        'grade_value' => 'decimal:2',
        'max_grade' => 'decimal:2',
        'date_given' => 'date',
    ];

    /**
     * Get the session that owns the grade.
     */
    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * Get the student that owns the grade.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the course that owns the grade.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the teacher that gave the grade.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the percentage grade.
     */
    public function getPercentageAttribute()
    {
        if ($this->max_grade > 0) {
            return round(($this->grade_value / $this->max_grade) * 100, 2);
        }
        return 0;
    }
}