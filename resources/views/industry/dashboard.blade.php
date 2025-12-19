@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Dashboard Pembimbing Lapangan Industri</h1>
    <p>Selamat datang, {{ auth()->user()->name }} (Pembimbing Lapangan).</p>
    <ul class="mt-4 list-disc list-inside">
        <li>Atur profil industri & kuota prakerin</li>
        <li>Konfirmasi siswa yang ditempatkan</li>
        <li>Validasi logbook dan penilaian siswa</li>
    </ul>
</div>
@endsection
