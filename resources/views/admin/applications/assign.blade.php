<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tetapkan Penempatan Prakerin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">

                    @if($errors->any())
                        <div class="bg-red-100 text-red-800 px-4 py-2 mb-4 rounded">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div>
                        <h3 class="font-semibold text-lg">Data Siswa</h3>
                        <p>Nama: {{ $application->student->user->name }}</p>
                        <p>Jurusan: {{ $application->student->major->name ?? '-' }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-lg">Industri</h3>
                        <p>Nama: {{ $industry->name }}</p>
                        <p>Bidang: {{ $industry->business_field }}</p>
                    </div>

                    <form action="{{ route('admin.applications.assign', $application) }}"
                          method="POST" class="space-y-4">
                        @csrf

                        <div>
                            @if($application->requestedQuota)
                            <div class="mb-2 text-sm text-gray-700">
                                Kuota yang dipilih siswa saat pengajuan:
                                <strong>
                                    {{ $application->requestedQuota->start_date->format('d/m/Y') }} -
                                    {{ $application->requestedQuota->end_date->format('d/m/Y') }}
                                </strong>
                                (Kuota: {{ $application->requestedQuota->max_students }} siswa)
                            </div>
                        @endif
                            <label class="block mb-1">Pilih Periode Kuota Industri</label>
                            <select name="industry_quota_id" class="border px-3 py-2 w-full" required>
                                <option value="">-- Pilih Periode --</option>
                                @foreach($quotas as $quota)
                                @php
                                    $currentEffectiveQuotaId = $application->industry_quota_id ?? $application->requested_quota_id;
                                    $isCurrent = ($currentEffectiveQuotaId === $quota->id);
                                    $remaining = $quota->remaining_slots ?? null;
                                    $used = $quota->used_slots ?? null;
                                    $isFull = is_int($remaining) && $remaining <= 0;
                                    $canSelect = $quota->can_select ?? true;
                                @endphp
                                    <option value="{{ $quota->id }}"
                                        @selected(old('industry_quota_id', $application->industry_quota_id ?? $application->requested_quota_id) == $quota->id)
                                        @disabled(!$canSelect)
                                        >
                                        {{ $quota->start_date->format('d/m/Y') }} -
                                        {{ $quota->end_date->format('d/m/Y') }}
                                        (Kuota: {{ $quota->max_students }} siswa)
                                        @if(is_int($used) && is_int($remaining))
                                            — Terisi: {{ $used }}/{{ $quota->max_students }}, Sisa: {{ $remaining }}
                                        @endif
                                        @if(!$canSelect)
                                            — Penuh
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block mb-1">Catatan Admin (opsional)</label>
                            <textarea name="admin_note" rows="3"
                                      class="border px-3 py-2 w-full">{{ old('admin_note', $application->admin_note) }}</textarea>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.applications.index') }}" class="px-4 py-2 border rounded">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                                Tetapkan Penempatan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
