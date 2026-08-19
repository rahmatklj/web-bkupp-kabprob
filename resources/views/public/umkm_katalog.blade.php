<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMADU SAE - Katalog Produk UMKM | DKUPP Kabupaten Probolinggo</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-700 font-sans antialiased flex flex-col min-h-screen selection:bg-emerald-600 selection:text-white">
    
    <!-- Top Bar (Single Row Minimalist & Responsive) -->
    <div class="bg-slate-950 text-slate-300 text-[11px] sm:text-xs py-2.5 px-4 sm:px-6 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-3">
            <span class="font-bold text-slate-200 truncate flex items-center gap-1.5">
                <i class="fas fa-store text-emerald-400"></i>
                <span>SIMADU SAE <span class="hidden sm:inline text-slate-400 font-medium">- Katalog Produk UMKM</span></span>
            </span>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full font-extrabold text-[11px] transition-all shadow-xs shrink-0">
                <i class="fas fa-arrow-left text-[10px]"></i>
                <span>Beranda</span>
            </a>
        </div>
    </div>

    <!-- Header & Navigation -->
    <header x-data="{ mobileMenu: false }" class="bg-white/90 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ $settings['logo_frontend'] ?? '' }}" alt="DKUPP Logo" class="h-9 sm:h-10 w-auto group-hover:scale-105 transition-transform">
                <div class="border-l border-emerald-500 pl-3">
                    <h1 class="font-bold text-slate-900 text-xs sm:text-sm tracking-tight">SIMADU SAE</h1>
                    <p class="text-[9px] sm:text-[10px] text-emerald-600 font-semibold uppercase tracking-wider">Katalog UMKM Probolinggo</p>
                </div>
            </a>

            <!-- Desktop Nav -->
            <div class="hidden md:flex items-center gap-1 text-sm font-semibold">
                <a href="{{ route('home') }}" class="px-4 py-2 text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">Home</a>
                <a href="{{ route('layanan') }}" class="px-4 py-2 text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">Layanan</a>
                <div class="ml-2">
                    <a href="{{ route('kontak') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-plus-circle"></i> Daftar UMKM
                    </a>
                </div>
            </div>

            <!-- Mobile Menu Toggle -->
            <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 text-slate-600 hover:text-emerald-600 focus:outline-none">
                <i class="fas text-xl" :class="mobileMenu ? 'fa-times' : 'fa-bars'"></i>
            </button>
        </div>

        <!-- Mobile Nav Dropdown -->
        <div x-show="mobileMenu" x-cloak x-transition class="md:hidden bg-white border-t border-slate-100 px-4 py-4 space-y-2 shadow-lg absolute w-full">
            <a href="{{ route('home') }}" class="block px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg">Home</a>
            <a href="{{ route('layanan') }}" class="block px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg">Layanan</a>
            <div class="pt-2">
                <a href="{{ route('kontak') }}" class="flex justify-center items-center gap-2 w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg">
                    <i class="fas fa-plus-circle"></i> Daftarkan Produk
                </a>
            </div>
        </div>
    </header>

    <!-- Clean Banner -->
    <section class="bg-emerald-800 text-white py-12 sm:py-16 px-4">
        <div class="max-w-3xl mx-auto text-center space-y-4">
            <span class="inline-block px-3 py-1 bg-emerald-700/50 text-emerald-100 border border-emerald-600/50 rounded-full text-[10px] font-bold uppercase tracking-widest">
                Katalog Digital UMKM
            </span>
            <h2 class="text-3xl sm:text-4xl font-bold tracking-tight">Temukan Produk Lokal Terbaik</h2>
            <p class="text-emerald-100/80 text-sm sm:text-base font-medium">
                Dukung pertumbuhan ekonomi daerah dengan menggunakan produk karya pelaku Usaha Mikro Kabupaten Probolinggo yang telah terverifikasi.
            </p>
        </div>
    </section>

    <!-- Search & Filter (Floating Layout) -->
    <section class="relative z-10 -mt-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200">
            <form action="{{ route('umkm.katalog') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 sm:gap-4">
                <!-- Search Input -->
                <div class="md:col-span-5 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama produk atau pemilik..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all placeholder:text-slate-400">
                </div>
                
                <!-- Category Select -->
                <div class="md:col-span-3">
                    <select name="category" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all bg-white text-slate-600">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- District Select & Submit Button -->
                <div class="md:col-span-4 flex gap-2">
                    <select name="district" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all bg-white text-slate-600">
                        <option value="">Semua Kecamatan</option>
                        @foreach($districts as $dist)
                            <option value="{{ $dist }}" {{ request('district') == $dist ? 'selected' : '' }}>{{ $dist }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl text-sm transition-colors flex items-center justify-center whitespace-nowrap shrink-0">
                        Terapkan
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Product Grid -->
    <main class="flex-grow py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        @if($products->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-6">
                @foreach($products as $product)
                    @php
                        $targetUrl = $product->website_url ?: route('umkm.detail', $product->slug);
                        $isExternal = !empty($product->website_url);
                    @endphp
                    <div class="group bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 overflow-hidden shadow-2xs hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col">
                        <!-- Image Container -->
                        <a href="{{ $targetUrl }}" {{ $isExternal ? 'target="_blank" rel="noopener noreferrer"' : '' }} class="relative aspect-square sm:aspect-[4/3] bg-slate-100 overflow-hidden block">
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            
                            <!-- Category Badge -->
                            <span class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-white/95 backdrop-blur-sm text-slate-800 text-[9px] sm:text-[10px] font-extrabold px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg shadow-2xs uppercase tracking-wider border border-slate-100">
                                {{ $product->category }}
                            </span>
                        </a>
                        
                        <!-- Card Body -->
                        <div class="p-3 sm:p-5 flex-grow flex flex-col justify-between space-y-2 sm:space-y-3">
                            <div>
                                <div class="flex items-center justify-between text-[10px] sm:text-[11px] text-slate-500 mb-1 sm:mb-2 font-medium">
                                    <span class="truncate pr-1 text-emerald-700 font-bold"><i class="fas fa-store me-1"></i>{{ $product->owner_name }}</span>
                                    <span class="shrink-0 text-slate-400 font-medium"><i class="fas fa-map-marker-alt text-emerald-500 me-0.5"></i>{{ $product->district }}</span>
                                </div>
                                <h3 class="font-extrabold text-slate-900 text-xs sm:text-base leading-snug group-hover:text-emerald-700 transition-colors line-clamp-2">
                                    <a href="{{ $targetUrl }}" {{ $isExternal ? 'target="_blank" rel="noopener noreferrer"' : '' }}>{{ $product->name }}</a>
                                </h3>
                            </div>
                            
                            <!-- Price & Action -->
                            <div class="pt-2 sm:pt-3 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    @if($product->price > 0 && !$isExternal)
                                        <span class="font-extrabold text-emerald-700 text-xs sm:text-base block sm:inline">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        <span class="text-[9px] sm:text-[10px] font-semibold text-slate-400">/ {{ $product->price_unit ?? 'pcs' }}</span>
                                    @else
                                        <span class="text-[11px] sm:text-xs font-extrabold text-emerald-700 flex items-center gap-1 group-hover:underline">
                                            <i class="fas fa-globe text-emerald-600"></i> Kunjungi Web
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ $targetUrl }}" {{ $isExternal ? 'target="_blank" rel="noopener noreferrer"' : '' }} class="w-7 h-7 sm:w-9 sm:h-9 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 group-hover:bg-emerald-700 group-hover:text-white transition-all shadow-2xs shrink-0" title="{{ $isExternal ? 'Kunjungi Web Produk' : 'Lihat Detail' }}">
                                    <i class="fas {{ $isExternal ? 'fa-external-link-alt' : 'fa-arrow-right' }} text-[10px] sm:text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                {{ $products->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-slate-200 px-4">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-box-open text-2xl text-slate-300"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-lg">Tidak ada produk ditemukan</h3>
                <p class="text-sm text-slate-500 mt-1 mb-5">Coba ubah kata kunci pencarian atau sesuaikan filter Anda.</p>
                <a href="{{ route('umkm.katalog') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-50 text-emerald-700 font-semibold rounded-lg hover:bg-emerald-100 transition-colors text-sm">
                    <i class="fas fa-sync-alt"></i> Reset Pencarian
                </a>
            </div>
        @endif
    </main>

    <!-- Minimalist Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-[11px] font-medium text-slate-500">
            <p>{{ $settings['copyright_text'] ?? 'DKUPP Kabupaten Probolinggo © 2026. All Rights Reserved.' }}</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="hover:text-emerald-600 transition-colors">Beranda Utama</a>
                <a href="{{ route('layanan') }}" class="hover:text-emerald-600 transition-colors">Bantuan & Layanan</a>
            </div>
        </div>
    </footer>

    @include('partials.tts_widget')
</body>
</html>