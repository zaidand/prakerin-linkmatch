<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penilaian Siswa Prakerin - '.$industry->name) }}
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
                        <div class="border-b py-4 flex justify-between">
                            <div>
                                <h3 class="font-semibold">
                                    {{ $app->student->user->name }}
                                    ({{ $app->student->major->name ?? '-' }})
                                </h3>
                                <p class="text-sm text-gray-600">
                                    Periode prakerin:
                                    @if($app->quota)
                                        {{ $app->quota->start_date->translatedFormat('d F Y') }}
                                        – {{ $app->quota->end_date->translatedFormat('d F Y') }}
                                    @else
                                        <span class="italic text-gray-500">Belum dijadwalkan</span>
                                    @endif
                                </p>
                                @if($app->industryAssessment)
                                    <p class="text-sm">
                                        Nilai Industri: {{ $app->industryAssessment->overall_score }}
                                    </p>
                                @endif
                            </div>
                            <div class="text-right">
                                <a href="{{ route('industry.assessments.edit', $app) }}"
                                   class="text-blue-600 underline text-sm">
                                    Isi / Edit Penilaian
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-600">
                            Belum ada siswa yang perlu dinilai.
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
