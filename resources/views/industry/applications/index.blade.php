<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Konfirmasi Siswa Prakerin - '.$industry->name) }}
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
                        <div class="border-b py-4">
                            <h3 class="font-semibold text-lg">
                                {{ $app->student->user->name }} ({{ $app->student->major->name ?? '-' }})
                            </h3>
                            @if($app->quota)
                                <p class="text-sm text-gray-600">
                                    Periode: {{ $app->quota->start_date->format('d/m/Y') }} -
                                    {{ $app->quota->end_date->format('d/m/Y') }}
                                </p>
                            @endif
                            <p class="text-sm text-gray-600">
                                Motivasi siswa: {{ $app->interest }}
                            </p>

                            <form action="{{ route('industry.applications.confirm', $app) }}"
                                  method="POST" class="mt-3 space-y-2">
                                @csrf
                                <label class="block mb-1 text-sm">Catatan / Feedback (opsional)</label>
                                <textarea name="industry_note" rows="2"
                                          class="border px-3 py-2 w-full">{{ old('industry_note') }}</textarea>

                                <div class="flex gap-2 mt-2">
                                    <button type="submit" name="action" value="accept"
                                            class="px-4 py-2 bg-green-600 text-white rounded text-sm">
                                        Terima
                                    </button>
                                    <button type="submit" name="action" value="reject"
                                            class="px-4 py-2 bg-red-600 text-white rounded text-sm">
                                        Tolak
                                    </button>
                                </div>
                            </form>
                        </div>
                    @empty
                        <p class="text-center text-gray-600">
                            Belum ada siswa yang menunggu konfirmasi.
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
