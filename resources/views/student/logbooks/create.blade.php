<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Logbook Harian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if($errors->any())
                        <div class="bg-red-100 text-red-800 px-4 py-2 mb-4 rounded">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4 text-sm">
                        <p><strong>Industri:</strong> {{ $application->industry->name }}</p>
                    </div>

                    <form action="{{ route('student.logbooks.store') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="space-y-4">
                        @csrf

                        <div>
                            <label class="block mb-1">Tanggal</label>
                            <input type="date" name="log_date"
                                   value="{{ old('log_date', now()->toDateString()) }}"
                                   class="border px-3 py-2 w-full" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-1">Jam Hadir</label>
                                <input type="time" name="check_in_time"
                                       value="{{ old('check_in_time') }}"
                                       class="border px-3 py-2 w-full">
                            </div>
                            <div>
                                <label class="block mb-1">Jam Pulang</label>
                                <input type="time" name="check_out_time"
                                       value="{{ old('check_out_time') }}"
                                       class="border px-3 py-2 w-full">
                            </div>
                        </div>

                        <div>
                            <label class="block mb-1">Deskripsi Kegiatan</label>
                            <textarea name="activity_description" rows="4"
                                      class="border px-3 py-2 w-full"
                                      required>{{ old('activity_description') }}</textarea>
                        </div>

                        <div>
                            <label class="block mb-1">Alat / Bahan (opsional)</label>
                            <textarea name="tools_used" rows="2"
                                      class="border px-3 py-2 w-full">{{ old('tools_used') }}</textarea>
                        </div>

                        <div>
                            <label class="block mb-1">Kompetensi yang Dipelajari / Diterapkan (opsional)</label>
                            <textarea name="competencies" rows="2"
                                      class="border px-3 py-2 w-full">{{ old('competencies') }}</textarea>
                        </div>

                        <div>
                            <label class="block mb-1">Dokumentasi (opsional, jpg/png/pdf, max 2MB)</label>
                            <input type="file" name="evidence" class="border px-3 py-2 w-full">
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('student.logbooks.index') }}"
                               class="px-4 py-2 border rounded">
                                Batal
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded">
                                Simpan Logbook
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
