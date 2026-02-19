<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengajuan Prakerin (Admin)') }}
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

                    @forelse($applications as $app)
                        @php
                            // surat boleh dicetak hanya kalau sudah assign admin / sudah accepted industri
                            $canPrint = in_array($app->status, [
                                \App\Models\InternshipApplication::STATUS_ASSIGNED_BY_ADMIN,
                                \App\Models\InternshipApplication::STATUS_ACCEPTED,
                            ]);

                            $statusLabel = match ($app->status) {
                                \App\Models\InternshipApplication::STATUS_APPROVED_BY_TEACHER =>
                                    'Menunggu Penempatan (Assign)',
                                \App\Models\InternshipApplication::STATUS_ASSIGNED_BY_ADMIN =>
                                    'Menunggu Konfirmasi Industri',
                                \App\Models\InternshipApplication::STATUS_ACCEPTED =>
                                    'Diterima Industri',
                                default => $app->status,
                            };

                            $statusClass = match ($app->status) {
                                \App\Models\InternshipApplication::STATUS_APPROVED_BY_TEACHER =>
                                    'bg-yellow-100 text-yellow-800',
                                \App\Models\InternshipApplication::STATUS_ASSIGNED_BY_ADMIN =>
                                    'bg-blue-100 text-blue-800',
                                \App\Models\InternshipApplication::STATUS_ACCEPTED =>
                                    'bg-green-100 text-green-800',
                                default => 'bg-gray-100 text-gray-800',
                            };
                        @endphp

                        <div class="border-b py-4">
                            <div class="flex items-start justify-between gap-4">
                                <h3 class="font-semibold text-lg">
                                    {{ $app->student->user->name }} ({{ $app->student->major->name ?? '-' }})
                                </h3>
                                <span class="px-2 py-1 text-xs rounded {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-600">
                                Industri: {{ $app->industry->name }}
                            </p>

                            <p class="text-sm text-gray-600">
                                Diajukan: {{ $app->created_at->format('d/m/Y H:i') }}
                            </p>

                            <p class="text-sm text-gray-600">
                                Catatan Guru: {{ $app->teacher_note ?? '-' }}
                            </p>

                            <div class="mt-2 flex gap-4">
                                @if($app->status === \App\Models\InternshipApplication::STATUS_APPROVED_BY_TEACHER)
                                    <a href="{{ route('admin.applications.assignForm', $app) }}"
                                       class="text-blue-600 underline text-sm">
                                        Tetapkan Penempatan
                                    </a>
                                @elseif($app->status === \App\Models\InternshipApplication::STATUS_ASSIGNED_BY_ADMIN)
                                    <a href="{{ route('admin.applications.assignForm', $app) }}"
                                       class="text-blue-600 underline text-sm">
                                        Lihat Penempatan
                                    </a>
                                @endif

                                @if($canPrint)
                                    <a href="{{ route('admin.applications.letter', ['application' => $app->id, 'print' => 1]) }}"
                                    class="text-blue-600 underline text-sm">
                                        Cetak Surat
                                    </a>
                                @endif
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-gray-600">
                            Tidak ada pengajuan pada daftar ini.
                        </p>
                    @endforelse


                    <div class="mt-4">
                        {{ $applications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
