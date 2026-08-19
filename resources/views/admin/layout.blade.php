<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page_title', 'Control Panel') | DKUPP Portal Admin</title>
    
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
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#022c22',
                            dark: '#0f172a',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased min-h-screen flex" x-data="{ sidebarOpen: false }">

    <!-- Mobile Backdrop -->
    <div x-show="sidebarOpen" 
         x-cloak 
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"></div>

    <!-- Sidebar Navigation -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 transform transition-transform duration-200 lg:translate-x-0 flex flex-col justify-between shadow-2xl"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        
        <div>
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center px-6 bg-slate-950 border-b border-slate-800 justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl {{ auth()->user()->isSuperAdmin() ? 'bg-emerald-600' : 'bg-blue-600' }} flex items-center justify-center text-white font-extrabold text-sm shadow-md">
                        {{ auth()->user()->isSuperAdmin() ? 'SA' : 'AG' }}
                    </div>
                    <div>
                        <h1 class="font-extrabold text-white text-sm tracking-wide line-clamp-1">{{ auth()->user()->name }}</h1>
                        <p class="text-[10px] {{ auth()->user()->isSuperAdmin() ? 'text-emerald-400' : 'text-blue-400' }} font-extrabold uppercase tracking-wider">
                            Role: {{ auth()->user()->role }}
                        </p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1">
                    <i class="fas fa-times text-base"></i>
                </button>
            </div>

            <!-- Sidebar Navigation Menu Links -->
            <nav class="p-4 space-y-1 text-xs font-semibold overflow-y-auto max-h-[calc(100vh-140px)]">
                <div class="text-[10px] text-slate-500 uppercase tracking-widest px-3 py-2 font-bold">Navigasi Utama</div>
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <i class="fas fa-chart-pie text-sm"></i> Dashboard Overview
                </a>

                <a href="{{ route('admin.umkm') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.umkm') ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <i class="fas fa-globe text-sm text-emerald-400"></i> SIMADU SAE (Link Website UMKM)
                </a>

                <a href="{{ route('admin.market-prices') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.market-prices') ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <i class="fas fa-shopping-basket text-sm text-amber-400"></i> Monitoring Harga Pasar
                </a>

                <a href="{{ route('admin.ppid') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.ppid') ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <i class="fas fa-info-circle text-sm text-sky-400"></i> Kelola Link Website PPID
                </a>

                <a href="{{ route('admin.maklumat') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.maklumat') ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <i class="fas fa-scroll text-sm text-amber-400"></i> Kelola Maklumat Pelayanan
                </a>

                <a href="{{ route('admin.qr-code') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.qr-code') ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <i class="fas fa-qrcode text-sm text-emerald-400"></i> Kelola Kode QR & SKM
                </a>

                <a href="{{ route('admin.documents') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.documents') ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <i class="fas fa-file-alt text-sm text-blue-400"></i> CRUD Dokumen Kinerja
                </a>

                <a href="{{ route('admin.news') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.news') ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <i class="fas fa-newspaper text-sm text-purple-400"></i> CRUD Berita & Informasi
                </a>

                <a href="{{ route('admin.gallery') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.gallery*') ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <i class="fas fa-photo-video text-sm text-pink-400"></i> CRUD Galeri Foto & Video
                </a>

                <!-- RESTRICTED TO SUPER ADMIN ONLY -->
                @if(auth()->user()->isSuperAdmin())
                    <div class="pt-4 text-[10px] text-slate-500 uppercase tracking-widest px-3 py-1 font-bold">Super Admin Controls</div>

                    <a href="{{ route('admin.sliders') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.sliders') ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-images text-sm text-amber-400"></i> CRUD Banner Sliders
                    </a>

                    <a href="{{ route('admin.menus') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.menus') ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-bars text-sm text-cyan-400"></i> CRUD Menu Header Topbar
                    </a>

                    <a href="{{ route('admin.pages') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ (request()->routeIs('admin.pages*') || request()->routeIs('admin.org-members*')) ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-sitemap text-sm text-rose-400"></i> CRUD Profil & Struktur Organisasi
                    </a>

                    <a href="{{ route('admin.widgets') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.widgets') ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-th-large text-sm text-emerald-400"></i> CRUD Widgets Sidebar
                    </a>

                    <a href="{{ route('admin.links') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.links') ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-link text-sm text-teal-400"></i> CRUD Tautan Terkait Logo
                    </a>

                    <a href="{{ route('admin.messages') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.messages') ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-bullhorn text-sm text-red-400"></i> Laporan Masuk / Kontak
                    </a>

                    <a href="{{ route('admin.users') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.users') ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-users-cog text-sm text-indigo-400"></i> CRUD Users & Roles
                    </a>

                    <a href="{{ route('admin.activity-logs') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.activity-logs*') ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-history text-sm text-yellow-400"></i> Log Aktivitas Sistem
                    </a>

                    <a href="{{ route('admin.settings') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.settings') ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-cog text-sm text-slate-400"></i> Pengaturan Website
                    </a>
                @endif

                <div class="pt-4 text-[10px] text-slate-500 uppercase tracking-widest px-3 py-1 font-bold">Public Portal</div>
                
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-white transition-all">
                    <i class="fas fa-external-link-alt text-sm"></i> Lihat Website Utama
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-950">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white text-xs font-extrabold rounded-xl transition-all">
                    <i class="fas fa-sign-out-alt"></i> Logout Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Layout -->
    <div class="flex-1 flex flex-col min-w-0 lg:pl-64">
        <!-- Topbar -->
        <header class="h-16 sm:h-20 bg-white/95 backdrop-blur-md border-b border-slate-200 px-3 sm:px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-2.5 sm:gap-4 min-w-0">
                <button @click="sidebarOpen = true" class="lg:hidden text-slate-700 hover:text-slate-900 p-2 rounded-xl hover:bg-slate-100 transition">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <div class="truncate">
                    <h2 class="font-extrabold text-slate-900 text-sm sm:text-base leading-tight truncate">@yield('page_title', 'Dashboard Admin')</h2>
                    <p class="text-[10px] sm:text-xs text-slate-500 truncate">DKUPP Kabupaten Probolinggo Control Panel</p>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('home') }}" target="_blank" 
                   class="px-2.5 py-1.5 sm:px-3 sm:py-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold text-[11px] sm:text-xs rounded-xl border border-emerald-200 transition-all flex items-center gap-1.5 shadow-xs">
                    <i class="fas fa-globe text-xs"></i> <span class="hidden sm:inline">Preview</span> Site
                </a>
            </div>
        </header>

        <main class="p-6 flex-grow">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl flex items-center gap-2">
                    <i class="fas fa-check-circle text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-100 border border-rose-300 text-rose-800 text-xs font-bold rounded-2xl flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
