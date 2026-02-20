<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMK YUPPENTEK 1 Kota Tangerang</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 selection:bg-blue-900 selection:text-white">

    <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-300 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/pentekbg.png') }}" alt="Logo SMK YUPPENTEK 1" class="h-12 w-auto">
                    <div class="flex flex-col">
                        <span class="font-bold text-lg leading-tight text-slate-900">SMK YUPPENTEK 1</span>
                        <span class="text-xs font-medium text-slate-500">Kota Tangerang</span>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-900 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-900 transition px-3 py-2 rounded-lg hover:bg-slate-100">Login</a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold bg-blue-950 text-white px-5 py-2.5 rounded-xl hover:bg-blue-900 hover:shadow-lg hover:shadow-blue-900/20 transition-all duration-300">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-20">

        <section class="relative overflow-hidden bg-white pt-24 pb-20">
            <div class="absolute inset-0 z-0 pointer-events-none opacity-40">
                <div class="absolute -top-[20%] -right-[10%] w-[50%] h-[50%] rounded-full bg-gradient-to-br from-blue-100 to-transparent blur-3xl"></div>
                <div class="absolute -bottom-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-gradient-to-tr from-sky-100 to-transparent blur-3xl"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-900 text-sm font-semibold mb-8 border border-blue-100">
                    <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                    PRAKERIN SMK YUPPENTEK 1 KOTA TANGERANG
                </div>

                <h1 class="text-4xl md:text-6xl font-extrabold text-slate-900 tracking-tight mb-6">
                    SMK Bisa, <br class="hidden sm:block" />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-950 via-sky-600 to-blue-900">
                        Yuppentek 1 Lebih Bisa.
                    </span>
                </h1>

                <p class="max-w-2xl mx-auto text-lg text-slate-600 mb-10 leading-relaxed">
                    Menjadi lembaga pendidikan dan pelatihan kejuruan yang unggul, berbudaya, serta menghasilkan lulusan yang kompeten, berakhlak mulia, dan siap bersaing di dunia kerja maupun berwirausaha.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-blue-950 text-white rounded-xl font-semibold shadow-lg shadow-blue-950/20 hover:bg-blue-900 hover:-translate-y-0.5 transition-all duration-300">
                        Daftar Sekarang
                    </a>
                    <a href="#jurusan" class="w-full sm:w-auto px-8 py-4 bg-white text-slate-700 rounded-xl font-semibold shadow-sm border border-slate-200 hover:bg-slate-50 hover:-translate-y-0.5 transition-all duration-300">
                        Lihat Jurusan
                    </a>
                </div>
            </div>
        </section>

        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 items-start">

                    <div class="lg:col-span-2">
                        <div class="sticky top-28">
                            <h2 class="text-3xl font-bold text-slate-900 mb-8 flex items-center gap-3">
                                Visi Kami
                                <div class="h-1 w-12 bg-yellow-400 rounded-full"></div>
                            </h2>

                            <div class="bg-gradient-to-br from-blue-950 to-blue-900 rounded-3xl p-8 md:p-10 text-white shadow-xl shadow-blue-900/20 relative overflow-hidden">
                                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                                <div class="absolute -left-10 -top-10 w-32 h-32 bg-sky-400/20 rounded-full blur-xl"></div>

                                <p class="text-xl md:text-2xl font-medium leading-relaxed relative z-10 text-blue-50">
                                    "Menjadi SMK pilihan utama yang mampu mencetak lulusan berkarakter, kompeten dan literat."
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-3">
                        <h2 class="text-3xl font-bold text-slate-900 mb-8 flex items-center gap-3">
                            Misi Kami
                            <div class="h-1 w-12 bg-red-500 rounded-full"></div>
                        </h2>

                        <div class="space-y-6">
                            <div class="flex gap-4 group">
                                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-blue-50 text-blue-900 font-bold flex items-center justify-center border border-blue-100 group-hover:bg-blue-900 group-hover:text-white transition-colors">
                                    1
                                </div>
                                <p class="text-slate-600 leading-relaxed pt-2">
                                    Menyelenggarakan Pendidikan kejuran/vokasi yang professional dan memiliki keunggulan merata pada semua program keahlian.
                                </p>
                            </div>

                            <div class="flex gap-4 group">
                                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-blue-50 text-blue-900 font-bold flex items-center justify-center border border-blue-100 group-hover:bg-blue-900 group-hover:text-white transition-colors">
                                    2
                                </div>
                                <div class="pt-2">
                                    <p class="text-slate-600 leading-relaxed mb-3">
                                        Membekali Peserta Didik sehingga:
                                    </p>
                                    <ul class="space-y-2">
                                        @foreach([
                                            'Beriman dan bertaqwa terhadap Tuhan Yang Maha Esa',
                                            'Berjiwa Pancasila dan cinta terhadap NKRI',
                                            'Cakap berkomunikasi dan adaptif terhadap lingkungan',
                                            'Kompeten dibidangnya sesuai dengan standar industri',
                                            'Entrepreneurship dan terampil mengelola diri dan orang lain',
                                            'Mampu memobilisasi inovasi dan iptek',
                                            'Terampil menyelesaikan tugas dan pekerjaan'
                                        ] as $item)
                                        <li class="flex items-start gap-2 text-sm text-slate-600">
                                            <svg class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span>{{ $item }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="flex gap-4 group">
                                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-blue-50 text-blue-900 font-bold flex items-center justify-center border border-blue-100 group-hover:bg-blue-900 group-hover:text-white transition-colors">
                                    3
                                </div>
                                <p class="text-slate-600 leading-relaxed pt-2">
                                    Menyelenggarakan pembelajaran berbasis teknologi informasi.
                                </p>
                            </div>

                            <div class="flex gap-4 group">
                                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-blue-50 text-blue-900 font-bold flex items-center justify-center border border-blue-100 group-hover:bg-blue-900 group-hover:text-white transition-colors">
                                    4
                                </div>
                                <p class="text-slate-600 leading-relaxed pt-2">
                                    Menjadi pusat informasi lowongan kerja, pelaksana pemasaran dan penyaluran tenaga kerja.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section id="jurusan" class="py-24 bg-slate-50 border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl font-bold text-slate-900 mb-4">Program Keahlian</h2>
                    <div class="h-1 w-20 bg-gradient-to-r from-red-500 via-yellow-400 to-blue-500 mx-auto rounded-full mb-4"></div>
                    <p class="text-slate-600">Kami menawarkan 7 program keahlian yang disesuaikan dengan kebutuhan industri masa kini, didukung fasilitas modern dan tenaga pengajar profesional.</p>
                </div>

                @php
                    // Data Jurusan
                    $jurusans = [
                        ['name' => 'Teknik Instalasi Tenaga Listrik', 'color' => 'bg-yellow-400', 'textColor' => 'text-yellow-600'],
                        ['name' => 'Teknik Mekanik Industri', 'color' => 'bg-slate-500', 'textColor' => 'text-slate-600'],
                        ['name' => 'Teknik Pemesinan', 'color' => 'bg-red-500', 'textColor' => 'text-red-600'],
                        ['name' => 'Teknik Kendaraan Ringan Otomotif', 'color' => 'bg-blue-950', 'textColor' => 'text-blue-950'],
                        ['name' => 'Teknik Bisnis Sepeda Motor', 'color' => 'bg-sky-500', 'textColor' => 'text-sky-600'],
                        ['name' => 'Teknik Komputer & Jaringan', 'color' => 'bg-emerald-500', 'textColor' => 'text-emerald-600'],
                        ['name' => 'Desain Komunikasi Visual', 'color' => 'bg-purple-500', 'textColor' => 'text-purple-600'],
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($jurusans as $index => $jurusan)
                        <div class="group bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 ease-out hover:-translate-y-1 relative overflow-hidden flex flex-col justify-between
                            {{ $index == 6 ? 'lg:col-start-2' : '' }}
                        ">
                            <div class="absolute top-0 left-0 w-full h-1 {{ $jurusan['color'] }} opacity-80 group-hover:opacity-100 transition-opacity"></div>

                            <div>
                                <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center mb-6 border border-slate-100 group-hover:scale-110 transition-transform duration-300">
                                    <span class="font-bold {{ $jurusan['textColor'] }}">{{ substr($jurusan['name'], 0, 1) }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 mb-2 leading-tight group-hover:text-blue-950 transition-colors">
                                    {{ $jurusan['name'] }}
                                </h3>
                                <p class="text-sm text-slate-500">
                                    Fokus pada keahlian praktis dan pemahaman mendalam sesuai standar kompetensi industri untuk siap kerja dan wirausaha.
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>

    </main>

    <footer class="bg-blue-950 border-t border-blue-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 items-start">

                <div class="flex flex-col items-center md:items-start text-center md:text-left">
                    <div class="flex items-center gap-3 mb-4 bg-white p-2 rounded-xl inline-flex">
                        <img src="{{ asset('images/pentekbg.png') }}" alt="Logo Footer" class="h-10 w-auto">
                    </div>
                    <h3 class="text-white font-bold text-lg mb-2">SMK YUPPENTEK 1 Kota Tangerang</h3>
                    <p class="text-blue-200 text-sm leading-relaxed">
                        Jl. Veteran No.1 Kel. Babakan Kec. Tangerang<br>
                        Kota Tangerang, Banten 15118
                    </p>
                </div>

                <div class="flex flex-col items-center text-center">
                    <h4 class="text-white font-semibold mb-5">Ikuti Kami</h4>
                    <div class="flex items-center gap-3">
                        <a href="https://web.facebook.com/Vtr1968/?_rdc=1&_rdr#" aria-label="Facebook" class="w-10 h-10 rounded-full bg-blue-900/50 flex items-center justify-center text-blue-200 hover:bg-[#1877F2] hover:text-white hover:-translate-y-1 transition-all duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                        </a>
                        <a href="https://www.instagram.com/smkyuppentek1vtr?igshid=ZDdkNTZiNTM%3D" aria-label="Instagram" class="w-10 h-10 rounded-full bg-blue-900/50 flex items-center justify-center text-blue-200 hover:bg-[#E4405F] hover:text-white hover:-translate-y-1 transition-all duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" /></svg>
                        </a>
                        <a href="#" aria-label="X (Twitter)" class="w-10 h-10 rounded-full bg-blue-900/50 flex items-center justify-center text-blue-200 hover:bg-slate-900 hover:text-white hover:-translate-y-1 transition-all duration-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://www.youtube.com/channel/UC7pEsbHG_t7yRE2PstBmc0A" aria-label="YouTube" class="w-10 h-10 rounded-full bg-blue-900/50 flex items-center justify-center text-blue-200 hover:bg-[#FF0000] hover:text-white hover:-translate-y-1 transition-all duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd" /></svg>
                        </a>
                    </div>
                </div>

                <div class="flex flex-col items-center md:items-end text-center md:text-right">
                    <h4 class="text-white font-semibold mb-5">Hubungi Kami</h4>
                    <a href="mailto:smkyuppentek1vtr@gmail.com" class="text-blue-200 hover:text-white text-sm mb-3 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        smkyuppentek1vtr@gmail.com
                    </a>
                    <a href="tel:0215524518" class="text-blue-200 hover:text-white text-sm transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        021-5524518
                    </a>
                </div>
            </div>

            <div class="border-t border-blue-900/50 mt-12 pt-8 text-center flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-blue-400 text-sm">
                    &copy; {{ date('Y') }} SMK YUPPENTEK 1 Kota Tangerang.
                </p>
                <p class="text-blue-400/70 text-xs">
                    Designed for Future Generation.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
