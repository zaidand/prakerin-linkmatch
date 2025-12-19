<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Akhir Prakerin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="bg-red-100 text-red-800 px-4 py-2 mb-4 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">

                    <div>
                        <h3 class="font-semibold text-lg">{{ $application->industry->name }}</h3>
                        <p class="text-sm text-gray-600">
                            Jurusan: {{ $application->student->major->name ?? '-' }}
                        </p>
                    </div>

                    @if(!$report)
                        {{-- Belum pernah upload --}}
                        <h4 class="font-semibold mt-4 mb-2">Unggah Laporan Akhir</h4>

                        <form action="{{ route('student.final_report.store') }}"
                              method="POST" enctype="multipart/form-data"
                              class="space-y-4">
                            @csrf

                            <div>
                                <label class="block mb-1 text-sm">File Laporan (PDF/DOC/DOCX, max 5MB)</label>
                                <input type="file" name="report_file"
                                       class="border px-3 py-2 w-full" required>
                            </div>

                            <div>
                                <label class="block mb-1 text-sm">Ringkasan / Abstrak (opsional)</label>
                                <textarea name="summary" rows="3"
                                          class="border px-3 py-2 w-full">{{ old('summary') }}</textarea>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded">
                                    Unggah Laporan
                                </button>
                            </div>
                        </form>

                    @else
                        {{-- Sudah ada laporan --}}
                        <div class="border-b pb-3 mb-3">
                            <p><strong>Status:</strong>
                                @if($report->status === 'waiting_teacher')
                                    Menunggu penilaian Guru Pembimbing
                                @elseif($report->status === 'revision')
                                    Perlu revisi dari Guru Pembimbing
                                @else
                                    Sudah dinilai
                                @endif
                            </p>
                            <p><strong>File:</strong>
                                <a href="{{ route('final_reports.file', $report) }}"
                                   target="_blank"
                                   class="text-blue-600 underline">
                                    Lihat Laporan
                                </a>
                            </p>
                            @if($report->summary)
                                <p class="mt-2"><strong>Ringkasan:</strong><br>{{ $report->summary }}</p>
                            @endif
                        </div>

                        @if($report->teacher_comment)
                            <div class="border-b pb-3 mb-3">
                                <p><strong>Catatan Guru Pembimbing:</strong></p>
                                <p>{{ $report->teacher_comment }}</p>
                                @if($report->teacher_score)
                                    <p class="mt-1">
                                        <strong>Nilai Laporan:</strong> {{ $report->teacher_score }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        @if($report->status === \App\Models\FinalReport::STATUS_REVISION)
                            {{-- Form revisi --}}
                            <h4 class="font-semibold mt-4 mb-2">Unggah Revisi Laporan</h4>

                            <form action="{{ route('student.final_report.update') }}"
                                  method="POST" enctype="multipart/form-data"
                                  class="space-y-4">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label class="block mb-1 text-sm">File Laporan Revisi (PDF/DOC/DOCX, max 5MB)</label>
                                    <input type="file" name="report_file"
                                           class="border px-3 py-2 w-full" required>
                                </div>

                                <div>
                                    <label class="block mb-1 text-sm">Ringkasan / Abstrak (opsional)</label>
                                    <textarea name="summary" rows="3"
                                              class="border px-3 py-2 w-full">{{ old('summary', $report->summary) }}</textarea>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white rounded">
                                        Unggah Revisi
                                    </button>
                                </div>
                            </form>
                        @endif

                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
