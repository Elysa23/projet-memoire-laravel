<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // Les champs qu'on peut remplir massivement
    protected $fillable = [
        'title', 'description', 'duration', 'status', 'thumbnail', 'content', 'attachment', 'video', 'user_id'

    ];

    // Relation avec le formateur
    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Accesseur pour vérifier si le cours est publié
    public function getIsPublishedAttribute()
    {
        return $this->status === 'published';
    }

    public function pages()
    {
        return $this->hasMany(CoursePage::class)->orderBy('order');
    }

    public function user() 
    {
         return $this->belongsTo(User::class, 'user_id'); 
    }

    /**
     * Calcule la progression de l'apprenant dans le cours
     * @param User $user L'utilisateur apprenant
     * @return int Le pourcentage de progression (0-100)
     */
    public function getProgress($user)
    {
        if (!$user || $user->role !== 'apprenant') {
            return 0;
        }

        $totalPages = $this->pages()->count();
        
        // Si le cours n'a pas de pages mais a du contenu initial
        if ($totalPages === 0 && !empty($this->content)) {
            // Vérifier si l'utilisateur a consulté le contenu initial
            $hasViewedContent = $user->courseProgress()
                ->where('course_id', $this->id)
                ->exists();
            
            return $hasViewedContent ? 100 : 0;
        }

        if ($totalPages === 0) {
            return 0;
        }

        // Récupérer la dernière page visitée par l'apprenant
        $lastVisitedPage = $user->courseProgress()
            ->where('course_id', $this->id)
            ->orderBy('last_page_number', 'desc')
            ->first();

        if (!$lastVisitedPage) {
            return 0;
        }

        return (int) (($lastVisitedPage->last_page_number / $totalPages) * 100);
    }
}
