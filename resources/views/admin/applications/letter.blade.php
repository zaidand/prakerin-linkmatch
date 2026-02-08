<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surat Pengantar Prakerin</title>

    {{-- kalau project kamu pakai tailwind via vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @page { size: A4; margin: 12.7mm; }

    @media print {
        .no-print { display: none !important; }

        html, body {
            background: #fff !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* HILANGKAN margin+padding tambahan dari wrapper saat print */
        .letter-wrap {
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
        }
    }

    /* tampilan layar boleh tetap rapi */
    .letter-wrap { max-width: 210mm; margin: 0 auto; }

    .text-11 { font-size: 11pt; }
    .text-12 { font-size: 12pt; }
    .lh { line-height: 1.35; }
    .hr-strong { border-top: 2px solid #000; }
    .hr-thin { border-top: 1px solid #000; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #000; padding: 6px 8px; }
</style>
</head>

<body class="bg-gray-100">
@php
    // tanggal surat: pakai admin_assigned_at kalau ada, kalau belum fallback ke now()
    $letterDate = $application->admin_assigned_at ?? now();

    // format tanggal Indonesia (pastikan ext intl/locale OK; kalau tidak, bisa pakai format biasa)
    $tanggalSurat = \Carbon\Carbon::parse($letterDate)->locale('id')->translatedFormat('d F Y');

    // nomor surat (sementara generate otomatis; kalau mau manual, bikin kolom letter_number di DB)
    $nomorSurat = sprintf('%03d/SMKYP/PKL/%s', $application->id, \Carbon\Carbon::parse($letterDate)->format('m/Y'));

    $student = $application->student;
    $studentUser = $student?->user;
    $major = $student?->major;

    $industry = $application->industry;
    $quota = $application->effective_quota;

    // coba ambil field yang mungkin ada (sesuaikan dengan nama kolom di tabel students/industries kamu)
    $nis = $student?->nis ?? $student?->nisn ?? '-';
    $kelas = $student?->kelas ?? $student?->class ?? $student?->class_name ?? '-';

    $programKeahlian = $major?->name ?? '-';

    $alamatIndustri = $industry?->address ?? $industry?->alamat ?? null;
    $kotaIndustri = $industry?->city ?? $industry?->kota ?? 'Tangerang';

    $periodeMulai = $quota?->start_date ? \Carbon\Carbon::parse($quota->start_date)->locale('id')->translatedFormat('d F Y') : null;
    $periodeSelesai = $quota?->end_date ? \Carbon\Carbon::parse($quota->end_date)->locale('id')->translatedFormat('d F Y') : null;

    $periode = ($periodeMulai && $periodeSelesai) ? ($periodeMulai.' s/d '.$periodeSelesai) : '-';
@endphp

<div class="letter-wrap bg-white shadow-sm my-6 p-8">
    {{-- tombol print --}}
    <div class="no-print mb-4 flex gap-2">
        <a href="{{ route('admin.applications.index') }}"
           class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
            Kembali
        </a>

        <button onclick="window.print()"
                class="px-3 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
            Cetak / Print
        </button>
    </div>

    {{-- HEADER (mirip PDF) --}}
    <div class="flex items-start gap-4">
        <div class="w-24 shrink-0">
            <img src="{{ asset('images/logo-pentek.jpg') }}" alt="Logo" class="w-24 h-24 object-contain">
        </div>

        <div class="flex-1 text-center">
            <div class="font-semibold text-12">YAYASAN USAHA PENINGKATAN PENDIDIKAN TEKNOLOGI</div>
            <div class="font-extrabold text-[18pt] tracking-wide">SMK YUPPENTEK 1 TANGERANG</div>

            {{-- daftar jurusan (buat 2 kolom seperti contoh PDF) --}}
            <div class="mt-1 grid grid-cols-2 gap-x-6 text-[10pt] lh">
                <ul class="list-disc list-inside text-left">
                    <li>Teknik Instalasi Tenaga Listrik</li>
                    <li>Teknik Otomasi Industri</li>
                    <li>Teknik Mekanik Industri</li>
                    <li>Teknik Pemesinan</li>
                </ul>
                <ul class="list-disc list-inside text-left">
                    <li>Teknik Kendaraan Ringan</li>
                    <li>Teknik Sepeda Motor</li>
                    <li>Teknik Komputer Dan Jaringan</li>
                    <li>Desain Komunikasi Visual</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="mt-2">
        <div class="hr-strong"></div>
        <div class="hr-thin mt-0.5"></div>
    </div>

    {{-- TANGGAL KANAN --}}
    <div class="mt-3 text-right text-11 lh">
        Tangerang, {{ $tanggalSurat }}
    </div>

    {{-- NOMOR / LAMP / HAL (kiri) --}}
    <div class="mt-2 grid grid-cols-2 gap-6 text-11 lh">
        <div>
            <div class="grid grid-cols-[70px_10px_1fr] gap-x-2">
                <div>Nomor</div><div>:</div><div>{{ $nomorSurat }}</div>
                <div>Lamp</div><div>:</div><div>-</div>
                <div>Hal</div><div>:</div><div>Permohonan Praktik Kerja Industri (PRAKERIN)</div>
            </div>
        </div>
        <div></div>
    </div>

    {{-- ALAMAT TUJUAN --}}
    <div class="mt-4 text-11 lh">
        <div>Kepada</div>
        <div>Yth. Saudara/i Kepala Bagian Personalia</div>
        <div class="font-semibold">{{ $industry?->name ?? '-' }}</div>
        @if($alamatIndustri)
            <div>{{ $alamatIndustri }}</div>
        @endif
        <div>Di</div>
        <div class="font-semibold">{{ $kotaIndustri }}</div>
    </div>

    {{-- ISI SURAT --}}
    <div class="mt-4 text-11 lh text-justify">
        <div>Dengan Hormat,</div>
        <p class="mt-2">
            Dalam rangka mendekatkan kesesuaian mutu tamatan Sekolah Menengah Kejuruan (SMK) YUPPENTEK,
            kami mohon kiranya Saudara/i dapat membantu siswa-siswa kami (sebagaimana tersebut dalam daftar di bawah ini)
            untuk melaksanakan Praktik Kerja Industri (PRAKERIN) di perusahaan yang Saudara/i pimpin.
        </p>

        <p class="mt-2">
            Adapun pelaksanaan PRAKERIN direncanakan pada periode: <strong>{{ $periode }}</strong>.
        </p>

        <p class="mt-2">
            Adapun daftar nama siswa yang kami maksud adalah sebagai berikut:
        </p>
    </div>

    {{-- TABEL SISWA (mirip PDF) --}}
    <div class="mt-2 text-11">
        <table>
            <thead>
                <tr class="text-center">
                    <th style="width: 40px;">No.</th>
                    <th>Nama</th>
                    <th style="width: 120px;">NISN/NIS</th>
                    <th style="width: 90px;">Kelas</th>
                    <th style="width: 180px;">Program Keahlian</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td class="uppercase">{{ $studentUser?->name ?? '-' }}</td>
                    <td class="text-center">{{ $nis }}</td>
                    <td class="text-center">{{ $kelas }}</td>
                    <td>{{ $programKeahlian }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- POIN-POIN --}}
    <div class="mt-4 text-11 lh">
        <p>
            Agar penyelenggaraan Praktik Kerja Industri (PRAKERIN) ini dapat terarah sesuai dengan yang diharapkan, segera akan kami kirimkan blanko-blanko sebagai berikut:
        </p>
        <ol class="list-decimal ml-5 mt-2">
            <li>Identitas Siswa</li>
            <li>Laporan kegiatan siswa secara berkala</li>
            <li>Laporan kemajuan praktik keahlian pada lini industri</li>
            <li>Laporan penilaian pembimbing dan Dunia Usaha / Industri</li>
            <li>Catatan Siswa Dan Pembimbing</li>
            <li>Daftar hadir siswa PRAKERIN</li>
        </ol>

        <p class="mt-3 text-justify">
            Setelah menyelesaikan program PRAKERIN, dimohon agar blanko-blanko yang telah diisi dikembalikan kepada kami.
            Data tersebut diperlukan untuk mengisi raport pada semester V dan sebagai syarat untuk mengikuti Ujian Nasional (UN).
        </p>

        <p class="mt-3">
            Demikian, atas perhatian dan kerja samanya kami ucapkan terima kasih.
        </p>
    </div>

    {{-- TANDA TANGAN --}}
    <div class="mt-8 grid grid-cols-2 text-11">
        <div></div>
        <div class="text-center">
            <div>Wakil Kepala Sekolah</div>
            <div>Urusan HUMAS &amp; HUB. DU/DI</div>

            <div style="height: 70px;"></div>

            <div class="font-semibold uppercase">( JAMALUDIN )</div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="mt-10 text-[10pt] text-center">
        <div class="hr-thin mb-1"></div>
        <div>
            Alamat Jl. Veteran No. 1 Kota Tangerang Telp. 021-5524518
            website: smkyuppentek1.sch.id Email: esemkayuppenteksatu@yahoo.co.id
        </div>
    </div>
</div>

{{-- auto print kalau dipanggil ?print=1 --}}
@if(request('print'))
    <script>
        window.addEventListener('load', () => window.print());
    </script>
@endif

</body>
</html>
