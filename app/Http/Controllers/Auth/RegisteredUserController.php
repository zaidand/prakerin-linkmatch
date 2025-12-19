<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Major;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        $majors = Major::orderBy('name')->get();

        return view('auth.register', compact('majors'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Validasi dasar + role
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'in:student,teacher,industry_supervisor'],
        ]);

        // Validasi tambahan tergantung role
        if ($request->role === 'student') {
            $request->validate([
                'nis'      => ['nullable', 'string', 'max:50', 'unique:students,nis'],
                'class'    => ['required', 'string', 'max:50'],
                'major_id' => ['required', 'exists:majors,id'],
            ]);
        }

        if ($request->role === 'teacher') {
            $request->validate([
                'nip'      => ['nullable', 'string', 'max:50'],
                'major_id' => ['nullable', 'exists:majors,id'],
            ]);
        }

        // Cari role_id
        $role = Role::where('name', $request->role)->firstOrFail();

        // Buat user dengan status pending
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $role->id,
            'status'   => 'pending',
        ]);

        // Buat profil sesuai role
        if ($request->role === 'student') {
            Student::create([
                'user_id'  => $user->id,
                'major_id' => $request->major_id,
                'nis'      => $request->nis,
                'class'    => $request->class,
            ]);
        }

        if ($request->role === 'teacher') {
            Teacher::create([
                'user_id'  => $user->id,
                'major_id' => $request->major_id,
                'nip'      => $request->nip,
            ]);
        }

        event(new Registered($user));

        // Jangan auto-login, karena masih pending
        return redirect()->route('login')->with(
            'status',
            'Registrasi berhasil. Akun Anda menunggu verifikasi Admin.'
        );
    }
}
