<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;

class QuizController extends Controller
{
    public function submitAnswers(Request $request, Quiz $quiz)
{

    dd('test';)
    // 1. Validation de la requête
    $request->validate([
        'answers' => 'required|array',
    ]);

    // 2. Parsing des questions du quiz
    $questions = parseQuizMarkdown($quiz->content); 
    $userAnswers = $request->input('answers');
    $score = 0;
    $total = 0;

    // 3. Log des données reçues
    \Log::info('Soumission quiz', [
        'user_id' => auth()->id(),
        'quiz_id' => $quiz->id,
        'userAnswers' => $userAnswers,
        'questions' => $questions,
    ]);

    // 4. Calcul du score
    foreach ($questions as $index => $q) {
        if (isset($q['correct']) && isset($userAnswers[$index])) {
            $total++;
            if ($userAnswers[$index] == $q['correct']) {
                $score++;
            }
        }
    }

    $finalScore = $total > 0 ? round(($score / $total) * 100, 2) : 0;
    $finalScore = floatval($finalScore);

    // 5. Log du score calculé
    \Log::info('Score calculé', [
        'finalScore' => $finalScore,
        'total' => $total,
        'score' => $score,
    ]);

    // 6. Debug complet avant enregistrement
    dd([
        'user_id' => auth()->id(),
        'quiz_id' => $quiz->id,
        'userAnswers' => $userAnswers,
        'questions' => $questions,
        'finalScore' => $finalScore,
        'total' => $total,
        'score' => $score,
    ]);

    // 7. Enregistrement dans la base (cette ligne ne sera pas exécutée tant que le dd() est présent)
    \App\Models\QuizAnswer::updateOrCreate(
        [
            'user_id' => auth()->id(),
            'quiz_id' => $quiz->id,
        ],
        [
            'answers' => json_encode($userAnswers),
            'score' => $finalScore,
        ]
    );

    // 8. Redirection
    return redirect()->route('student.quiz.stats')->with('success', 'Quiz soumis !');
}
}
