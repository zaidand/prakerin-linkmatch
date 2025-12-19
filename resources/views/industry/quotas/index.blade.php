<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kuota Prakerin - '.$industry->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="bg-red-100 text-red-800 px-4 py-2 mb-4 rounded">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <a href="{{ route('industry.quotas.create') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
                        + Tambah Kuota
                    </a>

                    <table class="min-w-full bg-white border">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2">Periode</th>
                            <th class="border px-4 py-2">Kuota</th>
                            <th class="border px-4 py-2">Aktif</th>
                            <th class="border px-4 py-2">Kriteria</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($quotas as $quota)
                            <tr>
                                <td class="border px-4 py-2">
                                    {{ $quota->start_date->format('d/m/Y') }} -
                                    {{ $quota->end_date->format('d/m/Y') }}
                                </td>
                                <td class="border px-4 py-2">{{ $quota->max_students }} siswa</td>
                                <td class="border px-4 py-2">
                                    @if($quota->is_active)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm">Aktif</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded text-sm">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="border px-4 py-2">{{ $quota->criteria }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('industry.quotas.edit', $quota) }}"
                                       class="text-blue-600 underline mr-2">Edit</a>

                                    <form action="{{ route('industry.quotas.destroy', $quota) }}"
                                          method="POST"
                                          class="inline-block"
                                          onsubmit="return confirm('Hapus kuota ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 underline">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border px-4 py-2 text-center">
                                    Belum ada kuota prakerin.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $quotas->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
