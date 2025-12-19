<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verifikasi Pengajuan Prakerin') }}
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
                                {{ $app->student->user->name }} ({{ $app->student->major->name ?? '-' }})
                            </h3>
                            <p class="text-sm text-gray-600">
                                Industri: {{ $app->industry->name }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Diajukan: {{ $app->created_at->format('d/m/Y H:i') }}
                            </p>
                            <a href="{{ route('teacher.applications.show', $app) }}"
                               class="text-blue-600 underline text-sm">
                                Lihat detail & verifikasi
                            </a>
                        </div>
                    @empty
                        <p class="text-center text-gray-600">
                            Belum ada pengajuan yang menunggu verifikasi.
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
