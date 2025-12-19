<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Jurusan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('admin.majors.create') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
                        + Tambah Jurusan
                    </a>

                    <table class="min-w-full bg-white border">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2">No</th>
                            <th class="border px-4 py-2">Nama Jurusan</th>
                            <th class="border px-4 py-2">Deskripsi</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($majors as $major)
                            <tr>
                                <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="border px-4 py-2">{{ $major->name }}</td>
                                <td class="border px-4 py-2">{{ $major->description }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('admin.majors.edit', $major) }}"
                                       class="text-blue-600 underline mr-2">Edit</a>

                                    <form action="{{ route('admin.majors.destroy', $major) }}"
                                          method="POST"
                                          class="inline-block"
                                          onsubmit="return confirm('Yakin hapus jurusan ini?')">
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
                                <td colspan="4" class="border px-4 py-2 text-center">
                                    Belum ada data jurusan.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $majors->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
