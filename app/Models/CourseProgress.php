<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'last_page_number',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime'
    ];

    public $timestamps = true;

    // Relation : progression liée à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation : progression liée à un cours
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relation : progression liée à une page du cours
    public function coursePage()
    {
        return $this->belongsTo(CoursePage::class);
    }
}
