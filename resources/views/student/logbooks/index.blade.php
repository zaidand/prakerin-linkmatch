<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Logbook Prakerin Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4">
                <p class="text-white"><strong>Industri:</strong> {{ $application->industry->name }}</p>
            </div>

            <div class="mb-4">
                <a href="{{ route('student.logbooks.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded text-sm">
                    + Tambah Logbook Harian
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @forelse($logbooks as $log)
                        <div class="border-b py-4">
                            <div class="flex justify-between">
                                <div>
                                    <h3 class="font-semibold">
                                        {{ $log->log_date->format('d/m/Y') }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Jam: {{ $log->check_in_time ? $log->check_in_time->format('H:i') : '-' }}
                                        -
                                        {{ $log->check_out_time ? $log->check_out_time->format('H:i') : '-' }}
                                    </p>
                                    <p class="text-sm mt-1">
                                        {{ Str::limit($log->activity_description, 120) }}
                                    </p>
                                </div>
                                <div class="text-right text-sm">
                                    <span class="px-2 py-1 rounded text-xs
                                        @if($log->status === 'approved') bg-green-100 text-green-800
                                        @elseif($log->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        @if($log->status === 'approved')
                                            Disetujui
                                        @elseif($log->status === 'rejected')
                                            Perlu perbaikan
                                        @else
                                            Menunggu validasi
                                        @endif
                                    </span>
                                    @if($log->industry_comment)
                                        <p class="mt-1 text-xs text-gray-700">
                                            Komentar: {{ $log->industry_comment }}
                                        </p>
                                    @endif

                                    @if($log->teacher_comment)
                                        <p class="mt-1 text-xs text-gray-700">
                                            Komentar Guru: {{ $log->teacher_comment }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-600">
                            Belum ada logbook yang diisi.
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
