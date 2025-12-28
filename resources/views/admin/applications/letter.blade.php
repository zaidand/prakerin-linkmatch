<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surat Pengantar Prakerin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
        }
    </style>
</head>
<body class="bg-gray-100">

@php
    $quota = $application->quota;

    // Nomor surat sederhana (silakan sesuaikan format sekolah)
    $noSurat = 'SP/PKL/' . now()->format('Y') . '/' . str_pad($application->id, 4, '0', STR_PAD_LEFT);

    $startDate = $quota?->start_date ? \Carbon\Carbon::parse($quota->start_date)->format('d/m/Y') : '-';
    $endDate   = $quota?->end_date   ? \Carbon\Carbon::parse($quota->end_date)->format('d/m/Y') : '-';

    $studentName = $application->student?->user?->name ?? '-';
    $nis         = $application->student?->nis ?? '-';
    $majorName   = $application->student?->major?->name ?? '-';

    $industryName    = $application->industry?->name ?? '-';
    $industryAddress = $application->industry?->address ?? '-';
@endphp

<div class="max-w-4xl mx-auto py-6 px-4">

    {{-- tombol aksi --}}
    <div class="no-print flex items-center justify-between mb-4">
        <a href="{{ route('admin.applications.index') }}"
           class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-sm">
            ← Kembali
        </a>

        <button onclick="window.print()"
                class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm">
            Cetak
        </button>
    </div>

    {{-- kertas surat --}}
    <div class="bg-white shadow rounded p-8">

        {{-- kop surat --}}
        <div class="flex items-center gap-4 border-b pb-4">
            {{-- sesuaikan nama file logo kamu jika berbeda --}}
            <img src="{{ asset('images/yayasanbg.png') }}"
                 alt="Logo"
                 class="h-16 w-auto"
                 onerror="this.style.display='none';">

            <div class="text-center flex-1">
                <div class="font-bold text-lg uppercase">
                    PRAKERIN LINKMATCH
                </div>
                <div class="text-sm text-gray-600">
                    (Isi nama sekolah/alamat di sini)
                </div>
            </div>
        </div>

        {{-- nomor surat --}}
        <div class="mt-6 text-sm">
            <div>Nomor : <span class="font-semibold">{{ $noSurat }}</span></div>
            <div>Lampiran : -</div>
            <div>Perihal : <span class="font-semibold">Surat Pengantar Prakerin</span></div>
        </div>

        {{-- tujuan --}}
        <div class="mt-6 text-sm">
            <div>Kepada Yth.</div>
            <div class="font-semibold">{{ $industryName }}</div>
            <div>{{ $industryAddress }}</div>
            <div class="mt-3">Di tempat</div>
        </div>

        {{-- isi surat --}}
        <div class="mt-6 text-sm leading-relaxed text-justify">
            <p>Dengan hormat,</p>

            <p>
                Bersama surat ini kami mengajukan permohonan kepada pihak
                <span class="font-semibold">{{ $industryName }}</span>
                untuk dapat menerima siswa kami melaksanakan kegiatan Praktek Kerja Industri (PRAKERIN)
                dengan data sebagai berikut:
            </p>

            <div class="mt-4">
                <table class="w-full text-sm">
                    <tr>
                        <td class="w-40">Nama</td>
                        <td class="w-4">:</td>
                        <td class="font-semibold">{{ $studentName }}</td>
                    </tr>
                    <tr>
                        <td>NIS</td>
                        <td>:</td>
                        <td>{{ $nis }}</td>
                    </tr>
                    <tr>
                        <td>Jurusan</td>
                        <td>:</td>
                        <td>{{ $majorName }}</td>
                    </tr>
                    <tr>
                        <td>Periode</td>
                        <td>:</td>
                        <td>{{ $startDate }} s/d {{ $endDate }}</td>
                    </tr>
                </table>
            </div>

            <p class="mt-4">
                Demikian surat pengantar ini kami sampaikan. Atas perhatian dan kerja sama Bapak/Ibu,
                kami ucapkan terima kasih.
            </p>
        </div>

        {{-- tanda tangan --}}
        <div class="mt-10 text-sm flex justify-end">
            <div class="text-center">
                <div>{{ now()->translatedFormat('d F Y') }}</div>
                <div class="mt-2">Hormat kami,</div>
                <div class="mt-16 font-semibold">(Kepala Sekolah)</div>
                <div class="text-gray-600 text-xs">(Silakan sesuaikan)</div>
            </div>
        </div>
    </div>
</div>

{{-- Auto print jika param ?print=1 --}}
@if(request('print') == 1)
<script>
    window.addEventListener('load', function () {
        window.print();
        window.onafterprint = function () {
            window.location.href = "{{ route('admin.applications.index') }}";
        };
    });
</script>
@endif

</body>
</html>
