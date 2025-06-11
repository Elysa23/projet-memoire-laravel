<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule; 
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Models\User; 

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

         // Validation
    $request->validate([
        'current_password' => ['required'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    // Vérifie si l'ancien mot de passe est correct
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
    }
        
    Auth::logoutOtherDevices($request->current_password); //second test
    // Hash du nouveau mot de passe et mise à jour
    $user->update([
        'password' => Hash::make($request->password),
    ]);

       // $hash = Hash::make($request->password); // First test
       

        return redirect()->route('profile.edit')->with('status', 'Mot de passe mis à jour avec succès..');


}

    public function updatePhoto(Request $request): RedirectResponse
    {
        $user = $request->user(); // Récupère l'utilisateur authentifié

         
        if (!in_array($user->role, ['apprenant', 'admin', 'formateur'])) {
        
             // Redirige avec une erreur
             return Redirect::route('profile.edit')->withErrors(['photo_update' => 'Vous n\'avez pas la permission de modifier la photo de profil.']);
            
        }
        // <<< Fin de la vérification de rôle

        $request->validateWithBag('updatePhoto', [
            'photo' => ['required', 'image', 'max:2048'], // 2MB max
        ]);

        $path = $request->file('photo')->store('profile-photos', 'public');

        // Supprimer l'ancienne photo si elle existe et n'est pas l'avatar par défaut
        
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
             
             // if ($user->profile_photo_path !== 'chemin/vers/default/avatar.png') {
                  Storage::disk('public')->delete($user->profile_photo_path);
             // }
        }

        // Mettre à jour le chemin de la nouvelle photo
        $user->update(['profile_photo_path' => $path]); 

        return Redirect::route('profile.edit')->with('status', 'Photo de profil mise à jour avec succès.');
    }
}
