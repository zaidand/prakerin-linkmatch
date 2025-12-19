<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Siswa Prakerin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @forelse($applications as $app)
                        <div class="border-b py-4">
                            <h3 class="font-semibold text-lg">
                                {{ $app->student->user->name }}
                                ({{ $app->student->major->name ?? '-' }})
                            </h3>
                            <p class="text-sm text-gray-600">
                                Industri: {{ $app->industry->name }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Status: Diterima industri
                            </p>
                            <a href="{{ route('teacher.monitoring.show', $app) }}"
                               class="text-blue-600 underline text-sm">
                                Lihat logbook & catatan monitoring
                            </a>
                        </div>
                    @empty
                        <p class="text-center text-gray-600">
                            Belum ada siswa yang sedang prakerin / belum terdata.
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
