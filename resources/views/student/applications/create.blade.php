<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajukan Prakerin - '.$industry->name) }}
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

                    <div class="mb-4">
                        <p><strong>Jurusan Anda:</strong> {{ $student->major->name ?? '-' }}</p>
                        <p><strong>Industri:</strong> {{ $industry->name }}</p>
                        <p><strong>Bidang Usaha:</strong> {{ $industry->business_field }}</p>
                    </div>

                    <form action="{{ route('student.applications.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <input type="hidden" name="industry_id" value="{{ $industry->id }}">

                        @if($quota)
                            <input type="hidden" name="requested_quota_id" value="{{ $quota->id }}">
                            <div class="mb-2 text-sm text-gray-700">
                                Periode kuota:
                                {{ $quota->start_date->format('d/m/Y') }} -
                                {{ $quota->end_date->format('d/m/Y') }},
                                Kuota: {{ $quota->max_students }} siswa
                            </div>
                        @endif

                        <div>
                            <label class="block mb-1">Nilai Rata-rata / Rapor (opsional)</label>
                            <input type="number" step="0.01" min="0" max="100" name="gpa"
                                   value="{{ old('gpa') }}"
                                   class="border px-3 py-2 w-full">
                        </div>

                        <div>
                            <label class="block mb-1">Motivasi / Minat Prakerin</label>
                            <textarea name="interest" rows="4"
                                      class="border px-3 py-2 w-full" required>{{ old('interest') }}</textarea>
                        </div>

                        <div>
                            <label class="block mb-1">Informasi Tambahan (opsional)</label>
                            <textarea name="additional_info" rows="3"
                                      class="border px-3 py-2 w-full">{{ old('additional_info') }}</textarea>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('student.industries.index') }}" class="px-4 py-2 border rounded">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                                Kirim Pengajuan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
