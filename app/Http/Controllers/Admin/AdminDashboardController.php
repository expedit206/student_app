<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Formation; // Suppose un modèle Formation

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // Vérifie que l'utilisateur est admin (sécurité supplémentaire)
        if ($user->role !== 'admin') {
            return redirect()->route('dashboard'); // Redirige si pas admin
        }

        // Données dynamiques pour le dashboard admin
        $dashboardData = [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'stats' => [
                'totalUsers' => User::count(),
                'totalAdmins' => User::where('role', 'admin')->count(),
                'totalFormateurs' => User::where('role', 'formateur')->count(),
                'totalApprenants' => User::where('role', 'apprenant')->count(),
                'totalFormations' => Formation::count(),
            ],
            'recentActivity' => User::latest()
                ->take(5)
                ->get()
                ->map(fn($user) => [
                    'description' => "Utilisateur {$user->name} ({$user->role}) inscrit",
                    'date' => $user->created_at->toDateTimeString(),
                ])
                ->toArray(),
        ];

        return Inertia::render('Admin/AdminDashboard', $dashboardData);
    }
}
