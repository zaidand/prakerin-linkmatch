<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verifikasi Akun Pengguna') }}
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
                    <table class="min-w-full bg-white border">
                        <thead>
                            @php $roleFilter = request('role'); @endphp
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2">Nama</th>
                            @if($roleFilter === 'student')
                            <th class="px-4 py-2">NIS</th>
                            @elseif($roleFilter === 'teacher')
                            <th class="px-4 py-2">NIP</th>
                            @endif
                            <th class="border px-4 py-2">Email</th>
                            <th class="border px-4 py-2">Role</th>
                            <th class="border px-4 py-2">Status</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="border px-4 py-2">{{ $user->name }}</td>
                                @if($roleFilter === 'student')
                                <td class="px-4 py-2">
                                    {{ $user->student->nis ?? '-' }}</td>
                                @elseif($roleFilter === 'teacher')
                                <td class="px-4 py-2">
                                    {{ $user->teacher->nip ?? '-' }}</td>
                                @endif
                                <td class="border px-4 py-2">{{ $user->email }}</td>
                                <td class="border px-4 py-2">{{ $user->role->name }}</td>
                                <td class="border px-4 py-2">
                                    <span class="px-2 py-1 rounded text-sm
                                        @if($user->status === 'active') bg-green-100 text-green-800
                                        @elseif($user->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="border px-4 py-2">
                                    @if($user->status !== 'active')
                                        <form action="{{ route('admin.users.update-status', $user) }}"
                                              method="POST"
                                              class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="text-green-600 underline mr-2">
                                                Aktifkan
                                            </button>
                                        </form>
                                    @endif

                                    @if($user->status !== 'rejected')
                                        <form action="{{ route('admin.users.update-status', $user) }}"
                                              method="POST"
                                              class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="text-red-600 underline">
                                                Tolak
                                            </button>

                                            <a href="{{ route('admin.users.edit', $user) }}"
                                                class="text-blue-600 underline mr-3">
                                                Edit</a>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border px-4 py-2 text-center">
                                    Belum ada user yang perlu diverifikasi.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
