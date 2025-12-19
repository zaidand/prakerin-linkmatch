<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pengajuan Prakerin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">

                    <div>
                        <h3 class="font-semibold text-lg">
                            {{ $application->student->user->name }}
                        </h3>
                        <p>Jurusan: {{ $application->student->major->name ?? '-' }}</p>
                        <p>Industri: {{ $application->industry->name }}</p>
                    </div>

                    <div>
                        <h4 class="font-semibold">Motivasi / Minat</h4>
                        <p>{{ $application->interest }}</p>
                    </div>

                    @if($application->gpa)
                        <div>
                            <h4 class="font-semibold">Nilai Rata-rata</h4>
                            <p>{{ $application->gpa }}</p>
                        </div>
                    @endif

                    @if($application->additional_info)
                        <div>
                            <h4 class="font-semibold">Informasi Tambahan</h4>
                            <p>{{ $application->additional_info }}</p>
                        </div>
                    @endif

                    <div class="border-t pt-4">
                        <h4 class="font-semibold mb-2">Catatan & Keputusan Guru</h4>

                        <form action="{{ route('teacher.applications.approve', $application) }}"
                            method="POST" class="space-y-3">
                            @csrf
                            <label class="block mb-1">Catatan (opsional)</label>
                            <textarea name="teacher_note" rows="3"
                                    class="border px-3 py-2 w-full">{{ old('teacher_note', $application->teacher_note) }}</textarea>

                            <div class="flex gap-2">
                                <button type="submit"
                                        class="px-4 py-2 bg-green-600 text-white rounded">
                                    Setujui & Rekomendasikan ke Admin
                                </button>
                            </div>
                        </form>

                        {{-- BARU: Hapus pengajuan --}}
                        <form action="{{ route('teacher.applications.destroy', $application) }}"
                            method="POST"
                            class="mt-4"
                            onsubmit="return confirm('Yakin ingin menghapus pengajuan ini? Tindakan ini tidak bisa dibatalkan.');">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded">
                                Hapus Pengajuan
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
