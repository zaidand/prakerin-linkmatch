<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Industri Prakerin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">

                    <div>
                        <h3 class="font-semibold text-lg">{{ $industry->name }}</h3>
                        <p>Bidang Usaha: {{ $industry->business_field }}</p>
                        <p>Alamat: {{ $industry->address }}</p>
                        <p>Telepon: {{ $industry->phone }}</p>
                        <p>Email: {{ $industry->email }}</p>
                    </div>

                    <div>
                        <h4 class="font-semibold">PIC (Pembimbing Lapangan)</h4>
                        <p>{{ $industry->user->name ?? '-' }}</p>
                        <p class="text-sm text-gray-600">{{ $industry->user->email ?? '' }}</p>
                    </div>

                    <div>
                        <h4 class="font-semibold">Jurusan yang Didukung</h4>
                        <p>{{ $industry->majors->pluck('name')->join(', ') ?: '-' }}</p>
                    </div>

                    <div>
                        <h4 class="font-semibold">Status</h4>
                        <span class="px-2 py-1 rounded text-xs
                            @if($industry->status === 'active') bg-green-100 text-green-800
                            @elseif($industry->status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($industry->status) }}
                        </span>
                    </div>

                    {{-- Kuota (opsional, informasi tambahan) --}}
                    @if($industry->quotas->count())
                        <div>
                            <h4 class="font-semibold">Kuota Prakerin</h4>
                            <ul class="list-disc list-inside text-sm">
                                @foreach($industry->quotas as $quota)
                                    <li>
                                        {{ $quota->start_date->format('d/m/Y') }} -
                                        {{ $quota->end_date->format('d/m/Y') }},
                                        Kuota: {{ $quota->max_students }} siswa,
                                        @if($quota->is_active) Aktif @else Nonaktif @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="border-t pt-4 flex gap-2">
                        <a href="{{ route('admin.industries.index') }}"
                           class="px-4 py-2 border rounded">
                            Kembali
                        </a>

                        <form action="{{ route('admin.industries.update-status', $industry) }}"
                              method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="active">
                            <button type="submit"
                                    class="px-4 py-2 bg-green-600 text-white rounded">
                                Setujui Industri
                            </button>
                        </form>

                        <form action="{{ route('admin.industries.update-status', $industry) }}"
                              method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded">
                                Tolak Industri
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
