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

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showUploadErrorSwal(msg, allowedTypesStr = 'JPG, PNG, atau WEBP') {
            const cleanMsg = msg || ('⚠️ GAGAL UPLOAD: Berkas yang Anda pilih tidak didukung! Mohon hanya mengunggah file berformat ' + allowedTypesStr + '.');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Format File Tidak Sesuai!',
                    html: '<div style="font-size:13px; font-weight:700; line-height:1.6; color:#be123c;" class="space-y-2"><div>' + cleanMsg + '</div><div style="font-size:11px; color:#64748b; font-weight:600; margin-top:8px;">Pastikan jenis berkas sesuai dengan ketentuan aplikasi.</div></div>',
                    confirmButtonText: '<i class="fas fa-check-circle me-1"></i> Saya Mengerti',
                    confirmButtonColor: '#e11d48',
                    customClass: {
                        popup: 'rounded-3xl shadow-2xl border border-slate-200',
                        confirmButton: 'px-6 py-2.5 rounded-xl font-bold text-xs shadow-md'
                    }
                });
            } else {
                alert(cleanMsg);
            }
        }
    </script>

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

            <!-- Sidebar Navigation Menu Links with Interactive Dropdowns -->
            <nav class="p-3 space-y-2 text-xs font-semibold overflow-y-auto max-h-[calc(100vh-140px)]"
                 x-data="{ 
                    openContent: {{ (request()->routeIs('admin.news*') || request()->routeIs('admin.documents*') || request()->routeIs('admin.services*') || request()->routeIs('admin.categories*') || request()->routeIs('admin.gallery*')) ? 'true' : 'true' }},
                    openPortal: {{ (request()->routeIs('admin.portal-links*') || request()->routeIs('admin.market-prices*') || request()->routeIs('admin.umkm*') || request()->routeIs('admin.ppid*') || request()->routeIs('admin.sp4n-lapor*') || request()->routeIs('admin.whatsapp*') || request()->routeIs('admin.maklumat*') || request()->routeIs('admin.qr-code*') || request()->routeIs('admin.sliders*') || request()->routeIs('admin.links*')) ? 'true' : 'false' }},
                    openWeb: {{ (request()->routeIs('admin.menus*') || request()->routeIs('admin.pages*') || request()->routeIs('admin.org-members*') || request()->routeIs('admin.contact-info*') || request()->routeIs('admin.social-media*') || request()->routeIs('admin.settings*')) ? 'true' : 'false' }},
                    openSystem: {{ (request()->routeIs('admin.messages*') || request()->routeIs('admin.users*') || request()->routeIs('admin.activity-logs*')) ? 'true' : 'false' }}
                 }">
                
                <!-- Dashboard Direct Link -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-700 text-white shadow-md font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <i class="fas fa-chart-pie text-sm text-emerald-400"></i>
                    <span>Dashboard Overview</span>
                </a>

                <!-- MENU GROUP 1: KELOLA KONTEN & LAYANAN (STAF & SUPER ADMIN) -->
                <div class="space-y-1">
                    <button type="button" @click="openContent = !openContent" 
                            class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all font-bold text-slate-300 hover:bg-slate-800 hover:text-white cursor-pointer">
                        <div class="flex items-center gap-2.5">
                            <i class="fas fa-folder-open text-emerald-400 text-sm"></i>
                            <span>Kelola Konten & Layanan</span>
                        </div>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': openContent }"></i>
                    </button>

                    <div x-show="openContent" x-cloak class="pl-3.5 pr-1 py-1 space-y-1 border-l-2 border-slate-800 ml-3.5">
                        <a href="{{ route('admin.news') }}" 
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.news*') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i class="fas fa-newspaper text-xs text-orange-400"></i> Berita & Informasi
                        </a>

                        <a href="{{ route('admin.documents') }}" 
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.documents*') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i class="fas fa-file-pdf text-xs text-rose-400"></i> Dokumen Kinerja
                        </a>

                        <a href="{{ route('admin.services') }}" 
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.services*') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i class="fas fa-handshake text-xs text-emerald-400"></i> Layanan Publik & SOP
                        </a>

                        <a href="{{ route('admin.gallery') }}" 
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.gallery*') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i class="fas fa-photo-video text-xs text-pink-400"></i> Galeri Foto & Video
                        </a>

                        <a href="{{ route('admin.activity-logs') }}" 
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.activity-logs*') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i class="fas fa-history text-xs text-yellow-400"></i> Log Aktivitas Sistem
                        </a>
                    </div>
                </div>

                <!-- RESTRICTED TO SUPER ADMIN ONLY -->
                @if(auth()->user()->isSuperAdmin())
                    
                    <!-- MENU GROUP 2: PORTAL & INTEGRASI PUBLIK -->
                    <div class="space-y-1 pt-1">
                        <button type="button" @click="openPortal = !openPortal" 
                                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all font-bold text-slate-300 hover:bg-slate-800 hover:text-white cursor-pointer">
                            <div class="flex items-center gap-2.5">
                                <i class="fas fa-globe text-cyan-400 text-sm"></i>
                                <span>Link Portal</span>
                            </div>
                            <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': openPortal }"></i>
                        </button>

                        <div x-show="openPortal" x-cloak class="pl-3.5 pr-1 py-1 space-y-1 border-l-2 border-slate-800 ml-3.5">
                            <a href="{{ route('admin.portal-links') }}" 
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ (request()->routeIs('admin.portal-links*') || request()->routeIs('admin.market-prices*') || request()->routeIs('admin.umkm*') || request()->routeIs('admin.ppid*') || request()->routeIs('admin.sp4n-lapor*') || request()->routeIs('admin.whatsapp*')) ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-network-wired text-xs text-emerald-400"></i> Link Portal & Integrasi
                            </a>

                            <a href="{{ route('admin.maklumat') }}" 
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.maklumat') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-scroll text-xs text-amber-400"></i> Maklumat Pelayanan
                            </a>

                            <a href="{{ route('admin.qr-code') }}" 
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.qr-code') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-qrcode text-xs text-emerald-400"></i> Kode QR & Hasil SKM
                            </a>

                            <a href="{{ route('admin.sliders') }}" 
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.sliders') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-images text-xs text-yellow-400"></i> Banner Sliders
                            </a>

                            <a href="{{ route('admin.links') }}" 
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.links') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-link text-xs text-teal-400"></i> Tautan Terkait Logo
                            </a>
                        </div>
                    </div>

                    <!-- MENU GROUP 3: PENGATURAN WEB & STRUKTUR -->
                    <div class="space-y-1 pt-1">
                        <button type="button" @click="openWeb = !openWeb" 
                                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all font-bold text-slate-300 hover:bg-slate-800 hover:text-white cursor-pointer">
                            <div class="flex items-center gap-2.5">
                                <i class="fas fa-sliders-h text-amber-400 text-sm"></i>
                                <span>Pengaturan Web & Struktur</span>
                            </div>
                            <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': openWeb }"></i>
                        </button>

                        <div x-show="openWeb" x-cloak class="pl-3.5 pr-1 py-1 space-y-1 border-l-2 border-slate-800 ml-3.5">
                            <a href="{{ route('admin.menus') }}" 
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.menus') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-bars text-xs text-cyan-400"></i> Menu Header Topbar
                            </a>

                            <a href="{{ route('admin.pages') }}" 
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ (request()->routeIs('admin.pages*') || request()->routeIs('admin.org-members*')) ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-sitemap text-xs text-rose-400"></i> Profil & Struktur Organisasi
                            </a>

                            <a href="{{ route('admin.contact-info') }}" 
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.contact-info*') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-map-marker-alt text-xs text-emerald-400"></i> Alamat & Kontak Office
                            </a>

                            <a href="{{ route('admin.social-media') }}" 
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.social-media*') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-share-alt text-xs text-sky-400"></i> Media Sosial Resmi
                            </a>

                            <a href="{{ route('admin.settings') }}" 
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.settings') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-cog text-xs text-slate-400"></i> Pengaturan Website & SEO
                            </a>
                        </div>
                    </div>

                    <!-- MENU GROUP 4: SISTEM & HAK AKSES -->
                    <div class="space-y-1 pt-1">
                        <button type="button" @click="openSystem = !openSystem" 
                                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all font-bold text-slate-300 hover:bg-slate-800 hover:text-white cursor-pointer">
                            <div class="flex items-center gap-2.5">
                                <i class="fas fa-user-shield text-indigo-400 text-sm"></i>
                                <span>Sistem & Hak Akses</span>
                            </div>
                            <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': openSystem }"></i>
                        </button>

                        <div x-show="openSystem" x-cloak class="pl-3.5 pr-1 py-1 space-y-1 border-l-2 border-slate-800 ml-3.5">
                            <a href="{{ route('admin.messages') }}" 
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.messages') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-bullhorn text-xs text-red-400"></i> Laporan Masuk / Kontak
                            </a>

                            <a href="{{ route('admin.users') }}" 
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.users') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-users-cog text-xs text-indigo-400"></i> Users & Roles Admin
                            </a>
                        </div>
                    </div>
                @endif

                <div class="pt-3 border-t border-slate-800/80">
                    <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-white transition-all font-semibold">
                        <i class="fas fa-external-link-alt text-xs"></i> Lihat Website Utama
                    </a>
                </div>
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
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showUploadErrorSwal(@json(session('error')));
                    });
                </script>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
