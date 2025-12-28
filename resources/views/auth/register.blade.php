<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Nama --}}
        <div>
            <x-input-label for="name" value="Nama" />
            <x-text-input id="name" class="block mt-1 w-full" type="text"
                          name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Email --}}
        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email"
                          name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Role --}}
        <div class="mt-4">
            <x-input-label for="role" value="Daftar Sebagai" />
            <select id="role" name="role"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                <option value="">-- Pilih Role --</option>
                <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Siswa</option>
                <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Guru Pembimbing</option>
                <option value="industry_supervisor" {{ old('role') === 'industry_supervisor' ? 'selected' : '' }}>
                    Pembimbing Lapangan (Industri)
                </option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        {{-- Data Siswa / Guru (major, nis, class, nip) --}}
        <div class="mt-4 border-t pt-4">
            <h2 class="font-semibold mb-2">Data Pendidikan</h2>

            <div class="mt-2">
                <x-input-label for="major_id" value="Jurusan (Untuk Guru & Siswa)" />
                <select id="major_id" name="major_id"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                    <option value="">-- Pilih Jurusan --</option>
                    @foreach($majors as $major)
                        <option value="{{ $major->id }}" {{ old('major_id') == $major->id ? 'selected' : '' }}>
                            {{ $major->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('major_id')" class="mt-2" />
            </div>

            <div class="mt-2">
                <x-input-label for="nis" value="NIS (untuk Siswa)" />
                <x-text-input id="nis" class="block mt-1 w-full" type="text" placeholder="10 digit angka"
                              name="nis" :value="old('nis')" />
                <x-input-error :messages="$errors->get('nis')" class="mt-2" />
            </div>

            <div class="mt-2">
                <x-input-label for="class" value="Kelas (untuk Siswa)" />
                <x-text-input id="class" class="block mt-1 w-full" type="text" placeholder="Contoh: XI TKRO 1"
                              name="class" :value="old('class')" />
                <x-input-error :messages="$errors->get('class')" class="mt-2" />
            </div>

            <div class="mt-2">
                <x-input-label for="nip" value="NIP / NUPTK (Untuk Guru)" />
                <x-text-input id="nip" class="block mt-1 w-full" type="text"
                              name="nip" :value="old('nip')" />
                <x-input-error :messages="$errors->get('nip')" class="mt-2" />
            </div>
        </div>

        {{-- Password --}}
        <div class="mt-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirm Password --}}
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-black-600 hover:text-blue-900"
               href="{{ route('login') }}">
                Sudah punya akun?
            </a>

            <x-primary-button class="ml-4">
                Daftar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
