<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Siswa</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-sm text-gray-500">Status Pengajuan Terakhir</p>
                    <p class="mt-2 font-semibold text-gray-800">
                        {{ $latestApplication?->status ?? 'Belum ada pengajuan' }}
                    </p>
                </div>

                <a href="{{ route('student.logbooks.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                    <p class="text-sm text-gray-500">Logbook Hari Ini</p>
                    <p class="text-3xl font-bold mt-2">{{ $logbookToday }}</p>
                </a>

                <a href="{{ route('student.logbooks.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                    <p class="text-sm text-gray-500">Logbook Pending Validasi</p>
                    <p class="text-3xl font-bold mt-2">{{ $myPendingLogbooks }}</p>
                </a>

                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-sm text-gray-500">Notifikasi Belum Dibaca</p>
                    <p class="text-3xl font-bold mt-2">{{ $unreadNotif }}</p>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
