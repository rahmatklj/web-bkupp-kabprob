<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $service->title }} | DKUPP Kabupaten Probolinggo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex flex-col min-h-screen">

    <header class="bg-white border-b border-slate-200 py-4 px-6 sticky top-0 z-40 flex items-center justify-between shadow-sm">
        <a href="{{ route('layanan') }}" class="font-bold text-xs text-emerald-800 hover:underline flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Layanan
        </a>
        <span class="text-xs font-bold text-slate-600">DKUPP Kabupaten Probolinggo</span>
    </header>

    <main class="flex-grow py-12 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xl space-y-6">
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl shadow-inner">
                    <i class="fas {{ $service->icon }}"></i>
                </div>
                <div>
                    <span class="text-xs font-extrabold text-emerald-700 uppercase tracking-widest block">{{ $service->category }}</span>
                    <h1 class="text-2xl font-extrabold text-slate-900 leading-tight">{{ $service->title }}</h1>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100 text-xs">
                <div>
                    <span class="text-slate-400 block">Biaya Layanan:</span>
                    <strong class="text-emerald-800 font-extrabold text-sm">{{ $service->cost }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block">Waktu Proses:</span>
                    <strong class="text-slate-800 font-extrabold text-sm">{{ $service->service_time }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block">Lokasi Pelayanan:</span>
                    <strong class="text-slate-800 font-extrabold text-sm">Loket MPP Kraksaan</strong>
                </div>
            </div>

            <div class="space-y-4 pt-2">
                <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-list-check text-emerald-700"></i> Persyaratan Dokumen
                </h3>
                <div class="prose text-xs text-slate-700 leading-relaxed bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                    {!! $service->requirements !!}
                </div>
            </div>

            <div class="space-y-4 pt-2">
                <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-route text-emerald-700"></i> Prosedur & Alur Pelayanan
                </h3>
                <div class="prose text-xs text-slate-700 leading-relaxed bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                    {!! $service->procedure !!}
                </div>
            </div>

            @if($service->external_url)
                <div class="pt-4 border-t border-slate-200">
                    <a href="{{ $service->external_url }}" class="inline-flex items-center gap-2 py-3 px-6 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-lg transition-colors">
                        <span>Akses Portal Layanan Langsung</span>
                        <i class="fas fa-external-link-alt text-[10px]"></i>
                    </a>
                </div>
            @endif
        </div>

    </main>

    <footer class="bg-slate-950 text-slate-400 py-6 text-xs text-center border-t border-slate-800">
        <p>DKUPP Kabupaten Probolinggo © 2026</p>
    </footer>
</body>
</html>
