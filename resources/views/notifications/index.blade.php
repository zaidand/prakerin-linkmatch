<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifikasi') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($notifications->isEmpty())
                        <p class="text-gray-600">Belum ada notifikasi.</p>
                    @else
                        <ul class="divide-y">
                            @foreach($notifications as $notification)
                                @php
                                    $data = $notification->data;
                                @endphp
                                <li class="py-3 flex justify-between items-start {{ is_null($notification->read_at) ? 'bg-gray-50' : '' }}">
                                    <div>
                                        <p class="font-semibold text-sm">
                                            {{ $data['message'] ?? 'Perubahan status pengajuan prakerin.' }}
                                        </p>
                                        <p class="text-xs text-gray-600 mt-1">
                                            @if(isset($data['industry_name']))
                                                Industri: {{ $data['industry_name'] }}
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $notification->created_at->format('d M Y H:i') }}
                                            @if(is_null($notification->read_at))
                                                · <span class="text-green-600 font-semibold">Belum dibaca</span>
                                            @endif
                                        </p>
                                    </div>

                                    @if(is_null($notification->read_at))
                                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="text-xs px-3 py-1 border rounded text-gray-700 hover:bg-gray-100">
                                                Tandai sudah dibaca
                                            </button>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
