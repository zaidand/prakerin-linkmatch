<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Jurusan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="bg-red-100 text-red-800 px-4 py-2 mb-4 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.majors.update', $major) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block mb-1">Nama Jurusan</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $major->name) }}"
                                   class="border px-3 py-2 w-full"
                                   required>
                        </div>

                        <div>
                            <label class="block mb-1">Deskripsi</label>
                            <textarea name="description"
                                      class="border px-3 py-2 w-full"
                                      rows="3">{{ old('description', $major->description) }}</textarea>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.majors.index') }}" class="px-4 py-2 border rounded">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                                Update
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
