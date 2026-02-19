@php
    $user = auth()->user();
    $role = $user?->role?->name;

    $roleLabel = match ($role) {
        'admin'    => 'Admin',
        'teacher'  => 'Guru Pembimbing',
        'student'  => 'Siswa',
        'industry' => 'Pembimbing Lapangan Industri',
        default    => 'Pengguna',
    };
@endphp

<div class="bg-white p-6 rounded-lg shadow mb-6">
    <p class="text-sm text-gray-500">Selamat datang</p>
    <div class="mt-1 flex flex-col sm:flex-row sm:items-baseline sm:gap-2">
        <h3 class="text-2xl font-bold text-gray-800">
            {{ $user?->name ?? $roleLabel }}
        </h3>
        <p class="text-sm text-gray-600">
            ({{ $roleLabel }})
        </p>
    </div>
    <p class="mt-2 text-sm text-gray-600">
        Silakan lanjutkan aktivitas di sistem Prakerin.
    </p>
</div>
