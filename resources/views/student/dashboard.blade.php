@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Dashboard Siswa</h1>
    <p>Selamat datang, {{ auth()->user()->name }} (Siswa).</p>
    <ul class="mt-4 list-disc list-inside">
        <li>Ajukan prakerin ke industri yang tersedia</li>
        <li>Isi logbook kegiatan harian prakerin</li>
        <li>Unggah laporan akhir dan lihat nilai</li>
    </ul>
</div>
@endsection
