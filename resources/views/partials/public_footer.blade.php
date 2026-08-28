<!-- Footer Resmi Model Disnakkeswan / DKUPP Kab. Probolinggo -->
<footer class="bg-[#09182b] text-slate-300 pt-6 sm:pt-12 pb-6 sm:pb-8 border-t border-slate-800 relative">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 space-y-4 sm:space-y-8">
        
        <!-- 3 Distinct Rounded Card Containers (3 Kolom Menyamping di Mobile & Desktop) -->
        <div class="grid grid-cols-3 lg:grid-cols-12 gap-2 sm:gap-6 items-stretch">
            
            <!-- KARTU 1: INSTANSI & SOSIAL MEDIA (KIRI - 1 COL MOBILE / 5 COLS DESKTOP) -->
            <div class="col-span-1 lg:col-span-5 bg-[#0f243e] rounded-xl sm:rounded-3xl border border-slate-700/70 p-2.5 sm:p-7 shadow-xl flex flex-col justify-between space-y-3 sm:space-y-6">
                <div class="space-y-2 sm:space-y-3">
                    <!-- Instansi Header Logo + Judul + Interaktif Garis Hijau Toska Berjalan -->
                    <div x-data="{ clicked: false }" 
                         @click="clicked = true; setTimeout(() => clicked = false, 2500)" 
                         class="flex items-center gap-1.5 sm:gap-3 cursor-pointer group select-none">
                        <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-full bg-black border border-slate-700/80 shadow-md flex items-center justify-center shrink-0 overflow-hidden">
                            <img src="{{ $settings['logo_frontend'] ?? 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Lambang_Kabupaten_Probolinggo.jpg/440px-Lambang_Kabupaten_Probolinggo.jpg' }}" 
                                 alt="Logo DKUPP" class="w-full h-full object-cover rounded-full scale-125">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-extrabold text-white text-[10px] sm:text-lg tracking-tight leading-tight group-hover:text-emerald-400 transition-colors line-clamp-1">
                                DKUPP Kab. Probolinggo
                            </h3>
                            <!-- Garis Hijau Toska Interaktif Berjalan -->
                            <div class="w-full h-0.5 sm:h-1 bg-slate-800/80 rounded-full mt-1 overflow-hidden relative">
                                <div class="h-full bg-gradient-to-r from-emerald-500 via-teal-300 to-emerald-400 rounded-full transition-all duration-500"
                                     :class="clicked ? 'w-full animate-running-line' : 'w-6 sm:w-10 group-hover:w-full'"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-[9px] sm:text-xs text-slate-300 leading-tight sm:leading-relaxed font-normal line-clamp-3 sm:line-clamp-none">
                        Dinas Koperasi, Usaha Mikro, Perdagangan dan Perindustrian Kabupaten Probolinggo berkomitmen mewujudkan kemandirian ekonomi & UMKM.
                    </p>
                </div>

                <!-- Media Sosial Resmi -->
                <div x-data="{ clicked: false }" 
                     @click="clicked = true; setTimeout(() => clicked = false, 2500)" 
                     class="pt-1 space-y-1.5 sm:space-y-2 cursor-pointer group select-none">
                    <div>
                        <span class="text-[9px] sm:text-[11px] font-extrabold text-slate-200 uppercase tracking-wider block group-hover:text-emerald-400 transition-colors line-clamp-1">
                            Media Sosial
                        </span>
                        <div class="w-full h-0.5 sm:h-1 bg-slate-800/80 rounded-full mt-0.5 overflow-hidden relative">
                            <div class="h-full bg-gradient-to-r from-emerald-500 via-teal-300 to-emerald-400 rounded-full transition-all duration-500"
                                 :class="clicked ? 'w-full animate-running-line' : 'w-6 sm:w-10 group-hover:w-full'"></div>
                        </div>
                    </div>

                    <!-- Social Media Outline Circular Buttons -->
                    <div class="flex flex-wrap items-center gap-1 sm:gap-2">
                        <a href="{{ $settings['facebook_url'] ?? 'https://www.facebook.com/dkuppkabprobolinggo' }}" target="_blank" rel="noopener noreferrer" title="Facebook"
                           class="w-6 h-6 sm:w-9 sm:h-9 rounded-full border border-slate-600/80 hover:border-emerald-400 hover:bg-emerald-400/10 text-slate-300 hover:text-emerald-400 flex items-center justify-center transition-all shadow-xs text-[9px] sm:text-xs">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="{{ $settings['instagram_url'] ?? 'https://www.instagram.com/dkuppkabprobolinggo/' }}" target="_blank" rel="noopener noreferrer" title="Instagram"
                           class="w-6 h-6 sm:w-9 sm:h-9 rounded-full border border-slate-600/80 hover:border-emerald-400 hover:bg-emerald-400/10 text-slate-300 hover:text-emerald-400 flex items-center justify-center transition-all shadow-xs text-[9px] sm:text-xs">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="{{ $settings['youtube_url'] ?? 'https://www.youtube.com/@dkuppkabprobolinggo' }}" target="_blank" rel="noopener noreferrer" title="YouTube"
                           class="w-6 h-6 sm:w-9 sm:h-9 rounded-full border border-slate-600/80 hover:border-emerald-400 hover:bg-emerald-400/10 text-slate-300 hover:text-emerald-400 flex items-center justify-center transition-all shadow-xs text-[9px] sm:text-xs">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="{{ $settings['tiktok_url'] ?? 'https://www.tiktok.com/@dkuppkabprobolinggo' }}" target="_blank" rel="noopener noreferrer" title="TikTok"
                           class="w-6 h-6 sm:w-9 sm:h-9 rounded-full border border-slate-600/80 hover:border-emerald-400 hover:bg-emerald-400/10 text-slate-300 hover:text-emerald-400 flex items-center justify-center transition-all shadow-xs text-[9px] sm:text-xs">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        @php
                            $footerWaUrl = $settings['dkupp_whatsapp_url'] ?? '';
                        @endphp
                        <a href="{{ $footerWaUrl }}" target="_blank" rel="noopener noreferrer" title="WhatsApp Resmi DKUPP"
                           class="w-6 h-6 sm:w-9 sm:h-9 rounded-full border border-slate-600/80 hover:border-emerald-400 hover:bg-emerald-400/10 text-slate-300 hover:text-emerald-400 flex items-center justify-center transition-all shadow-xs text-[9px] sm:text-xs">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- KARTU 2: SCAN KODE QR (TENGAH - 1 COL MOBILE / 3 COLS DESKTOP) -->
            <div id="footer-qr-code" class="col-span-1 lg:col-span-3 bg-[#0f243e] rounded-xl sm:rounded-3xl border border-slate-700/70 p-2.5 sm:p-7 shadow-xl flex flex-col items-center justify-between text-center space-y-2 sm:space-y-2.5 scroll-mt-24">
                <div x-data="{ clicked: false }" 
                     @click="clicked = true; setTimeout(() => clicked = false, 2500)" 
                     class="cursor-pointer group select-none w-full">
                    <span class="text-[9px] sm:text-[11px] font-extrabold text-slate-200 uppercase tracking-wider block group-hover:text-emerald-400 transition-colors line-clamp-1">
                        SCAN KODE QR
                    </span>
                    <div class="w-full h-0.5 sm:h-1 bg-slate-800/80 rounded-full mt-0.5 overflow-hidden relative">
                        <div class="h-full bg-gradient-to-r from-emerald-500 via-teal-300 to-emerald-400 rounded-full transition-all duration-500"
                             :class="clicked ? 'w-full animate-running-line' : 'w-6 sm:w-10 group-hover:w-full mx-auto'"></div>
                    </div>
                </div>

                <div class="p-1 sm:p-2 bg-white rounded-lg sm:rounded-xl shadow-lg border border-slate-200 inline-block transition-transform hover:scale-105">
                    @php
                        $qrRaw = $settings['qr_code_image'] ?? '';
                        if (empty($qrRaw)) {
                            $qrRaw = '/uploads/settings/qr_code_dkupp.svg';
                        }
                        $qrSrc = (str_starts_with($qrRaw, 'http://') || str_starts_with($qrRaw, 'https://')) ? $qrRaw : asset($qrRaw);
                    @endphp
                    <img src="{{ $qrSrc }}" alt="Scan QR Code DKUPP" class="w-16 h-16 sm:w-36 sm:h-36 object-contain rounded-md sm:rounded-lg">
                </div>

                <div>
                    <a href="{{ $settings['survey_url'] ?? 'https://sukma.jatimprov.go.id/' }}" target="_blank" rel="noopener noreferrer" class="text-[8px] sm:text-xs font-extrabold text-emerald-400 hover:underline flex items-center justify-center gap-0.5 leading-tight">
                        <i class="fas fa-hand-pointer text-[7px] sm:text-[9px]"></i>
                        <span class="line-clamp-1">{{ $settings['qr_code_label'] ?? 'Scan QR Portal Pelayanan' }}</span>
                    </a>
                    <span class="text-[8px] sm:text-[10px] text-slate-400 block mt-0.5 font-medium line-clamp-1">DKUPP Kab. Probolinggo</span>
                </div>
            </div>

            <!-- KARTU 3: ALAMAT KANTOR & KONTAK (KANAN - 1 COL MOBILE / 4 COLS DESKTOP) -->
            <div class="col-span-1 lg:col-span-4 bg-[#0f243e] rounded-xl sm:rounded-3xl border border-slate-700/70 p-2.5 sm:p-7 shadow-xl flex flex-col justify-between space-y-2 sm:space-y-4">
                <div class="space-y-2 sm:space-y-3">
                    <div x-data="{ clicked: false }" 
                         @click="clicked = true; setTimeout(() => clicked = false, 2500)" 
                         class="cursor-pointer group select-none">
                        <h3 class="font-extrabold text-white text-[10px] sm:text-lg tracking-tight group-hover:text-emerald-400 transition-colors line-clamp-1">
                            Alamat Kantor
                        </h3>
                        <div class="w-full h-0.5 sm:h-1 bg-slate-800/80 rounded-full mt-0.5 overflow-hidden relative">
                            <div class="h-full bg-gradient-to-r from-emerald-500 via-teal-300 to-emerald-400 rounded-full transition-all duration-500"
                                 :class="clicked ? 'w-full animate-running-line' : 'w-6 sm:w-10 group-hover:w-full'"></div>
                        </div>
                    </div>

                    <ul class="text-[9px] sm:text-xs text-slate-300 space-y-1.5 sm:space-y-2.5 font-normal">
                        <li class="flex items-start gap-1.5 sm:gap-2.5">
                            <i class="fas fa-map-marker-alt text-emerald-400 text-[9px] sm:text-xs mt-0.5 shrink-0"></i>
                            <span class="leading-tight sm:leading-relaxed line-clamp-3 sm:line-clamp-none">{{ $settings['address'] ?? 'Jl. Raya Panglima Sudirman No. 134 / Loket MPP Kraksaan-Kabupaten Probolinggo' }}</span>
                        </li>
                        <li class="flex items-start gap-1.5 sm:gap-2.5">
                            <i class="fas fa-phone-alt text-emerald-400 text-[9px] sm:text-xs mt-0.5 shrink-0"></i>
                            <span class="line-clamp-2 sm:line-clamp-none">Phone: {{ $settings['phone'] ?? '(0335) 844554' }}</span>
                        </li>
                        <li class="flex items-start gap-1.5 sm:gap-2.5">
                            <i class="fas fa-envelope text-emerald-400 text-[9px] sm:text-xs mt-0.5 shrink-0"></i>
                            <span class="break-all line-clamp-2 sm:line-clamp-none">Email: {{ $settings['email'] ?? 'dkupp@probolinggokab.go.id' }}</span>
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
