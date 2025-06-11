@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-4 dark:text-white">Modifier le quiz</h1>
        <form action="{{ route('quizzes.update', $quiz) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block mb-1 dark:text-white">Cours associé</label>
                <input type="text" value="{{ $quiz->course->title ?? 'Cours inconnu' }}" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" disabled>
            </div>
            <div class="mb-4">
                <label for="title" class="block text-gray-700">Titre du quiz</label>
                <input type="text" name="title" id="title" value="{{ old('title', $quiz->title ?? '') }}" class="form-input mt-1 block w-full" required>
            </div>
            <div class="mb-4">
                <label for="content" class="block mb-1 dark:text-white">Contenu du quiz</label>
                <textarea name="content" id="content" rows="10" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" required>{{ old('content', $quiz->content) }}</textarea>
                <small class="text-gray-500">
                    Tu peux utiliser la syntaxe <a href="https://www.markdownguide.org/cheat-sheet/" target="_blank" class="underline">Markdown</a> pour mettre en forme le quiz (titres, listes, gras, etc.).
                </small>
                @error('content')
                    <div class="text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="flex justify-between">
                <a href="{{ route('quizzes.index') }}" class="bg-gray-400 hover:bg-gray-600 text-white px-4 py-2 rounded">Annuler</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-2 rounded">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection