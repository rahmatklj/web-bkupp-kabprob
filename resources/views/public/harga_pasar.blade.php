<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $marketWebTitle ?? 'Monitoring Harga Bahan Pokok | DKUPP Kabupaten Probolinggo' }}</title>
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

    <main class="flex-grow py-8 sm:py-16 max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-8 flex flex-col justify-center">
        
        <!-- Single Web Link Showcase Card -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden text-center p-6 sm:p-12 space-y-6">
            
            <div class="w-20 h-20 bg-emerald-100 text-emerald-700 rounded-3xl mx-auto flex items-center justify-center text-3xl shadow-inner">
                <i class="fas fa-shopping-basket"></i>
            </div>

            <div class="space-y-3 max-w-xl mx-auto">
                <span class="inline-block px-3.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-[11px] font-extrabold uppercase tracking-wide">
                    <i class="fas fa-store me-1"></i> Pasar Daerah Kabupaten Probolinggo
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">
                    {{ $marketWebTitle ?? 'Portal Web Pemantauan Harga Bahan Pokok' }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    {{ $marketWebDesc ?? 'Update harga komoditas pangan harian dari Pasar Kraksaan, Semampir, Paiton, dan Dringu Kabupaten Probolinggo & Jawa Timur.' }}
                </p>
            </div>

            <!-- Single URL Card Display -->
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 max-w-md mx-auto flex items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2 truncate text-slate-700 font-bold">
                    <i class="fas fa-globe text-emerald-600 text-sm shrink-0"></i>
                    <span class="truncate font-mono">{{ $marketWebUrl ?? 'https://siskaperbapo.jatimprov.go.id/' }}</span>
                </div>
                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-extrabold rounded-md uppercase shrink-0">Resmi</span>
            </div>

            <!-- Big Direct Button -->
            <div class="pt-2">
                <a href="{{ $marketWebUrl ?? 'https://siskaperbapo.jatimprov.go.id/' }}" target="_blank" rel="noopener noreferrer" 
                   class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-emerald-700 hover:bg-emerald-800 text-white rounded-2xl font-extrabold text-sm sm:text-base shadow-lg transition-all hover:scale-105 group">
                    <i class="fas fa-external-link-alt text-base group-hover:rotate-12 transition-transform"></i>
                    <span>Klik Untuk Buka Website Pemantauan Harga</span>
                </a>
            </div>
        </div>

    </main>

    <footer class="bg-slate-950 text-slate-400 py-6 text-xs text-center border-t border-slate-800">
        <p>DKUPP Kabupaten Probolinggo © 2026</p>
    </footer>
    @include('partials.tts_widget')
</body>
</html>
