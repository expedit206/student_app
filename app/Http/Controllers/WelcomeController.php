<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Controller;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function welcome(Request $request)
    {
        $evolutionData = [
            'labels' => ['Semaine 1', 'Semaine 2', 'Semaine 3', 'Semaine 4'],
            'data' => [2, 4, 15, 20],
        ];

        return Inertia::render('Welcome', [  // Assurez-vous que c'est 'Home' ici
            'isAuthenticated' => $request->user() !== null,
            'evolution' => $evolutionData,
        ]);
    }

}
