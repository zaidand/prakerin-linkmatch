<?php

namespace App\Http\Controllers\Industry;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndustryProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        // cari profil industri berdasarkan user_id
        $industry = Industry::with('majors')->firstOrNew([
            'user_id' => $user->id,
        ]);

        $majors = Major::orderBy('name')->get();

        return view('industry.profile.edit', compact('industry', 'majors'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'           => 'required|string|max:255',
            'address'        => 'required|string',
            'phone'          => 'required|string|max:50',
            'email'          => 'nullable|email',
            'business_field' => 'required|string|max:255',
            'description'    => 'nullable|string',
            'major_ids'      => 'required|array',
            'major_ids.*'    => 'exists:majors,id',
        ]);

        $industry = Industry::firstOrNew(['user_id' => $user->id]);

        $industry->fill([
            'name'           => $request->name,
            'address'        => $request->address,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'business_field' => $request->business_field,
            'description'    => $request->description,
        ]);

        // kalau baru dibuat, status biarkan pending
        if (! $industry->exists) {
            $industry->status = 'pending';
        }

        $industry->save();

        // sync jurusan (Link & Match)
        $industry->majors()->sync($request->major_ids);

        return redirect()
            ->route('industry.profile.edit')
            ->with('success', 'Profil industri berhasil disimpan. Menunggu verifikasi admin.');
    }
}
