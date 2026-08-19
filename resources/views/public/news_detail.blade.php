<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $news->title }} | DKUPP Kabupaten Probolinggo</title>
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

    <main class="flex-grow max-w-4xl w-full mx-auto px-4 sm:px-6 py-8 sm:py-12 space-y-6">
        <div class="space-y-3">
            <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-extrabold rounded-full uppercase tracking-wider">{{ $news->category }}</span>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 leading-tight tracking-tight">{{ $news->title }}</h1>
            <div class="flex items-center gap-4 text-xs text-slate-500 pt-1">
                <span><i class="far fa-calendar-alt text-emerald-600 me-1"></i> {{ optional($news->published_at)->format('d F Y') }}</span>
                <span>•</span>
                <span><i class="far fa-eye text-emerald-600 me-1"></i> {{ $news->views }} kali dibaca</span>
            </div>
        </div>

        @if($news->image_url)
            <div class="rounded-2xl overflow-hidden shadow-sm border border-slate-200 bg-slate-100">
                <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="w-full h-auto max-h-[460px] object-cover">
            </div>
        @endif

        <div class="prose max-w-none text-xs sm:text-sm text-slate-700 leading-relaxed space-y-4 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-2xs">
            {!! nl2br(e($news->content)) !!}
        </div>
    </main>

    <footer class="bg-slate-950 text-slate-400 py-6 text-xs text-center border-t border-slate-800">
        <p>DKUPP Kabupaten Probolinggo © 2026</p>
    </footer>

    @include('partials.tts_widget')
</body>
</html>
