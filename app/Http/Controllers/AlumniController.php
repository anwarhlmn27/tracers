<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AlumniController extends Controller
{
    /**
     * Display a listing of students (alumni data).
     */
    public function index()
    {
        $students = Student::with(['user', 'prodi'])->orderBy('created_at', 'desc')->get();
        $prodis = Prodi::all();

        return view('alumni', compact('students', 'prodis'));
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_student' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:20', 'unique:students,nim'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'prodi_id' => ['required', 'exists:prodis,id'],
            'angkatan' => ['required', 'integer', 'min:2000', 'max:2099'],
            'status' => ['required', Rule::in(['aktif', 'lulus', 'cuti', 'drop_out'])],
        ]);

        $user = User::create([
            'id' => Str::uuid(),
            'name' => $validated['nama_student'],
            'email' => $validated['email'],
            'password' => Hash::make('password123'),
            'role' => 'alumni',
        ]);

        Student::create([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'prodi_id' => $validated['prodi_id'],
            'nim' => $validated['nim'],
            'nama_student' => $validated['nama_student'],
            'angkatan' => $validated['angkatan'],
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Data alumni berhasil ditambahkan!');
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'nama_student' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:20', Rule::unique('students', 'nim')->ignore($student->id)],
            'prodi_id' => ['required', 'exists:prodis,id'],
            'angkatan' => ['required', 'integer', 'min:2000', 'max:2099'],
            'status' => ['required', Rule::in(['aktif', 'lulus', 'cuti', 'drop_out'])],
        ]);

        $student->update([
            'nama_student' => $validated['nama_student'],
            'nim' => $validated['nim'],
            'prodi_id' => $validated['prodi_id'],
            'angkatan' => $validated['angkatan'],
            'status' => $validated['status'],
        ]);

        // Also update user name
        if ($student->user) {
            $student->user->update(['name' => $validated['nama_student']]);
        }

        return back()->with('success', 'Data alumni berhasil diperbarui!');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);

        // Delete associated user as well
        if ($student->user) {
            $student->user->delete();
        }

        $student->delete();

        return back()->with('success', 'Data alumni berhasil dihapus!');
    }
}
