<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengajuan Menunggu Penetapan Admin') }}
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
                                \App\Models\InternshipApplication::STATUS_APPROVED_BY_TEACHER,
                                \App\Models\InternshipApplication::STATUS_ASSIGNED_BY_ADMIN,
                                \App\Models\InternshipApplication::STATUS_ACCEPTED,
                            ]);
                        @endphp

                        <div class="border-b py-4">
                            <h3 class="font-semibold text-lg">
                                {{ $app->student->user->name }} ({{ $app->student->major->name ?? '-' }})
                            </h3>

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
                                <a href="{{ route('admin.applications.assignForm', $app) }}"
                                class="text-blue-600 underline text-sm">
                                    Tetapkan Penempatan
                                </a>

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
                            Tidak ada pengajuan yang menunggu penetapan.
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
