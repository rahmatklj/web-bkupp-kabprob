<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman Login | DKUPP Kabupaten Probolinggo</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome & Tailwind CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-white min-h-screen font-sans text-slate-800 antialiased flex flex-col justify-between"
      x-data="{ 
          email: '{{ old('email', 'admin@dkupp.probolinggokab.go.id') }}', 
          password: 'admin123',
          showPassword: false, 
          showForgotModal: false,
          forgotEmail: '{{ old('email', 'admin@dkupp.probolinggokab.go.id') }}',
          forgotPhone: '',
          forgotReferral: '',
          forgotSuccess: false,
          captchaCode: '{{ $captchaData['code'] }}', 
          captchaSvg: '{{ $captchaData['svg'] }}',
          captchaInput: '',
          activeSlide: 0,
          totalSlides: {{ isset($sliders) && count($sliders) > 0 ? count($sliders) : 1 }}
      }"
      x-init="if(totalSlides > 1) setInterval(() => { activeSlide = (activeSlide + 1) % totalSlides }, 6000)">

    <div class="min-h-screen w-full flex flex-col lg:flex-row">
        
        <!-- LEFT PANEL: Hero Banner Slider Background & Probolinggo Branding -->
        <div class="lg:w-1/2 bg-slate-950 relative flex flex-col justify-between p-8 lg:p-16 overflow-hidden border-r border-slate-800 min-h-[400px] lg:min-h-screen">
            
            <!-- Dynamic Banner Slider Background Images -->
            @if(isset($sliders) && count($sliders) > 0)
                @foreach($sliders as $idx => $slide)
                    <div x-show="activeSlide === {{ $idx }}"
                         x-transition:enter="transition opacity duration-1000 ease-out"
                         x-transition:enter-start="opacity-0 scale-105"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition opacity duration-700 ease-in"
                         class="absolute inset-0 w-full h-full bg-cover bg-center bg-no-repeat"
                         style="background-image: url('{{ $slide->image_url }}');">
                    </div>
                @endforeach
            @else
                <div class="absolute inset-0 w-full h-full bg-cover bg-center bg-no-repeat"
                     style="background-image: url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1200&auto=format&fit=crop');">
                </div>
            @endif

            <!-- Ultra Light Overlay for 100% Clear & Crisp Photo Visibility -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-black/20 to-black/30"></div>

            <!-- Top Logos & Title -->
            <div class="relative z-10 space-y-6 max-w-lg mx-auto lg:mx-0 pt-4 text-white">
                <div class="flex items-center gap-4">
                    <img src="{{ $settings['logo_frontend'] ?? 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Lambang_Kabupaten_Probolinggo.jpg/440px-Lambang_Kabupaten_Probolinggo.jpg' }}" 
                         alt="Logo Probolinggo" 
                         class="h-20 w-auto object-contain drop-shadow-[0_4px_12px_rgba(0,0,0,0.8)]">
                    <div>
                        <span class="font-extrabold text-2xl tracking-wide bg-gradient-to-r from-amber-400 via-rose-300 to-emerald-400 bg-clip-text text-transparent block drop-shadow-[0_2px_6px_rgba(0,0,0,0.9)]">endless</span>
                        <span class="font-extrabold text-3xl tracking-tight text-cyan-300 block -mt-2 drop-shadow-[0_2px_6px_rgba(0,0,0,0.9)]">probo<span class="text-amber-300">linggo</span></span>
                    </div>
                </div>

                <div class="pt-4 space-y-2">
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-white leading-snug tracking-tight drop-shadow-[0_3px_10px_rgba(0,0,0,0.95)]">
                        Dinas Koperasi, Usaha Mikro, Perdagangan dan Perindustrian
                    </h2>
                    <p class="text-base font-bold text-emerald-400 tracking-wide drop-shadow-[0_2px_6px_rgba(0,0,0,0.9)]">Kabupaten Probolinggo</p>
                </div>

                <!-- Minimalist Banner Slider Nav Dots -->
                @if(isset($sliders) && count($sliders) > 1)
                    <div class="pt-3 flex items-center gap-2">
                        @foreach($sliders as $idx => $slide)
                            <button type="button" @click="activeSlide = {{ $idx }}" 
                                    class="h-2 rounded-full transition-all duration-300 shadow-md"
                                    :class="activeSlide === {{ $idx }} ? 'w-8 bg-emerald-400' : 'w-2 bg-white/40 hover:bg-white'"></button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Bottom City Skyline Illustration Vector -->
            <div class="relative z-10 pt-12 mt-auto">
                <svg class="w-full h-28 text-emerald-400/40 opacity-80" viewBox="0 0 1200 120" preserveAspectRatio="none" fill="currentColor">
                    <path d="M0,120 L0,80 L30,80 L30,40 L60,40 L60,80 L90,80 L90,20 L130,20 L130,80 L160,80 L160,50 L200,50 L200,80 L230,80 L230,10 L280,10 L280,80 L320,80 L320,60 L360,60 L360,80 L400,80 L400,30 L450,30 L450,80 L500,80 L500,0 L560,0 L560,80 L610,80 L610,40 L660,40 L660,80 L710,80 L710,15 L770,15 L770,80 L820,80 L820,45 L880,45 L880,80 L930,80 L930,25 L990,25 L990,80 L1050,80 L1050,55 L1110,55 L1110,80 L1200,80 L1200,120 Z"></path>
                </svg>
            </div>
        </div>

        <!-- RIGHT PANEL: Login Form & Exact Captcha Box Matching Screenshot -->
        <div class="lg:w-1/2 bg-slate-50/50 flex flex-col justify-between p-8 lg:p-16">
            
            <div class="max-w-md w-full mx-auto my-auto space-y-6">
                
                <div class="text-center space-y-2">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Login Admin</h1>
                </div>

                @if($errors->any())
                    <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center gap-2">
                        <i class="fas fa-exclamation-circle text-rose-500 text-sm"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- Demo Account Quick Selector -->
                <div class="bg-white p-3 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider text-center">Petunjuk / Isi Otomatis Akun Demo:</p>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" @click="email = 'admin@dkupp.probolinggokab.go.id'; password = 'admin123';" 
                                :class="email === 'admin@dkupp.probolinggokab.go.id' ? 'bg-emerald-700 text-white font-bold' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'"
                                class="py-2 px-3 rounded-xl text-xs flex flex-col items-center border border-slate-200 transition-all">
                            <span>Super Admin</span>
                            <span class="text-[9px] opacity-80">(Auto-isi akun)</span>
                        </button>
                        <button type="button" @click="email = 'staf@dkupp.probolinggokab.go.id'; password = 'admin123';" 
                                :class="email === 'staf@dkupp.probolinggokab.go.id' ? 'bg-blue-600 text-white font-bold' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'"
                                class="py-2 px-3 rounded-xl text-xs flex flex-col items-center border border-slate-200 transition-all">
                            <span>Staf Pelayanan</span>
                            <span class="text-[9px] opacity-80">(Auto-isi akun)</span>
                        </button>
                    </div>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-5 text-xs">
                    @csrf

                    <!-- Field 1: Username / Email -->
                    <div>
                        <label class="block font-medium text-slate-600 mb-1.5 text-xs">Username <span class="text-rose-500">*</span></label>
                        <input type="text" name="email" required x-model="email" placeholder="Masukkan Username / Email" autocomplete="off"
                               class="w-full px-4 py-3 bg-[#f0f4f8] border-0 rounded-lg focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none font-medium text-slate-800 text-sm">
                    </div>

                    <!-- Field 2: Password with Toggle Eye Icon & Lupa Sandi Link Underneath -->
                    <div class="space-y-1.5">
                        <label class="block font-medium text-slate-600 text-xs">Password <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" name="password" required x-model="password" placeholder="Masukkan Password" autocomplete="new-password"
                                   class="w-full px-4 py-3 bg-[#f0f4f8] border-0 rounded-lg focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none font-medium text-slate-800 text-sm pr-10">
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                <i class="far" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        <div class="flex justify-end pt-0.5">
                            <button type="button" @click="showForgotModal = true" class="text-xs font-extrabold text-blue-600 hover:text-blue-800 hover:underline transition-colors flex items-center gap-1 cursor-pointer">
                                <i class="fas fa-key text-[10px]"></i> Lupa password?
                            </button>
                        </div>
                    </div>

                    <!-- Field 3: Captcha Box (Exact Match to Screenshot) -->
                    <div class="space-y-2">
                        <label class="block font-medium text-slate-600 text-xs">Masukkan kode berikut</label>
                        
                        <!-- Captcha Graphical Box & Refresh Button -->
                        <div class="flex items-center h-12">
                            <div class="flex-1 h-full rounded-l-lg overflow-hidden border border-r-0 border-slate-300 bg-[#e5e7eb]">
                                <img :src="captchaSvg" alt="Captcha Code" class="w-full h-full object-cover select-none">
                            </div>
                            <button type="button" 
                                    @click="fetch('{{ route('captcha.refresh') }}').then(r => r.json()).then(d => { captchaCode = d.code; captchaSvg = d.svg; captchaInput = ''; })" 
                                    title="Acak Ulang Kode" 
                                    class="h-full px-4 bg-[#2ecc71] hover:bg-[#27ae60] text-white rounded-r-lg flex items-center justify-center transition-colors shadow-xs">
                                <i class="fas fa-sync-alt text-base"></i>
                            </button>
                        </div>

                        <!-- Captcha Input Field -->
                        <input type="text" name="captcha" required x-model="captchaInput" placeholder="Ketik kode di gambar..." maxlength="6" autocomplete="off"
                               class="w-full px-4 py-3 bg-[#f0f4f8] border-0 rounded-lg focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none font-bold text-slate-800 text-sm tracking-widest">
                    </div>

                    <!-- Bottom Action Buttons (Kembali & Masuk) -->
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <a href="{{ route('home') }}" class="py-3 px-4 bg-[#e2e8f0] hover:bg-slate-300 text-slate-700 rounded-lg font-bold text-xs text-center transition-all flex items-center justify-center">
                            Kembali
                        </a>
                        <button type="submit" class="py-3 px-4 bg-[#00a8ff] hover:bg-blue-600 text-white rounded-lg font-bold text-xs shadow-md transition-all flex items-center justify-center">
                            Masuk
                        </button>
                    </div>

                </form>

            </div>

            <div class="text-center pt-8 text-[11px] text-slate-400">
                <p>2026 Dinas Koperasi, Usaha Mikro, Perdagangan dan Perindustrian Kabupaten Probolinggo</p>
            </div>

        </div>

    </div>

    <!-- Ultra-Premium Instagram-Style Modal Popup Lupa Kata Sandi -->
    <div x-show="showForgotModal" 
         x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90 backdrop-blur-none"
         x-transition:enter-end="opacity-100 scale-100 backdrop-blur-md"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 backdrop-blur-md"
         x-transition:leave-end="opacity-0 scale-95 backdrop-blur-none"
         class="fixed inset-0 z-50 p-4 bg-slate-950/70 backdrop-blur-md flex items-center justify-center overflow-y-auto">
        
        <div @click.away="showForgotModal = false" 
             class="bg-white rounded-3xl p-6 sm:p-7 max-w-md w-full shadow-[0_25px_60px_-15px_rgba(0,0,0,0.35)] text-center border border-slate-100 relative my-auto space-y-4 transform transition-all">
            
            <!-- Close Button Top Right -->
            <button type="button" @click="showForgotModal = false; forgotSuccess = false;" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 w-8 h-8 rounded-full hover:bg-slate-100 flex items-center justify-center transition-colors">
                <i class="fas fa-times text-base"></i>
            </button>

            <!-- Instagram Signature Story Gradient Ring & Dynamic Website Logo Image (DIBIARKAN ATAU TETAP LOGO WEBSITE) -->
            <div class="pt-1 flex justify-center">
                <div class="p-[3px] bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 rounded-full shadow-lg hover:scale-105 transition-transform duration-300">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center p-1 shadow-inner overflow-hidden border border-slate-100">
                        <img src="{{ $settings['logo_frontend'] ?? 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Lambang_Kabupaten_Probolinggo.jpg/440px-Lambang_Kabupaten_Probolinggo.jpg' }}" 
                             alt="Logo Website" 
                             class="w-full h-full object-cover rounded-full">
                    </div>
                </div>
            </div>

            <!-- Header Title & Subtitle -->
            <div class="space-y-1.5 px-1">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gradient-to-r from-purple-50 via-rose-50 to-amber-50 text-purple-700 font-extrabold text-[10px] uppercase tracking-widest rounded-full border border-purple-100/80 shadow-2xs">
                    <i class="fas fa-key text-rose-500"></i> Lupa Password
                </span>
                <h3 class="font-extrabold text-slate-900 text-lg tracking-tight">Pemulihan Kata Sandi Akun</h3>
                <p class="text-[11px] text-slate-500 leading-relaxed px-1">
                    Lengkapi Email / Username, Nomor WhatsApp, dan Kode Referral Anda di bawah ini untuk memaksa masuk ke akun Anda
                </p>
            </div>

            <!-- Form Body (Gaya Foto ke-2 dengan Field Lengkap & Boks Gmail Style) -->
            <template x-if="!forgotSuccess">
                <div class="space-y-3.5 text-xs text-left">
                    <!-- Field 1: Username / Email Resmi Administrator -->
                    <div class="space-y-1">
                        <div class="flex justify-between items-center">
                            <label class="font-bold text-slate-800 text-xs">Email / Username Login <span class="text-rose-500">*</span></label>
                            <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-1" x-show="forgotEmail && forgotEmail.endsWith('@gmail.com')">
                                <i class="fas fa-check text-[9px]"></i> Berakhiran @gmail.com
                            </span>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-user text-xs"></i>
                            </div>
                            <input type="text" x-model="forgotEmail" placeholder="sukma@gmail.com atau username" 
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50/80 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-600 focus:border-purple-600 focus:outline-none font-medium text-slate-800 text-xs transition-all shadow-2xs">
                        </div>
                        <p class="text-[10px] text-blue-600 font-medium flex items-center gap-1 pt-0.5">
                            <i class="fas fa-info-circle text-xs"></i> Email terdaftar pengguna administrator
                        </p>
                    </div>

                    <!-- Field 2 & 3 Grid: No. WhatsApp & Kode Referral (Layout Foto ke-2) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- No. WhatsApp (Validasi 1) -->
                        <div class="space-y-1">
                            <div class="flex justify-between items-center">
                                <label class="font-bold text-slate-800 text-[11px] flex items-center gap-1">
                                    <i class="fab fa-whatsapp text-emerald-600"></i> No. WA (Validasi 1)
                                </label>
                            </div>
                            <input type="text" x-model="forgotPhone" placeholder="089876543210" 
                                   class="w-full px-3 py-2 bg-slate-50/80 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none font-medium text-slate-800 text-xs transition-all shadow-2xs">
                            <p class="text-[9px] text-slate-500 flex items-center gap-1">
                                <i class="fas fa-info-circle text-emerald-600 text-[9px]"></i> 11-13 digit angka
                            </p>
                        </div>

                        <!-- Kode Referral (Validasi 2) -->
                        <div class="space-y-1">
                            <div class="flex justify-between items-center">
                                <label class="font-bold text-slate-800 text-[11px] flex items-center gap-1">
                                    <i class="fas fa-key text-blue-600"></i> Kode Referral (Validasi 2)
                                </label>
                            </div>
                            <input type="text" x-model="forgotReferral" placeholder="SUK202" 
                                   class="w-full px-3 py-2 bg-slate-50/80 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none font-bold text-slate-800 text-xs transition-all shadow-2xs">
                            <p class="text-[9px] text-slate-500 flex items-center gap-1">
                                <i class="fas fa-info-circle text-blue-600 text-[9px]"></i> Contoh: <strong class="text-blue-600">ADI123</strong>
                            </p>
                        </div>
                    </div>

                    <!-- Gmail Style Combination Requirement Box (Fitur Foto ke-2) -->
                    <div class="p-3 bg-slate-50/90 border border-slate-200/80 rounded-2xl space-y-1.5 text-slate-700">
                        <h5 class="text-[9px] font-extrabold text-slate-700 tracking-wider uppercase">SYARAT KOMBINASI INSTRUKSI PEMULIHAN AKUN (GMAIL STYLE):</h5>
                        <div class="grid grid-cols-2 gap-x-3 gap-y-1 text-[10px] font-medium">
                            <div class="flex items-center gap-1.5" :class="forgotEmail && forgotEmail.length >= 8 ? 'text-emerald-700 font-bold' : 'text-slate-500'">
                                <i class="far" :class="forgotEmail && forgotEmail.length >= 8 ? 'fa-check-circle text-emerald-600' : 'fa-circle text-slate-300'"></i> 
                                <span>Min. 8 Karakter</span>
                            </div>
                            <div class="flex items-center gap-1.5" :class="forgotEmail && /[A-Z]/.test(forgotEmail) ? 'text-emerald-700 font-bold' : 'text-slate-500'">
                                <i class="far" :class="forgotEmail && /[A-Z]/.test(forgotEmail) ? 'fa-check-circle text-emerald-600' : 'fa-circle text-slate-300'"></i> 
                                <span>Huruf Besar (A-Z)</span>
                            </div>
                            <div class="flex items-center gap-1.5" :class="forgotEmail && /[a-z]/.test(forgotEmail) ? 'text-emerald-700 font-bold' : 'text-slate-500'">
                                <i class="far" :class="forgotEmail && /[a-z]/.test(forgotEmail) ? 'fa-check-circle text-emerald-600' : 'fa-circle text-slate-300'"></i> 
                                <span>Huruf Kecil (a-z)</span>
                            </div>
                            <div class="flex items-center gap-1.5" :class="forgotPhone && forgotPhone.length >= 10 ? 'text-emerald-700 font-bold' : 'text-slate-500'">
                                <i class="far" :class="forgotPhone && forgotPhone.length >= 10 ? 'fa-check-circle text-emerald-600' : 'fa-circle text-slate-300'"></i> 
                                <span>No. WA Valid</span>
                            </div>
                            <div class="col-span-2 flex items-center gap-1.5" :class="forgotReferral ? 'text-emerald-700 font-bold' : 'text-slate-500'">
                                <i class="far" :class="forgotReferral ? 'fa-check-circle text-emerald-600' : 'fa-circle text-slate-300'"></i> 
                                <span>Kode Referral (3 Huruf + 3 Angka)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Form POST untuk Memaksa Masuk ke Akun -->
                    <form action="{{ route('force.login') }}" method="POST" class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100 mt-2">
                        @csrf
                        <input type="hidden" name="email" :value="forgotEmail || email">
                        <button type="button" @click="showForgotModal = false; forgotSuccess = false;" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer flex items-center gap-1.5">
                            <i class="fas fa-arrow-left text-[11px]"></i>
                            <span>Batal</span>
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95 cursor-pointer flex items-center gap-2">
                            <i class="fas fa-sign-in-alt text-xs"></i>
                            <span>Memaksa Masuk ke Akun</span>
                        </button>
                    </form>
                </div>
            </template>

            <!-- Success State Screen -->
            <template x-if="forgotSuccess">
                <div class="space-y-4 text-xs py-2">
                    <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-2xl shadow-inner animate-bounce">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="space-y-1 text-center">
                        <h4 class="font-extrabold text-slate-900 text-base">Instruksi Pemulihan Siap!</h4>
                        <p class="text-xs text-slate-500 leading-relaxed px-1">
                            Pesan bantuan pemulihan untuk akun <strong class="text-emerald-700 font-extrabold" x-text="forgotEmail || email"></strong> (Nomor WA: <span x-text="forgotPhone || '-'"></span>) telah disiapkan. Klik tombol di bawah untuk membuka WhatsApp Admin.
                        </p>
                    </div>
                    <a :href="'https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['dkupp_whatsapp_url'] ?? '628123123911') }}?text=Halo%20Admin%20DKUPP,%20saya%20minta%20instruksi%20pemulihan%20kata%20sandi%20akun%20saya.%0A-%20Username%2FEmail%3A%20' + encodeURIComponent(forgotEmail || email) + '%0A-%20Nomor%20WA%20Pemohon%3A%20' + encodeURIComponent(forgotPhone || '-') + (forgotReferral ? '%0A-%20Kode%20Referral%3A%20' + encodeURIComponent(forgotReferral) : '') + '%0AMohon%20bantuan%20reset%20password.'" 
                       target="_blank" 
                       class="w-full py-3.5 px-5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-extrabold text-xs shadow-md transition-all flex items-center justify-center gap-2 active:scale-95 cursor-pointer">
                        <i class="fab fa-whatsapp text-lg"></i>
                        <span>Buka Chat WhatsApp Admin</span>
                    </a>
                    <div class="pt-2 border-t border-slate-100">
                        <button type="button" @click="showForgotModal = false; forgotSuccess = false;" class="w-full py-2 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors cursor-pointer flex items-center justify-center gap-1.5">
                            <i class="fas fa-arrow-left text-[11px]"></i>
                            <span>Kembali ke Halaman Login</span>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

</body>
</html>
