<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Logbook Siswa
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">

                    {{-- Identitas Siswa & Industri --}}
                    <div>
                        <h3 class="font-semibold text-lg">
                            {{ $application->student->user->name }}
                        </h3>
                        <p>Jurusan: {{ $application->student->major->name ?? '-' }}</p>
                        <p>Industri: {{ $application->industry->name }}</p>
                    </div>

                    <hr>

                    {{-- Detail Logbook --}}
                    <div>
                        <p class="font-semibold">Tanggal:</p>
                        <p>
                            @if($logbookEntry->log_date instanceof \Illuminate\Support\Carbon ||
                                $logbookEntry->log_date instanceof \Carbon\Carbon)
                                {{ $logbookEntry->log_date->format('d/m/Y') }}
                            @else
                                {{ \Carbon\Carbon::parse($logbookEntry->log_date)->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="font-semibold">Kegiatan:</p>
                        <p>{{ $logbookEntry->activity_description ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold">Alat / Bahan:</p>
                        <p>{{ $logbookEntry->tools_used ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold">Kompetensi yang dipelajari:</p>
                        <p>{{ $logbookEntry->competencies ?? '-' }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="font-semibold">Jam Hadir:</p>
                            <p>{{ $logbookEntry->check_in_time ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold">Jam Pulang:</p>
                            <p>{{ $logbookEntry->check_out_time ?? '-' }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="font-semibold">Komentar Industri:</p>
                        <p>{{ $logbookEntry->industry_comment ?? '-' }}</p>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 text-green-800 px-4 py-2 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div>
                        <p class="font-semibold">Komentar Guru Pembimbing:</p>

                        @if($logbookEntry->teacher_comment)
                            <p class="text-sm text-gray-800 mb-2">{{ $logbookEntry->teacher_comment }}</p>
                        @else
                            <p class="text-sm text-gray-500 mb-2 italic">Belum ada komentar dari guru.</p>
                        @endif

                        <form action="{{ route('teacher.logbooks.comment', $logbookEntry) }}" method="POST" class="space-y-2">
                            @csrf
                            <textarea
                                name="teacher_comment"
                                rows="3"
                                class="w-full border rounded p-2 text-sm"
                                placeholder="Tulis komentar untuk siswa..."
                            >{{ old('teacher_comment', $logbookEntry->teacher_comment) }}</textarea>

                            @error('teacher_comment')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror

                            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded text-sm">
                                Simpan Komentar
                            </button>
                        </form>
                    </div>

                    <div>
                        <p class="font-semibold mb-1">Status:</p>
                        @php
                            $status = $logbookEntry->status ?? 'pending';
                            $badgeClass = match($status) {
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">
                            {{ ucfirst($status) }}
                        </span>
                    </div>

                    {{-- Dokumentasi --}}
                    @if($logbookEntry->evidence_path)
                        <div>
                            <p class="font-semibold mb-2">Dokumentasi:</p>

                            <img
                                src="{{ asset('storage/'.$logbookEntry->evidence_path) }}"
                                alt="Dokumentasi kegiatan"
                                class="max-w-full rounded border mb-2"
                            >

                            <a href="{{ route('teacher.logbooks.documentation', $logbookEntry) }}"
                               target="_blank"
                               class="text-sm text-blue-600 underline">
                                Buka / unduh file dokumentasi
                            </a>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('teacher.monitoring.show', $application) }}"
                           class="text-sm text-blue-600 underline">
                            ← Kembali ke Monitoring Siswa
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
