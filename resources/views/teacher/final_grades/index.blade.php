<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rekap Nilai Akhir Prakerin') }}
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
                                    Industri: {{ $app->industry->name }}
                                </p>
                                @if($app->finalGrade)
                                    <p class="text-sm">
                                        Nilai Akhir: {{ $app->finalGrade->final_score }}
                                        ({{ $app->finalGrade->grade_letter }})
                                    </p>
                                @else
                                    <p class="text-sm text-red-600">
                                        Belum ada nilai akhir.
                                    </p>
                                @endif
                            </div>
                            <div class="text-right">
                                <a href="{{ route('teacher.final_grades.edit', $app) }}"
                                   class="text-blue-600 underline text-sm">
                                    Kelola Nilai
                                </a>
                            </div>

                            @if($app->finalReport)
                                <p class="text-sm">
                                    <a href="{{ route('final_reports.file', $app->finalReport) }}"
                                    target="_blank"
                                    class="text-blue-600 underline">
                                        Lihat Laporan
                                    </a>
                                </p>
                            @endif

                        </div>
                    @empty
                        <p class="text-center text-gray-600">
                            Belum ada data siswa prakerin.
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
