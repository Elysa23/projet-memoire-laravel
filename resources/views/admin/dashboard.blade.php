@extends('layouts.app')

@section('content')
@if(Auth::user()->role === 'admin' || Auth::user()->role === 'formateur')
<div class="container mx-auto py-8" x-data="{ modal: null }">
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Statistiques cours</h1>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Carte Cours créés -->
        <div @click="modal = 'created'" class="cursor-pointer bg-blue-100 dark:bg-blue-900 rounded-lg shadow p-6 text-center hover:bg-blue-200 hover:shadow-xl transition-all duration-300">
            <div class="text-3xl font-bold text-blue-700 dark:text-blue-300">{{ $total }}</div>
            <div class="text-lg text-blue-900 dark:text-blue-100 mt-2">Cours créés</div>
        </div>
        <!-- Carte Brouillons -->
        <div @click="modal = 'draft'" class="cursor-pointer bg-yellow-100 dark:bg-yellow-900 rounded-lg shadow p-6 text-center hover:bg-yellow-200 hover:shadow-xl transition-all duration-300">
            <div class="text-3xl font-bold text-yellow-700 dark:text-yellow-300">{{ $draft }}</div>
            <div class="text-lg text-yellow-900 dark:text-yellow-100 mt-2">Brouillons</div>
        </div>
        <!-- Carte Archivés -->
        <div @click="modal = 'archived'" class="cursor-pointer bg-gray-100 dark:bg-gray-800 rounded-lg shadow p-6 text-center hover:bg-gray-200 hover:shadow-xl transition-all duration-300">
            <div class="text-3xl font-bold text-gray-700 dark:text-gray-300">{{ $archived }}</div>
            <div class="text-lg text-gray-900 dark:text-gray-100 mt-2">Cours archivés</div>
        </div>
        <!-- Carte Taux de déploiement -->
        <div @click="modal = 'deployment'" class="cursor-pointer bg-green-100 dark:bg-green-900 rounded-lg shadow p-6 text-center hover:bg-green-200 hover:shadow-xl transition-all duration-300">
            <div class="text-3xl font-bold text-green-700 dark:text-green-300">{{ $deploymentRate }}%</div>
            <div class="text-lg text-green-900 dark:text-green-100 mt-2">Taux de déploiement</div>
        </div>
    </div>

    <!-- Modale Cours créés -->
    <div x-show="modal === 'created'" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-2xl relative">
            <button @click="modal = null" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-2xl">&times;</button>
            <h2 class="text-xl font-bold mb-4 dark:text-white">Cours créés par formateur</h2>
            <x-trainers-table :trainers="$byTrainer['created']" />
        </div>
    </div>
    <!-- Modale Brouillons -->
    <div x-show="modal === 'draft'" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-2xl relative">
            <button @click="modal = null" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-2xl">&times;</button>
            <h2 class="text-xl font-bold mb-4 dark:text-white">Brouillons par formateur</h2>
            <x-trainers-table :trainers="$byTrainer['draft']" />
        </div>
    </div>
    <!-- Modale Archivés -->
    <div x-show="modal === 'archived'" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-2xl relative">
            <button @click="modal = null" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-2xl">&times;</button>
            <h2 class="text-xl font-bold mb-4 dark:text-white">Cours archivés par formateur</h2>
            <x-trainers-table :trainers="$byTrainer['archived']" />
        </div>
    </div>
    <!-- Modale Taux de déploiement -->
    <div x-show="modal === 'deployment'" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white dark:bg-gray-600 rounded-lg shadow-lg p-8 w-full max-w-2xl relative">
            <button @click="modal = null" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-2xl">&times;</button>
            <h2 class="text-xl font-bold mb-4 dark:text-white">Détail du taux de déploiement</h2>
            <div class="mb-4">
                <span class="font-semibold">Cours commencés :</span> {{ $started }}<br>
                <span class="font-semibold">Cours publiés :</span> {{ $total }}<br>
                <span class="font-semibold">Taux de déploiement :</span> {{ $deploymentRate }}%
            </div>
            <h3 class="text-lg font-semibold mb-2 dark:text-white">Progression moyenne par formateur/admin</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-900 rounded-lg shadow">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left dark:text-white">Nom</th>
                            <th class="px-4 py-2 text-left dark:text-white">Progression moyenne (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($progressByTrainer as $trainer)
                            <tr>
                                <td class="border-t px-4 py-2 dark:text-gray-200">{{ $trainer['name'] }}</td>
                                <td class="border-t px-4 py-2 dark:text-gray-200">{{ $trainer['average'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="border-t px-4 py-2 text-center text-gray-500 dark:text-gray-400">Aucune donnée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<div class="container mx-auto py-8" x-data="{ modal: null }">
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Statistiques des quizz</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div @click="modal = 'globalAvg'" class="cursor-pointer bg-blue-100 dark:bg-blue-900 rounded-lg shadow p-6 text-center hover:bg-blue-200 hover:shadow-xl transition-all duration-300">
            <div class="text-3xl font-bold text-blue-700 dark:text-blue-300">{{ $globalAvg }}</div>
            <div class="text-lg text-blue-900 dark:text-blue-100 mt-2">Note moyenne globale</div>
        </div>
        <div @click="modal = 'globalSuccess'" class="cursor-pointer bg-green-100 dark:bg-green-900 rounded-lg shadow p-6 text-center hover:bg-green-200 hover:shadow-xl transition-all duration-300">
            <div class="text-3xl font-bold text-green-700 dark:text-green-300">{{ $globalSuccess }}%</div>
            <div class="text-lg text-green-900 dark:text-green-100 mt-2">Taux de réussite global</div>
        </div>
        <div @click="modal = 'avgByQuiz'" class="cursor-pointer bg-yellow-100 dark:bg-yellow-900 rounded-lg shadow p-6 text-center hover:bg-yellow-200 hover:shadow-xl transition-all duration-300">
            <div class="text-3xl font-bold text-yellow-700 dark:text-yellow-300">Voir</div>
            <div class="text-lg text-yellow-900 dark:text-yellow-100 mt-2">Note moyenne par quizz</div>
        </div>
    </div>

    <!-- Modale Note moyenne globale par formateur -->
    <div x-show="modal === 'globalAvg'" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-2xl relative">
            <button @click="modal = null" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-2xl">&times;</button>
            <h2 class="text-xl font-bold mb-4 dark:text-white">Note moyenne par formateur</h2>
            <table class="min-w-full bg-white dark:bg-gray-900 rounded-lg shadow">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left dark:text-white">Formateur</th>
                        <th class="px-4 py-2 text-left dark:text-white">Moyenne</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($avgByTrainer as $trainer)
                        <tr>
                            <td class="border-t px-4 py-2 dark:text-gray-200">{{ $trainer['name'] }}</td>
                            <td class="border-t px-4 py-2 dark:text-gray-200">{{ $trainer['average'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modale Taux de réussite global par quizz -->
    <div x-show="modal === 'globalSuccess'" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-2xl relative">
            <button @click="modal = null" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-2xl">&times;</button>
            <h2 class="text-xl font-bold mb-4 dark:text-white">Taux de réussite par quizz</h2>
            <table class="min-w-full bg-white dark:bg-gray-900 rounded-lg shadow">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left dark:text-white">Quizz</th>
                        <th class="px-4 py-2 text-left dark:text-white">Taux de réussite (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($successByQuiz as $quiz)
                        <tr>
                            <td class="border-t px-4 py-2 dark:text-gray-200">{{ $quiz['quiz_title'] }}</td>
                            <td class="border-t px-4 py-2 dark:text-gray-200">{{ $quiz['success_rate'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modale Note moyenne par quizz -->
    <div x-show="modal === 'avgByQuiz'" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-2xl relative">
            <button @click="modal = null" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-2xl">&times;</button>
            <h2 class="text-xl font-bold mb-4 dark:text-white">Note moyenne par quizz</h2>
            <table class="min-w-full bg-white dark:bg-gray-900 rounded-lg shadow">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left dark:text-white">Quizz</th>
                        <th class="px-4 py-2 text-left dark:text-white">Moyenne</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($avgByQuiz as $quiz)
                        <tr>
                            <td class="border-t px-4 py-2 dark:text-gray-200">{{ $quiz['quiz_title'] }}</td>
                            <td class="border-t px-4 py-2 dark:text-gray-200">{{ $quiz['average'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection