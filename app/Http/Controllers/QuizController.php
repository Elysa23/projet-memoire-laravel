<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Course;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use League\CommonMark\CommonMarkConverter;

class QuizController extends Controller
{
    // Affiche la liste des quiz
    public function index()
    {
        $quizzes = Quiz::with('course', 'user')->latest()->get();
        $courses = \App\Models\Course::all(); 
        return view('quizzes.index', compact('quizzes','courses'));
    }

    // Affiche le formulaire/modal de création de quiz
    public function create()
    {
        $courses = Course::all(); // Ou seulement les cours du formateur connecté
        return view('quizzes.create', compact('courses'));
    }

    // Enregistre le quiz validé
    public function store(Request $request)
{
    $request->validate([
        'course_id' => 'required|exists:courses,id',
        'content' => 'required',
        'title' => 'required|string|max:255',
    ]);

    $quiz = Quiz::create([
        'course_id' => $request->course_id,
        'user_id' => Auth::id(),
        'content' => $request->content,
        'title' => $request->title,
    ]);

    // Si la requête attend du JSON (AJAX)
    if ($request->wantsJson() || $request->ajax()) {
        return response()->json(['success' => true, 'quiz_id' => $quiz->id]);
    }

    // Sinon, comportement classique
    return redirect()->route('quizzes.index')->with('success', 'Quiz enregistré avec succès !');
}

    // Affiche un quiz

    public function show(Quiz $quiz)
    {
        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        $quizContentHtml = $converter->convertToHtml($quiz->content);
    
        return view('quizzes.show', compact('quiz', 'quizContentHtml'));
    }

    // Génère un quiz via l’API IA (exemple avec Mistral)
    public function generate(Request $request)
{
    $request->validate([
        'course_id' => 'required|exists:courses,id',
    ]);

    $course = Course::findOrFail($request->course_id);

    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('MISTRAL_API_KEY'),
            'HTTP-Referer' => 'http://localhost',
            'X-Title' => 'LaravelQuizGenerator',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'mistralai/mistral-7b-instruct',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => "Génère un quiz varié (QCM, vrai/faux, ouvert) de difficulté raisonnable sur ce contenu, en utilisant la syntaxe Markdown. 
                    - Utilise des titres (#) pour chaque question.
                    - Mets les questions en gras (**).
                    - Utilise des listes à puces pour les propositions de réponses.
                    - Indique la bonne réponse en la mettant en gras ou avec [x] pour les QCM.
                    - Ajoute une ligne de séparation (---) entre chaque question.
                    -Utilise toujours la syntaxe - [ ] pour chaque proposition, et - [x] pour la bonne réponse.  

                    Contenu du cours : " . $course->content,    
                ],
            ],
        ]);
        
        $quizText = $response->json()['choices'][0]['message']['content'] ?? 'Erreur de génération';
        
        // Générer un titre par défaut basé sur le cours
        $defaultTitle = "Quiz sur " . $course->title;
        
        return response()->json([
            'quiz' => $quizText,
            'title' => $defaultTitle
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Erreur lors de la génération du quiz', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Erreur lors de la génération du quiz'], 500);
    }
}

    public function edit(Quiz $quiz)
{
    // Optionnel : vérifier que l'utilisateur a le droit de modifier
    return view('quizzes.edit', compact('quiz'));
}

    public function update(Request $request, Quiz $quiz)
{
    $request->validate([
        'content' => 'required',
        'title' => 'required|string|max:255',
    ]);

    $quiz->update([
        'content' => $request->content
    ]);

    return redirect()->route('quizzes.index')->with('success', 'Quiz modifié avec succès !');
}

    public function destroy(Quiz $quiz)
{
    // Optionnel : vérifier que l'utilisateur a le droit de supprimer
    $quiz->delete();
    return redirect()->route('quizzes.index')->with('success', 'Quiz supprimé avec succès !');
}

public function answer(Quiz $quiz)
{
    
    $questions = $this->parseQuizMarkdown($quiz->content);

    return view('quizzes.answer', compact('quiz', 'questions'));
}

public function submitAnswers(Request $request, Quiz $quiz)
{
    $request->validate([
        'answers' => 'required|array',
        'time_spent' => 'nullable|integer'
    ]);

    QuizAnswer::create([
        'quiz_id' => $quiz->id,
        'user_id' => Auth::id(),
        'answers' => $request->answers,
        'time_spent' => $request->time_spent,
    ]);

    return redirect()->route('quizzes.index')->with('success', 'Quiz soumis avec succès !');
}

public function parseQuizMarkdown($markdown)
{
    $questionsRaw = preg_split('/^\s*---\s*$/m', $markdown);
    $questions = [];

    foreach ($questionsRaw as $block) {
        $block = trim($block);
        if (!$block) continue;

        // Question (en gras)
        if (preg_match('/\*\*(.+?)\*\*/s', $block, $m)) {
            $question = trim($m[1]);
        } else {
            $question = null;
        }

        // Choix QCM format - [ ] ou - [x]
        preg_match_all('/^- [\[\(]( |x)[\]\)] (.+)$/m', $block, $matches, PREG_SET_ORDER);
        $choices = [];
        $correct = null;
        if (count($matches) > 0) {
            foreach ($matches as $i => $match) {
                $choices[] = $match[2];
                if (strtolower($match[1]) === 'x') {
                    $correct = $i;
                }
            }
        } else {
            // Sinon, tente de parser les listes simples - A. ... ou - **[D. ...]**
            preg_match_all('/^- (?:\*\*)?\[?([A-Z])\.? (.+?)\]?(?:\*\*)?$/m', $block, $matches2, PREG_SET_ORDER);
            foreach ($matches2 as $i => $match) {
                $choices[] = $match[1] . '. ' . $match[2];
                // Si la ligne est en gras ou entre crochets, on la considère comme bonne réponse
                if (strpos($match[0], '**') !== false || strpos($match[0], '[') !== false) {
                    $correct = $i;
                }
            }
        }

        $type = (count($choices) > 0) ? 'qcm' : 'ouvert';

        $questions[] = [
            'question' => $question,
            'choices' => $choices,
            'correct' => $correct,
            'type' => $type,
        ];
    }

    return $questions;
}
}

