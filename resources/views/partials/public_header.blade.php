<style>
@keyframes navbarRunningLine {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
.animate-running-line {
    background-size: 200% 200% !important;
    animation: navbarRunningLine 1.2s ease infinite !important;
}
</style>

<!-- Top Bar (Minimalist) -->
<div class="bg-slate-900 text-slate-200 text-[11px] sm:text-xs py-2 px-4">
    <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
        <div class="flex items-center gap-3">
            @if(!request()->routeIs('home'))
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full font-extrabold text-[11px] transition-all shadow-xs hover:scale-105">
                    <i class="fas fa-arrow-left text-[10px]"></i> <span>Kembali ke Beranda</span>
                </a>
            @endif
            <span class="hidden sm:inline-flex items-center gap-1.5 font-semibold text-emerald-400">
                <i class="fas fa-headset"></i> HOTLINE DKUPP
            </span>
            <span class="hidden md:inline font-medium tracking-wide">{{ $settings['phone'] ?? '(0335) 844554' }}</span>
            <span class="hidden md:inline text-slate-600">|</span>
            <span class="hidden md:inline font-medium">
                <i class="fas fa-clock me-1"></i> Jam Layanan MPP: Sen - Jum (08.00 - 16.00 WIB)
            </span>
        </div>
        <div class="flex items-center gap-3">
            <span class="font-medium hidden sm:inline">Aksesibilitas:</span>
            <div class="flex items-center gap-1">
                <button @click="fontSize = fontSize >= 120 ? 100 : fontSize + 10" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 rounded transition font-bold">A+</button>
                <button @click="fontSize = 100" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 rounded transition">A</button>
                <button @click="highContrast = !highContrast" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 rounded transition"><i class="fas fa-adjust me-1"></i>Kontras</button>
            </div>
        </div>
    </div>
</div>

<!-- Header / Navbar -->
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center gap-3">
                <a href="{{ route('home') }}" class="group flex items-center gap-3">
                    <img src="{{ $settings['logo_frontend'] ?? '' }}" 
                         alt="DKUPP Logo" class="h-12 w-auto object-contain transition-transform group-hover:scale-105 duration-200">
                    <div class="hidden sm:block text-left border-l border-emerald-500 pl-3">
                        <h1 class="font-bold text-slate-900 text-sm leading-tight tracking-tight uppercase">DKUPP KAB. PROBOLINGGO</h1>
                        <p class="text-[10px] text-emerald-600 font-semibold uppercase tracking-wider">Dinas Koperasi, Usaha Mikro, Perdagangan & Perindustrian</p>
                    </div>
                </a>
            </div>

            <!-- Desktop Nav -->
            <nav class="hidden lg:flex items-center space-x-1">
                @if(isset($navMenus))
                    @foreach($navMenus as $menu)
                        @if($menu->children && count($menu->children) > 0)
                            <div class="relative" x-data="{ open: false, clicked: false }" @mouseleave="open = false">
                                <button @mouseover="open = true" 
                                        @click="open = !open; clicked = true; setTimeout(() => clicked = false, 2000)" 
                                        class="relative group px-3.5 py-2.5 rounded-xl text-xs font-extrabold tracking-wide text-slate-700 hover:text-emerald-700 hover:bg-emerald-50/70 uppercase transition-all flex items-center gap-1.5 active:scale-95">
                                    <span>{{ $menu->title }}</span>
                                    <i class="fas fa-chevron-down text-[10px] transition-transform duration-200 text-slate-400 group-hover:text-emerald-600" :class="{ 'rotate-180': open }"></i>
                                    
                                    <!-- Garis Berjalan / Running Line Animation Saat Ditekan / Hover -->
                                    <span class="absolute bottom-0 left-2 right-2 h-[3px] bg-gradient-to-r from-emerald-500 via-teal-300 to-emerald-600 rounded-full transition-all duration-300 transform scale-x-0 group-hover:scale-x-100"
                                          :class="clicked ? 'scale-x-100 animate-running-line' : ''"></span>
                                </button>
                                <div x-show="open" x-cloak
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-2"
                                     class="absolute left-0 mt-1 w-56 rounded-2xl bg-white shadow-xl ring-1 ring-slate-900/5 border border-slate-100 py-2 z-50">
                                    @foreach($menu->children as $child)
                                        <a href="{{ $child->url }}" target="{{ $child->target }}"
                                           class="group/item flex items-center justify-between px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
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
                                   class="relative group px-3.5 py-2.5 rounded-xl text-xs font-extrabold tracking-wide text-slate-700 hover:text-emerald-700 hover:bg-emerald-50/70 uppercase transition-all block active:scale-95">
                                    <span>{{ $menu->title }}</span>
                                    
                                    <!-- Garis Berjalan / Running Line Animation Saat Ditekan / Hover -->
                                    <span class="absolute bottom-0 left-2 right-2 h-[3px] bg-gradient-to-r from-emerald-500 via-teal-300 to-emerald-600 rounded-full transition-all duration-300 transform scale-x-0 group-hover:scale-x-100"
                                          :class="clicked ? 'scale-x-100 animate-running-line' : ''"></span>
                                </a>
                            </div>
                        @endif
                    @endforeach
                @endif
            </nav>

            <!-- Right Action & Mobile Toggle -->
            <div class="flex items-center gap-3 sm:gap-4">
                <img src="{{ $settings['logo_berakhlak'] ?? '' }}" 
                     alt="BerAKHLAK" class="h-8 sm:h-10 w-auto object-contain hidden sm:block">
                
                @auth
                    <a href="{{ route('admin.dashboard') }}" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition-all shadow-xs hover:scale-105">
                        <i class="fas fa-tachometer-alt text-xs"></i> <span>Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition-all shadow-xs hover:scale-105">
                        <i class="fas fa-sign-in-alt text-xs"></i> <span>Masuk</span>
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
                @if($menu->children && count($menu->children) > 0)
                    <div x-data="{ subOpen: false }">
                        <button @click="subOpen = !subOpen" class="w-full flex justify-between items-center px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-emerald-50 rounded-lg">
                            <span>{{ $menu->title }}</span>
                            <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': subOpen }"></i>
                        </button>
                        <div x-show="subOpen" class="pl-4 space-y-1 mt-1">
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

        <div class="pt-2 border-t border-slate-100">
            @auth
                <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 text-white font-bold text-sm rounded-xl">
                    <i class="fas fa-tachometer-alt"></i> Dashboard Admin
                </a>
            @else
                <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 text-white font-bold text-sm rounded-xl">
                    <i class="fas fa-sign-in-alt"></i> Masuk / Login
                </a>
            @endauth
        </div>
    </div>
</header>
