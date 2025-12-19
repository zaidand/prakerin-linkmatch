<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
    $query = User::query()->with(['role', 'student']); // kalau ada relasi student

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
}
