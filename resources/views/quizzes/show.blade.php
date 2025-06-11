@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-4 dark:text-white">
            Quiz du cours : {{ $quiz->course->title ?? 'Cours inconnu' }}
        </h1>
        <<td class="text-gray-700 dark:text-white">{{ $quiz->title }}</td>
        <<div class="prose dark:prose-invert dark:text-white max-w-none mb-6">
        {!! $quizContentHtml !!}
        </div>
        @if(Auth::id() === $quiz->user_id || Auth::user()->role==='admin')
        <div class="flex justify-between">
        <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Supprimer ce quiz ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-800 text-white px-4 py-2 rounded">Supprimer</button>
            </form>
            <a href="{{ route('quizzes.edit', $quiz) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Modifier</a>
        </div>
        @endif
        <a href="{{ route('quizzes.index') }}" class="block mt-6 text-blue-600 hover:underline">← Retour à la liste des quiz</a>
    </div>
</div>
@endsection