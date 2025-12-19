<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Info Siswa --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold">
                        {{ $application->student->user->name ?? '-' }}
                    </h3>
                    <p class="text-sm text-gray-700">
                        Jurusan: {{ $application->student->major->name ?? '-' }}
                    </p>
                    <p class="text-sm text-gray-700">
                        Industri: {{ $application->industry->name ?? '-' }}
                    </p>
                </div>
            </div>

            {{-- Rekap Logbook --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Rekap Logbook</h3>

                    @forelse(($logbookEntries ?? []) as $entry)
                        <div class="border-t first:border-t-0 py-3 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                            <div>
                                <div class="text-sm font-semibold">
                                    {{ optional($entry->log_date)->format('d/m/Y') ?? '-' }}
                                </div>
                                <div class="text-sm text-gray-700">
                                    {{ \Illuminate\Support\Str::limit($entry->activity, 80) ?? '-' }}
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($entry->status === 'approved')
                                    <span class="inline-block px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">
                                        Disetujui Industri
                                    </span>
                                @elseif($entry->status === 'rejected')
                                    <span class="inline-block px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                        Ditolak Industri
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                                        Menunggu Validasi
                                    </span>
                                @endif

                                <a href="{{ route('teacher.logbooks.show', $entry) }}"
                                   class="text-xs text-blue-600 underline">
                                    Lihat detail
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-600">
                            Belum ada logbook yang diisi siswa ini.
                        </p>
                    @endforelse
                </div>
            </div>

            {{-- Catatan Monitoring Guru --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Catatan Monitoring Guru</h3>

                        @if (Route::has('teacher.monitoring.notes.store'))
                            <form action="{{ route('teacher.monitoring.notes.store', $application) }}"
                                  method="POST" class="flex gap-2">
                                @csrf
                                <input type="date" name="note_date"
                                       class="border rounded px-2 py-1 text-sm"
                                       value="{{ now()->toDateString() }}">
                                <input type="text" name="note"
                                       class="border rounded px-2 py-1 text-sm w-64"
                                       placeholder="Tambahkan catatan singkat...">
                                <button type="submit"
                                        class="px-3 py-1 bg-blue-600 text-white rounded text-sm">
                                    Simpan
                                </button>
                            </form>
                        @endif
                    </div>

                    @forelse(($monitoringNotes ?? []) as $note)
                        <div class="border-t first:border-t-0 pt-3">
                            <div class="text-xs text-gray-500">
                                {{ optional($note->note_date)->format('d/m/Y') ?? $note->created_at->format('d/m/Y') }}
                            </div>
                            <div class="text-sm text-gray-800">
                                {{ $note->note }}
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-600">
                            Belum ada catatan monitoring.
                        </p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
