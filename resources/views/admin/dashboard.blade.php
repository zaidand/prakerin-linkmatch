<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Admin</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                <a href="{{ route('admin.applications.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                    <p class="text-sm text-gray-500">Menunggu Penempatan (Assign)</p>
                    <p class="text-3xl font-bold mt-2">{{ $waitingAssign }}</p>
                </a>

                <a href="{{ route('admin.applications.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                    <p class="text-sm text-gray-500">Menunggu Konfirmasi Industri</p>
                    <p class="text-3xl font-bold mt-2">{{ $waitingIndustry }}</p>
                </a>

                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-sm text-gray-500">Diterima Industri</p>
                    <p class="text-3xl font-bold mt-2">{{ $accepted }}</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-sm text-gray-500">Ditolak</p>
                    <p class="text-3xl font-bold mt-2">{{ $rejected }}</p>
                </div>

                <a href="{{ route('admin.users.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                    <p class="text-sm text-gray-500">Akun Pending</p>
                    <p class="text-3xl font-bold mt-2">{{ $pendingUsers }}</p>
                </a>

                <a href="{{ route('admin.users.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                    <p class="text-sm text-gray-500">Akun Aktif</p>
                    <p class="text-3xl font-bold mt-2">{{ $activeUsers }}</p>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
