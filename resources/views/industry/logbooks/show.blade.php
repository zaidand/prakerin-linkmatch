<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Logbook Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">

                    <div>
                        <h3 class="font-semibold text-lg">
                            {{ $logbook->application->student->user->name }}
                        </h3>
                        <p>Jurusan: {{ $logbook->application->student->major->name ?? '-' }}</p>
                        <p>Tanggal: {{ $logbook->log_date->format('d/m/Y') }}</p>
                        <p>Jam:
                            {{ $logbook->check_in_time ? $logbook->check_in_time->format('H:i') : '-' }}
                            -
                            {{ $logbook->check_out_time ? $logbook->check_out_time->format('H:i') : '-' }}
                        </p>
                    </div>

                    <div>
                        <h4 class="font-semibold">Deskripsi Kegiatan</h4>
                        <p>{{ $logbook->activity_description }}</p>
                    </div>

                    @if($logbook->tools_used)
                        <div>
                            <h4 class="font-semibold">Alat / Bahan</h4>
                            <p>{{ $logbook->tools_used }}</p>
                        </div>
                    @endif

                    @if($logbook->competencies)
                        <div>
                            <h4 class="font-semibold">Kompetensi</h4>
                            <p>{{ $logbook->competencies }}</p>
                        </div>
                    @endif

                    @if($logbook->evidence_path)
                        <div>
                            <h4 class="font-semibold">Dokumentasi</h4>
                            <a href="{{ route('industry.logbooks.evidence', $logbook) }}"
                                target="_blank"
                                class="text-blue-600 underline text-sm">
                                Lihat file
                            </a>
                        </div>
                    @endif

                    <div class="border-t pt-4">
                        <h4 class="font-semibold mb-2">Validasi Logbook</h4>

                        <form action="{{ route('industry.logbooks.validate', $logbook) }}"
                              method="POST"
                              class="space-y-3">
                            @csrf

                            <label class="block mb-1 text-sm">Komentar / Koreksi (opsional)</label>
                            <textarea name="industry_comment" rows="3"
                                      class="border px-3 py-2 w-full">{{ old('industry_comment', $logbook->industry_comment) }}</textarea>

                            <div class="flex gap-2">
                                <button type="submit" name="action" value="approve"
                                        class="px-4 py-2 bg-green-600 text-white rounded">
                                    Setujui
                                </button>
                                <button type="submit" name="action" value="reject"
                                        class="px-4 py-2 bg-red-600 text-white rounded">
                                    Tolak / Perlu diperbaiki
                                </button>
                                <a href="{{ route('industry.logbooks.index') }}"
                                   class="px-4 py-2 border rounded">
                                    Kembali
                                </a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
