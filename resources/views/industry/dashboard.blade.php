<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Pembimbing Lapangan</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <a href="{{ route('industry.applications.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                    <p class="text-sm text-gray-500">Menunggu Konfirmasi</p>
                    <p class="text-3xl font-bold mt-2">{{ $waitingConfirm }}</p>
                </a>

                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-sm text-gray-500">Siswa Diterima</p>
                    <p class="text-3xl font-bold mt-2">{{ $acceptedInterns }}</p>
                </div>

                <a href="{{ route('industry.quotas.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                    <p class="text-sm text-gray-500">Kuota Aktif (periode berjalan)</p>
                    <p class="text-3xl font-bold mt-2">{{ $activeQuotas }}</p>
                </a>

                <a href="{{ route('industry.logbooks.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                    <p class="text-sm text-gray-500">Logbook Pending Validasi</p>
                    <p class="text-3xl font-bold mt-2">{{ $pendingLogbooks }}</p>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
