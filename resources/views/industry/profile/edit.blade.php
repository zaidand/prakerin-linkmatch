<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil Industri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="bg-green-100 text-green-800 px-4 py-2 mb-4 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($industry->exists)
                        <div class="mb-4">
                            <span class="font-semibold">Status:</span>
                            <span class="px-2 py-1 rounded text-sm
                                @if($industry->status === 'active') bg-green-100 text-green-800
                                @elseif($industry->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($industry->status) }}
                            </span>
                        </div>
                    @endif

                    <form action="{{ route('industry.profile.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block mb-1">Nama Industri</label>
                            <input type="text" name="name"
                                   value="{{ old('name', $industry->name) }}"
                                   class="border px-3 py-2 w-full" required>
                            <x-input-error :messages="$errors->get('name')" class="mt-1"/>
                        </div>

                        <div>
                            <label class="block mb-1">Alamat</label>
                            <textarea name="address" rows="3"
                                      class="border px-3 py-2 w-full" required>{{ old('address', $industry->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-1"/>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-1">Telepon</label>
                                <input type="text" name="phone"
                                       value="{{ old('phone', $industry->phone) }}"
                                       class="border px-3 py-2 w-full" required>
                                <x-input-error :messages="$errors->get('phone')" class="mt-1"/>
                            </div>
                            <div>
                                <label class="block mb-1">Email</label>
                                <input type="email" name="email"
                                       value="{{ old('email', $industry->email) }}"
                                       class="border px-3 py-2 w-full">
                                <x-input-error :messages="$errors->get('email')" class="mt-1"/>
                            </div>
                        </div>

                        <div>
                            <label class="block mb-1">Bidang Usaha</label>
                            <input type="text" name="business_field"
                                   value="{{ old('business_field', $industry->business_field) }}"
                                   class="border px-3 py-2 w-full" required>
                            <x-input-error :messages="$errors->get('business_field')" class="mt-1"/>
                        </div>

                        <div>
                            <label class="block mb-1">Deskripsi Singkat</label>
                            <textarea name="description" rows="3"
                                      class="border px-3 py-2 w-full">{{ old('description', $industry->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-1"/>
                        </div>

                        <div>
                            <label class="block mb-1">Jurusan/Kompentensi yang Sesuai</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 border p-3 rounded">
                                @php
                                    $selectedMajors = old('major_ids', $industry->majors->pluck('id')->toArray());
                                @endphp
                                @foreach($majors as $major)
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox"
                                               name="major_ids[]"
                                               value="{{ $major->id }}"
                                               @if(in_array($major->id, $selectedMajors)) checked @endif>
                                        <span>{{ $major->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('major_ids')" class="mt-1"/>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                                Simpan Profil
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
