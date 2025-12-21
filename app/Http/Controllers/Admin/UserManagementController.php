<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
    $query = User::query()->with(['role', 'student', 'teacher']); // kalau ada relasi student

    // default: semua user non-admin
    $query->whereHas('role', function ($q) {
        $q->where('name', '!=', 'admin');
    });

    // filter role (student / teacher / dll)
    if ($request->filled('role')) {
        $role = $request->get('role');

        $query->whereHas('role', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }

    if (request('role') === 'student') {
    $query->whereHas('role', fn ($q) => $q->where('name', 'student'));
    }


    // KHUSUS: Data Siswa (status=active)
    if ($request->get('role') === 'student' && $request->get('status') === 'active') {
        // SESUAIKAN NAMA KOLOM-NYA YA:
        // misalnya: 'is_active' atau 'active' atau 'status'
        // $query->where('is_active', 1);
        // kalau di tabel users pakai 'status' = 'active', ganti:
        // $query->where('status', 'active');
    }

    $users = $query->paginate(15);

    return view('admin.users.index', compact('users'));
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,rejected',
        ]);

        if ($user->role->name === 'admin') {
            abort(403, 'Tidak boleh mengubah status admin.');
        }

        $user->status = $request->status;
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Status akun berhasil diperbarui.');
    }

    public function edit(User $user)
    {
    $user->load('role', 'student', 'teacher');

    $role = $user->role?->name;

    if ($role === 'student') {
        return view('admin.users.edit-student', compact('user'));
    }

    if ($role === 'teacher') {
        return view('admin.users.edit-teacher', compact('user'));
    }

    abort(404);
    }

    public function update(Request $request, User $user)
    {
    $user->load('role', 'student', 'teacher');

    $role = $user->role?->name;

    // validasi umum
    $baseRules = [
        'name'  => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
    ];

    if ($role === 'student') {
        $validated = $request->validate($baseRules + [
            'nis' => ['nullable', 'string', 'max:50'],
        ]);

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        $user->student()->updateOrCreate(
            ['user_id' => $user->id],
            ['nis' => $validated['nis'] ?? null]
        );

        return redirect()
            ->route('admin.users.index', ['role' => 'student'])
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    if ($role === 'teacher') {
        $validated = $request->validate($baseRules + [
            'nip' => ['nullable', 'string', 'max:50'],
        ]);

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        // simpan NIP di tabel teachers
        $user->teacher()->updateOrCreate(
            ['user_id' => $user->id],
            ['nip' => $validated['nip'] ?? null]
        );

        return redirect()
            ->route('admin.users.index', ['role' => 'teacher'])
            ->with('success', 'Data guru pembimbing berhasil diperbarui.');
    }

    abort(404);
    }
}
