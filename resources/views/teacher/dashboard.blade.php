@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Dashboard Guru Pembimbing</h1>
    <p>Selamat datang, {{ auth()->user()->name }} (Guru Pembimbing).</p>
    <ul class="mt-4 list-disc list-inside">
        <li>Lihat dan verifikasi pengajuan prakerin siswa</li>
        <li>Monitoring logbook dan perkembangan siswa</li>
        <li>Rekap dan penilaian akhir prakerin</li>
    </ul>
</div>
@endsection
