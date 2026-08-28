<style>
html, body {
    overflow-x: hidden !important;
    max-width: 100vw !important;
}
@keyframes navbarRunningLine {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
.animate-running-line {
    background-size: 200% 200% !important;
    animation: navbarRunningLine 1.2s ease infinite !important;
}
/* Custom Smooth Scrollbar for Submenus */
.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 9999px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 9999px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #059669;
}
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}
</style>

<!-- Top Bar (Minimalist & Responsive without Horizontal Overflow) -->
<div class="bg-slate-900 text-slate-200 text-[10px] sm:text-xs py-1.5 px-3 sm:px-6 w-full overflow-hidden">
    <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
        <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
            <span class="inline-flex items-center gap-1 font-semibold text-emerald-400 text-[10px] sm:text-xs shrink-0">
                <i class="fas fa-headset text-[9px]"></i> HOTLINE DKUPP
            </span>
            <span class="hidden sm:inline font-medium tracking-wide text-[10px] sm:text-xs">{{ $settings['phone'] ?? '(0335) 844554' }}</span>
            <span class="hidden lg:inline text-slate-700">|</span>
            <span class="hidden lg:inline font-medium text-[10px] sm:text-xs">
                <i class="fas fa-clock me-1"></i> Jam Layanan MPP: Sen - Jum (08.00 - 16.00 WIB)
            </span>
        </div>
        <div class="flex items-center gap-2 shrink-0 ms-auto">
            <span class="font-medium hidden sm:inline text-[10px] sm:text-xs text-slate-400">Aksesibilitas:</span>
            <div class="flex items-center gap-1 text-[10px] sm:text-xs">
                <button @click="fontSize = fontSize >= 120 ? 100 : fontSize + 10" class="px-1.5 py-0.5 bg-slate-800 hover:bg-slate-700 rounded transition font-bold">A+</button>
                <button @click="fontSize = 100" class="px-1.5 py-0.5 bg-slate-800 hover:bg-slate-700 rounded transition">A</button>
                <button @click="highContrast = !highContrast" class="px-1.5 py-0.5 bg-slate-800 hover:bg-slate-700 rounded transition"><i class="fas fa-adjust me-1"></i>Kontras</button>
            </div>
    </div>
</div>

<!-- Header / Navbar -->
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs transition-all duration-300">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">
            
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center gap-2.5">
                <a href="{{ route('home') }}" class="group flex items-center gap-2.5">
                    <img src="{{ $settings['logo_frontend'] ?? '' }}" 
                         alt="DKUPP Logo" class="h-9 sm:h-11 w-auto object-contain transition-transform group-hover:scale-105 duration-200">
                    <div class="hidden sm:block text-left border-l border-emerald-500 pl-2.5">
                        <h1 class="font-extrabold text-slate-900 text-xs sm:text-sm leading-tight tracking-tight uppercase">DKUPP KAB. PROBOLINGGO</h1>
                        <p class="text-[9px] sm:text-[10px] text-emerald-600 font-semibold uppercase tracking-wider">Dinas Koperasi, Usaha Mikro, Perdagangan & Perindustrian</p>
                    </div>
                </a>
            </div>

            <!-- Desktop Nav -->
            <nav class="hidden lg:flex items-center space-x-0.5 xl:space-x-1">
                @if(isset($navMenus))
                    @foreach($navMenus as $menu)
                        @if(strtolower(trim($menu->title)) === 'login' || trim($menu->url, '/') === 'login' || str_contains($menu->url, '/login'))
                            @continue
                        @endif
                        @if($menu->children && count($menu->children) > 0)
                            <div class="relative" x-data="{ open: false, clicked: false }" @mouseleave="open = false">
                                <button @mouseover="open = true" 
                                        @click="open = !open; clicked = true; setTimeout(() => clicked = false, 2000)" 
                                        class="relative group px-1.5 py-1.5 lg:px-2.5 xl:px-3.5 py-2 rounded-xl text-[11px] xl:text-xs font-extrabold tracking-wide text-slate-700 hover:text-emerald-700 hover:bg-emerald-50/70 uppercase transition-all flex items-center gap-1 active:scale-95">
                                    <span>{{ $menu->title }}</span>
                                    <i class="fas fa-chevron-down text-[9px] xl:text-[10px] transition-transform duration-200 text-slate-400 group-hover:text-emerald-600" :class="{ 'rotate-180': open }"></i>
                                    
                                    <!-- Garis Berjalan / Running Line Animation Saat Ditekan / Hover -->
                                    <span class="absolute bottom-0 left-2 right-2 h-[2.5px] bg-gradient-to-r from-emerald-500 via-teal-300 to-emerald-600 rounded-full transition-all duration-300 transform scale-x-0 group-hover:scale-x-100"
                                          :class="clicked ? 'scale-x-100 animate-running-line' : ''"></span>
                                </button>
                                <div x-show="open" x-cloak
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-2"
                                     class="absolute left-0 mt-1 w-52 rounded-2xl bg-white shadow-xl ring-1 ring-slate-900/5 border border-slate-100 py-2 z-50 max-h-[65vh] sm:max-h-[75vh] overflow-y-auto custom-scrollbar">
                                    @foreach($menu->children as $child)
                                        <a href="{{ $child->url }}" target="{{ $child->target }}"
                                           class="group/item flex items-center justify-between px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                            <span>{{ $child->title }}</span>
                                            <i class="fas fa-chevron-right text-[9px] text-emerald-500 opacity-0 group-hover/item:opacity-100 group-hover/item:translate-x-0.5 transition-all"></i>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div x-data="{ clicked: false }" class="relative">
                                <a href="{{ $menu->url }}" target="{{ $menu->target }}" 
                                   @click="clicked = true"
                                   class="relative group px-1.5 py-1.5 lg:px-2.5 xl:px-3.5 py-2 rounded-xl text-[11px] xl:text-xs font-extrabold tracking-wide text-slate-700 hover:text-emerald-700 hover:bg-emerald-50/70 uppercase transition-all block active:scale-95">
                                    <span>{{ $menu->title }}</span>
                                    
                                    <!-- Garis Berjalan / Running Line Animation Saat Ditekan / Hover -->
                                    <span class="absolute bottom-0 left-2 right-2 h-[2.5px] bg-gradient-to-r from-emerald-500 via-teal-300 to-emerald-600 rounded-full transition-all duration-300 transform scale-x-0 group-hover:scale-x-100"
                                          :class="clicked ? 'scale-x-100 animate-running-line' : ''"></span>
                                </a>
                            </div>
                        @endif
                    @endforeach
                @endif
            </nav>

            <!-- Right Action & Mobile Toggle -->
            <div class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
                @php
                    $berakhlakLogo = !empty($settings['logo_berakhlak']) && !str_contains($settings['logo_berakhlak'], 'diskominfo') ? $settings['logo_berakhlak'] : '/uploads/settings/logo_berakhlak.svg';
                @endphp
                <img src="{{ asset($berakhlakLogo) }}?v=3" 
                     onerror="this.onerror=null; this.src='{{ asset('/uploads/settings/logo_berakhlak.png') }}';"
                     alt="BerAKHLAK" class="h-6 sm:h-7 lg:h-7 xl:h-8 max-w-[120px] xl:max-w-[150px] w-auto object-contain hidden sm:block shrink-0">
                
                @auth
                    <div class="flex items-center gap-1 shrink-0">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg font-extrabold text-[11px] transition-all shadow-2xs hover:scale-105 shrink-0"
                           title="Masuk ke Panel Control Admin">
                            <i class="fas fa-tachometer-alt text-[10px]"></i> <span>Panel Admin</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline shrink-0">
                            @csrf
                            <button type="submit" 
                                    class="px-2 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg font-bold text-[11px] transition-colors flex items-center gap-1 shrink-0" 
                                    title="Keluar Akun / Logout">
                                <i class="fas fa-sign-out-alt text-[10px]"></i> <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-[11px] sm:text-xs transition-all shadow-2xs hover:scale-105 shrink-0">
                        <i class="fas fa-sign-in-alt text-[10px]"></i> <span>Masuk</span>
                    </a>
                @endauth

                <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 text-slate-600 hover:text-emerald-600 focus:outline-none">
                    <i class="fas text-xl" :class="mobileMenu ? 'fa-times' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Nav -->
    <div x-show="mobileMenu" x-cloak x-transition class="lg:hidden bg-white border-t border-slate-100 px-4 py-4 space-y-2 shadow-lg">
        @if(isset($navMenus))
            @foreach($navMenus as $menu)
                @if(auth()->check() && (strtolower(trim($menu->title)) === 'login' || trim($menu->url, '/') === 'login' || str_contains($menu->url, '/login')))
                    @continue
                @endif
                @if($menu->children && count($menu->children) > 0)
                    <div x-data="{ subOpen: false }">
                        <button @click="subOpen = !subOpen" class="w-full flex justify-between items-center px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-emerald-50 rounded-lg">
                            <span>{{ $menu->title }}</span>
                            <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': subOpen }"></i>
                        </button>
                        <div x-show="subOpen" class="pl-4 space-y-1 mt-1 max-h-60 overflow-y-auto custom-scrollbar">
                            @foreach($menu->children as $child)
                                <a href="{{ $child->url }}" class="block px-3 py-2 text-sm text-slate-600 hover:text-emerald-600 rounded-md">
                                    {{ $child->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $menu->url }}" class="block px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-emerald-50 rounded-lg">
                        {{ $menu->title }}
                    </a>
                @endif
            @endforeach
        @endif

        <div class="pt-2 border-t border-slate-100 space-y-2">
            @auth
                <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-xs">
                    <i class="fas fa-tachometer-alt"></i> Panel Admin ({{ auth()->user()->name }})
                </a>
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-extrabold text-xs rounded-xl transition-colors">
                        <i class="fas fa-sign-out-alt"></i> Keluar / Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 text-white font-bold text-sm rounded-xl">
                    <i class="fas fa-sign-in-alt"></i> Masuk / Login
                </a>
            @endauth
        </div>
    </div>

    <!-- Running Text Berita Terkini Bar (Tanpa Background / Transparent) -->
    @if(isset($runningNews) && count($runningNews) > 0)
        <div class="py-1.5 px-3 sm:px-6 w-full overflow-hidden relative">
            <div class="max-w-7xl mx-auto flex items-center gap-3">
                <!-- Badge Berita Terkini -->
                <a href="{{ route('berita') }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg font-extrabold text-[10px] sm:text-xs uppercase tracking-wider shrink-0 shadow-2xs transition-all hover:scale-105 active:scale-95 group">
                    <i class="fas fa-bullhorn text-amber-300 animate-pulse group-hover:rotate-12 transition-transform"></i>
                    <span class="whitespace-nowrap">BERITA TERKINI</span>
                    <i class="fas fa-chevron-right text-[9px] text-emerald-200"></i>
                </a>

                <!-- Running News Marquee / Ticker -->
                <div class="flex-1 overflow-hidden relative font-semibold text-[11px] sm:text-xs">
                    <marquee behavior="scroll" direction="left" scrollamount="4" onmouseover="this.stop();" onmouseout="this.start();" class="py-0.5">
                        <div class="inline-flex items-center gap-6 sm:gap-10">
                            @foreach($runningNews as $item)
                                <a href="{{ route('berita.detail', $item->slug) }}" class="inline-flex items-center gap-2 text-slate-800 hover:text-emerald-700 transition-colors group/item cursor-pointer">
                                    <span class="bg-emerald-100 text-emerald-800 border border-emerald-200 text-[9px] px-1.5 py-0.5 rounded font-extrabold uppercase">
                                        {{ $item->category ?: 'BERITA' }}
                                    </span>
                                    <span class="group-hover/item:underline font-bold tracking-wide text-slate-800">
                                        {{ $item->title }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-normal">
                                        ({{ optional($item->published_at)->format('d M Y') }})
                                    </span>
                                    <span class="text-emerald-500/60 mx-2">•</span>
                                </a>
                            @endforeach
                        </div>
                    </marquee>
                </div>
            </div>
        </div>
    @endif
</header>
