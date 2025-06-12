@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-4 dark:text-white">
            Résultats du Quiz : {{ $quiz->course->title ?? 'Cours inconnu' }}
        </h1>

        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div class="text-lg font-semibold dark:text-white">Votre score</div>
                <div class="text-2xl font-bold {{ $score >= $quiz->passing_score ? 'text-green-600' : 'text-red-600' }}">
                    {{ $score }}%
                </div>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-4 dark:bg-gray-700 mb-2">
                <div class="h-4 rounded-full transition-all duration-500 ease-in-out {{ $score >= $quiz->passing_score ? 'bg-green-500' : 'bg-red-500' }}"
                     style="width: {{ $score }}%"></div>
            </div>

            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                <span>0%</span>
                <span>Seuil de réussite : {{ $quiz->passing_score }}%</span>
                <span>100%</span>
            </div>
        </div>

        @if($score >= $quiz->passing_score)
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Félicitations !</strong>
                <span class="block sm:inline"> Vous avez réussi le quiz.</span>
            </div>
        @else
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Dommage !</strong>
                <span class="block sm:inline"> Vous n'avez pas atteint le seuil de réussite.</span>
            </div>
        @endif

        <div class="mt-8">
            <a href="{{ route('courses.show', $quiz->course_id) }}" class="bg-blue-600 hover:bg-blue-800 text-white px-6 py-2 rounded text-lg">
                Retour au cours
            </a>
        </div>
    </div>
</div>
@endsection 