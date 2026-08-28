<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $news->title }} | DKUPP Kabupaten Probolinggo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .news-content p {
            margin-bottom: 1.35rem !important;
            line-height: 1.85 !important;
            color: #334155 !important;
        }
        .news-content h1, .news-content h2, .news-content h3, .news-content h4 {
            margin-top: 1.85rem !important;
            margin-bottom: 0.85rem !important;
            font-weight: 800 !important;
            color: #0f172a !important;
            line-height: 1.35 !important;
        }
        .news-content h1 { font-size: 1.6rem !important; }
        .news-content h2 { font-size: 1.35rem !important; }
        .news-content h3 { font-size: 1.15rem !important; }
        .news-content ul, .news-content ol {
            margin-bottom: 1.35rem !important;
            padding-left: 1.5rem !important;
        }
        .news-content ul { list-style-type: disc !important; }
        .news-content ol { list-style-type: decimal !important; }
        .news-content li { margin-bottom: 0.45rem !important; line-height: 1.75 !important; }
        .news-content blockquote {
            border-left: 4px solid #10b981;
            padding-left: 1.25rem;
            font-style: italic;
            margin-bottom: 1.35rem;
            color: #475569;
            background: #f8fafc;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            border-radius: 0 0.85rem 0.85rem 0;
        }
        .news-content img {
            max-width: 100%;
            height: auto;
            border-radius: 1rem;
            margin-top: 1.25rem;
            margin-bottom: 1.25rem;
        }
    </style>
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

        @php
            $rawContent = $news->content;
            if (strpos($rawContent, '<p>') === false && strpos($rawContent, '<div>') === false) {
                $paragraphs = array_filter(array_map('trim', explode("\n\n", $rawContent)));
                if (!empty($paragraphs)) {
                    $rawContent = '<p>' . implode('</p><p>', array_map('nl2br', $paragraphs)) . '</p>';
                } else {
                    $rawContent = '<p>' . nl2br($rawContent) . '</p>';
                }
            }
        @endphp

        <div class="news-content prose max-w-none text-xs sm:text-sm text-slate-700 bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-sm">
            {!! $rawContent !!}
        </div>
    </main>

    <footer class="bg-slate-950 text-slate-400 py-6 text-xs text-center border-t border-slate-800">
        <p>DKUPP Kabupaten Probolinggo © 2026</p>
    </footer>

    @include('partials.tts_widget')
</body>
</html>
