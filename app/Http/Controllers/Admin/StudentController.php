<?php

namespace App\Http\Controllers\Admin;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        // Ajout de la logique de recherche
        if ($request->has('search') && $request->search !== '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        // Récupérer les étudiants avec pagination (facultatif)
        $students = $query->paginate(10); // Par exemple, 10 étudiants par page

        return inertia('Students/Index', ['students' => $students]);
    }

    public function create()
    {
        return inertia('Students/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = bcrypt($validated['password']); // Hash le mot de passe

        Student::create($validated);

        return redirect()->route('student.index');
    }

    public function edit(Student $student)
    {
        return inertia('Students/Edit', ['student' => $student]);
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email,' . $student->id,
        ]);



        $student->update($validated);

        return redirect()->route('student.index');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('student.index');
    }
}
