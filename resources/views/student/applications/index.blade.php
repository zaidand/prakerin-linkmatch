<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengajuan Prakerin Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @forelse($applications as $app)
                        <div class="border-b py-4">
                            <h3 class="font-semibold text-lg">
                                {{ $app->industry->name }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                Status:
                                <strong>
                                    @switch($app->status)
                                        @case('waiting_teacher') Menunggu verifikasi Guru @break
                                        @case('revision') Perlu revisi @break
                                        @case('waiting_admin') Menunggu penetapan Admin @break
                                        @case('waiting_industry') Menunggu konfirmasi Industri @break
                                        @case('accepted') Diterima Industri @break
                                        @case('rejected') Ditolak @break
                                    @endswitch
                                </strong>
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                Diajukan pada: {{ $app->created_at->format('d/m/Y H:i') }}
                            </p>
                            @if($app->teacher_note)
                                <p class="text-sm text-gray-700 mt-1">
                                    Catatan Guru: {{ $app->teacher_note }}
                                </p>
                            @endif
                            @if($app->admin_note)
                                <p class="text-sm text-gray-700 mt-1">
                                    Catatan Admin: {{ $app->admin_note }}
                                </p>
                            @endif
                            @if($app->industry_note)
                                <p class="text-sm text-gray-700 mt-1">
                                    Catatan Industri: {{ $app->industry_note }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-600 text-center">
                            Belum ada pengajuan prakerin.
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
