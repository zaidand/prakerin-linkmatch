<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penilaian Siswa Prakerin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">

                    <div>
                        <h3 class="font-semibold text-lg">{{ $application->student->user->name }}</h3>
                        <p>Jurusan: {{ $application->student->major->name ?? '-' }}</p>
                        <p>Industri: {{ $application->industry->name }}</p>
                    </div>

                    <div class="mt-2 text-sm">
                        @if($application->finalReport)
                            <p>
                                <strong>Laporan Akhir Siswa:</strong>
                                <a href="{{ route('final_reports.file', $application->finalReport) }}"
                                target="_blank"
                                class="text-blue-600 underline">
                                    Lihat Laporan
                                </a>
                            </p>
                        @else
                            <p class="text-gray-500">
                                Laporan akhir belum diunggah oleh siswa.
                            </p>
                        @endif
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

                    <form action="{{ route('industry.assessments.update', $application) }}"
                          method="POST"
                          class="space-y-4">
                        @csrf

                        @php
                            $a = $assessment;
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-1 text-sm">Sikap & Kedisiplinan</label>
                                <input type="number" name="discipline" min="0" max="100"
                                       value="{{ old('discipline', $a->discipline ?? '') }}"
                                       class="border px-3 py-2 w-full" required>
                            </div>
                            <div>
                                <label class="block mb-1 text-sm">Kemampuan Teknis</label>
                                <input type="number" name="technical_skill" min="0" max="100"
                                       value="{{ old('technical_skill', $a->technical_skill ?? '') }}"
                                       class="border px-3 py-2 w-full" required>
                            </div>
                            <div>
                                <label class="block mb-1 text-sm">Kerjasama</label>
                                <input type="number" name="teamwork" min="0" max="100"
                                       value="{{ old('teamwork', $a->teamwork ?? '') }}"
                                       class="border px-3 py-2 w-full" required>
                            </div>
                            <div>
                                <label class="block mb-1 text-sm">Komunikasi</label>
                                <input type="number" name="communication" min="0" max="100"
                                       value="{{ old('communication', $a->communication ?? '') }}"
                                       class="border px-3 py-2 w-full" required>
                            </div>
                            <div>
                                <label class="block mb-1 text-sm">Tanggung Jawab</label>
                                <input type="number" name="responsibility" min="0" max="100"
                                       value="{{ old('responsibility', $a->responsibility ?? '') }}"
                                       class="border px-3 py-2 w-full" required>
                            </div>
                        </div>

                        <div>
                            <label class="block mb-1 text-sm">Komentar / Rekomendasi (opsional)</label>
                            <textarea name="notes" rows="3"
                                      class="border px-3 py-2 w-full">{{ old('notes', $a->notes ?? '') }}</textarea>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('industry.assessments.index') }}"
                               class="px-4 py-2 border rounded">
                                Batal
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded">
                                Simpan Penilaian
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
