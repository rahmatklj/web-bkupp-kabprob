<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Informasi & Berita Terkini | DKUPP Kabupaten Probolinggo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex flex-col min-h-screen"
      x-data="{ mobileMenu: false, highContrast: false, fontSize: 100 }"
      :class="{ 'high-contrast': highContrast }"
      :style="`font-size: ${fontSize}%`">
    
    @include('partials.public_header')

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 py-6 sm:py-12 space-y-6">
        <!-- Tombol Kembali ke Beranda Utama -->
        <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-emerald-600 text-slate-700 hover:text-white border border-slate-200 hover:border-emerald-600 rounded-full font-extrabold text-xs shadow-xs transition-all hover:scale-105 group">
                <i class="fas fa-arrow-left text-xs text-emerald-600 group-hover:text-white transition-transform group-hover:-translate-x-0.5"></i>
                <span>Kembali ke Beranda Utama</span>
            </a>
            <span class="text-xs text-slate-400 font-semibold hidden sm:inline">Portal Berita & Informasi DKUPP</span>
        </div>

        <div class="text-center max-w-xl mx-auto space-y-2">
            <span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full text-[11px] font-extrabold uppercase tracking-wide">
                Portal Berita Resmi
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-snug">Informasi & Berita Terkini</h1>
            <p class="text-xs sm:text-sm text-slate-500">Arsip berita, pengumuman, dan siaran pers resmi DKUPP Kabupaten Probolinggo</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($newsList as $news)
                <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="relative overflow-hidden aspect-video">
                            <img src="{{ $news->image_url }}" alt="News" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-5 space-y-2.5">
                            <span class="inline-block px-2.5 py-0.5 bg-orange-100 text-orange-800 rounded-md text-[10px] font-extrabold uppercase tracking-wide">{{ $news->category }}</span>
                            <h3 class="font-bold text-sm text-slate-900 leading-snug line-clamp-2">
                                <a href="{{ route('news.detail', $news->slug) }}" class="hover:text-emerald-700 transition-colors">
                                    {{ $news->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ $news->summary }}</p>
                        </div>
                    </div>
                    <div class="px-5 pb-5 pt-2 flex items-center justify-between text-[11px] text-slate-400 border-t border-slate-100">
                        <span><i class="far fa-calendar-alt me-1"></i>{{ optional($news->published_at)->format('d M Y') }}</span>
                        <a href="{{ route('news.detail', $news->slug) }}" class="text-emerald-700 font-bold hover:underline">Baca &rarr;</a>
                    </div>
                </div>
            @endforeach
        </div>

        @if($newsList->hasPages())
            <div class="pt-4 flex justify-center">
                {{ $newsList->links() }}
            </div>
        @endif
    </main>

    <footer class="bg-slate-950 text-slate-400 py-6 text-xs text-center border-t border-slate-800">
        <p>DKUPP Kabupaten Probolinggo © 2026</p>
    </footer>

    @include('partials.tts_widget')
</body>
</html>
