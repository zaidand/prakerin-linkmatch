<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Guru Pembimbing</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(!$teacherMajorId)
                <div class="mb-4 bg-yellow-100 text-yellow-800 px-4 py-2 rounded">
                    Major guru belum terisi, sehingga data dashboard belum bisa difilter.
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('teacher.applications.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                    <p class="text-sm text-gray-500">Menunggu Verifikasi</p>
                    <p class="text-3xl font-bold mt-2">{{ $waitingTeacher }}</p>
                </a>

                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-sm text-gray-500">Direkomendasikan ke Admin</p>
                    <p class="text-3xl font-bold mt-2">{{ $approvedByTeacher }}</p>
                </div>

                <a href="{{ route('teacher.monitoring.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                    <p class="text-sm text-gray-500">Siswa Prakerin Aktif</p>
                    <p class="text-3xl font-bold mt-2">{{ $activeInterns }}</p>
                </a>

                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-sm text-gray-500">Logbook 7 Hari Terakhir</p>
                    <p class="text-3xl font-bold mt-2">{{ $logbookLast7Days }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
