<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Niveau;
use App\Models\Apprenant;
use App\Models\Formation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ApprenantController extends Controller
{
    public function index(Request $request)
    {
        $query = Apprenant::query();

        // Ajout de la logique de recherche
        if ($request->has('search') && $request->search !== '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        // Récupérer les étudiants avec pagination (facultatif)
        $apprenants = $query->with('user', 'formation')->orderBy('created_at', 'desc')->get(); // Par exemple, 10 étudiants par page
    //   dd($apprenants);
        return inertia('Admin/Apprenants/Index', compact('apprenants'));
    }

    public function create()
    {
        // Récupérer tous les niveaux et formations
        $niveaux = Niveau::all();
        $formations = Formation::all();

        // Passer les niveaux et formations à la vue
        return inertia('Admin/Apprenants/Create', [
            'niveaux' => $niveaux,
            'formations' => $formations,
        ]);
    }

    public function store(Request $request)
    {
        // Validation des données entrantes
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'niveau_id' => 'required|exists:niveaux,id',
            'formation_id' => 'required|exists:formations,id',
            'email' => 'required|string|email|max:255|unique:users', // Utilisation de la table users pour les emails
            'password' => 'required|string|min:8',
        ]);

        // Hash le mot de passe
        $validated['password'] = Hash::make($validated['password']);

        // Créer un nouvel utilisateur
        $user = User::create([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        // Créer l'apprenant associé à l'utilisateur
        Apprenant::create(array_merge($validated, ['user_id' => $user->id]));

        // Redirection vers la liste des apprenants avec un message de succès
        return redirect()->route('apprenants.index')->with('success', 'Étudiant ajouté avec succès.');
    }

    public function edit(Apprenant $apprenant)
    {
        $apprenant->load('user');
        $formations= Formation::all();
        $niveaux= Niveau::all();
        return inertia('Admin/Apprenants/Edit', compact('apprenant','formations', 'niveaux'));
    }

    public function update(Request $request, Apprenant $apprenant)
    {
        // Validation des données pour l'apprenant
        $validatedApprenant = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'niveau_id' => 'required|exists:niveaux,id',
            'formation_id' => 'required|exists:formations,id',
        ]);

        // Récupérer l'apprenant et l'utilisateur associé
        $user = $apprenant->user()->firstOrFail();

        // Validation des données pour l'utilisateur
        $validatedUser = $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, // Ignorer l'email de l'utilisateur
            'password' => 'nullable|string|min:8', // Mot de passe optionnel
        ]);

        // Mettre à jour l'utilisateur
        $user->email = $validatedUser['email'];
        if (!empty($validatedUser['password'])) {
            $user->password = bcrypt($validatedUser['password']);
        }
        $user->save(); // Enregistrer les modifications de l'utilisateur

        // Mettre à jour les informations de l'apprenant
        $apprenant->update($validatedApprenant);

        return redirect()->route('apprenants.index')->with('success', 'Apprenant mis à jour avec succès.');
    }

    public function destroy(Apprenant $apprenant)
    {
        $apprenant->delete();
        return redirect()->route('apprenants.index');
    }
}
