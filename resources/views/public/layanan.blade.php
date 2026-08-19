<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Standar Pelayanan Publik | DKUPP Kabupaten Probolinggo</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex flex-col min-h-screen selection:bg-emerald-600 selection:text-white" 
      x-data="{ mobileMenu: false, highContrast: false, fontSize: 100, activeModal: null }"
      :class="{ 'high-contrast': highContrast }"
      :style="`font-size: ${fontSize}%`">

    @include('partials.public_header')

    <main class="flex-grow py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 w-full">
        
        <!-- Tombol Kembali ke Beranda Utama -->
        <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-emerald-600 text-slate-700 hover:text-white border border-slate-200 hover:border-emerald-600 rounded-full font-extrabold text-xs shadow-xs transition-all hover:scale-105 group">
                <i class="fas fa-arrow-left text-xs text-emerald-600 group-hover:text-white transition-transform group-hover:-translate-x-0.5"></i>
                <span>Kembali ke Beranda Utama</span>
            </a>
            <span class="text-xs text-slate-400 font-semibold hidden sm:inline">Standar Pelayanan Publik DKUPP</span>
        </div>

        <!-- Header Title -->
        <div class="text-center max-w-2xl mx-auto space-y-2.5">
            <span class="px-3.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-[11px] font-extrabold uppercase tracking-wider">Mal Pelayanan Publik Kraksaan</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Standar Pelayanan Publik DKUPP</h1>
            <p class="text-xs text-slate-500">Informasi syarat, prosedur, biaya, dan waktu penyelesaian pelayanan publik sektor Koperasi, UMKM, Perdagangan & Perindustrian.</p>
        </div>

        <!-- Filter Tabs (Sub-Menu Pelayanan Sesuai Foto 2) -->
        <div class="flex items-center justify-center flex-wrap gap-2 pt-2">
            <a href="{{ route('layanan') }}" 
               class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all shadow-2xs {{ empty($activeSlug) ? 'bg-emerald-700 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                <i class="fas fa-th-large me-1"></i> Semua Layanan
            </a>
            @foreach($allServices as $srv)
                <a href="{{ route('layanan', ['slug' => $srv->slug]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all shadow-2xs {{ $activeSlug == $srv->slug ? 'bg-emerald-700 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                    <i class="fas {{ $srv->icon }} me-1"></i> {{ $srv->title }}
                </a>
            @endforeach
        </div>

        @if(!empty($activeSlug))
            <div class="flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-2xl p-4 text-xs">
                <span class="text-emerald-900 font-bold flex items-center gap-2">
                    <i class="fas fa-check-circle text-emerald-600"></i> Menampilkan Layanan Spesifik: <strong class="font-extrabold text-emerald-950">{{ $services->first()->title ?? '' }}</strong>
                </span>
                <a href="{{ route('layanan') }}" class="text-emerald-700 hover:text-emerald-900 font-extrabold underline">
                    Tampilkan Semua Layanan
                </a>
            </div>
        @endif

        <!-- Services Grid (Tampilan Card Sesuai Foto 1) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($services as $service)
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl shrink-0 shadow-inner">
                            <i class="fas {{ $service->icon }}"></i>
                        </div>
                        <span class="text-[10px] font-extrabold text-emerald-700 uppercase tracking-widest block">{{ $service->category }}</span>
                        <h3 class="font-extrabold text-slate-900 text-lg leading-snug">{{ $service->title }}</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $service->summary }}</p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 space-y-3 text-xs">
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Biaya Layanan:</span>
                            <strong class="text-emerald-700 font-extrabold text-xs">{{ $service->cost }}</strong>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Waktu Proses:</span>
                            <strong class="text-slate-800 font-extrabold text-xs">{{ $service->service_time }}</strong>
                        </div>
                        <div class="pt-2">
                            @if($service->external_url && filter_var($service->external_url, FILTER_VALIDATE_URL))
                                <a href="{{ $service->external_url }}" target="_blank" rel="noopener noreferrer" 
                                   class="w-full py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold rounded-2xl text-xs flex items-center justify-center gap-2 shadow-sm transition-all hover:scale-[1.02]">
                                    <span>Buka Portal Web Resmi</span> <i class="fas fa-external-link-alt text-[10px]"></i>
                                </a>
                            @else
                                <button @click="activeModal = {{ $service->id }}" 
                                        class="w-full py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold rounded-2xl text-xs flex items-center justify-center gap-2 shadow-sm transition-all hover:scale-[1.02]">
                                    <span>Lihat Persyaratan & SOP</span> <i class="fas fa-arrow-right text-[10px]"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-3xl border border-slate-200 p-8 space-y-3">
                    <i class="fas fa-folder-open text-4xl text-slate-300"></i>
                    <p class="text-sm font-semibold text-slate-600">Layanan tidak ditemukan.</p>
                    <a href="{{ route('layanan') }}" class="inline-block px-4 py-2 bg-emerald-700 text-white font-bold rounded-xl text-xs">Lihat Semua Layanan</a>
                </div>
            @endforelse
        </div>

    </main>

    <!-- Modal Pop-Up untuk Persyaratan & SOP Detail -->
    @foreach($allServices as $service)
        <div x-show="activeModal === {{ $service->id }}" 
             x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs">
            
            <div @click.away="activeModal = null" class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 border border-slate-200 shadow-2xl space-y-6 relative max-h-[90vh] overflow-y-auto">
                <button @click="activeModal = null" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors">
                    <i class="fas fa-times"></i>
                </button>

                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl shrink-0 shadow-inner">
                        <i class="fas {{ $service->icon }}"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-emerald-700 uppercase tracking-widest block">{{ $service->category }}</span>
                        <h2 class="text-xl font-extrabold text-slate-900">{{ $service->title }}</h2>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-200 text-xs">
                    <div>
                        <span class="text-slate-500 block">Biaya Layanan:</span>
                        <strong class="text-emerald-800 font-extrabold text-xs sm:text-sm">{{ $service->cost }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-500 block">Waktu Proses:</span>
                        <strong class="text-slate-800 font-extrabold text-xs sm:text-sm">{{ $service->service_time }}</strong>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <span class="text-slate-500 block">Lokasi Pelayanan:</span>
                        <strong class="text-slate-800 font-extrabold text-xs sm:text-sm">Loket MPP Kraksaan</strong>
                    </div>
                </div>

                <div class="space-y-3">
                    <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-list-check text-emerald-700"></i> Persyaratan Dokumen
                    </h3>
                    <div class="prose text-xs text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-200">
                        {!! $service->requirements !!}
                    </div>
                </div>

                <div class="space-y-3">
                    <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-route text-emerald-700"></i> Prosedur & Alur Pelayanan
                    </h3>
                    <div class="prose text-xs text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-200">
                        {!! $service->procedure !!}
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 flex justify-end">
                    <button @click="activeModal = null" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs">
                        Tutup SOP
                    </button>
                </div>
            </div>
        </div>
    @endforeach

    <footer class="bg-slate-950 text-slate-400 py-6 text-xs text-center border-t border-slate-800">
        <p>DKUPP Kabupaten Probolinggo © 2026</p>
    </footer>
</body>
</html>
