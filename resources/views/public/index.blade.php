<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $settings['site_title'] ?? 'Website Resmi | DKUPP Kabupaten Probolinggo' }}</title>
    <meta name="description" content="{{ $settings['site_description'] ?? 'Website Resmi Dinas Koperasi, Usaha Mikro, Perdagangan dan Perindustrian Kabupaten Probolinggo' }}">
    <link rel="icon" type="image/png" href="{{ $settings['logo_frontend'] ?? '' }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome & Tailwind CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#022c22',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .glass-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        .high-contrast { filter: contrast(150%) brightness(95%); }

        @keyframes runningLineAnim {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .animate-running-line {
            animation: runningLineAnim 1.2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-700 font-sans antialiased min-h-screen flex flex-col selection:bg-emerald-600 selection:text-white overflow-x-hidden w-full max-w-full"
      x-data="{ 
          mobileMenu: false, 
          highContrast: false, 
          fontSize: 100, 
          activeSlide: 0,
          activePhoto: null,
          totalSlides: {{ count($sliders ?? []) ?: 1 }},
          nextSlide() { this.activeSlide = (this.activeSlide + 1) % this.totalSlides },
          prevSlide() { this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides }
      }"
      :class="{ 'high-contrast': highContrast }"
      :style="`font-size: ${fontSize}%`"
      x-init="if(totalSlides > 1) setInterval(() => nextSlide(), 7000)">

    @include('partials.public_header')

    <!-- Main Content -->
    <main class="flex-grow">
        
        <!-- Hero Slider (Clean & 100% Clear Background Image) -->
        <section class="relative bg-slate-950 overflow-hidden min-h-[380px] sm:min-h-[460px] lg:min-h-[520px] flex items-center">
            @foreach($sliders as $index => $slide)
                <div x-show="activeSlide === {{ $index }}"
                     x-transition:enter="transition opacity duration-700 ease-out"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition opacity duration-500 ease-in"
                     class="absolute inset-0 w-full h-full">
                    
                    <!-- Background Banner Image (Full Brightness, No Dark Boxes) -->
                    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-transform duration-10000"
                         style="background-image: url('{{ $slide->image_url }}');">
                        <!-- Ultra subtle bottom gradient ONLY for text contrast without blocking photo -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/20"></div>
                    </div>

                    <!-- Content Container (Moved lower to the bottom of the banner) -->
                    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-end z-10 pb-10 sm:pb-12 pt-24 sm:pt-36">
                        <div class="max-w-xl text-white space-y-1.5 sm:space-y-2.5 drop-shadow-[0_2px_8px_rgba(0,0,0,0.9)] mb-2">
                            <!-- Minimalist Title with Animated Running Line -->
                            <div class="space-y-2.5">
                                <h1 class="text-lg sm:text-2xl font-extrabold tracking-tight text-white leading-snug drop-shadow-[0_2px_4px_rgba(0,0,0,0.9)]">
                                    {{ $slide->title }}
                                </h1>
                                
                                <!-- Garis Berjalan / Animated Running Line Under Banner Title -->
                                <div class="h-1 max-w-sm w-full bg-slate-800/80 rounded-full overflow-hidden relative shadow-md">
                                    <div class="h-full w-full bg-gradient-to-r from-emerald-500 via-teal-300 to-emerald-600 rounded-full animate-running-line shadow-lg"></div>
                                </div>

                                <!-- Minimalist Subtitle with Text Shadow -->
                                <p class="text-xs sm:text-sm text-white/90 font-bold leading-relaxed drop-shadow-[0_1px_3px_rgba(0,0,0,0.9)]">
                                    {{ $slide->subtitle }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Slider Controls (Minimalist Dots) -->
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
                @foreach($sliders as $index => $slide)
                    <button @click="activeSlide = {{ $index }}" class="h-2 rounded-full transition-all duration-300 shadow-md"
                            :class="activeSlide === {{ $index }} ? 'w-8 bg-emerald-500' : 'w-2 bg-white/60 hover:bg-white'"></button>
                @endforeach
            </div>
        </section>

        <!-- Sambutan Kadin (Lebih Bersih) -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row gap-12 items-center">
                    <div class="w-full lg:w-1/3 flex justify-center">
                        <div class="relative w-64">
                            <img src="{{ $settings['kadin_photo'] ?? 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=600&auto=format&fit=crop' }}" 
                                 alt="Kepala DKUPP" class="w-full h-80 object-cover rounded-2xl shadow-lg border border-slate-100">
                            <div class="absolute -bottom-4 inset-x-4 bg-white border border-slate-100 py-3 px-4 rounded-xl shadow-sm text-center">
                                <h4 class="font-bold text-sm text-slate-800">{{ $settings['kadin_name'] ?? 'Nama Kepala Dinas' }}</h4>
                                <p class="text-[10px] text-emerald-600 font-semibold uppercase mt-0.5">Kepala DKUPP</p>
                            </div>
                        </div>
                    </div>

                    <div class="w-full lg:w-2/3 space-y-5 text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 text-emerald-600 text-xs font-bold uppercase tracking-widest">
                            Sambutan Kepala Dinas
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 leading-tight">
                            Mendorong Pemberdayaan Koperasi dan UMKM Menuju Ekonomi Probolinggo Mandiri & Berdaya Saing
                        </h2>
                        <p class="text-slate-600 text-sm leading-relaxed max-w-3xl">
                            Selamat Datang di Portal Resmi <strong>Dinas Koperasi, Usaha Mikro, Perdagangan dan Perindustrian (DKUPP) Kabupaten Probolinggo</strong>. Kami berkomitmen menyajikan pelayanan publik prima, memberikan kepastian perlindungan konsumen melalui Metrologi Legal, memajukan kelembagaan Koperasi, serta memperluas akses pasar digital produk UMKM melalui platform <strong>SIMADU SAE</strong>.
                        </p>
                        <div class="pt-4 flex flex-wrap gap-4 items-center justify-center lg:justify-start">
                            <a href="{{ route('page', 'visi-misi') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold text-sm transition-colors">
                                Visi & Misi Kami <i class="fas fa-arrow-right ms-1 text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Integrated Key Portals Section (4 Portal Utama Dalam 1 Grid Responsive) -->
        @php 
            $defaultLogo = 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Lambang_Kabupaten_Probolinggo.jpg/440px-Lambang_Kabupaten_Probolinggo.jpg';

            $singleMarketUrl = \App\Models\SiteSetting::get('market_price_url', 'https://siskaperbapo.jatimprov.go.id/'); 
            $marketLogo = \App\Models\SiteSetting::get('market_price_logo', $defaultLogo);
            $isExternalMarket = filter_var($singleMarketUrl, FILTER_VALIDATE_URL);

            $simaduUrl = \App\Models\SiteSetting::get('simadu_sae_url', 'https://simadu.probolinggokab.go.id/');
            $simaduLogo = \App\Models\SiteSetting::get('simadu_sae_logo', $defaultLogo);
            $isExternalSimadu = filter_var($simaduUrl, FILTER_VALIDATE_URL);

            $ppidUrl = \App\Models\SiteSetting::get('ppid_url', '/halaman/ppid-dkupp');
            $ppidLogo = \App\Models\SiteSetting::get('ppid_logo', $defaultLogo);
            $ppidTitle = \App\Models\SiteSetting::get('ppid_title', 'Portal Layanan PPID DKUPP');
            $ppidDesc = \App\Models\SiteSetting::get('ppid_desc', 'Akses permohonan informasi publik, DIP, dan transparansi kinerja DKUPP.');
            $isExternalPpid = filter_var($ppidUrl, FILTER_VALIDATE_URL);

            $laporUrl = \App\Models\SiteSetting::get('lapor_sp4n_url', 'https://www.lapor.go.id/');
            $laporLogo = \App\Models\SiteSetting::get('lapor_sp4n_logo', $defaultLogo);
            $isExternalLapor = filter_var($laporUrl, FILTER_VALIDATE_URL);

            $waRawNum = $settings['whatsapp_number'] ?? '081234567890';
            $waUrl = $settings['hallosae_whatsapp_url'] ?? ($settings['whatsapp_url'] ?? '');
            $waTitle = $settings['whatsapp_title'] ?? 'Pengaduan hallosae';
            $waDesc = $settings['whatsapp_desc'] ?? 'Pengaduan & konsultasi cepat terhubung langsung ke WhatsApp Lapor Hallo SAE.';
            $waMsg = $settings['whatsapp_default_message'] ?? 'Halo Lapor Hallo SAE Kabupaten Probolinggo, saya ingin menyampaikan pengaduan.';
            $waLogo = $settings['whatsapp_logo'] ?? 'fab fa-whatsapp';
            
            if (empty($waUrl)) {
                $waClean = preg_replace('/[^0-9]/', '', $waRawNum);
                if (str_starts_with($waClean, '0')) {
                    $waClean = '62' . substr($waClean, 1);
                }
                $waUrl = 'https://wa.me/' . $waClean;
            }
            if (!str_contains($waUrl, 'text=')) {
                $waUrl .= (str_contains($waUrl, '?') ? '&' : '?') . 'text=' . urlencode($waMsg);
            }
        @endphp
        <section class="py-12 bg-slate-50 border-y border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <!-- Section Header -->
                <div class="text-center space-y-1 max-w-2xl mx-auto">
                    <span class="text-[11px] font-extrabold text-emerald-700 uppercase tracking-widest bg-emerald-100/80 px-3 py-1 rounded-full inline-block">
                        <i class="fas fa-cubes me-1"></i> Layanan & Portal Utama
                    </span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">Akses Cepat Portal Resmi DKUPP</h2>
                    <p class="text-xs text-slate-500">Integrasi pemantauan harga sembako, katalog produk UMKM SIMADU SAE, keterbukaan informasi PPID, dan Lapor Hallo SAE.</p>
                </div>

                <!-- 5 Portal Cards (Minimalis & 100% Responsif: 2-3 Kolom Mobile, 5 Kolom Desktop) -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                    <!-- Portal 1: Siskaperbapo Harga Pasar -->
                    <a href="{{ $singleMarketUrl }}" {{ $isExternalMarket ? 'target="_blank" rel="noopener noreferrer"' : '' }}
                       class="group bg-white p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl hover:border-amber-400 transition-all duration-300 flex flex-col justify-between space-y-3 hover:-translate-y-1">
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between gap-1.5">
                                <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-amber-50/80 border border-amber-200/60 shadow-2xs flex items-center justify-center group-hover:scale-105 transition-transform overflow-hidden p-1 shrink-0">
                                    @if(filter_var($marketLogo, FILTER_VALIDATE_URL) || str_starts_with($marketLogo, '/') || str_contains($marketLogo, '.'))
                                        <img src="{{ $marketLogo }}" alt="Simaduhttps://simadu.probolinggokab.go.id/assets/logo-ZIdY9hoJ.png" class="w-full h-full object-contain">
                                    @else
                                        <i class="{{ $marketLogo }} text-amber-600 text-sm sm:text-base"></i>
                                    @endif
                                </div>
                                <span class="text-[9px] sm:text-[10px] font-extrabold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md uppercase tracking-wider border border-amber-200/80 shrink-0">
                                    SIMADU SAE
                                </span>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-xs sm:text-sm group-hover:text-amber-700 transition-colors leading-snug line-clamp-1 sm:line-clamp-2">
                                    Pemantauan Harga Pokok
                                </h3>
                                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed line-clamp-2 hidden sm:block">
                                    Update harian harga komoditas pangan dari Pasar Kabupaten Probolinggo & Jatim.
                                </p>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] sm:text-xs font-bold text-emerald-700 group-hover:text-amber-700">
                            <span>Buka Portal</span>
                            <i class="fas fa-arrow-right text-[10px] sm:text-xs group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </a>

                    <!-- Portal 2: SIMADU SAE UMKM -->
                    <a href="{{ $simaduUrl }}" {{ $isExternalSimadu ? 'target="_blank" rel="noopener noreferrer"' : '' }}
                       class="group bg-white p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl hover:border-emerald-400 transition-all duration-300 flex flex-col justify-between space-y-3 hover:-translate-y-1">
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between gap-1.5">
                                <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-emerald-50/80 border border-emerald-200/60 shadow-2xs flex items-center justify-center group-hover:scale-105 transition-transform overflow-hidden p-1 shrink-0">
                                    @if(filter_var($simaduLogo, FILTER_VALIDATE_URL) || str_starts_with($simaduLogo, '/') || str_contains($simaduLogo, '.'))
                                        <img src="{{ $simaduLogo }}" alt="SIMADU SAE" class="w-full h-full object-contain">
                                    @else
                                        <i class="{{ $simaduLogo }} text-emerald-600 text-sm sm:text-base"></i>
                                    @endif
                                </div>
                                <span class="text-[9px] sm:text-[10px] font-extrabold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md uppercase tracking-wider border border-emerald-200/80 shrink-0">
                                    SIMADU SAE
                                </span>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-xs sm:text-sm group-hover:text-emerald-700 transition-colors leading-snug line-clamp-1 sm:line-clamp-2">
                                    Produk UMKM Unggulan
                                </h3>
                                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed line-clamp-2 hidden sm:block">
                                    Katalog produk pangan, kerajinan, fashion, dan toko online UMKM Probolinggo.
                                </p>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] sm:text-xs font-bold text-emerald-700">
                            <span>Katalog Web</span>
                            <i class="fas fa-arrow-right text-[10px] sm:text-xs group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </a>

                    <!-- Portal 3: PPID DKUPP -->
                    <a href="{{ $ppidUrl }}" {{ $isExternalPpid ? 'target="_blank" rel="noopener noreferrer"' : '' }}
                       class="group bg-white p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl hover:border-blue-400 transition-all duration-300 flex flex-col justify-between space-y-3 hover:-translate-y-1">
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between gap-1.5">
                                <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-blue-50/80 border border-blue-200/60 shadow-2xs flex items-center justify-center group-hover:scale-105 transition-transform overflow-hidden p-1 shrink-0">
                                    @if(filter_var($ppidLogo, FILTER_VALIDATE_URL) || str_starts_with($ppidLogo, '/') || str_contains($ppidLogo, '.'))
                                        <img src="{{ $ppidLogo }}" alt="PPID" class="w-full h-full object-contain">
                                    @else
                                        <i class="{{ $ppidLogo }} text-blue-600 text-sm sm:text-base"></i>
                                    @endif
                                </div>
                                <span class="text-[9px] sm:text-[10px] font-extrabold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md uppercase tracking-wider border border-blue-200/80 shrink-0">
                                    PPID
                                </span>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-xs sm:text-sm group-hover:text-blue-700 transition-colors leading-snug line-clamp-1 sm:line-clamp-2">
                                    {{ $ppidTitle }}
                                </h3>
                                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed line-clamp-2 hidden sm:block">
                                    {{ $ppidDesc }}
                                </p>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] sm:text-xs font-bold text-blue-700">
                            <span>Portal PPID</span>
                            <i class="fas fa-arrow-right text-[10px] sm:text-xs group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </a>

                    <!-- Portal 4: SP4N LAPOR! -->
                    <a href="{{ $laporUrl }}" {{ $isExternalLapor ? 'target="_blank" rel="noopener noreferrer"' : '' }}
                       class="group bg-white p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl hover:border-rose-400 transition-all duration-300 flex flex-col justify-between space-y-3 hover:-translate-y-1">
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between gap-1.5">
                                <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-rose-50/80 border border-rose-200/60 shadow-2xs flex items-center justify-center group-hover:scale-105 transition-transform overflow-hidden p-1 shrink-0">
                                    @if(!empty($laporLogo) && (str_starts_with($laporLogo, 'data:') || filter_var($laporLogo, FILTER_VALIDATE_URL) || str_starts_with($laporLogo, '/') || str_starts_with($laporLogo, 'http') || str_contains($laporLogo, '.')))
                                        <img src="{{ $laporLogo }}" alt="SP4N LAPOR!" class="w-full h-full object-contain" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='block';">
                                        <i class="fas fa-bullhorn text-rose-600 text-sm sm:text-base hidden"></i>
                                    @else
                                        <i class="{{ (!empty($laporLogo) && !str_contains($laporLogo, 'data:')) ? $laporLogo : 'fas fa-bullhorn' }} text-rose-600 text-sm sm:text-base"></i>
                                    @endif
                                </div>
                                <span class="text-[9px] sm:text-[10px] font-extrabold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-md uppercase tracking-wider border border-rose-200/80 shrink-0">
                                    SP4N LAPOR!
                                </span>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-xs sm:text-sm group-hover:text-rose-700 transition-colors leading-snug line-clamp-1 sm:line-clamp-2">
                                    SP4N LAPOR!
                                </h3>
                                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed line-clamp-2 hidden sm:block">
                                    Layanan pengaduan & aspirasi resmi masyarakat secara nasional.
                                </p>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] sm:text-xs font-bold text-rose-700">
                            <span>Buat Laporan</span>
                            <i class="fas fa-arrow-right text-[10px] sm:text-xs group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </a>

                    <!-- Portal 5: Pengaduan WhatsApp -->
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                       class="group bg-white p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl hover:border-emerald-500 transition-all duration-300 flex flex-col justify-between space-y-3 hover:-translate-y-1">
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between gap-1.5">
                                <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-emerald-50/80 border border-emerald-200/60 shadow-2xs flex items-center justify-center group-hover:scale-105 transition-transform shrink-0 overflow-hidden p-1">
                                    @if(!empty($waLogo) && (str_starts_with($waLogo, 'data:') || filter_var($waLogo, FILTER_VALIDATE_URL) || str_starts_with($waLogo, '/') || str_contains($waLogo, '.')))
                                        <img src="{{ $waLogo }}" alt="WhatsApp Logo" class="w-full h-full object-contain">
                                    @else
                                        <i class="{{ $waLogo ?: 'fab fa-whatsapp' }} text-emerald-600 text-lg sm:text-2xl"></i>
                                    @endif
                                </div>
                                <span class="text-[9px] sm:text-[10px] font-extrabold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-md uppercase tracking-wider border border-emerald-300/80 shrink-0">
                                    HaloSAE
                                </span>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-xs sm:text-sm group-hover:text-emerald-700 transition-colors leading-snug line-clamp-1 sm:line-clamp-2">
                                    {{ $waTitle }}
                                </h3>
                                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed line-clamp-2 hidden sm:block">
                                    {{ $waDesc }}
                                </p>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] sm:text-xs font-bold text-emerald-700">
                            <span>Chat WhatsApp</span>
                            <i class="fab fa-whatsapp text-[12px] group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <!-- Berita & Sidebar Minimalis (2 Kolom Mobile, 12 Kolom Desktop) -->
        <section class="py-8 sm:py-12 bg-slate-50 border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8">
                    
                    <!-- Left: Latest News (2 Kolom di Mobile) -->
                    <div class="lg:col-span-8 space-y-4 sm:space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-200/80 pb-2.5">
                            <h2 class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                                <i class="far fa-newspaper text-emerald-600"></i> Info Terkini
                            </h2>
                            <a href="{{ route('informasi') }}" class="text-[11px] sm:text-xs font-bold text-emerald-700 hover:underline flex items-center gap-1">
                                Lihat Semua <i class="fas fa-arrow-right text-[9px]"></i>
                            </a>
                        </div>

                        <div class="grid grid-cols-2 gap-3 sm:gap-5">
                            @foreach(collect($latestNews ?? [])->take(2) as $news)
                                <article class="group bg-white rounded-xl sm:rounded-2xl border border-slate-200/80 overflow-hidden shadow-2xs hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                                    <div>
                                        <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                                            <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            <span class="absolute top-2 right-2 bg-slate-900/85 text-white text-[8px] sm:text-[10px] font-bold px-1.5 py-0.5 rounded backdrop-blur-xs">
                                                {{ $news->category }}
                                            </span>
                                        </div>
                                        <div class="p-2.5 sm:p-4 space-y-1">
                                            <div class="text-[9px] sm:text-[11px] font-semibold text-slate-400 flex items-center gap-1">
                                                <i class="far fa-calendar text-emerald-500"></i> {{ \Carbon\Carbon::parse($news->published_at)->translatedFormat('d M Y') }}
                                            </div>
                                            <h3 class="font-extrabold text-xs sm:text-sm text-slate-900 leading-snug group-hover:text-emerald-600 transition-colors line-clamp-2">
                                                <a href="{{ route('news.detail', $news->slug) }}">{{ $news->title }}</a>
                                            </h3>
                                            <p class="text-[11px] sm:text-xs text-slate-500 line-clamp-2 leading-relaxed hidden sm:block">{{ $news->summary }}</p>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right Sidebar -->
                    <aside class="lg:col-span-4 w-full flex flex-col justify-start space-y-4 sm:space-y-5">
                        
                        <!-- KARTU 1: Maklumat Pelayanan (Tampilkan Gambar Poster Maklumat Langsung) -->
                        <div x-data="{ showMaklumatModal: false }" class="bg-white rounded-2xl border border-slate-200/80 p-3 sm:p-4 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between items-center text-center w-full space-y-2.5">
                            <!-- Direct Image Display of Maklumat Poster -->
                            <div class="w-full relative rounded-xl overflow-hidden bg-slate-900 border border-slate-200 group cursor-pointer" @click="showMaklumatModal = true">
                                <img src="{{ !empty($settings['maklumat_image']) ? $settings['maklumat_image'] : '/uploads/settings/maklumat_1787796518_maklumat-dkupp.jpeg' }}" 
                                     alt="Maklumat Pelayanan" 
                                     class="w-full h-44 sm:h-48 object-contain p-1 group-hover:scale-105 transition-transform duration-300">
                                
                                <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white p-2">
                                    <span class="px-3 py-1.5 bg-emerald-600/90 rounded-xl text-xs font-extrabold shadow-md flex items-center gap-1.5">
                                        <i class="fas fa-search-plus"></i> Perbesar Gambar Maklumat
                                    </span>
                                </div>
                            </div>

                            <!-- Quick Action Button -->
                            <button @click="showMaklumatModal = true" 
                                    class="inline-flex items-center justify-center gap-1.5 w-full py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition-all shadow-xs active:scale-95 cursor-pointer">
                                <i class="fas fa-search-plus text-xs"></i>
                                <span>Perbesar Maklumat Pelayanan</span>
                            </button>

                            <!-- Interactive Modal Popup Maklumat Pelayanan -->
                            <div x-show="showMaklumatModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
                                <div @click.away="showMaklumatModal = false" class="bg-white rounded-3xl p-5 sm:p-7 max-w-xl w-full space-y-4 shadow-2xl text-left border border-slate-100 relative max-h-[90vh] overflow-y-auto my-auto">
                                    <button @click="showMaklumatModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 transition-colors z-10">
                                        <i class="fas fa-times text-base"></i>
                                    </button>
                                    
                                    <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                                        <div class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg shrink-0">
                                            <i class="fas fa-file-signature"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-extrabold text-slate-900 text-base">Maklumat Pelayanan Resmi</h4>
                                            <p class="text-[11px] text-emerald-700 font-bold uppercase tracking-wider">DKUPP Kabupaten Probolinggo</p>
                                        </div>
                                    </div>

                                    @if(!empty($settings['maklumat_image']))
                                        <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-xs bg-slate-50">
                                            <img src="{{ $settings['maklumat_image'] }}" alt="Dokumen Maklumat Pelayanan" class="w-full h-auto object-contain max-h-[500px]">
                                        </div>
                                    @endif

                                    <div class="space-y-3 text-xs text-slate-600 leading-relaxed bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-100">
                                        <div class="prose max-w-none font-bold text-slate-800 text-xs sm:text-sm text-center leading-relaxed">
                                            {!! $settings['maklumat_text'] ?? 'DENGAN INI, KAMI MENYATAKAN SANGGUP MENYELENGGARAKAN PELAYANAN SESUAI STANDAR PELAYANAN YANG TELAH DITETAPKAN DAN APABILA TIDAK MENEPATI JANJI, KAMI SIAP MENERIMA SANKSI SESUAI PERATURAN PERUNDANG-UNDANGAN YANG BERLAKU.' !!}
                                        </div>
                                        <div class="pt-2 border-t border-slate-200 text-slate-500 space-y-0.5 text-center font-medium text-[11px]">
                                            <p>Kepala {{ $settings['agency_name'] ?? 'Dinas Koperasi, Usaha Mikro, Perdagangan dan Perindustrian' }}</p>
                                            <strong class="text-slate-900 block font-bold text-xs">{{ $settings['regency_name'] ?? 'Kabupaten Probolinggo' }}</strong>
                                        </div>
                                    </div>

                                    <div class="pt-1 flex justify-end">
                                        <button @click="showMaklumatModal = false" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl font-bold text-xs shadow transition-colors">
                                            Tutup Maklumat
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KARTU 2: Hasil SKM (Tampilkan Gambar Poster SKM Langsung) -->
                        <div x-data="{ showSkmModal: false }" class="bg-white rounded-2xl border border-slate-200/80 p-3 sm:p-4 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between items-center text-center w-full space-y-2.5">
                            <!-- Direct Image Display of SKM Poster -->
                            <div class="w-full relative rounded-xl overflow-hidden bg-slate-900 border border-slate-200 group cursor-pointer" @click="showSkmModal = true">
                                <img src="{{ $settings['skm_image'] ?? '/uploads/settings/skm_poster.svg' }}" 
                                     alt="Hasil SKM Pelayanan" 
                                     class="w-full h-44 sm:h-48 object-contain p-1 group-hover:scale-105 transition-transform duration-300">
                                
                                <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white p-2">
                                    <span class="px-3 py-1.5 bg-blue-600/90 rounded-xl text-xs font-extrabold shadow-md flex items-center gap-1.5">
                                        <i class="fas fa-search-plus"></i> Perbesar Gambar Hasil SKM
                                    </span>
                                </div>
                            </div>

                            <!-- Quick Action Bar -->
                            <div class="w-full flex items-center gap-2">
                                <button @click="showSkmModal = true" 
                                        class="inline-flex items-center justify-center gap-1.5 w-full py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition-all shadow-xs active:scale-95 cursor-pointer">
                                    <i class="fas fa-search-plus text-xs"></i>
                                    <span>Perbesar Hasil SKM</span>
                                </button>
                                <a href="#footer-qr-code" 
                                   class="inline-flex items-center justify-center gap-1.5 py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-all shrink-0 cursor-pointer"
                                   title="Ke Kode QR Footer">
                                    <i class="fas fa-qrcode text-xs"></i>
                                    <span class="hidden sm:inline">Ke QR Bawah</span>
                                </a>
                            </div>

                            <!-- Interactive Modal Popup Hasil SKM & Kode QR -->
                            <div x-show="showSkmModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
                                <div @click.away="showSkmModal = false" class="bg-white rounded-3xl p-5 sm:p-7 max-w-2xl w-full space-y-4 shadow-2xl text-left border border-slate-100 relative max-h-[90vh] overflow-y-auto my-auto">
                                    <button @click="showSkmModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 transition-colors z-10">
                                        <i class="fas fa-times text-base"></i>
                                    </button>
                                    
                                    <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                                        <div class="w-11 h-11 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-lg shrink-0">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-extrabold text-slate-900 text-base">Poster Hasil SKM & Kode QR</h4>
                                            <p class="text-[11px] text-blue-700 font-bold uppercase tracking-wider">DKUPP Kabupaten Probolinggo</p>
                                        </div>
                                    </div>

                                    <!-- Poster SKM / Kode QR Image -->
                                    <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-xs bg-slate-900 flex justify-center p-2">
                                        <img src="{{ $settings['skm_image'] ?? '/uploads/settings/skm_poster.svg' }}" alt="Hasil SKM Pelayanan" class="max-h-[60vh] w-auto object-contain rounded-xl">
                                    </div>

                                    <div class="space-y-2 text-xs text-slate-600 leading-relaxed bg-blue-50/60 p-4 rounded-2xl border border-blue-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                                        <div>
                                            <strong class="text-slate-900 block font-extrabold text-xs mb-0.5">Scan Kode QR / Klik Tombol Akses</strong>
                                            <p class="text-[11px] text-slate-500">Berikan penilaian & masukan Anda terhadap standar pelayanan DKUPP Kabupaten Probolinggo.</p>
                                        </div>
                                        <a href="#footer-qr-code" @click="showSkmModal = false" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-xl text-xs whitespace-nowrap shadow-xs flex items-center gap-1.5 shrink-0">
                                            <i class="fas fa-qrcode"></i> <span>Menuju Kode QR Bawah</span>
                                        </a>
                                    </div>

                                    <div class="pt-1 flex justify-end">
                                        <button @click="showSkmModal = false" class="px-5 py-2 bg-slate-700 hover:bg-slate-800 text-white rounded-xl font-bold text-xs shadow transition-colors">
                                            Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        <!-- Logo Instansi Mitra & Tautan Terkait (Minimalis & Responsif: 2 Kolom Mobile, 4 Kolom Desktop) -->
        <section class="py-8 sm:py-10 bg-white border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
                <div class="text-center space-y-0.5">
                    <span class="text-[10px] font-extrabold text-emerald-700 uppercase tracking-widest bg-emerald-50 px-2.5 py-0.5 rounded-full inline-block border border-emerald-200/60">Sinergi Instansi</span>
                    <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Tautan & Logo Instansi Resmi</h3>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4">
                    @foreach($relatedLinks ?? [] as $link)
                        <a href="{{ $link->url }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="group bg-white p-2.5 sm:p-3.5 rounded-xl sm:rounded-2xl border border-slate-200/80 hover:border-emerald-500 hover:shadow-md transition-all duration-200 flex items-center gap-2.5 sm:gap-3 active:scale-98">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-slate-50 border border-slate-200/80 p-1 flex items-center justify-center shrink-0 group-hover:scale-105 group-hover:bg-emerald-50/50 group-hover:border-emerald-300 transition-all overflow-hidden">
                                <img src="{{ $link->image_url }}" 
                                     alt="{{ $link->title }}" 
                                     class="w-full h-full object-contain">
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="font-extrabold text-slate-900 group-hover:text-emerald-700 text-xs sm:text-sm leading-tight block transition-colors line-clamp-1 sm:line-clamp-2">
                                    {{ $link->title }}
                                </span>
                                <span class="text-[9px] sm:text-[10px] text-slate-400 font-semibold group-hover:text-emerald-600 flex items-center gap-1 mt-0.5">
                                    <span>Website Resmi</span>
                                    <i class="fas fa-external-link-alt text-[8px]"></i>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="py-10 bg-white border-t border-slate-100" 
                 x-data="{ 
                     activePhoto: null, 
                     activePhotoIdx: 0,
                     activeVideo: null,
                     openPhotoAlbum(album) {
                         this.activePhoto = album;
                         this.activePhotoIdx = 0;
                     },
                     nextPhoto() {
                         if (!this.activePhoto || !this.activePhoto.images || !this.activePhoto.images.length) return;
                         this.activePhotoIdx = (this.activePhotoIdx + 1) % this.activePhoto.images.length;
                     },
                     prevPhoto() {
                         if (!this.activePhoto || !this.activePhoto.images || !this.activePhoto.images.length) return;
                         this.activePhotoIdx = (this.activePhotoIdx - 1 + this.activePhoto.images.length) % this.activePhoto.images.length;
                     }
                 }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                
                <!-- Section Header Banner with View All Link -->
                <div class="bg-slate-50 p-4.5 sm:p-6 rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5 text-center sm:text-left">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-emerald-100/80 text-emerald-700 flex items-center justify-center text-xl shrink-0">
                            <i class="fas fa-photo-video text-emerald-700"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-0.5 rounded-full bg-emerald-100/80 text-emerald-800 border border-emerald-200/60 inline-block">
                                Dokumentasi Visual Resmi
                            </span>
                            <h2 class="text-base sm:text-xl font-extrabold text-slate-900 mt-1">Galeri Foto & Video Kegiatan</h2>
                            <p class="text-xs text-slate-500 mt-0.5 hidden sm:block">Dokumentasi kegiatan pelayanan, bimbingan UMKM, tera ulang, dan acara resmi DKUPP.</p>
                        </div>
                    </div>

                    <a href="{{ route('galeri') }}" 
                       class="w-full sm:w-auto px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl sm:rounded-2xl font-extrabold text-xs shadow-xs transition-all flex items-center justify-center gap-2 hover:scale-105 shrink-0">
                        <span>Buka Semua Galeri</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <!-- Side-by-Side Layout: Foto Kegiatan di Kiri & Video Kegiatan di Kanan (Sejajar 2 Kolom di HP / Mobile) -->
                <div class="grid grid-cols-2 gap-2.5 sm:gap-6 items-start">
                    
                    <!-- SISI KIRI: FOTO KEGIATAN -->
                    <div class="space-y-2 sm:space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
                            <h3 class="font-extrabold text-slate-900 text-[11px] sm:text-sm flex items-center gap-1.5 truncate">
                                <i class="fas fa-camera text-emerald-600 text-xs sm:text-sm shrink-0"></i> 
                                <span class="truncate">Foto Kegiatan</span>
                            </h3>
                            <a href="{{ route('galeri') }}" class="text-[9px] sm:text-xs font-bold text-emerald-700 hover:underline flex items-center gap-0.5 shrink-0">
                                <span>Lihat Semua</span> <i class="fas fa-arrow-right text-[8px]"></i>
                            </a>
                        </div>

                        @if(isset($photoGalleries) && $photoGalleries->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4">
                                @foreach(collect($photoGalleries)->take(2) as $img)
                                    <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/80 overflow-hidden shadow-2xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between cursor-pointer hover:-translate-y-0.5"
                                         @click="openPhotoAlbum({{ json_encode(['title' => $img->title, 'category' => $img->category ?: 'Dokumentasi Kegiatan', 'caption' => $img->caption, 'images' => $img->images]) }})">
                                        <div class="aspect-[4/3] bg-slate-100 overflow-hidden relative">
                                            <img src="{{ $img->cover_image }}" alt="{{ $img->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            <span class="absolute top-2 right-2 bg-slate-900/80 backdrop-blur-md text-white text-[9px] font-extrabold px-2 py-0.5 rounded-full shadow-md z-10">
                                                📸 {{ $img->photo_count }} Foto
                                            </span>
                                            <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white p-1.5">
                                                <span class="px-2 py-0.5 bg-emerald-600/90 rounded-md text-[9px] font-extrabold shadow-sm flex items-center gap-1">
                                                    <i class="fas fa-search-plus"></i> Buka Album ({{ $img->photo_count }})
                                                </span>
                                            </div>
                                        </div>
                                        <div class="p-2 sm:p-3.5 space-y-0.5">
                                            <h4 class="font-extrabold text-slate-900 text-[11px] sm:text-xs line-clamp-1 sm:line-clamp-2 leading-snug group-hover:text-emerald-700 transition-colors">{{ $img->title }}</h4>
                                            @if($img->caption)
                                                <p class="text-[10px] sm:text-[11px] text-slate-500 line-clamp-1 leading-relaxed hidden sm:block">{{ $img->caption }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 sm:p-6 bg-slate-50 rounded-2xl border border-slate-200/80 text-center text-slate-400 text-[10px] sm:text-xs">
                                <i class="fas fa-images text-emerald-500 text-xl sm:text-2xl mb-1 block"></i>
                                Belum ada foto kegiatan.
                            </div>
                        @endif
                    </div>

                    <!-- SISI KANAN: VIDEO KEGIATAN (YOUTUBE / FILE) -->
                    <div class="space-y-2 sm:space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
                            <h3 class="font-extrabold text-slate-900 text-[11px] sm:text-sm flex items-center gap-1.5 truncate">
                                <i class="fab fa-youtube text-red-600 text-xs sm:text-sm shrink-0"></i> 
                                <span class="truncate">Video Kegiatan</span>
                            </h3>
                            <a href="{{ route('galeri') }}" class="text-[9px] sm:text-xs font-bold text-red-600 hover:underline flex items-center gap-0.5 shrink-0">
                                <span>Lihat Semua</span> <i class="fas fa-arrow-right text-[8px]"></i>
                            </a>
                        </div>

                        @if(isset($videos) && $videos->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4">
                                @foreach(collect($videos)->take(2) as $vid)
                                    @php
                                        $ytId = '';
                                        if ($vid->youtube_url) {
                                            preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|shorts\/))([\w-]{11})/', $vid->youtube_url, $matches);
                                            $ytId = $matches[1] ?? '';
                                        }
                                        $ytThumb = $ytId ? "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg" : '';
                                        $embedUrl = $ytId ? "https://www.youtube.com/embed/{$ytId}?autoplay=1&rel=0" : '';
                                    @endphp
                                    <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/80 overflow-hidden shadow-2xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group cursor-pointer hover:-translate-y-0.5"
                                         @click="activeVideo = {{ json_encode(['title' => $vid->title, 'caption' => $vid->caption, 'embedUrl' => $embedUrl, 'file_path' => $vid->file_path, 'ytId' => $ytId, 'youtube_url' => $vid->youtube_url]) }}">
                                        <div class="relative aspect-[4/3] bg-slate-950 overflow-hidden">
                                            @if($ytThumb)
                                                <img src="{{ $ytThumb }}" alt="{{ $vid->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 opacity-90 group-hover:opacity-100">
                                            @elseif($vid->file_path)
                                                <video src="{{ $vid->file_path }}" class="w-full h-full object-cover"></video>
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-600 bg-slate-900">
                                                    <i class="fas fa-video text-xl sm:text-2xl"></i>
                                                </div>
                                            @endif

                                            <!-- Play Overlay Button -->
                                            <div class="absolute inset-0 bg-slate-950/40 group-hover:bg-slate-950/20 transition-all flex items-center justify-center">
                                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-red-600 group-hover:bg-red-700 text-white flex items-center justify-center text-xs sm:text-sm shadow-md transition-all transform group-hover:scale-110">
                                                    <i class="fas fa-play ms-0.5"></i>
                                                </div>
                                            </div>

                                            <span class="absolute bottom-1.5 right-1.5 bg-slate-950/85 backdrop-blur-xs text-white text-[8px] sm:text-[9px] font-extrabold px-1.5 py-0.5 rounded flex items-center gap-1">
                                                <i class="fab fa-youtube text-red-500"></i> Video
                                            </span>
                                        </div>
                                        <div class="p-2 sm:p-3.5 space-y-0.5">
                                            <h4 class="font-extrabold text-slate-900 text-[11px] sm:text-xs line-clamp-1 sm:line-clamp-2 leading-snug group-hover:text-red-600 transition-colors">
                                                {{ $vid->title }}
                                            </h4>
                                            @if($vid->caption)
                                                <p class="text-[10px] sm:text-[11px] text-slate-500 line-clamp-1 leading-relaxed hidden sm:block">{{ $vid->caption }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 sm:p-6 bg-slate-50 rounded-2xl border border-slate-200/80 text-center text-slate-400 text-[10px] sm:text-xs">
                                <i class="fab fa-youtube text-red-500 text-xl sm:text-2xl mb-1 block"></i>
                                Belum ada video.
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Photo Lightbox Modal Popup (Album Viewer with All Photos) -->
                <div x-show="activePhoto !== null" 
                     x-cloak 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="fixed inset-0 z-50 p-3 sm:p-6 flex items-center justify-center bg-slate-950/90 backdrop-blur-md">
                    
                    <div @click.away="activePhoto = null" class="bg-slate-900 rounded-3xl max-w-5xl w-full max-h-[92vh] border border-slate-800 shadow-2xl flex flex-col overflow-hidden relative">
                        <div class="p-4 sm:p-5 bg-slate-950 text-white flex items-center justify-between gap-4 border-b border-slate-800 shrink-0">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg shrink-0">
                                    <i class="fas fa-images"></i>
                                </div>
                                <div class="truncate">
                                    <span class="text-[10px] font-extrabold text-emerald-400 uppercase tracking-widest flex items-center gap-2">
                                        <span>Album Dokumentasi</span>
                                        <template x-if="activePhoto && activePhoto.images && activePhoto.images.length > 0">
                                            <span class="bg-emerald-950 text-emerald-400 px-2 py-0.5 rounded-full text-[9px] font-bold border border-emerald-800" x-text="`Foto ${activePhotoIdx + 1} dari ${activePhoto.images.length}`"></span>
                                        </template>
                                    </span>
                                    <h3 class="font-extrabold text-xs sm:text-sm text-white truncate" x-text="activePhoto ? activePhoto.title : ''"></h3>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <a :href="activePhoto && activePhoto.images ? activePhoto.images[activePhotoIdx] : '#'" target="_blank" download class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors shadow-xs">
                                    <i class="fas fa-download text-[10px]"></i> <span class="hidden sm:inline">Unduh Foto</span>
                                </a>
                                <button @click="activePhoto = null" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 flex items-center justify-center transition-colors cursor-pointer">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Main Photo Viewing Slider Area -->
                        <div class="flex-grow bg-slate-950 p-2 sm:p-4 relative overflow-hidden flex flex-col justify-center items-center select-none">
                            <!-- Prev Arrow Button -->
                            <template x-if="activePhoto && activePhoto.images && activePhoto.images.length > 1">
                                <button @click="prevPhoto()" class="absolute left-3 z-10 w-11 h-11 rounded-full bg-slate-900/80 hover:bg-emerald-600 text-white border border-slate-700 hover:border-emerald-500 flex items-center justify-center text-lg transition-all shadow-xl hover:scale-110 active:scale-95 cursor-pointer">
                                    <i class="fas fa-chevron-left me-0.5"></i>
                                </button>
                            </template>

                            <!-- Active Image -->
                            <template x-if="activePhoto && activePhoto.images && activePhoto.images.length > 0">
                                <img :src="activePhoto.images[activePhotoIdx]" :alt="activePhoto.title" class="max-h-[60vh] sm:max-h-[65vh] w-auto max-w-full rounded-2xl object-contain shadow-2xl border border-slate-800 mx-auto transition-all duration-300">
                            </template>

                            <!-- Next Arrow Button -->
                            <template x-if="activePhoto && activePhoto.images && activePhoto.images.length > 1">
                                <button @click="nextPhoto()" class="absolute right-3 z-10 w-11 h-11 rounded-full bg-slate-900/80 hover:bg-emerald-600 text-white border border-slate-700 hover:border-emerald-500 flex items-center justify-center text-lg transition-all shadow-xl hover:scale-110 active:scale-95 cursor-pointer">
                                    <i class="fas fa-chevron-right ms-0.5"></i>
                                </button>
                            </template>
                        </div>

                        <!-- Bottom Photo Album Thumbnail Strip -->
                        <div class="p-3 bg-slate-950 border-t border-slate-800 shrink-0 space-y-2" x-show="activePhoto && activePhoto.images && activePhoto.images.length > 0">
                            <div class="flex items-center gap-2 overflow-x-auto custom-scrollbar pb-1">
                                <template x-for="(img, idx) in (activePhoto ? activePhoto.images : [])" :key="idx">
                                    <button @click="activePhotoIdx = idx" 
                                            :class="activePhotoIdx === idx ? 'ring-2 ring-emerald-500 scale-105 opacity-100' : 'opacity-50 hover:opacity-100 hover:scale-105'" 
                                            class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl overflow-hidden shrink-0 border border-slate-800 transition-all cursor-pointer bg-slate-900">
                                        <img :src="img" class="w-full h-full object-cover">
                                    </button>
                                </template>
                            </div>
                            <p class="text-[11px] text-slate-400 font-medium text-center truncate" x-text="activePhoto ? activePhoto.caption : ''"></p>
                        </div>
                    </div>
                </div>

                <!-- Video Lightbox & Enlarged Player Modal Popup (Bisa Dijalankan & Diperbesar) -->
                <div x-show="activeVideo !== null" 
                     x-cloak 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="fixed inset-0 z-50 p-3 sm:p-6 flex items-center justify-center bg-slate-950/90 backdrop-blur-md">
                    
                    <div @click.away="activeVideo = null" class="bg-slate-900 rounded-3xl max-w-5xl w-full max-h-[95vh] border border-slate-800 shadow-2xl flex flex-col overflow-hidden relative">
                        <!-- Modal Header -->
                        <div class="p-4 sm:p-5 bg-slate-950 text-white flex items-center justify-between gap-4 border-b border-slate-800 shrink-0">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-9 h-9 rounded-xl bg-red-500/20 text-red-400 flex items-center justify-center text-lg shrink-0">
                                    <i class="fab fa-youtube"></i>
                                </div>
                                <div class="truncate">
                                    <span class="text-[10px] font-extrabold text-red-400 uppercase tracking-widest block">Video Kegiatan Resmi DKUPP</span>
                                    <h3 class="font-extrabold text-xs sm:text-sm text-white truncate" x-text="activeVideo ? activeVideo.title : ''"></h3>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <template x-if="activeVideo && activeVideo.youtube_url">
                                    <a :href="activeVideo.youtube_url" target="_blank" rel="noopener noreferrer" class="px-3.5 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors shadow-xs">
                                        <i class="fab fa-youtube"></i> <span class="hidden sm:inline">Buka di YouTube</span>
                                    </a>
                                </template>
                                <button @click="activeVideo = null" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 flex items-center justify-center transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Video Player Screen -->
                        <div class="flex-grow bg-slate-950 p-2 sm:p-6 relative overflow-auto flex flex-col justify-center items-center">
                            <div class="w-full max-w-4xl aspect-video rounded-2xl overflow-hidden shadow-2xl bg-black border border-slate-800">
                                <template x-if="activeVideo && activeVideo.embedUrl">
                                    <iframe :src="activeVideo.embedUrl" title="YouTube Video Player"
                                            class="w-full h-full border-0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                            allowfullscreen></iframe>
                                </template>
                                <template x-if="activeVideo && !activeVideo.embedUrl && activeVideo.file_path">
                                    <video :src="activeVideo.file_path" controls autoplay class="w-full h-full object-contain"></video>
                                </template>
                            </div>
                            <div class="mt-4 text-center max-w-2xl px-2" x-show="activeVideo && activeVideo.caption">
                                <p class="text-xs sm:text-sm text-slate-300 font-medium leading-relaxed" x-text="activeVideo ? activeVideo.caption : ''"></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <!-- Footer Resmi Model Disnakkeswan / DKUPP Kab. Probolinggo -->
    @include('partials.public_footer')

    @include('partials.tts_widget')
</body>
</html>