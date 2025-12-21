<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Data Siswa
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="mb-4 text-green-700 bg-green-100 px-4 py-2 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input name="name" value="{{ old('name', $user->name) }}"
                               class="mt-1 w-full border rounded px-3 py-2">
                        @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input name="email" value="{{ old('email', $user->email) }}"
                               class="mt-1 w-full border rounded px-3 py-2">
                        @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIS</label>
                        <input name="nis" value="{{ old('nis', $user->student->nis ?? '') }}"
                               class="mt-1 w-full border rounded px-3 py-2">
                        @error('nis') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                            Simpan
                        </button>
                        <a href="{{ route('admin.users.index', ['role' => 'student']) }}"
                           class="px-4 py-2 bg-gray-200 rounded">
                            Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
