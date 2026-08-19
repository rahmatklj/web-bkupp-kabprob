<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} - SIMADU SAE UMKM | DKUPP Kabupaten Probolinggo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex flex-col min-h-screen">
    
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 py-3 px-4 sm:px-6 sticky top-0 z-40 flex items-center justify-between shadow-xs">
        <a href="{{ route('umkm.katalog') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 rounded-full font-extrabold text-xs transition-all shadow-2xs">
            <i class="fas fa-arrow-left text-[10px]"></i> <span>Katalog UMKM</span>
        </a>
        <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-full font-extrabold text-xs transition-all shadow-xs">
            <i class="fas fa-home text-[10px]"></i> <span class="hidden sm:inline">Beranda</span>
        </a>
    </header>

    <main class="flex-grow py-12 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-12 gap-8 p-6 lg:p-8">
            <div class="md:col-span-6">
                <div class="h-80 lg:h-96 rounded-2xl overflow-hidden bg-slate-100 shadow-inner">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
            </div>
            <div class="md:col-span-6 flex flex-col justify-between space-y-6">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-extrabold rounded-full uppercase">
                            {{ $product->category }}
                        </span>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">
                            <i class="fas fa-check-circle me-1"></i> Terverifikasi DKUPP
                        </span>
                    </div>
                    <h1 class="text-2xl lg:text-3xl font-extrabold text-slate-900 leading-tight">{{ $product->name }}</h1>
                    <div class="text-xs text-slate-500 space-y-1">
                        <p><i class="fas fa-user text-emerald-600 me-2"></i>Pemilik: <strong>{{ $product->owner_name }}</strong></p>
                        <p><i class="fas fa-map-marker-alt text-emerald-600 me-2"></i>Kecamatan: <strong>{{ $product->district }}</strong></p>
                    </div>
                    <div class="pt-2">
                        <span class="text-xs text-slate-400 block">Harga Produk</span>
                        <div class="text-3xl font-extrabold text-emerald-800">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                            <span class="text-xs text-slate-500 font-medium">/ {{ $product->price_unit }}</span>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-slate-100">
                        <h4 class="font-bold text-xs text-slate-700 uppercase tracking-wider mb-2">Deskripsi Produk:</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $product->description }}</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 space-y-2">
                    @if($product->website_url)
                        <a href="{{ $product->website_url }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs rounded-2xl shadow-lg transition-all">
                            <i class="fas fa-globe text-base text-emerald-400"></i>
                            <span>Kunjungi Website / Toko Online Produk</span>
                            <i class="fas fa-external-link-alt text-[10px]"></i>
                        </a>
                    @endif
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $product->phone ?? '6281234567890') }}?text=Halo%20{{ urlencode($product->owner_name) }},%20saya%20tertarik%20dengan%20produk%20{{ urlencode($product->name) }}%20dari%20SIMADU%20SAE%20DKUPP" 
                       target="_blank" 
                       class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-2xl shadow-lg shadow-emerald-700/30 transition-all">
                        <i class="fab fa-whatsapp text-lg"></i>
                        <span>Hubungi Penjual via WhatsApp ({{ $product->phone }})</span>
                    </a>
                </div>
            </div>
        </div>

        @if($relatedProducts->count() > 0)
            <div class="mt-12 space-y-6">
                <h3 class="font-extrabold text-slate-900 text-lg">Produk Serupa di Category {{ $product->category }}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @foreach($relatedProducts as $rel)
                        <a href="{{ route('umkm.detail', $rel->slug) }}" class="bg-white p-4 rounded-2xl border border-slate-200 hover:shadow-lg transition-all flex items-center gap-4">
                            <img src="{{ $rel->image }}" alt="{{ $rel->name }}" class="w-16 h-16 rounded-xl object-cover">
                            <div>
                                <h4 class="font-bold text-xs text-slate-900 line-clamp-1 hover:text-emerald-700">{{ $rel->name }}</h4>
                                <span class="text-xs font-extrabold text-emerald-800">Rp {{ number_format($rel->price, 0, ',', '.') }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </main>

    <footer class="bg-slate-950 text-slate-400 py-6 text-xs text-center">
        <p>SIMADU SAE - DKUPP Kabupaten Probolinggo</p>
    </footer>
</body>
</html>
