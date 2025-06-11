@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Mon Profil</h1>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 max-w-3xl mx-auto">

        {{-- Message de statut (par exemple après une mise à jour) --}}
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        {{-- Informations de l'utilisateur --}}
        <div class="mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold mb-4 dark:text-white">Mes Informations</h2>

            <div class="flex flex-col md:flex-row items-center md:items-start">
                {{-- Photo de profil --}}
                <div class="md:mr-8 mb-6 md:mb-0 flex-shrink-0">
                    <img src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : asset('storage/images/default-avatar.jpg') }}"
                         alt="{{ $user->name }}"
                         class="rounded-full h-24 w-24 object-cover">
                </div>

                {{-- Détails --}}
                <div class="flex-grow">
                    <p class="text-gray-700 dark:text-gray-300 mb-2">
                        <span class="font-semibold dark:text-white">Nom Complet :</span> {{ $user->name }} {{ $user->first_name }}
                    </p>
                    <p class="text-gray-700 dark:text-gray-300 mb-2">
                        <span class="font-semibold dark:text-white">Email :</span> {{ $user->email }}
                    </p>
                    <p class="text-gray-700 dark:text-gray-300 mb-2">
                        <span class="font-semibold dark:text-white">Genre :</span> {{ ucfirst($user->gender ?? 'Non spécifié') }}
                    </p>
                    <p class="text-gray-700 dark:text-gray-300 mb-2">

                    <span class="font-semibold dark:text-white">Projet :</span> {{ $user->project->name ?? 'Non attribué' }}
                    </p>
                     <p class="text-gray-700 dark:text-gray-300 mb-2">
                        <span class="font-semibold dark:text-white">Rôle :</span> {{ ucfirst($user->role ?? 'Non spécifié') }}
                    </p>
                </div>
            </div>
        </div>


            {{-- Modifier le mot de passe --}}
            <div class="mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold mb-4 dark:text-white">Modifier mon mot de passe</h2>
                <form method="POST" action="{{ route('profile.updatePassword') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="current_password" class="block mb-1 dark:text-white">Mot de passe actuel</label>
                        <input id="current_password" name="current_password" type="password" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white @error('current_password', 'updatePassword') border-red-500 @enderror" required>
                        @error('current_password', 'updatePassword')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block mb-1 dark:text-white">Nouveau mot de passe</label>
                        <input id="password" name="password" type="password" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white @error('password', 'updatePassword') border-red-500 @enderror" required>
                         @error('password', 'updatePassword')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="block mb-1 dark:text-white">Confirmer le nouveau mot de passe</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-2 rounded">
                        Modifier le mot de passe
                    </button>
                </form>
            </div>

            {{-- Modifier la photo de profil --}}
            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-4 dark:text-white">Modifier ma photo de profil</h2>
                <form method="POST" action="{{ route('profile.updatePhoto') }}" enctype="multipart/form-data">
                    @csrf
                    {{-- Pas besoin de @method('PUT') pour les formulaires avec fichiers --}}

                    <div class="mb-4">
                        <label for="photo" class="block mb-1 dark:text-white">Sélectionner une nouvelle photo</label>
                        <input id="photo" name="photo" type="file" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white @error('photo', 'updatePhoto') border-red-500 @enderror" required>
                        @error('photo', 'updatePhoto')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-2 rounded">
                        Uploader la photo
                    </button>
                </form>
            </div>
        

    </div>
</div>
@endsection

