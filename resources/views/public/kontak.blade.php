<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kontak Kami | DKUPP Kabupaten Probolinggo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex flex-col min-h-screen">

    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 py-3 px-4 sm:px-6 sticky top-0 z-40 flex items-center justify-between shadow-xs">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 rounded-full font-extrabold text-xs transition-all shadow-2xs">
            <i class="fas fa-arrow-left text-[10px]"></i> <span>Beranda</span>
        </a>
        <span class="text-xs font-extrabold text-slate-600 truncate max-w-[180px] sm:max-w-none">DKUPP Kabupaten Probolinggo</span>
    </header>

    <main class="flex-grow py-12 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <span class="px-3.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-extrabold uppercase">Hubungi DKUPP</span>
            <h1 class="text-3xl font-extrabold text-slate-900">Kontak & Lokasi Pelayanan</h1>
            <p class="text-xs text-slate-500">Silakan hubungi kami atau kunjungi loket layanan DKUPP di Mal Pelayanan Publik (MPP) Kraksaan.</p>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl flex items-center gap-2">
                <i class="fas fa-check-circle text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-5 bg-white p-6 rounded-3xl border border-slate-200 shadow-xl space-y-6">
                <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3">Informasi Kontak</h3>
                <div class="space-y-4 text-xs text-slate-600">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <strong class="text-slate-900 block">Alamat Kantor:</strong>
                            <span>{{ $settings['address'] ?? 'Jl. Raya Panglima Sudirman No. 134 Kraksaan, Kabupaten Probolinggo' }}</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <strong class="text-slate-900 block">Telepon / WhatsApp:</strong>
                            <span>{{ $settings['phone'] ?? '(0335) 844554' }}</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <strong class="text-slate-900 block">Email Resmi:</strong>
                            <span>{{ $settings['email'] ?? 'dkupp@probolinggokab.go.id' }}</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <strong class="text-slate-900 block">Jam Operasional MPP Kraksaan:</strong>
                            <span>Senin - Jumat: 08.00 - 16.00 WIB</span>
                        </div>
                    </div>
                </div>

                <!-- Media Sosial Resmi Block -->
                <div class="pt-5 border-t border-slate-100 space-y-3">
                    <strong class="text-slate-900 text-xs block font-extrabold uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fas fa-share-alt text-emerald-600"></i> Media Sosial Resmi DKUPP
                    </strong>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
                        <a href="{{ $settings['instagram_url'] ?? 'https://www.instagram.com/dkuppkabprobolinggo/' }}" target="_blank" rel="noopener noreferrer" class="p-2.5 bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 text-white rounded-xl font-bold flex items-center justify-center gap-2 shadow-xs hover:opacity-90 transition-all">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            <span>Instagram</span>
                        </a>
                        <a href="{{ $settings['facebook_url'] ?? 'https://www.facebook.com/dkuppkabprobolinggo' }}" target="_blank" rel="noopener noreferrer" class="p-2.5 bg-blue-600 text-white rounded-xl font-bold flex items-center justify-center gap-2 shadow-xs hover:bg-blue-700 transition-all">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            <span>Facebook</span>
                        </a>
                        <a href="{{ $settings['tiktok_url'] ?? 'https://www.tiktok.com/@dkuppkabprobolinggo' }}" target="_blank" rel="noopener noreferrer" class="p-2.5 bg-slate-900 text-white rounded-xl font-bold flex items-center justify-center gap-2 shadow-xs hover:bg-black transition-all">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.96-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.83.57-1.32 1.55-1.35 2.56-.04 1.4.92 2.72 2.26 3.08 1.33.36 2.82-.13 3.59-1.24.49-.69.74-1.54.74-2.39.01-4.75.01-9.51 0-14.26z"/></svg>
                            <span>TikTok</span>
                        </a>
                        <a href="{{ $settings['whatsapp_url'] ?? 'https://wa.me/6281234567890' }}" target="_blank" rel="noopener noreferrer" class="p-2.5 bg-emerald-600 text-white rounded-xl font-bold flex items-center justify-center gap-2 shadow-xs hover:bg-emerald-700 transition-all">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            <span>WhatsApp</span>
                        </a>
                        <a href="{{ $settings['youtube_url'] ?? 'https://www.youtube.com/@dkuppkabprobolinggo' }}" target="_blank" rel="noopener noreferrer" class="p-2.5 bg-red-600 text-white rounded-xl font-bold flex items-center justify-center gap-2 shadow-xs hover:bg-red-700 transition-all">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            <span>YouTube</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 bg-white p-6 rounded-3xl border border-slate-200 shadow-xl space-y-4">
                <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3">Kirim Pesan Langsung</h3>
                <form action="{{ route('kontak.store') }}" method="POST" class="space-y-4 text-xs">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="font-bold text-slate-700 block mb-1">Nama Lengkap</label>
                            <input type="text" name="name" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                        </div>
                        <div>
                            <label class="font-bold text-slate-700 block mb-1">Email</label>
                            <input type="email" name="email" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="font-bold text-slate-700 block mb-1">Nomor Telepon / WA</label>
                            <input type="text" name="phone" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                        </div>
                        <div>
                            <label class="font-bold text-slate-700 block mb-1">Subjek</label>
                            <input type="text" name="subject" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="font-bold text-slate-700 block mb-1">Pesan Anda</label>
                        <textarea name="message" rows="4" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-600 focus:outline-none"></textarea>
                    </div>
                    <button type="submit" class="w-full py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-xs rounded-xl shadow-lg transition-colors">
                        Kirim Pesan ke DKUPP
                    </button>
                </form>
            </div>
        </div>

    </main>

    <footer class="bg-slate-950 text-slate-400 py-6 text-xs text-center border-t border-slate-800">
        <p>DKUPP Kabupaten Probolinggo © 2026</p>
    </footer>
    @include('partials.tts_widget')
</body>
</html>
