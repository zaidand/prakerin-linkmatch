@php
    $user = auth()->user();
    $role = $user?->role->name ?? null; // admin, teacher, industry_supervisor, student, atau null jika guest
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo / Brand -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}">
                        <span class="font-bold text-blue-700 text-lg">
                            PRAKERIN YUPPENTEK 1
                            <img
                        src="{{ asset('images/yayasanbg.png') }}"
                        alt="Logo Yuppentek"
                        class="h-10 w-auto"
                    >
                        </span>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                @auth
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        {{-- Menu umum --}}
                        @if (Route::has('dashboard'))
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                Dashboard
                            </x-nav-link>
                        @endif

                        {{-- ================= ADMIN ================= --}}
                        @if ($role === 'admin')
                            @php
                                // dipakai untuk garis bawah aktif kalau salah satu menu master sedang dibuka
                                $masterActive =
                                    request()->routeIs('admin.majors.*') ||
                                    (request()->routeIs('admin.users.*') && in_array(request('role'), ['student','teacher'])) ||
                                    request()->routeIs('admin.industries.*');
                            @endphp

                            {{-- DATA MASTER (dropdown, sejajar dengan menu lain) --}}
                            <div x-data="{ openMaster: false }" class="relative flex items-center">
                                <button
                                    type="button"
                                    @click="openMaster = !openMaster"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition
                                        {{ $masterActive
                                            ? 'border-indigo-400 text-gray-900 focus:outline-none focus:border-indigo-700'
                                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300' }}"
                                >
                                    <span>Data Master</span>
                                    <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                {{-- DROPDOWN LINK --}}
                                <div
                                    x-cloak
                                    x-show="openMaster"
                                    @click.away="openMaster = false"
                                    x-transition
                                    class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 w-52 rounded-md shadow-lg bg-white ring-1 ring-black/5 z-50"
                                >
                                    <div class="py-1">
                                        {{-- Data Jurusan --}}
                                        @if (Route::has('admin.majors.index'))
                                            <a href="{{ route('admin.majors.index') }}"
                                            @click="openMaster = false"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Data Jurusan
                                            </a>
                                        @endif

                                        {{-- Data Siswa --}}
                                        @if (Route::has('admin.users.index'))
                                            <a href="{{ route('admin.users.index', ['role' => 'student']) }}"
                                            @click="openMaster = false"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Data Siswa
                                            </a>

                                            {{-- Data Guru Pembimbing --}}
                                            <a href="{{ route('admin.users.index', ['role' => 'teacher']) }}"
                                            @click="openMaster = false"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Data Guru Pembimbing
                                            </a>
                                        @endif

                                        {{-- Data Industri --}}
                                        @if (Route::has('admin.industries.index'))
                                            <a href="{{ route('admin.industries.index') }}"
                                            @click="openMaster = false"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Data Industri
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- MENU ADMIN LAINNYA TETAP --}}
                            @if (Route::has('admin.users.index'))
                                {{-- Manajemen Akun = semua user non-admin (tanpa filter role=...) --}}
                                <x-nav-link :href="route('admin.users.index')"
                                            :active="request()->routeIs('admin.users.index') && ! request()->has('role')">
                                    Manajemen Akun
                                </x-nav-link>
                            @endif

                            @if (Route::has('admin.applications.index'))
                                <x-nav-link :href="route('admin.applications.index')" :active="request()->routeIs('admin.applications.*')">
                                    Pengajuan Prakerin
                                </x-nav-link>
                            @endif

                            @if (Route::has('admin.final_grades.index'))
                                <x-nav-link :href="route('admin.final_grades.index')" :active="request()->routeIs('admin.final_grades.*')">
                                    Rekap Nilai
                                </x-nav-link>
                            @endif
                        @endif

                        {{-- ================= GURU PEMBIMBING ================= --}}
                        @if ($role === 'teacher')
                            @if (Route::has('teacher.applications.index'))
                                <x-nav-link :href="route('teacher.applications.index')" :active="request()->routeIs('teacher.applications.*')">
                                    Verifikasi Pengajuan
                                </x-nav-link>
                            @endif

                            @if (Route::has('teacher.monitoring.index'))
                                <x-nav-link :href="route('teacher.monitoring.index')" :active="request()->routeIs('teacher.monitoring.*')">
                                    Monitoring Siswa
                                </x-nav-link>
                            @endif

                            @if (Route::has('teacher.final_grades.index'))
                                <x-nav-link :href="route('teacher.final_grades.index')" :active="request()->routeIs('teacher.final_grades.*')">
                                    Nilai Akhir
                                </x-nav-link>
                            @endif
                        @endif

                        {{-- ================= PEMBIMBING LAPANGAN ================= --}}
                        @if ($role === 'industry_supervisor')
                            @if (Route::has('industry.profile.edit'))
                                <x-nav-link :href="route('industry.profile.edit')" :active="request()->routeIs('industry.profile.*')">
                                    Profil Industri
                                </x-nav-link>
                            @endif

                            @if (Route::has('industry.quotas.index'))
                                <x-nav-link :href="route('industry.quotas.index')" :active="request()->routeIs('industry.quotas.*')">
                                    Kuota Prakerin
                                </x-nav-link>
                            @endif

                            @if (Route::has('industry.applications.index'))
                                <x-nav-link :href="route('industry.applications.index')" :active="request()->routeIs('industry.applications.*')">
                                    Pengajuan Siswa
                                </x-nav-link>
                            @endif

                            @if (Route::has('industry.confirmations.index'))
                                <x-nav-link :href="route('industry.confirmations.index')" :active="request()->routeIs('industry.confirmations.*')">
                                    Konfirmasi Siswa
                                </x-nav-link>
                            @endif

                            @if (Route::has('industry.logbooks.index'))
                                <x-nav-link :href="route('industry.logbooks.index')" :active="request()->routeIs('industry.logbooks.*')">
                                    Validasi Logbook
                                </x-nav-link>
                            @endif

                            @if (Route::has('industry.assessments.index'))
                                <x-nav-link :href="route('industry.assessments.index')" :active="request()->routeIs('industry.assessments.*')">
                                    Penilaian Siswa
                                </x-nav-link>
                            @endif
                        @endif

                        {{-- ================= SISWA ================= --}}
                        @if ($role === 'student')
                            @if (Route::has('student.industries.index'))
                                <x-nav-link :href="route('student.industries.index')" :active="request()->routeIs('student.industries.*')">
                                    Cari Tempat Prakerin
                                </x-nav-link>
                            @endif

                            @if (Route::has('student.applications.index'))
                                <x-nav-link :href="route('student.applications.index')" :active="request()->routeIs('student.applications.*')">
                                    Pengajuan Saya
                                </x-nav-link>
                            @endif

                            @if (Route::has('student.logbooks.index'))
                                <x-nav-link :href="route('student.logbooks.index')" :active="request()->routeIs('student.logbooks.*')">
                                    Logbook Harian
                                </x-nav-link>
                            @endif

                            @if (Route::has('student.final_report.index'))
                                <x-nav-link :href="route('student.final_report.index')" :active="request()->routeIs('student.final_report.*')">
                                    Laporan Akhir
                                </x-nav-link>
                            @endif
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Right Side: Auth / Guest Links -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    {{-- NOTIFIKASI LONCENG (DESKTOP) --}}
                    @php
                        $unreadCount = $user?->unreadNotifications()->count() ?? 0;
                        $latestNotifications = $user?->notifications()
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp

                    <div class="relative mr-4" x-data="{ notifOpen: false }">
                        <button
                            type="button"
                            @click="notifOpen = !notifOpen"
                            class="relative inline-flex items-center justify-center rounded-full p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none"
                        >
                            {{-- icon lonceng --}}
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002
                                      6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165
                                      6 8.388 6 11v3.159c0 .538-.214 1.055-.595
                                      1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>

                            {{-- badge jumlah --}}
                            @if($unreadCount > 0)
                                <span class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center
                                             px-1.5 py-0.5 text-[10px] font-bold leading-none
                                             text-white bg-red-600 rounded-full">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>

                        {{-- dropdown notifikasi --}}
                        <div
                            x-show="notifOpen"
                            @click.outside="notifOpen = false"
                            x-transition
                            class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-md shadow-lg z-50"
                        >
                            <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
                                <span class="font-semibold text-sm text-gray-700">Notifikasi</span>
                                @if($unreadCount > 0)
                                    <span class="text-xs text-red-600 font-semibold">
                                        {{ $unreadCount }} belum dibaca
                                    </span>
                                @endif
                            </div>

                            @if($latestNotifications->isEmpty())
                                <div class="px-4 py-3 text-sm text-gray-500">
                                    Belum ada notifikasi.
                                </div>
                            @else
                                <ul class="max-h-64 overflow-y-auto">
                                    @foreach($latestNotifications as $notification)
                                        @php
                                            $data = $notification->data ?? [];
                                            $message = $data['message'] ?? 'Status pengajuan prakerin Anda berubah.';
                                            $created = $notification->created_at?->format('d M Y H:i');
                                            $unread = is_null($notification->read_at);
                                        @endphp
                                        <li class="px-4 py-3 text-sm {{ $unread ? 'bg-gray-50' : 'bg-white' }} border-b border-gray-100 last:border-b-0">
                                            <p class="text-gray-800">{{ $message }}</p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $created }}
                                                @if($unread)
                                                    · <span class="text-green-600 font-semibold">Belum dibaca</span>
                                                @endif
                                            </p>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                    {{-- AKHIR NOTIFIKASI LONCENG --}}

                    <!-- User Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                <div>{{ $user->name ?? 'User' }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Profile -->
                            @if (Route::has('profile.edit'))
                                <x-dropdown-link href="{{ route('profile.edit') }}">
                                    Profil
                                </x-dropdown-link>
                            @endif

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                                 onclick="event.preventDefault(); this.closest('form').submit();">
                                    Keluar
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    {{-- Guest (belum login): tampilkan tombol Login dan Register --}}
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}"
                           class="text-sm text-gray-700 underline mr-4">
                            Masuk
                        </a>
                    @endif

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="text-sm text-gray-700 underline">
                            Daftar
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Hamburger (mobile) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500
                               hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500
                               transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Menu (Mobile) -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        @auth
            <div class="pt-2 pb-3 space-y-1">
                {{-- Dashboard --}}
                @if (Route::has('dashboard'))
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-responsive-nav-link>
                @endif

                            {{-- ADMIN --}}
            @if ($role === 'admin')
                @php
                    $masterActiveMobile =
                        request()->routeIs('admin.majors.*') ||
                        request()->routeIs('admin.users.*') ||
                        request()->routeIs('admin.industries.*');
                @endphp

                {{-- DATA MASTER (mobile, collapsible) --}}
                <div x-data="{ openMasterMobile: {{ $masterActiveMobile ? 'true' : 'false' }} }" class="mt-2">
                    <button
                        type="button"
                        @click="openMasterMobile = !openMasterMobile"
                        class="w-full flex items-center justify-between px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none"
                    >
                        <span>Data Master</span>
                        <svg
                            :class="{ 'transform rotate-180': openMasterMobile }"
                            class="h-4 w-4 transition-transform"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="openMasterMobile" x-transition class="mt-1 space-y-1">
                        {{-- Data Jurusan --}}
                        @if (Route::has('admin.majors.index'))
                            <x-responsive-nav-link
                                :href="route('admin.majors.index')"
                                :active="request()->routeIs('admin.majors.*')"
                            >
                                Data Jurusan
                            </x-responsive-nav-link>
                        @endif

                        {{-- Data Siswa --}}
                        @if (Route::has('admin.users.index'))
                            <x-responsive-nav-link
                                :href="route('admin.users.index', ['role' => 'student'])"
                                :active="request()->routeIs('admin.users.index') && request('role') === 'student'">

                                Data Siswa
                            </x-responsive-nav-link>

                            {{-- Data Guru Pembimbing --}}
                            <x-responsive-nav-link
                                :href="route('admin.users.index', ['role' => 'teacher'])"
                                :active="request()->routeIs('admin.users.index') && request('role') === 'teacher'">

                                Data Guru Pembimbing
                            </x-responsive-nav-link>
                        @endif

                        {{-- Data Industri --}}
                        @if (Route::has('admin.industries.index'))
                            <x-responsive-nav-link
                                :href="route('admin.industries.index')"
                                :active="request()->routeIs('admin.industries.*')"
                            >
                                Data Industri
                            </x-responsive-nav-link>
                        @endif
                    </div>
                </div>

                {{-- MENU ADMIN LAIN (di luar Data Master) --}}
                @if (Route::has('admin.users.index'))
                    <x-responsive-nav-link
                        :href="route('admin.users.index')"
                        :active="request()->routeIs('admin.users.*') && !request()->has('role')"
                    >
                        Manajemen Akun
                    </x-responsive-nav-link>
                @endif

                @if (Route::has('admin.applications.index'))
                    <x-responsive-nav-link
                        :href="route('admin.applications.index')"
                        :active="request()->routeIs('admin.applications.*')"
                    >
                        Pengajuan Prakerin
                    </x-responsive-nav-link>
                @endif

                @if (Route::has('admin.final_grades.index'))
                    <x-responsive-nav-link
                        :href="route('admin.final_grades.index')"
                        :active="request()->routeIs('admin.final_grades.*')"
                    >
                        Rekap Nilai
                    </x-responsive-nav-link>
                @endif
            @endif


                {{-- GURU (mobile) --}}
                @if ($role === 'teacher')
                    @if (Route::has('teacher.applications.index'))
                        <x-responsive-nav-link :href="route('teacher.applications.index')" :active="request()->routeIs('teacher.applications.*')">
                            Verifikasi Pengajuan
                        </x-responsive-nav-link>
                    @endif
                    @if (Route::has('teacher.monitoring.index'))
                        <x-responsive-nav-link :href="route('teacher.monitoring.index')" :active="request()->routeIs('teacher.monitoring.*')">
                            Monitoring Siswa
                        </x-responsive-nav-link>
                    @endif
                    @if (Route::has('teacher.final_grades.index'))
                        <x-responsive-nav-link :href="route('teacher.final_grades.index')" :active="request()->routeIs('teacher.final_grades.*')">
                            Nilai Akhir
                        </x-responsive-nav-link>
                    @endif
                @endif

                {{-- PEMBIMBING LAPANGAN (mobile) --}}
                @if ($role === 'industry_supervisor')
                    @if (Route::has('industry.profile.edit'))
                        <x-responsive-nav-link :href="route('industry.profile.edit')" :active="request()->routeIs('industry.profile.*')">
                            Profil Industri
                        </x-responsive-nav-link>
                    @endif
                    @if (Route::has('industry.quotas.index'))
                        <x-responsive-nav-link :href="route('industry.quotas.index')" :active="request()->routeIs('industry.quotas.*')">
                            Kuota Prakerin
                        </x-responsive-nav-link>
                    @endif
                    @if (Route::has('industry.applications.index'))
                        <x-responsive-nav-link :href="route('industry.applications.index')" :active="request()->routeIs('industry.applications.*')">
                            Pengajuan Siswa
                        </x-responsive-nav-link>
                    @endif
                    @if (Route::has('industry.confirmations.index'))
                        <x-responsive-nav-link :href="route('industry.confirmations.index')" :active="request()->routeIs('industry.confirmations.*')">
                            Konfirmasi Siswa
                        </x-responsive-nav-link>
                    @endif
                    @if (Route::has('industry.logbooks.index'))
                        <x-responsive-nav-link :href="route('industry.logbooks.index')" :active="request()->routeIs('industry.logbooks.*')">
                            Validasi Logbook
                        </x-responsive-nav-link>
                    @endif
                    @if (Route::has('industry.assessments.index'))
                        <x-responsive-nav-link :href="route('industry.assessments.index')" :active="request()->routeIs('industry.assessments.*')">
                            Penilaian Siswa
                        </x-responsive-nav-link>
                    @endif
                @endif

                {{-- SISWA (mobile) --}}
                @if ($role === 'student')
                    @if (Route::has('student.industries.index'))
                        <x-responsive-nav-link :href="route('student.industries.index')" :active="request()->routeIs('student.industries.*')">
                            Cari Tempat Prakerin
                        </x-responsive-nav-link>
                    @endif
                    @if (Route::has('student.applications.index'))
                        <x-responsive-nav-link :href="route('student.applications.index')" :active="request()->routeIs('student.applications.*')">
                            Pengajuan Saya
                        </x-responsive-nav-link>
                    @endif
                    @if (Route::has('student.logbooks.index'))
                        <x-responsive-nav-link :href="route('student.logbooks.index')" :active="request()->routeIs('student.logbooks.*')">
                            Logbook Harian
                        </x-responsive-nav-link>
                    @endif
                    @if (Route::has('student.final_report.index'))
                        <x-responsive-nav-link :href="route('student.final_report.index')" :active="request()->routeIs('student.final_report.*')">
                            Laporan Akhir
                        </x-responsive-nav-link>
                    @endif
                @endif
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ $user->name ?? 'User' }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ $user->email ?? '' }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    @if (Route::has('profile.edit'))
                        <x-responsive-nav-link href="{{ route('profile.edit') }}">
                            Profil
                        </x-responsive-nav-link>
                    @endif

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link href="{{ route('logout') }}"
                                               onclick="event.preventDefault(); this.closest('form').submit();">
                            Keluar
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            {{-- Guest (mobile) --}}
            <div class="pt-2 pb-3 space-y-1">
                @if (Route::has('login'))
                    <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                        Masuk
                    </x-responsive-nav-link>
                @endif

                @if (Route::has('register'))
                    <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                        Daftar
                    </x-responsive-nav-link>
                @endif
            </div>
        @endauth
    </div>
</nav>
