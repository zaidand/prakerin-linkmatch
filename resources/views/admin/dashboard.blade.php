@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Dashboard Admin</h1>
    <p>Selamat datang, {{ auth()->user()->name }} (Admin).</p>
    <ul class="mt-4 list-disc list-inside">
        <li>Kelola akun user</li>
        <li>Verifikasi industri</li>
        <li>Penempatan siswa prakerin</li>
    </ul>
</div>
@endsection
