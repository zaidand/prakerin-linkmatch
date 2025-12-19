<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rekap Nilai Akhir Prakerin (Admin)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4 flex justify-end">
                <a href="{{ route('admin.final_grades.export_csv') }}"
                   class="px-4 py-2 bg-green-600 text-white rounded text-sm">
                    Export ke CSV (Excel)
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <table class="min-w-full border text-sm">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-2 py-1">Nama Siswa</th>
                            <th class="border px-2 py-1">Jurusan</th>
                            <th class="border px-2 py-1">Industri</th>
                            <th class="border px-2 py-1">Nilai Industri</th>
                            <th class="border px-2 py-1">Nilai Laporan</th>
                            <th class="border px-2 py-1">Nilai Kehadiran</th>
                            <th class="border px-2 py-1">Nilai Akhir</th>
                            <th class="border px-2 py-1">Grade</th>
                            <th class="border px-2 py-1">Laporan</th> {{-- ✅ baru --}}
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($grades as $g)
                            @php $app = $g->application; @endphp
                            <tr>
                                <td class="border px-2 py-1">{{ $app->student->user->name ?? '' }}</td>
                                <td class="border px-2 py-1">{{ $app->student->major->name ?? '' }}</td>
                                <td class="border px-2 py-1">{{ $app->industry->name ?? '' }}</td>
                                <td class="border px-2 py-1 text-center">{{ $g->industry_score }}</td>
                                <td class="border px-2 py-1 text-center">{{ $g->report_score }}</td>
                                <td class="border px-2 py-1 text-center">{{ $g->attendance_score }}</td>
                                <td class="border px-2 py-1 text-center">{{ $g->final_score }}</td>
                                <td class="border px-2 py-1 text-center">{{ $g->grade_letter }}</td>
                                <td class="border px-2 py-1 text-center">
                                @if($app->finalReport)
                                    <a href="{{ route('final_reports.file', $app->finalReport) }}"
                                    target="_blank"
                                    class="text-blue-600 underline text-xs">
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="border px-2 py-2 text-center text-gray-600">
                                    Belum ada data nilai akhir.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $grades->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
