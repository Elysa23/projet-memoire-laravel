@extends('layouts.app')

@section('content')

<!--Statistiques-->

<!-- Section des statistiques -->
<div class="grid grid-cols-1 md:grid-cols-3 mt-10 lg:grid-cols-5 gap-4 mb-6">
    <!-- Carte Admin -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 h-[200px]">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div class="mt-4">
            <h3 class="text-xl font-semibold mb-2 dark:text-white">Admins</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['admin'] }}</p>
        </div>
    </div>

    <!-- Carte Formateurs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 h-[200px]">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
            <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <div class="mt-4">
            <h3 class="text-xl font-semibold mb-2 dark:text-white">Formateurs</h3>
            <p class="text-3xl font-bold text-green-600">{{ $stats['formateur'] }}</p>
        </div>
    </div>

    <!-- Carte Apprenants -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 h-[200px]">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900">
            <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <div class="mt-4">
            <h3 class="text-xl font-semibold mb-2 dark:text-white">Apprenants</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $stats['apprenant'] }}</p>
        </div>
    </div>

    <!-- Carte Nouveaux ce mois -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 h-[200px]">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900">
            <svg class="w-6 h-6 text-orange-600 dark:text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </div>
        <div class="mt-4">
            <h3 class="text-xl font-semibold mb-2 dark:text-white">Nouveaux ce mois</h3>
            <div class="flex items-end justify-between">
                <p class="text-3xl font-bold text-orange-600">{{ $stats['nouveaux_mois'] }}</p>
                @if($stats['pourcentage_evolution'] != 0)
                    <span class="flex items-center {{ $stats['pourcentage_evolution'] > 0 ? 'text-green-500' : 'text-red-500' }}">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($stats['pourcentage_evolution'] > 0)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            @endif
                        </svg>
                        {{ abs(round($stats['pourcentage_evolution'])) }}%
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Carte Évolution -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <div class="mt-4">
            <h3 class="text-xl font-semibold mb-2 dark:text-white">Évolution mensuelle</h3>
            <div class="flex items-end gap-2 mt-2">
                <div class="flex flex-col items-center">
                    <div class="h-20 w-8 bg-gray-200 dark:bg-gray-700 rounded">
                        <div class="h-{{ ceil(($stats['evolution']['mois_dernier'] / max($stats['evolution']['mois_dernier'], $stats['evolution']['ce_mois'])) * 20) }} bg-indigo-500 rounded-t"></div>
                    </div>
                    <span class="text-xs mt-1 dark:text-gray-400">M-1</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="h-20 w-8 bg-gray-200 dark:bg-gray-700 rounded">
                        <div class="h-{{ ceil(($stats['evolution']['ce_mois'] / max($stats['evolution']['mois_dernier'], $stats['evolution']['ce_mois'])) * 20) }} bg-green-500 rounded-t"></div>
                    </div>
                    <span class="text-xs mt-1 dark:text-gray-400">M</span>
                </div>
            </div>
        </div>
    </div>
</div>


    <h1 class="text-2xl font-bold mb-4 flex justify-center dark:text-white">
        @if(Auth::user()->role === 'formateur')
            Liste des apprenants
        @else
            Liste des utilisateurs
        @endif
    </h1>

    <div class="flex justify-end mb-4">
    <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow"
        onclick="document.getElementById('add-user-modal').style.display='flex'">
        + Ajouter un utilisateur
    </button>
</div>

<!-- Modal d'ajout d'utilisateur -->
<div id="add-user-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md relative">
        <!-- Bouton de fermeture -->
        <button
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl"
            onclick="document.getElementById('add-user-modal').style.display='none'">
            &times;
        </button>
        <h2 class="text-xl font-bold mb-4 ">Ajouter un utilisateur</h2>
        <form action="{{ url('/utilisateurs') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block font-medium">Nom</label>
                <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label for="email" class="block font-medium">Email</label>
                <input type="email" name="email" id="email" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label for="password" class="block font-medium">Mot de passe</label>
                <input type="password" name="password" id="password" class="w-full border rounded px-3 py-2" required>
            </div>
            @if(Auth::user()->role === 'admin')
            <div>
                <label for="role" class="block font-medium">Rôle</label>
                <select name="role" id="role" class="w-full border rounded px-3 py-2" required>
                    <option value="admin">Admin</option>
                    <option value="formateur">Formateur</option>
                    <option value="apprenant">Apprenant</option>
                </select>
            </div>
            @else
            <input type="hidden" name="role" value="apprenant">
            @endif
            <div class="flex justify-end">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Ajouter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal d'édition d'utilisateur -->
<div id="edit-user-modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-md relative">
        <button onclick="closeEditUserModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-2xl">&times;</button>
        <h2 class="text-xl font-bold mb-4 dark:text-white">Modifier l'utilisateur</h2>
        <form id="edit-user-form" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block mb-1">Nom :</label>
                <input type="text" name="name" id="edit-user-name" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Email :</label>
                <input type="email" name="email" id="edit-user-email" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" required>
            </div>
            @if(Auth::user()->role === 'admin')
            <div class="mb-4">
                <label class="block mb-1">Rôle :</label>
                <select name="role" id="edit-user-role" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" required>
                    <option value="admin">Admin</option>
                    <option value="formateur">Formateur</option>
                    <option value="apprenant">Apprenant</option>
                </select>
            </div>
            @else
            <input type="hidden" name="role" value="apprenant">
            @endif
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

            <!-- Formulaire de recherche-->

        <form id="search-form" method="GET" action="{{ url('/utilisateurs') }}" class="mb-4 flex flex-wrap gap-2 items-center">
    <input id="search-input" type="text" name="search" placeholder="Nom ou email..." value="{{ request('search') }}"
        class="border rounded px-3 py-2 dark:bg-gray-600" />
    @if(Auth::user()->role === 'admin')
    <select name="role" class="border rounded px-7 py-2 dark:bg-gray-600">
        <option value=""> Rôles </option>
        <option value="admin" @if(request('role')=='admin') selected @endif>Admin</option>
        <option value="formateur" @if(request('role')=='formateur') selected @endif>Formateur</option>
        <option value="apprenant" @if(request('role')=='apprenant') selected @endif>Apprenant</option>
    </select>
    @endif
    <button type="submit" class="bg-blue-400 text-white px-4 py-2 rounded">Rechercher</button>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const searchForm = document.getElementById('search-form');

        let lastValue = searchInput.value;

        searchInput.addEventListener('input', function() {
            if (lastValue !== '' && searchInput.value === '') {
                // Si on vient de vider le champ, on soumet le formulaire
                searchForm.submit();
            }
            lastValue = searchInput.value;
        });
    });
</script>

    <table class="min-w-full bg-white dark:bg-gray-600">
        <thead>
            <tr>
                <th class="px-4 py-2">Nom</th>
                <th class="px-4 py-2">Email</th>
                @if(Auth::user()->role === 'admin')
                <th class="px-4 py-2">Rôle</th>
                @endif
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="border px-4 py-2">{{ $user->name }}</td>
                    <td class="border px-4 py-2">{{ $user->email }}</td>
                    @if(Auth::user()->role === 'admin')
                    <td class="border px-4 py-2">{{ $user->role }}</td>
                    @endif
                    <td class="border px-4 py-2">
                    <button onclick="openEditUserModal({ 
    id: '{{ $user->id }}', 
    name: '{{ $user->name }}', 
    email: '{{ $user->email }}', 
    role: '{{ $user->role }}' 
})"
class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
    Modifier
</button>
                    </td>
                    <td class="border px-4 py-2">
                        
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline"
                                    onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')">
                                    Supprimer
                                </button>
                            </form>
                    </td>
                   
                </tr>
            @endforeach
        </tbody>
    </table>

    <!--PAGINATION-->

    <div class="mt-6">
        <!-- Sélecteur du nombre d'éléments par page -->
        <div class="flex items-center justify-between mb-4">
            <select onchange="window.location.href=this.value" class="w-40 sm:w-48 md:w-25 mb-4 sm:mb-0 border rounded px-2 py-1 dark:bg-gray-700 dark:text-white">
                @foreach([10, 25, 50, 100] as $perPage)
                    <option value="{{ request()->fullUrlWithQuery(['per_page' => $perPage]) }}"
                        {{ request('per_page', 10) == $perPage ? 'selected' : '' }}>
                        {{ $perPage }} par page
                    </option>
                @endforeach
            </select>
            
            <span class="text-gray-600 dark:text-gray-400">
                Affichage de {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} sur {{ $users->total() }} utilisateurs
            </span>
        </div>

        <!-- Liens de pagination -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

    <script>
function openEditUserModal(user) {
    document.getElementById('edit-user-modal').style.display = 'flex';
    document.getElementById('edit-user-name').value = user.name;
    document.getElementById('edit-user-email').value = user.email;
    document.getElementById('edit-user-role').value = user.role;
    document.getElementById('edit-user-form').action = "{{ route('users.update', ':id') }}".replace(':id', user.id);
}
function closeEditUserModal() {
    document.getElementById('edit-user-modal').style.display = 'none';
}
</script>
@endsection