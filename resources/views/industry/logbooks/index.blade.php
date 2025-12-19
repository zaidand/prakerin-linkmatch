<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Validasi Logbook Siswa - '.$industry->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @forelse($logbooks as $log)
                        <div class="border-b py-4">
                            <div class="flex justify-between">
                                <div>
                                    <h3 class="font-semibold text-lg">
                                        {{ $log->application->student->user->name }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Tanggal: {{ $log->log_date->format('d/m/Y') }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Jam: {{ $log->check_in_time ? $log->check_in_time->format('H:i') : '-' }}
                                        -
                                        {{ $log->check_out_time ? $log->check_out_time->format('H:i') : '-' }}
                                    </p>
                                    <p class="text-sm mt-1">
                                        {{ Str::limit($log->activity_description, 120) }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <a href="{{ route('industry.logbooks.show', $log) }}"
                                       class="text-blue-600 underline text-sm">
                                        Lihat detail & validasi
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-600">
                            Tidak ada logbook yang menunggu validasi.
                        </p>
                    @endforelse

                    <div class="mt-4">
                        {{ $logbooks->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
