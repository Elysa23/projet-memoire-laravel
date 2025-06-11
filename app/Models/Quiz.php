<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'content',
        'title',
    ];

    // Un quiz appartient à un cours
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Un quiz appartient à un utilisateur (créateur)
    public function user()
    {
        return $this->belongsTo(User::class);
    }



    public function answers()
{
    return $this->hasMany(QuizAnswer::class);
}
}
