<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Industri Sesuai Jurusan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4">
                <p class="text-white">Jurusan Anda: <strong>{{ $student->major->name ?? '-' }}</strong></p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @forelse($industries as $industry)
                        <div class="border-b py-4">
                            <h3 class="text-lg font-semibold">{{ $industry->name }}</h3>
                            <p class="text-sm text-gray-600">
                                Bidang: {{ $industry->business_field }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Alamat: {{ $industry->address }}
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                Jurusan yang didukung:
                                {{ $industry->majors->pluck('name')->join(', ') }}
                            </p>

                            <div class="mt-2">
                                <h4 class="font-semibold text-sm mb-1">Kuota Aktif:</h4>
                                @forelse($industry->quotas as $quota)
                                    <div class="text-sm mb-1">
                                        Periode:
                                        {{ $quota->start_date->format('d/m/Y') }} -
                                        {{ $quota->end_date->format('d/m/Y') }},
                                        Kuota: {{ $quota->max_students }} siswa
                                        @if(isset($quota->remaining_slots))
                                            , Terisi: {{ $quota->used_slots ?? 0 }}
                                            , Sisa: {{ $quota->remaining_slots }}
                                        @endif
                                        @if($quota->criteria)
                                            <br><span class="text-gray-600">Kriteria: {{ $quota->criteria }}</span>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Tidak ada kuota aktif.</p>
                                @endforelse
                            </div>

                            @php
                                // nilai ini kita siapkan dari controller (lihat bagian 2)
                                $remainingSlots = $industry->remaining_slots ?? null;
                                $firstQuotaId   = $industry->first_available_quota_id ?? optional($industry->quotas->first())->id;
                            @endphp

                            <div class="mt-2 flex justify-between items-center">
                                <div class="text-sm text-gray-600">
                                    @if(!is_null($remainingSlots))
                                        Kuota tersisa: {{ $remainingSlots }} siswa
                                    @endif
                                </div>

                                {{-- Kalau ada info sisa kuota --}}
                                @if(!is_null($remainingSlots))
                                    @if($remainingSlots <= 0)
                                        {{-- KUOTA PENUH --}}
                                        <span class="text-xs font-semibold text-red-600">
                                            Kuota sudah penuh
                                        </span>
                                    @else
                                       @if(!$firstQuotaId)
+                                            <span class="text-xs font-semibold text-red-600">Kuota sudah penuh</span>
+                                        @else
+                                            {{-- MASIH ADA SLOT: TAMPILKAN TOMBOL AJUKAN (pakai kuota yang masih tersedia) --}}
+                                            <a href="{{ route('student.applications.create', [
                                                'industry_id' => $industry->id,
                                                'quota_id'    => $firstQuotaId,
                                            ]) }}"
                                           class="text-blue-600 underline text-sm">
                                            Ajukan Prakerin ke industri ini
                                        </a>
                                        @endif
                                    @endif
                                @else
                                    {{-- fallback: kalau remaining_slots belum dihitung di controller,
                                         tetap tampilkan tombol seperti biasa --}}
                                    @if($industry->quotas->isNotEmpty())
                                        <a href="{{ route('student.applications.create', [
                                                'industry_id' => $industry->id,
                                                'quota_id'    => $firstQuotaId,
                                            ]) }}"
                                           class="text-blue-600 underline text-sm">
                                            Ajukan Prakerin ke industri ini
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-600">
                            Belum ada industri yang membuka kuota prakerin untuk jurusan Anda saat ini.
                        </p>
                    @endforelse

                    <div class="mt-4">
                        {{ $industries->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
