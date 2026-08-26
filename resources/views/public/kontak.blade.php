<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kontak & Lokasi Pelayanan | DKUPP Kabupaten Probolinggo</title>
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

    <main class="flex-grow py-8 sm:py-12 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 sm:space-y-10 w-full">
        
        <!-- Header Banner Section -->
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <span class="px-3.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-extrabold uppercase tracking-wider">Hubungi Kami</span>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Kontak & Lokasi Pelayanan</h1>
            <p class="text-xs sm:text-sm text-slate-500">Silakan hubungi kami atau kunjungi loket pelayanan DKUPP di Mal Pelayanan Publik (MPP) Kraksaan, Kabupaten Probolinggo.</p>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl flex items-center gap-2 shadow-xs">
                <i class="fas fa-check-circle text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Row 1: Informasi Kontak (5 Cols) & Form Kirim Pesan (7 Cols) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
            
            <!-- Left Card: Informasi Kontak & Media Sosial -->
            <div class="lg:col-span-5 bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-lg space-y-6">
                <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fas fa-address-card text-emerald-600"></i> Informasi Kontak Resmi
                </h3>
                
                <div class="space-y-4 text-xs text-slate-600">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 text-sm">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <strong class="text-slate-900 block text-xs">Alamat Kantor:</strong>
                            <span class="leading-relaxed">{{ $settings['address'] ?? 'Jl. Raya Panglima Sudirman No. 134 / Loket MPP Kraksaan, Kabupaten Probolinggo' }}</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 text-sm">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <strong class="text-slate-900 block text-xs">Telepon / WhatsApp CS:</strong>
                            <span>{{ $settings['phone'] ?? '(0335) 844554' }}</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 text-sm">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <strong class="text-slate-900 block text-xs">Email Resmi:</strong>
                            <span class="break-all font-mono">{{ $settings['email'] ?? 'dkupp@probolinggokab.go.id' }}</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 text-sm">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <strong class="text-slate-900 block text-xs">Jam Operasional Pelayanan:</strong>
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
                            <i class="fab fa-instagram text-sm"></i>
                            <span>Instagram</span>
                        </a>
                        <a href="{{ $settings['facebook_url'] ?? 'https://www.facebook.com/dkuppkabprobolinggo' }}" target="_blank" rel="noopener noreferrer" class="p-2.5 bg-blue-600 text-white rounded-xl font-bold flex items-center justify-center gap-2 shadow-xs hover:bg-blue-700 transition-all">
                            <i class="fab fa-facebook-f text-sm"></i>
                            <span>Facebook</span>
                        </a>
                        <a href="{{ $settings['tiktok_url'] ?? 'https://www.tiktok.com/@dkuppkabprobolinggo' }}" target="_blank" rel="noopener noreferrer" class="p-2.5 bg-slate-900 text-white rounded-xl font-bold flex items-center justify-center gap-2 shadow-xs hover:bg-black transition-all">
                            <i class="fab fa-tiktok text-sm"></i>
                            <span>TikTok</span>
                        </a>
                        <a href="{{ $settings['dkupp_whatsapp_url'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="p-2.5 bg-emerald-600 text-white rounded-xl font-bold flex items-center justify-center gap-2 shadow-xs hover:bg-emerald-700 transition-all">
                            <i class="fab fa-whatsapp text-sm"></i>
                            <span>WhatsApp</span>
                        </a>
                        <a href="{{ $settings['youtube_url'] ?? 'https://www.youtube.com/@dkuppkabprobolinggo' }}" target="_blank" rel="noopener noreferrer" class="p-2.5 bg-red-600 text-white rounded-xl font-bold flex items-center justify-center gap-2 shadow-xs hover:bg-red-700 transition-all">
                            <i class="fab fa-youtube text-sm"></i>
                            <span>YouTube</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Card: Kirim Pesan Form -->
            <div class="lg:col-span-7 bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-lg space-y-4">
                <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fas fa-paper-plane text-emerald-600"></i> Kirim Pesan / Aspirasi Langsung
                </h3>
                <form action="{{ route('kontak.store') }}" method="POST" class="space-y-4 text-xs">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="font-bold text-slate-700 block mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required placeholder="Nama Anda..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-600 focus:outline-none font-medium">
                        </div>
                        <div>
                            <label class="font-bold text-slate-700 block mb-1">Email <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" required placeholder="email@contoh.com" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-600 focus:outline-none font-medium">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="font-bold text-slate-700 block mb-1">Nomor Telepon / WA</label>
                            <input type="text" name="phone" placeholder="08..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-600 focus:outline-none font-medium">
                        </div>
                        <div>
                            <label class="font-bold text-slate-700 block mb-1">Subjek Pesan <span class="text-rose-500">*</span></label>
                            <input type="text" name="subject" required placeholder="Topik atau judul pesan..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-600 focus:outline-none font-medium">
                        </div>
                    </div>
                    <div>
                        <label class="font-bold text-slate-700 block mb-1">Pesan / Masukan Anda <span class="text-rose-500">*</span></label>
                        <textarea name="message" rows="4" required placeholder="Tuliskan pesan atau pertanyaan Anda di sini..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-600 focus:outline-none font-medium leading-relaxed"></textarea>
                    </div>
                    <button type="submit" class="w-full py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-xs rounded-xl shadow-lg transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> Kirim Pesan ke DKUPP
                    </button>
                </form>
            </div>

        </div>

        <!-- Row 2: Full-Width Peta Lokasi Google Maps Card -->
        @php
            $rawMap = $settings['google_map_embed'] ?? 'https://maps.google.com/maps?q=Kantor%20Bupati%20Probolinggo&t=&z=16&ie=UTF8&iwloc=&output=embed';
            if (str_contains($rawMap, 'src="')) {
                preg_match('/src="([^"]+)"/', $rawMap, $matches);
                if (!empty($matches[1])) {
                    $rawMap = $matches[1];
                }
            }
        @endphp
        <div class="w-full bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xl space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <h3 class="font-extrabold text-slate-900 text-base sm:text-lg flex items-center gap-2">
                    <i class="fas fa-map-marked-alt text-emerald-600"></i> Peta Lokasi Kantor & Pelayanan DKUPP
                </h3>
                <a href="{{ $rawMap }}" target="_blank" class="px-3.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl border border-emerald-200 transition-colors inline-flex items-center gap-1.5 shrink-0">
                    <i class="fas fa-external-link-alt text-[10px]"></i> Buka Google Maps Lengkap
                </a>
            </div>
            
            <!-- Map Responsive Iframe Container -->
            <div class="w-full h-80 sm:h-[480px] rounded-2xl overflow-hidden border border-slate-200 shadow-inner bg-slate-100 relative">
                <iframe src="{{ $rawMap }}" 
                        class="w-full h-full border-0" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

    </main>

    @include('partials.public_footer')
    @include('partials.tts_widget')
</body>
</html>
