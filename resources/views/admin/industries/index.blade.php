<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verifikasi Industri Prakerin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Filter status --}}
            <div class="mb-4 bg-white shadow-sm rounded-lg p-4">
                <form method="GET" action="{{ route('admin.industries.index') }}" class="flex items-center gap-2">
                    <label for="status" class="text-sm font-semibold">Filter status:</label>
                    <select name="status" id="status" class="border px-3 py-1 text-sm">
                        <option value="">Semua</option>
                        <option value="pending"  {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active"   {{ ($status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="rejected" {{ ($status ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white text-sm rounded">
                        Terapkan
                    </button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <table class="min-w-full bg-white border">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2">Nama Industri</th>
                            <th class="border px-4 py-2">Bidang Usaha</th>
                            <th class="border px-4 py-2">PIC (Pembimbing Lapangan)</th>
                            <th class="border px-4 py-2">Jurusan</th>
                            <th class="border px-4 py-2">Status</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($industries as $industry)
                            <tr>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('admin.industries.show', $industry) }}"
                                       class="text-blue-600 underline">
                                        {{ $industry->name }}
                                    </a>
                                </td>
                                <td class="border px-4 py-2">{{ $industry->business_field }}</td>
                                <td class="border px-4 py-2">
                                    {{ $industry->user->name ?? '-' }}<br>
                                    <span class="text-xs text-gray-600">
                                        {{ $industry->user->email ?? '' }}
                                    </span>
                                </td>
                                <td class="border px-4 py-2 text-sm">
                                    {{ $industry->majors->pluck('name')->join(', ') ?: '-' }}
                                </td>
                                <td class="border px-4 py-2">
                                    <span class="px-2 py-1 rounded text-xs
                                        @if($industry->status === 'active') bg-green-100 text-green-800
                                        @elseif($industry->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($industry->status) }}
                                    </span>
                                </td>
                                <td class="border px-4 py-2 text-sm">
                                    {{-- Setujui --}}
                                    @if($industry->status !== 'active')
                                        <form action="{{ route('admin.industries.update-status', $industry) }}"
                                              method="POST"
                                              class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit"
                                                    class="text-green-600 underline mr-2"
                                                    onclick="return confirm('Setujui industri ini?')">
                                                Setujui
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tolak --}}
                                    @if($industry->status !== 'rejected')
                                        <form action="{{ route('admin.industries.update-status', $industry) }}"
                                              method="POST"
                                              class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit"
                                                    class="text-red-600 underline"
                                                    onclick="return confirm('Tolak industri ini?')">
                                                Tolak
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="border px-4 py-2 text-center text-gray-600">
                                    Belum ada data industri.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $industries->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
