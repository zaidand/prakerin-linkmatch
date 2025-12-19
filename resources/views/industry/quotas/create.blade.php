<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Kuota Prakerin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
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

                    <form action="{{ route('industry.quotas.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-1">Tanggal Mulai</label>
                                <input type="date" name="start_date"
                                       value="{{ old('start_date') }}"
                                       class="border px-3 py-2 w-full" required>
                            </div>
                            <div>
                                <label class="block mb-1">Tanggal Selesai</label>
                                <input type="date" name="end_date"
                                       value="{{ old('end_date') }}"
                                       class="border px-3 py-2 w-full" required>
                            </div>
                        </div>

                        <div>
                            <label class="block mb-1">Kuota Maksimal Siswa</label>
                            <input type="number" name="max_students"
                                   value="{{ old('max_students') }}"
                                   class="border px-3 py-2 w-full" min="1" required>
                        </div>

                        <div>
                            <label class="block mb-1">Kriteria Siswa (opsional)</label>
                            <textarea name="criteria" rows="3"
                                      class="border px-3 py-2 w-full">{{ old('criteria') }}</textarea>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                   class="border"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active">Kuota Aktif</label>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('industry.quotas.index') }}" class="px-4 py-2 border rounded">Batal</a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
