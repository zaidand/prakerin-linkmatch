<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penetapan Nilai Akhir Prakerin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">

                    <div>
                        <h3 class="font-semibold text-lg">{{ $application->student->user->name }}</h3>
                        <p>Jurusan: {{ $application->student->major->name ?? '-' }}</p>
                        <p>Industri: {{ $application->industry->name }}</p>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-100 text-red-800 px-4 py-2 mb-4 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Info komponen nilai --}}
                    <div class="border p-4 rounded space-y-2 text-sm">
                        <p><strong>Nilai Industri:</strong>
                            {{ $application->industryAssessment->overall_score ?? '-' }}
                        </p>
                        <p><strong>Nilai Laporan Akhir (Guru):</strong>
                            {{ $application->finalReport->teacher_score ?? '-' }}
                        </p>
                        <p><strong>Nilai Kehadiran (isi manual):</strong>
                            {{ $application->finalGrade->attendance_score ?? '-' }}
                        </p>

                         @if($application->finalReport)
                            <p class="mt-2">
                                <strong>File Laporan:</strong>
                                <a href="{{ route('final_reports.file', $application->finalReport) }}"
                                target="_blank"
                                class="text-blue-600 underline">
                                    Lihat Laporan
                                </a>
                            </p>
                        @endif
                    </div>

                    <form action="{{ route('teacher.final_grades.update', $application) }}"
                          method="POST"
                          class="space-y-4">
                        @csrf

                        @php
                            $g = $grade;
                        @endphp

                        <h4 class="font-semibold">Komponen Nilai</h4>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block mb-1 text-sm">Nilai Industri</label>
                                <input type="number" step="0.01" min="0" max="100"
                                       name="industry_score"
                                       value="{{ old('industry_score', $g->industry_score ?? $application->industryAssessment->overall_score ?? '') }}"
                                       class="border px-3 py-2 w-full">
                            </div>
                            <div>
                                <label class="block mb-1 text-sm">Nilai Laporan</label>
                                <input type="number" step="0.01" min="0" max="100"
                                       name="report_score"
                                       value="{{ old('report_score', $g->report_score ?? $application->finalReport->teacher_score ?? '') }}"
                                       class="border px-3 py-2 w-full">
                            </div>
                            <div>
                                <label class="block mb-1 text-sm">Nilai Kehadiran</label>
                                <input type="number" step="0.01" min="0" max="100"
                                       name="attendance_score"
                                       value="{{ old('attendance_score', $g->attendance_score ?? '') }}"
                                       class="border px-3 py-2 w-full">
                            </div>
                        </div>

                        <h4 class="font-semibold mt-4">Bobot (%)</h4>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block mb-1 text-sm">Bobot Industri</label>
                                <input type="number" name="weight_industry" min="0" max="100"
                                       value="{{ old('weight_industry', $g->weight_industry ?? 40) }}"
                                       class="border px-3 py-2 w-full">
                            </div>
                            <div>
                                <label class="block mb-1 text-sm">Bobot Laporan</label>
                                <input type="number" name="weight_report" min="0" max="100"
                                       value="{{ old('weight_report', $g->weight_report ?? 40) }}"
                                       class="border px-3 py-2 w-full">
                            </div>
                            <div>
                                <label class="block mb-1 text-sm">Bobot Kehadiran</label>
                                <input type="number" name="weight_attendance" min="0" max="100"
                                       value="{{ old('weight_attendance', $g->weight_attendance ?? 20) }}"
                                       class="border px-3 py-2 w-full">
                            </div>
                        </div>

                        @if($g && $g->final_score)
                            <div class="border p-3 rounded text-sm mt-2">
                                <p><strong>Nilai Akhir Saat Ini:</strong> {{ $g->final_score }}
                                    ({{ $g->grade_letter }})
                                </p>
                            </div>
                        @endif

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('teacher.final_grades.index') }}"
                               class="px-4 py-2 border rounded">
                                Batal
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded">
                                Simpan Nilai Akhir
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
