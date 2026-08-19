<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lapor SP4N! | DKUPP Kabupaten Probolinggo</title>
    <meta name="description" content="Layanan Pengaduan Masyarakat SP4N LAPOR! DKUPP Kabupaten Probolinggo">
    <link rel="icon" type="image/png" href="{{ $settings['logo_frontend'] ?? '' }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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

    <main class="flex-grow py-6 sm:py-10 max-w-7xl w-full mx-auto px-4 sm:px-6 space-y-6">
        
        <!-- Tombol Kembali ke Beranda Utama -->
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200/60 pb-3">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-full font-extrabold text-xs shadow-md transition-all hover:scale-105 group">
                <i class="fas fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i>
                <span>Kembali ke Beranda Utama</span>
            </a>
            
            <div class="flex items-center gap-2">
                <a href="{{ $targetUrl ?? 'https://www.lapor.go.id/' }}" target="_blank" rel="noopener noreferrer" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-full font-bold text-xs transition-all shadow-xs">
                    <i class="fas fa-external-link-alt text-[10px]"></i>
                    <span>Buka Layanan SP4N LAPOR! di Tab Baru</span>
                </a>
            </div>
        </div>

        <!-- Banner Information -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4 text-left">
                    <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl shrink-0 shadow-xs border border-rose-200">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div>
                        <span class="inline-block px-3 py-0.5 bg-rose-100 text-rose-800 rounded-full text-[10px] font-extrabold uppercase tracking-wide mb-1">
                            Layanan Aspirasi & Pengaduan Online Rakyat
                        </span>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 leading-snug">Portal SP4N LAPOR! RI</h1>
                        <p class="text-xs text-slate-500 mt-1">Layanan pengaduan resmi terintegrasi langsung dengan Pemerintah Kabupaten Probolinggo & Kementerian PANRB.</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    <a href="{{ route('home') }}" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 transition-all shadow-xs">
                        <i class="fas fa-arrow-left"></i> Kembali ke Web
                    </a>
                    <a href="{{ $targetUrl ?? 'https://www.lapor.go.id/' }}" target="_blank" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 transition-all shadow-md">
                        <i class="fas fa-paper-plane"></i> Buat Laporan Pengaduan
                    </a>
                </div>
            </div>

            <!-- Embedded Live Portal SP4N LAPOR! Window Frame -->
            <div class="rounded-2xl border border-slate-300 shadow-lg overflow-hidden bg-white mt-4">
                <!-- Frame Header Controls -->
                <div class="bg-slate-900 text-white px-4 py-3 flex items-center justify-between text-xs border-b border-slate-800">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                        <span class="font-bold ms-2 text-slate-300">Tampilan Portal SP4N LAPOR! Resmi</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('home') }}" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-[11px] flex items-center gap-1 transition-all">
                            <i class="fas fa-arrow-left"></i> Kembali ke Web Utama DKUPP
                        </a>
                        <a href="{{ $targetUrl ?? 'https://www.lapor.go.id/' }}" target="_blank" class="text-rose-400 hover:text-white font-semibold flex items-center gap-1">
                            <i class="fas fa-expand"></i> Buka Penuh
                        </a>
                    </div>
                </div>

                <!-- Iframe Viewer / Direct Access Screen -->
                <div class="relative w-full h-[75vh] bg-slate-100">
                    <iframe src="{{ $targetUrl ?? 'https://www.lapor.go.id/' }}" title="Portal SP4N LAPOR!" class="w-full h-full border-0" allowfullscreen></iframe>
                </div>
            </div>
        </div>

    </main>

    <footer class="bg-slate-950 text-slate-400 py-6 text-xs text-center border-t border-slate-800">
        <p>DKUPP Kabupaten Probolinggo © 2026</p>
    </footer>
    @include('partials.tts_widget')
</body>
</html>
