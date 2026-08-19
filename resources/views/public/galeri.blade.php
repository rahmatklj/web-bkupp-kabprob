<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Galeri Foto & Video Kegiatan | DKUPP Kabupaten Probolinggo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex flex-col min-h-screen"
      x-data="{ mobileMenu: false, highContrast: false, fontSize: 100 }"
      :class="{ 'high-contrast': highContrast }"
      :style="`font-size: ${fontSize}%`">

    @include('partials.public_header')

    <main class="flex-grow py-8 sm:py-14 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8" x-data="{ activeTab: '{{ request()->get('tab', 'foto') }}', activePhoto: null, activeVideo: null }">
        
        <!-- Tombol Kembali ke Beranda Utama -->
        <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-emerald-600 text-slate-700 hover:text-white border border-slate-200 hover:border-emerald-600 rounded-full font-extrabold text-xs shadow-xs transition-all hover:scale-105 group">
                <i class="fas fa-arrow-left text-xs text-emerald-600 group-hover:text-white transition-transform group-hover:-translate-x-0.5"></i>
                <span>Kembali ke Beranda Utama</span>
            </a>
            <span class="text-xs text-slate-400 font-semibold hidden sm:inline">Galeri Dokumentasi DKUPP</span>
        </div>

        <div class="text-center max-w-2xl mx-auto space-y-3">
            <span class="px-3.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-extrabold uppercase">Dokumentasi Visual</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Galeri Foto & Video Kegiatan DKUPP</h1>
            <p class="text-xs sm:text-sm text-slate-500">Dokumentasi program kerja, sidang tera ulang, bazar UMKM SIMADU SAE, pelatihan koperasi, dan video kegiatan resmi.</p>
        </div>

        <!-- Tab Buttons Switcher -->
        <div class="flex justify-center">
            <div class="bg-slate-200/80 p-1.5 rounded-2xl inline-flex items-center gap-1 max-w-full overflow-x-auto">
                <button @click="activeTab = 'foto'" 
                        :class="activeTab === 'foto' ? 'bg-white text-emerald-800 shadow-xs font-extrabold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                        class="px-5 py-2.5 rounded-xl text-xs sm:text-sm transition-all flex items-center gap-2">
                    <i class="fas fa-camera text-emerald-600 text-sm"></i>
                    <span>Foto Kegiatan</span>
                </button>
                <button @click="activeTab = 'video'" 
                        :class="activeTab === 'video' ? 'bg-white text-red-700 shadow-xs font-extrabold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                        class="px-5 py-2.5 rounded-xl text-xs sm:text-sm transition-all flex items-center gap-2">
                    <i class="fab fa-youtube text-red-600 text-sm"></i>
                    <span>Video YouTube</span>
                </button>
            </div>
        </div>

        <!-- FOTO TAB CONTENT -->
        <div x-show="activeTab === 'foto'" class="space-y-6">
            @if(isset($imageGalleries) && $imageGalleries->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($imageGalleries as $img)
                        <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-xs hover:shadow-md transition-all group cursor-pointer"
                             @click="activePhoto = {{ json_encode(['title' => $img->title, 'caption' => $img->caption, 'file_path' => $img->file_path]) }}">
                            <div class="aspect-square bg-slate-100 overflow-hidden relative">
                                <img src="{{ $img->file_path }}" alt="{{ $img->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white p-3">
                                    <span class="px-3 py-1.5 bg-emerald-600/90 rounded-xl text-xs font-bold shadow-md flex items-center gap-1.5">
                                        <i class="fas fa-search-plus"></i> Lihat Foto
                                    </span>
                                </div>
                            </div>
                            <div class="p-4 space-y-1">
                                <h4 class="font-extrabold text-slate-900 text-xs line-clamp-2 leading-snug group-hover:text-emerald-700 transition-colors">{{ $img->title }}</h4>
                                @if($img->caption)
                                    <p class="text-[11px] text-slate-500 line-clamp-2">{{ $img->caption }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($newsWithImages as $item)
                        <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-xs hover:shadow-md transition-all cursor-pointer"
                             @click="activePhoto = {{ json_encode(['title' => $item->title, 'caption' => $item->category, 'file_path' => $item->image_url]) }}">
                            <div class="h-56 bg-slate-100 overflow-hidden relative group">
                                <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white p-3">
                                    <span class="px-3 py-1.5 bg-emerald-600/90 rounded-xl text-xs font-bold shadow-md flex items-center gap-1.5">
                                        <i class="fas fa-search-plus"></i> Lihat Foto
                                    </span>
                                </div>
                            </div>
                            <div class="p-4 space-y-1">
                                <span class="text-[10px] font-extrabold text-emerald-700 uppercase">{{ $item->category }}</span>
                                <h4 class="font-extrabold text-slate-900 text-xs line-clamp-2">{{ $item->title }}</h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- VIDEO TAB CONTENT -->
        <div x-show="activeTab === 'video'" x-cloak class="space-y-6">
            @if(isset($videoGalleries) && $videoGalleries->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($videoGalleries as $vid)
                        @php
                            $ytId = '';
                            if ($vid->youtube_url) {
                                preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|shorts\/))([\w-]{11})/', $vid->youtube_url, $matches);
                                $ytId = $matches[1] ?? '';
                            }
                            $ytThumb = $ytId ? "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg" : '';
                            $embedUrl = $ytId ? "https://www.youtube.com/embed/{$ytId}?autoplay=1&rel=0" : '';
                        @endphp
                        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between group cursor-pointer hover:-translate-y-1"
                             @click="activeVideo = {{ json_encode(['title' => $vid->title, 'caption' => $vid->caption, 'embedUrl' => $embedUrl, 'file_path' => $vid->file_path, 'ytId' => $ytId, 'youtube_url' => $vid->youtube_url]) }}">
                            <div class="relative aspect-video bg-slate-950 overflow-hidden">
                                @if($ytThumb)
                                    <img src="{{ $ytThumb }}" alt="{{ $vid->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90 group-hover:opacity-100">
                                @elseif($vid->file_path)
                                    <video src="{{ $vid->file_path }}" class="w-full h-full object-cover"></video>
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-600 bg-slate-900">
                                        <i class="fas fa-video text-4xl"></i>
                                    </div>
                                @endif

                                <!-- Play Overlay Button -->
                                <div class="absolute inset-0 bg-slate-950/40 group-hover:bg-slate-950/20 transition-all flex items-center justify-center">
                                    <div class="w-14 h-14 rounded-full bg-red-600 group-hover:bg-red-700 text-white flex items-center justify-center text-xl shadow-xl transition-all transform group-hover:scale-110">
                                        <i class="fas fa-play ms-1"></i>
                                    </div>
                                </div>

                                <span class="absolute bottom-2.5 right-2.5 bg-slate-950/80 backdrop-blur-xs text-white text-[10px] font-extrabold px-2.5 py-1 rounded-lg flex items-center gap-1">
                                    <i class="fab fa-youtube text-red-500"></i> Putar Video HD
                                </span>
                            </div>
                            <div class="p-5 space-y-1.5">
                                <h4 class="font-extrabold text-slate-900 text-xs sm:text-sm leading-snug line-clamp-2 group-hover:text-red-600 transition-colors">
                                    {{ $vid->title }}
                                </h4>
                                @if($vid->caption)
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ $vid->caption }}</p>
                                @endif
                                <div class="pt-2 flex items-center text-[11px] font-bold text-red-600 group-hover:underline">
                                    <span>Klik Untuk Memperbesar & Putar</span> <i class="fas fa-expand ms-1 text-[10px]"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white p-12 rounded-3xl border border-slate-200 text-center space-y-3 max-w-lg mx-auto">
                    <i class="fab fa-youtube text-red-500 text-4xl block"></i>
                    <h3 class="font-extrabold text-slate-800 text-base">Belum Ada Video Kegiatan</h3>
                    <p class="text-xs text-slate-500">Video kegiatan DKUPP akan ditampilkan di sini setelah diunggah melalui Admin Panel.</p>
                </div>
            @endif
        </div>

        <!-- Photo Lightbox Modal Popup (Klik Foto Tampil di Web) -->
        <div x-show="activePhoto !== null" 
             x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-50 p-3 sm:p-6 flex items-center justify-center bg-slate-950/90 backdrop-blur-md">
            
            <div @click.away="activePhoto = null" class="bg-slate-900 rounded-3xl max-w-5xl w-full max-h-[92vh] border border-slate-800 shadow-2xl flex flex-col overflow-hidden relative">
                <!-- Modal Header -->
                <div class="p-4 sm:p-5 bg-slate-950 text-white flex items-center justify-between gap-4 border-b border-slate-800 shrink-0">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg shrink-0">
                            <i class="fas fa-camera"></i>
                        </div>
                        <div class="truncate">
                            <span class="text-[10px] font-extrabold text-emerald-400 uppercase tracking-widest block">Dokumentasi Foto Kegiatan</span>
                            <h3 class="font-extrabold text-xs sm:text-sm text-white truncate" x-text="activePhoto ? activePhoto.title : ''"></h3>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a :href="activePhoto ? activePhoto.file_path : '#'" target="_blank" download class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors shadow-xs">
                            <i class="fas fa-download text-[10px]"></i> <span class="hidden sm:inline">Unduh Foto</span>
                        </a>
                        <button @click="activePhoto = null" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 flex items-center justify-center transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Content Body (Full HD Image Preview) -->
                <div class="flex-grow bg-slate-950 p-3 sm:p-6 relative overflow-auto flex flex-col justify-center items-center">
                    <template x-if="activePhoto">
                        <img :src="activePhoto.file_path" :alt="activePhoto.title" class="max-h-[70vh] w-auto max-w-full rounded-2xl object-contain shadow-2xl border border-slate-800 mx-auto">
                    </template>
                    <div class="mt-4 text-center max-w-2xl px-2" x-show="activePhoto && activePhoto.caption">
                        <p class="text-xs sm:text-sm text-slate-300 font-medium leading-relaxed" x-text="activePhoto ? activePhoto.caption : ''"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Lightbox & Enlarged Player Modal Popup (Bisa Dijalankan & Diperbesar) -->
        <div x-show="activeVideo !== null" 
             x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-50 p-3 sm:p-6 flex items-center justify-center bg-slate-950/90 backdrop-blur-md">
            
            <div @click.away="activeVideo = null" class="bg-slate-900 rounded-3xl max-w-5xl w-full max-h-[95vh] border border-slate-800 shadow-2xl flex flex-col overflow-hidden relative">
                <!-- Modal Header -->
                <div class="p-4 sm:p-5 bg-slate-950 text-white flex items-center justify-between gap-4 border-b border-slate-800 shrink-0">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-9 h-9 rounded-xl bg-red-500/20 text-red-400 flex items-center justify-center text-lg shrink-0">
                            <i class="fab fa-youtube"></i>
                        </div>
                        <div class="truncate">
                            <span class="text-[10px] font-extrabold text-red-400 uppercase tracking-widest block">Video Kegiatan Resmi DKUPP</span>
                            <h3 class="font-extrabold text-xs sm:text-sm text-white truncate" x-text="activeVideo ? activeVideo.title : ''"></h3>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <template x-if="activeVideo && activeVideo.youtube_url">
                            <a :href="activeVideo.youtube_url" target="_blank" rel="noopener noreferrer" class="px-3.5 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors shadow-xs">
                                <i class="fab fa-youtube"></i> <span class="hidden sm:inline">Buka di YouTube</span>
                            </a>
                        </template>
                        <button @click="activeVideo = null" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 flex items-center justify-center transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Video Player Screen -->
                <div class="flex-grow bg-slate-950 p-2 sm:p-6 relative overflow-auto flex flex-col justify-center items-center">
                    <div class="w-full max-w-4xl aspect-video rounded-2xl overflow-hidden shadow-2xl bg-black border border-slate-800">
                        <template x-if="activeVideo && activeVideo.embedUrl">
                            <iframe :src="activeVideo.embedUrl" title="YouTube Video Player"
                                    class="w-full h-full border-0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                    allowfullscreen></iframe>
                        </template>
                        <template x-if="activeVideo && !activeVideo.embedUrl && activeVideo.file_path">
                            <video :src="activeVideo.file_path" controls autoplay class="w-full h-full object-contain"></video>
                        </template>
                    </div>
                    <div class="mt-4 text-center max-w-2xl px-2" x-show="activeVideo && activeVideo.caption">
                        <p class="text-xs sm:text-sm text-slate-300 font-medium leading-relaxed" x-text="activeVideo ? activeVideo.caption : ''"></p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <footer class="bg-slate-950 text-slate-400 py-6 text-xs text-center border-t border-slate-800">
        <p>DKUPP Kabupaten Probolinggo © 2026</p>
    </footer>
    @include('partials.tts_widget')
</body>
</html>
