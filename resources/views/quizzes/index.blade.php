
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Liste des quiz</h1>
    @if(auth()->user()->role !== 'apprenant')
    <div class="flex justify-end mb-4 mr-4">
    <button onclick="openQuizModal()" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-6 rounded mb-4">
        + Créer un quiz
    </button>
</div>
    @endif
    <!--Affichage quizz dans l'onglet quizz-->

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($quizzes as $quiz)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-2 dark:text-white">
                 {{ $quiz->course->title ?? 'Cours inconnu' }}
            </h3>
            {{-- J'ai déplacé les classes text-gray et dark:text ici pour être sûr qu'elles s'appliquent --}}
            <div class="text-gray-700 dark:text-white mb-4">{{ $quiz->title }}</div> {{-- J'ai remplacé <td> par <div> --}}
            <p class="text-gray-700 dark:text-gray-200 mb-4"> {{-- Utilisation de mb-4 pour le margin-bottom --}}
                {!! nl2br(e(Str::limit($quiz->content, 200))) !!}
            </p>
            <div class="flex justify-between items-center mb-4"> {{-- Ajout de mb-4 --}}
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Créé par : {{ $quiz->user->name ?? 'Inconnu' }}
                </span>
            </div>

            {{-- Condition pour l'apprenant --}}
            @if(auth()->user()->role === 'apprenant')
                <div class="flex justify-center mt-4"> {{-- Conteneur flex pour centrer le bouton et margin-top --}}
                    <a href="{{ route('quizzes.answer', $quiz) }}" class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-2 rounded">
                        Passer le quiz
                    </a>
                </div>
            {{-- Condition pour admin et formateur --}}
            @else
                <div class="flex justify-between items-center mt-4"> {{-- Conteneur flex pour aligner de part et d'autre et margin-top --}}
                    <a href="{{ route('quizzes.show', $quiz) }}" class="text-blue-600 hover:underline">Voir</a>
                    @if(Auth::id() === $quiz->user_id || Auth::user()->role==='admin')
                        <a href="{{ route('quizzes.edit', $quiz) }}" class="text-yellow-600 hover:underline ml-2">Modifier</a>
                    @endif
                </div>
            @endif
        </div>
    @empty
        <div class="col-span-3 text-center text-gray-500 dark:text-gray-400">
            Aucun quiz généré pour le moment.
        </div>
    @endforelse
</div>

<div id="success-message" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="bg-green-100 border border-green-400 text-green-800 px-8 py-4 rounded shadow-lg text-lg font-semibold">
        <span id="success-message-text"></span>
    </div>
</div>

<!-- Modal de création/génération de quiz -->
<div id="quiz-modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-lg relative">
        <button onclick="closeQuizModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-2xl">&times;</button>
        <h2 class="text-xl font-bold mb-4 dark:text-white">Générer un quiz IA</h2>
        <form id="quiz-generate-form" >
            @csrf
            <div class="mb-4">
                <label for="course_id" class="block mb-1 dark:text-white">Sélectionner un cours</label>
                <select name="course_id" id="course_id" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" required>
                    <option value="">-- Choisir un cours --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <button type="button" id="generate-btn" onclick="generateQuiz()" class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-2 rounded">
                    Générer le quiz
                </button>
            </div>
            <div id="quiz-result" class="mb-4 hidden">
                <label class="block mb-1 dark:bg-gray-700 dark:text-white">Titre du quiz :</label>
                <input type="text" id="quiz-title" name="title" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white mb-2" required>
                <label class="block mb-1 dark:text-white">Quiz généré (modifiable) :</label>
                <textarea id="quiz-content" name="content" rows="10" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white"></textarea>
                <div class="flex justify-between mt-2">
                    <button type="button" onclick="generateQuiz()" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Regénérer</button>
                    <button type="button" onclick="saveQuiz()" class="bg-green-600 hover:bg-green-800 text-white px-4 py-2 rounded">Enregistrer</button>
                </div>
            </div>
            <div id="quiz-loading" class="text-center text-blue-600 font-semibold hidden">
                Génération du quiz en cours...
            </div>
        </form>
    </div>
</div>

<script>
function openQuizModal() {
    document.getElementById('quiz-modal').style.display = 'flex';
    document.getElementById('quiz-result').classList.add('hidden');
    document.getElementById('quiz-loading').classList.add('hidden');
    document.getElementById('quiz-content').value = '';
}
function closeQuizModal() {
    document.getElementById('quiz-modal').style.display = 'none';
}

function generateQuiz() {
    const courseId = document.getElementById('course_id').value;
    if (!courseId) {
        alert('Sélectionne un cours.');
        return;
    }
    document.getElementById('quiz-loading').classList.remove('hidden');
    document.getElementById('quiz-result').classList.add('hidden');
    
    fetch("{{ route('quizzes.generate') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ course_id: courseId })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('quiz-loading').classList.add('hidden');
        document.getElementById('quiz-result').classList.remove('hidden');
        document.getElementById('quiz-content').value = data.quiz;
        document.getElementById('quiz-title').value = data.title; // Remplir automatiquement le titre
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.getElementById('quiz-loading').classList.add('hidden');
        alert('Erreur lors de la génération du quiz.');
    });
}

function showSuccessMessage(message) {
    const msgBox = document.getElementById('success-message');
    const msgText = document.getElementById('success-message-text');
    msgText.textContent = message;
    msgBox.classList.remove('hidden');
    // Masquer après 2 secondes et recharger la page
    setTimeout(() => {
        msgBox.classList.add('hidden');
        window.location.reload();
    }, 2000);
}

function saveQuiz() {
    const courseId = document.getElementById('course_id').value;
    const content = document.getElementById('quiz-content').value;
    const title = document.getElementById('quiz-title').value;
    
    if (!title) {
        alert('Veuillez entrer un titre pour le quiz');
        return;
    }
    
    fetch("{{ route('quizzes.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ 
            course_id: courseId, 
            content: content,
            title: title 
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        closeQuizModal();
        showSuccessMessage('Quiz enregistré avec succès !');
    })
    .catch(err => {
        if (err && err.errors) {
            alert(Object.values(err.errors).join("\n"));
        } else {
            alert('Erreur lors de l\'enregistrement du quiz.');
        }
    });
}
</script>
@endsection