<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Liste des utilisateurs
    public function index(Request $request)
    {
        $query = User::query();

        // Recherche par nom ou email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        // Filtre par rôle
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Si l'utilisateur est un formateur, ne montrer que les apprenants
        if (Auth::user()->role === 'formateur') {
            $query->where('role', 'apprenant');
        }

        $users = $query->paginate($request->input('per_page', 10));

        // Calculer les statistiques
        $stats = [
            'admin' => User::where('role', 'admin')->count(),
            'formateur' => User::where('role', 'formateur')->count(),
            'apprenant' => User::where('role', 'apprenant')->count(),
            'nouveaux_mois' => User::where('role', 'apprenant')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Log des résultats
        \Log::info('Stats calculées :', $stats);

        // Vérifier tous les formateurs
        $tousFormateurs = User::where('role', 'formateur')->get();
        \Log::info('Liste des formateurs :', $tousFormateurs->toArray());

        // Calcul de l'évolution
        $stats['evolution'] = [
            'mois_dernier' => User::where('role', 'apprenant')
                ->whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count(),
            'ce_mois' => $stats['nouveaux_mois']
        ];

        // Calcul du pourcentage d'évolution
        $stats['pourcentage_evolution'] = $stats['evolution']['mois_dernier'] > 0 
            ? (($stats['evolution']['ce_mois'] - $stats['evolution']['mois_dernier']) / $stats['evolution']['mois_dernier']) * 100 
            : 0;

        return view('users.index', compact('users', 'stats'));
    }

    public function store(Request $request)
    {
        // Valider les données
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:apprenant,formateur,admin',
        ]);

        // Si l'utilisateur est un formateur, il ne peut créer que des apprenants
        if (Auth::user()->role === 'formateur' && $request->role !== 'apprenant') {
            return redirect()->back()->with('error', 'Les formateurs ne peuvent créer que des apprenants.');
        }

        // Créer l'utilisateur
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/utilisateurs')->with('success', 'Utilisateur ajouté avec succès.');
    }

    // Edition du formulaire de modification des utilisateurs
    public function edit($id)
    {
        // On récupère l'utilisateur à modifier
        $user = User::findOrFail($id);
        // On envoie l'utilisateur à la vue d'édition
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Si l'utilisateur est un formateur, il ne peut modifier que des apprenants
        if (Auth::user()->role === 'formateur' && $user->role !== 'apprenant') {
            return redirect()->back()->with('error', 'Les formateurs ne peuvent modifier que les apprenants.');
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:apprenant,formateur,admin',
        ]);

        // Si l'utilisateur est un formateur, il ne peut pas changer le rôle
        if (Auth::user()->role === 'formateur') {
            $request->merge(['role' => 'apprenant']);
        }

        $user->update($request->only(['name', 'email', 'role']));

        return redirect()->route('users.index', $user->id)
            ->with('success', 'Utilisateur modifié avec succès !')
            ->with('redirect', true);
    }

    // SUPPRESSION D'UN UTILISATEUR
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Si l'utilisateur est un formateur, il ne peut supprimer que des apprenants
        if (Auth::user()->role === 'formateur' && $user->role !== 'apprenant') {
            return redirect()->back()->with('error', 'Les formateurs ne peuvent supprimer que les apprenants.');
        }

        if (auth()->id() == $user->id) {
            return redirect('/utilisateurs')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect('/utilisateurs')->with('success', 'Utilisateur supprimé avec succès.');
    }
}