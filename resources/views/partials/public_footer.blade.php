<!-- Footer Resmi Model Disnakkeswan / DKUPP Kab. Probolinggo -->
<footer class="bg-[#09182b] text-slate-300 pt-8 sm:pt-12 pb-6 sm:pb-8 border-t border-slate-800 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">
        
        <!-- 3 Distinct Rounded Card Containers -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 sm:gap-6 items-stretch">
            
            <!-- KARTU 1: INSTANSI & SOSIAL MEDIA (KIRI - 5 COLS) -->
            <div class="lg:col-span-5 bg-[#0f243e] rounded-2xl sm:rounded-3xl border border-slate-700/70 p-4.5 sm:p-7 shadow-xl flex flex-col justify-between space-y-4 sm:space-y-6">
                <div class="space-y-3">
                    <!-- Instansi Header Logo + Judul + Interaktif Garis Hijau Toska Berjalan -->
                    <div x-data="{ clicked: false }" 
                         @click="clicked = true; setTimeout(() => clicked = false, 2500)" 
                         class="flex items-center gap-3 cursor-pointer group select-none">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-white p-1 border border-slate-200 shadow-md flex items-center justify-center shrink-0">
                            <img src="{{ $settings['logo_frontend'] ?? 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Lambang_Kabupaten_Probolinggo.jpg/440px-Lambang_Kabupaten_Probolinggo.jpg' }}" 
                                 alt="Logo DKUPP" class="w-full h-full object-contain">
                        </div>
                        <div class="flex-1">
                            <h3 class="font-extrabold text-white text-sm sm:text-lg tracking-tight leading-none group-hover:text-emerald-400 transition-colors">
                                DKUPP Kab. Probolinggo
                            </h3>
                            <!-- Garis Hijau Toska Interaktif Berjalan -->
                            <div class="w-full h-1 bg-slate-800/80 rounded-full mt-1.5 overflow-hidden relative">
                                <div class="h-full bg-gradient-to-r from-emerald-500 via-teal-300 to-emerald-400 rounded-full transition-all duration-500"
                                     :class="clicked ? 'w-full animate-running-line' : 'w-10 group-hover:w-full'"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-xs text-slate-300 leading-relaxed font-normal">
                        Dinas Koperasi, Usaha Mikro, Perdagangan dan Perindustrian Kabupaten Probolinggo berkomitmen mewujudkan kemandirian ekonomi, pemberdayaan UMKM, pengelolaan pasar, dan metrologi legal secara prima.
                    </p>
                </div>

                <!-- Media Sosial Resmi (Dengan Garis Hijau Toska Interaktif Berjalan) -->
                <div x-data="{ clicked: false }" 
                     @click="clicked = true; setTimeout(() => clicked = false, 2500)" 
                     class="pt-1 space-y-2 cursor-pointer group select-none">
                    <div>
                        <span class="text-[10px] sm:text-[11px] font-extrabold text-slate-200 uppercase tracking-widest block group-hover:text-emerald-400 transition-colors">
                            Media Sosial Resmi
                        </span>
                        <!-- Garis Hijau Toska Interaktif Berjalan -->
                        <div class="w-full h-1 bg-slate-800/80 rounded-full mt-1 overflow-hidden relative">
                            <div class="h-full bg-gradient-to-r from-emerald-500 via-teal-300 to-emerald-400 rounded-full transition-all duration-500"
                                 :class="clicked ? 'w-full animate-running-line' : 'w-10 group-hover:w-full'"></div>
                        </div>
                    </div>

                    <!-- Social Media Outline Circular Buttons -->
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ $settings['facebook_url'] ?? 'https://www.facebook.com/dkuppkabprobolinggo' }}" target="_blank" rel="noopener noreferrer" title="Facebook"
                           class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-slate-600/80 hover:border-emerald-400 hover:bg-emerald-400/10 text-slate-300 hover:text-emerald-400 flex items-center justify-center transition-all shadow-xs">
                            <i class="fab fa-facebook-f text-xs"></i>
                        </a>
                        <a href="{{ $settings['instagram_url'] ?? 'https://www.instagram.com/dkuppkabprobolinggo/' }}" target="_blank" rel="noopener noreferrer" title="Instagram"
                           class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-slate-600/80 hover:border-emerald-400 hover:bg-emerald-400/10 text-slate-300 hover:text-emerald-400 flex items-center justify-center transition-all shadow-xs">
                            <i class="fab fa-instagram text-xs"></i>
                        </a>
                        <a href="{{ $settings['youtube_url'] ?? 'https://www.youtube.com/@dkuppkabprobolinggo' }}" target="_blank" rel="noopener noreferrer" title="YouTube"
                           class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-slate-600/80 hover:border-emerald-400 hover:bg-emerald-400/10 text-slate-300 hover:text-emerald-400 flex items-center justify-center transition-all shadow-xs">
                            <i class="fab fa-youtube text-xs"></i>
                        </a>
                        <a href="{{ $settings['tiktok_url'] ?? 'https://www.tiktok.com/@dkuppkabprobolinggo' }}" target="_blank" rel="noopener noreferrer" title="TikTok"
                           class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-slate-600/80 hover:border-emerald-400 hover:bg-emerald-400/10 text-slate-300 hover:text-emerald-400 flex items-center justify-center transition-all shadow-xs">
                            <i class="fab fa-tiktok text-xs"></i>
                        </a>
                        <a href="{{ $settings['whatsapp_url'] ?? 'https://wa.me/6281234567890' }}" target="_blank" rel="noopener noreferrer" title="WhatsApp"
                           class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-slate-600/80 hover:border-emerald-400 hover:bg-emerald-400/10 text-slate-300 hover:text-emerald-400 flex items-center justify-center transition-all shadow-xs">
                            <i class="fab fa-whatsapp text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- KARTU 2: SCAN KODE QR (TENGAH - 3 COLS) -->
            <div class="lg:col-span-3 bg-[#0f243e] rounded-2xl sm:rounded-3xl border border-slate-700/70 p-4.5 sm:p-7 shadow-xl flex flex-col items-center justify-between text-center space-y-2.5">
                <div x-data="{ clicked: false }" 
                     @click="clicked = true; setTimeout(() => clicked = false, 2500)" 
                     class="cursor-pointer group select-none w-full">
                    <span class="text-[10px] sm:text-[11px] font-extrabold text-slate-200 uppercase tracking-widest block group-hover:text-emerald-400 transition-colors">
                        SCAN KODE QR
                    </span>
                    <!-- Garis Hijau Toska Interaktif Berjalan -->
                    <div class="w-full h-1 bg-slate-800/80 rounded-full mt-1 overflow-hidden relative">
                        <div class="h-full bg-gradient-to-r from-emerald-500 via-teal-300 to-emerald-400 rounded-full transition-all duration-500"
                             :class="clicked ? 'w-full animate-running-line' : 'w-10 group-hover:w-full mx-auto'"></div>
                    </div>
                </div>

                <div class="p-2 bg-white rounded-xl shadow-lg border border-slate-200 inline-block transition-transform hover:scale-105">
                    @php
                        $qrRaw = $settings['qr_code_image'] ?? '';
                        if (empty($qrRaw)) {
                            $qrRaw = '/uploads/settings/qr_code_dkupp.svg';
                        }
                        $qrSrc = (str_starts_with($qrRaw, 'http://') || str_starts_with($qrRaw, 'https://')) ? $qrRaw : asset($qrRaw);
                    @endphp
                    <img src="{{ $qrSrc }}" alt="Scan QR Code DKUPP" class="w-28 h-28 sm:w-36 sm:h-36 object-contain rounded-lg">
                </div>

                <div>
                    <a href="{{ $settings['survey_url'] ?? 'https://sukma.jatimprov.go.id/' }}" target="_blank" rel="noopener noreferrer" class="text-[11px] sm:text-xs font-extrabold text-emerald-400 hover:underline flex items-center justify-center gap-1">
                        <i class="fas fa-hand-pointer text-[9px]"></i>
                        <span>{{ $settings['qr_code_label'] ?? 'Scan QR Portal Pelayanan' }}</span>
                    </a>
                    <span class="text-[9px] sm:text-[10px] text-slate-400 block mt-0.5 font-medium">DKUPP Kab. Probolinggo</span>
                </div>
            </div>

            <!-- KARTU 3: ALAMAT KANTOR & KONTAK (KANAN - 4 COLS) -->
            <div class="sm:col-span-2 lg:col-span-4 bg-[#0f243e] rounded-2xl sm:rounded-3xl border border-slate-700/70 p-4.5 sm:p-7 shadow-xl flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div x-data="{ clicked: false }" 
                         @click="clicked = true; setTimeout(() => clicked = false, 2500)" 
                         class="cursor-pointer group select-none">
                        <h3 class="font-extrabold text-white text-sm sm:text-lg tracking-tight group-hover:text-emerald-400 transition-colors">
                            Alamat Kantor
                        </h3>
                        <!-- Garis Hijau Toska Interaktif Berjalan -->
                        <div class="w-full h-1 bg-slate-800/80 rounded-full mt-1 overflow-hidden relative">
                            <div class="h-full bg-gradient-to-r from-emerald-500 via-teal-300 to-emerald-400 rounded-full transition-all duration-500"
                                 :class="clicked ? 'w-full animate-running-line' : 'w-10 group-hover:w-full'"></div>
                        </div>
                    </div>

                    <ul class="text-xs text-slate-300 space-y-2.5 font-normal">
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-map-marker-alt text-emerald-400 text-xs mt-0.5 shrink-0"></i>
                            <span class="leading-relaxed">{{ $settings['address'] ?? 'Jl. Raya Panglima Sudirman No. 134 / Loket MPP Kraksaan-Kabupaten Probolinggo' }}</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-phone-alt text-emerald-400 text-xs mt-0.5 shrink-0"></i>
                            <span>Phone: {{ $settings['phone'] ?? '(0335) 844554' }}</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-envelope text-emerald-400 text-xs mt-0.5 shrink-0"></i>
                            <span class="break-all">Email: {{ $settings['email'] ?? 'dkupp@probolinggokab.go.id' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        <!-- Base Footer Bar & Security Button -->
        <div class="pt-4 sm:pt-6 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-[10px] sm:text-[11px] text-slate-400">
            <!-- Shield Security Icon Button (Left) -->
            <div class="flex items-center gap-2">
                <button title="Sistem Terlindungi & Terverifikasi Resmi" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-[#0f243e] border border-slate-700 text-slate-300 hover:text-emerald-400 flex items-center justify-center transition-colors shadow-xs">
                    <i class="fas fa-shield-alt text-xs sm:text-sm"></i>
                </button>
            </div>

            <!-- Copyright Notice (Center) -->
            <div class="text-center sm:text-right font-medium">
                <p>DKUPP Kab. Probolinggo - Kabupaten Probolinggo © 2026. All Rights Reserved.</p>
            </div>
        </div>

    </div>
</footer>
