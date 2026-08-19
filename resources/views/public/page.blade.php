<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->title }} | DKUPP Kabupaten Probolinggo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex flex-col min-h-screen"
      x-data="{ mobileMenu: false, highContrast: false, fontSize: 100 }"
      :class="{ 'high-contrast': highContrast }"
      :style="`font-size: ${fontSize}%`">
    
    @include('partials.public_header')

    <main class="flex-grow max-w-6xl w-full mx-auto px-3 sm:px-6 py-4 sm:py-10 space-y-6">
        <!-- Tombol Kembali ke Beranda Utama -->
        <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-emerald-600 text-slate-700 hover:text-white border border-slate-200 hover:border-emerald-600 rounded-full font-extrabold text-xs shadow-xs transition-all hover:scale-105 group">
                <i class="fas fa-arrow-left text-xs text-emerald-600 group-hover:text-white transition-transform group-hover:-translate-x-0.5"></i>
                <span>Kembali ke Beranda Utama</span>
            </a>
            <span class="text-xs text-slate-400 font-semibold hidden sm:inline">Informasi Halaman Profil DKUPP</span>
        </div>

        <div class="bg-white p-4 sm:p-8 rounded-2xl sm:rounded-3xl border border-slate-200 shadow-xs leading-relaxed text-sm text-slate-700">
            @if($page->slug == 'struktur-organisasi' && isset($orgMembers) && $orgMembers->count() > 0)
                @php
                    $kadin = $orgMembers->firstWhere('parent_id', null) ?? $orgMembers->first();
                    $fungsional = $orgMembers->firstWhere('type', 'kelompok_fungsional');
                    $sekretaris = $orgMembers->firstWhere('parent_id', optional($kadin)->id);
                    
                    $kasubags = $sekretaris ? $orgMembers->where('parent_id', $sekretaris->id) : collect();
                    $bidangList = $orgMembers->where('parent_id', optional($kadin)->id)
                                            ->where('id', '!=', optional($sekretaris)->id)
                                            ->where('type', '!=', 'kelompok_fungsional');
                @endphp

                <div class="space-y-6 sm:space-y-8 py-2">
                    <!-- Header Title -->
                    <div class="text-center max-w-xl mx-auto space-y-1.5">
                        <span class="inline-block px-3.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-extrabold uppercase tracking-wider">
                            Bagan Struktur Resmi
                        </span>
                        <h2 class="text-xl sm:text-3xl font-extrabold text-slate-900">Struktur Organisasi DKUPP</h2>
                        <p class="text-xs sm:text-sm text-slate-500">Dinas Koperasi, Usaha Mikro, Perdagangan dan Perindustrian Kabupaten Probolinggo</p>
                    </div>

                    <!-- MOBILE LAYOUT (< 768px): Vertical Minimalist Stack -->
                    <div class="block md:hidden space-y-4">
                        
                        <!-- 1. KEPALA DINAS CARD -->
                        @if($kadin)
                        <div class="bg-gradient-to-b from-slate-900 to-slate-950 text-white rounded-2xl p-5 text-center shadow-md space-y-3 relative overflow-hidden">
                            <div class="w-24 h-24 mx-auto rounded-full overflow-hidden border-4 border-emerald-400 shadow-md">
                                <img src="{{ $kadin->photo ?: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=600&auto=format&fit=crop' }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="font-extrabold text-base text-white leading-tight">{{ $kadin->name }}</h3>
                                <span class="inline-block mt-1 px-3 py-0.5 bg-emerald-600 text-white rounded-md text-[10px] font-black uppercase tracking-wider">
                                    {{ $kadin->position }}
                                </span>
                            </div>
                        </div>
                        @endif

                        <div class="w-0.5 h-6 bg-slate-300 mx-auto"></div>

                        <!-- 2. KELOMPOK FUNGSIONAL BADGE -->
                        @if($fungsional)
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-2.5 text-center text-xs font-bold text-emerald-800">
                            <i class="fas fa-users me-1.5 text-emerald-600"></i> {{ $fungsional->position }}
                        </div>
                        @endif

                        <div class="w-0.5 h-6 bg-slate-300 mx-auto"></div>

                        <!-- 3. SEKRETARIS & KASUBAG CARD -->
                        @if($sekretaris)
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 space-y-3 shadow-2xs">
                            <div class="flex items-center gap-3 border-b border-slate-200 pb-3">
                                <img src="{{ $sekretaris->photo ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=600&auto=format&fit=crop' }}" class="w-14 h-14 rounded-full object-cover border-2 border-emerald-600 shrink-0">
                                <div>
                                    <h4 class="font-extrabold text-slate-900 text-sm leading-tight">{{ $sekretaris->name }}</h4>
                                    <span class="inline-block mt-1 px-2.5 py-0.5 bg-emerald-700 text-white rounded text-[10px] font-bold uppercase">
                                        {{ $sekretaris->position }}
                                    </span>
                                </div>
                            </div>

                            @if($kasubags->count() > 0)
                            <div class="grid grid-cols-2 gap-2 pt-1">
                                @foreach($kasubags as $kas)
                                <div class="bg-white p-2.5 rounded-xl border border-slate-200 text-center space-y-1">
                                    <img src="{{ $kas->photo ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=300&auto=format&fit=crop' }}" class="w-10 h-10 rounded-full object-cover mx-auto border border-emerald-500">
                                    <h5 class="font-bold text-slate-900 text-[11px] leading-tight line-clamp-1">{{ $kas->name }}</h5>
                                    <span class="text-[9px] text-slate-500 font-semibold block leading-tight">{{ $kas->position }}</span>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endif

                        <div class="w-0.5 h-6 bg-slate-300 mx-auto"></div>

                        <!-- 4. KEPALA BIDANG-BIDANG (GRID 2 COLUMNS ON MOBILE) -->
                        @if($bidangList->count() > 0)
                        <div class="space-y-2">
                            <h4 class="text-center font-extrabold text-slate-700 text-xs uppercase tracking-wider">Kepala Bidang DKUPP</h4>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($bidangList as $bdg)
                                <div class="bg-white p-3 rounded-2xl border border-slate-200 text-center space-y-1.5 shadow-2xs">
                                    <img src="{{ $bdg->photo ?: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=400&auto=format&fit=crop' }}" class="w-14 h-14 rounded-full object-cover mx-auto border-2 border-blue-600 shadow-xs">
                                    <h5 class="font-extrabold text-slate-900 text-xs leading-snug line-clamp-2">{{ $bdg->name }}</h5>
                                    <span class="px-2 py-0.5 bg-slate-900 text-white text-[9px] font-bold rounded uppercase block">
                                        {{ $bdg->position }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                    </div>

                    <!-- DESKTOP LAYOUT (>= 768px): Graphical Tree Diagram -->
                    <div class="hidden md:block bg-gradient-to-b from-slate-50 via-emerald-50/20 to-slate-50 p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-xs overflow-x-auto">
                        <div class="min-w-[850px] space-y-8 text-center relative">
                            
                            <!-- LEVEL 1: KEPALA DINAS -->
                            <div class="relative flex justify-center items-center">
                                @if($fungsional)
                                <div class="absolute left-2 top-1/2 -translate-y-1/2 bg-white px-3 py-2 rounded-xl border border-slate-300 shadow-xs text-[10px] font-bold text-slate-600 uppercase tracking-tight max-w-[130px] leading-tight">
                                    {{ $fungsional->position }}
                                </div>
                                @endif

                                @if($kadin)
                                <div class="flex flex-col items-center space-y-2 relative z-10">
                                    <div class="relative w-28 h-28 sm:w-32 sm:h-32">
                                        <img src="{{ $kadin->photo ?: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=600&auto=format&fit=crop' }}" 
                                             alt="{{ $kadin->name }}" 
                                             class="w-full h-full object-cover rounded-full border-4 border-slate-900 shadow-lg">
                                    </div>
                                    <div class="space-y-1">
                                        <h3 class="font-extrabold text-slate-900 text-base sm:text-lg">{{ $kadin->name ?: 'Kepala Dinas' }}</h3>
                                        <span class="inline-block px-5 py-1.5 bg-slate-900 text-white rounded-lg text-xs font-black uppercase tracking-wider shadow-md">
                                            {{ $kadin->position }}
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="w-0.5 h-8 bg-slate-400 mx-auto"></div>

                            <!-- LEVEL 2: SEKRETARIS DINAS -->
                            @if($sekretaris)
                            <div class="flex flex-col items-center space-y-3 relative z-10">
                                <div class="w-24 h-24 sm:w-28 sm:h-28">
                                    <img src="{{ $sekretaris->photo ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=600&auto=format&fit=crop' }}" 
                                         alt="{{ $sekretaris->name }}" 
                                         class="w-full h-full object-cover rounded-full border-4 border-emerald-600 shadow-md">
                                </div>
                                <div class="space-y-1">
                                    <h4 class="font-extrabold text-slate-900 text-sm sm:text-base">{{ $sekretaris->name }}</h4>
                                    <span class="inline-block px-4 py-1 bg-emerald-700 text-white rounded-lg text-xs font-extrabold uppercase tracking-wider shadow-xs">
                                        {{ $sekretaris->position }}
                                    </span>
                                </div>

                                @if($kasubags->count() > 0)
                                <div class="pt-3 flex justify-center gap-6">
                                    @foreach($kasubags as $kas)
                                    <div class="flex flex-col items-center space-y-1.5 bg-white p-3 rounded-2xl border border-slate-200 shadow-2xs w-[170px]">
                                        <img src="{{ $kas->photo ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=300&auto=format&fit=crop' }}" class="w-14 h-14 rounded-full object-cover border-2 border-emerald-500">
                                        <span class="font-bold text-slate-900 text-[11px] leading-tight">{{ $kas->name }}</span>
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 text-[9px] font-bold rounded uppercase">{{ $kas->position }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @endif

                            @if($bidangList->count() > 0)
                            <div class="relative pt-4">
                                <div class="w-0.5 h-6 bg-slate-400 mx-auto"></div>
                                <div class="w-[82%] h-0.5 bg-slate-400 mx-auto"></div>

                                <div class="grid grid-cols-{{ min($bidangList->count(), 4) }} gap-4 pt-6 text-center">
                                    @foreach($bidangList as $bdg)
                                    <div class="flex flex-col items-center space-y-2 relative">
                                        <div class="w-0.5 h-6 bg-slate-400 absolute -top-6"></div>
                                        <div class="w-20 h-20">
                                            <img src="{{ $bdg->photo ?: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=400&auto=format&fit=crop' }}" class="w-full h-full object-cover rounded-full border-3 border-blue-600 shadow-sm">
                                        </div>
                                        <h5 class="font-extrabold text-slate-900 text-xs leading-snug">{{ $bdg->name }}</h5>
                                        <span class="px-3 py-1 bg-slate-900 text-white text-[10px] font-extrabold rounded-md uppercase tracking-tight block">
                                            {{ $bdg->position }}
                                        </span>
                                        <div class="mt-1.5 bg-white px-2 py-1 rounded border border-slate-200 text-[9px] text-slate-500 font-semibold uppercase">
                                            Kelompok Jabatan Fungsional
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            @else
                @if(!empty($page->image))
                    @php
                        $ext = strtolower(pathinfo($page->image, PATHINFO_EXTENSION));
                        $isPdf = $ext === 'pdf';
                        $isDoc = in_array($ext, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar']);
                    @endphp

                    @if($isPdf)
                        <div class="mb-6 rounded-3xl overflow-hidden shadow-sm border border-slate-200 bg-slate-900 p-3 sm:p-5 space-y-3">
                            <div class="flex items-center justify-between px-2 text-white text-xs">
                                <span class="font-extrabold flex items-center gap-2 text-emerald-400">
                                    <i class="fas fa-file-pdf text-rose-400 text-lg"></i> Berkas Lampiran PDF Halaman
                                </span>
                                <a href="{{ $page->image }}" target="_blank" download class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs inline-flex items-center gap-1.5 transition-all shadow-xs">
                                    <i class="fas fa-download text-[10px]"></i> Unduh File PDF
                                </a>
                            </div>
                            <iframe src="{{ $page->image }}" class="w-full h-[600px] rounded-2xl bg-white border border-slate-700"></iframe>
                        </div>
                    @elseif($isDoc)
                        <div class="mb-6 rounded-2xl border border-slate-200 bg-emerald-50/70 p-4 sm:p-5 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-emerald-700 text-white flex items-center justify-center text-xl shrink-0">
                                    <i class="fas fa-file-word"></i>
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-slate-900 text-sm">Berkas Lampiran Halaman</h4>
                                    <p class="text-xs text-slate-500 font-mono">{{ basename($page->image) }}</p>
                                </div>
                            </div>
                            <a href="{{ $page->image }}" target="_blank" download class="px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-xs inline-flex items-center gap-2 shrink-0">
                                <i class="fas fa-download"></i> Unduh File
                            </a>
                        </div>
                    @else
                        <!-- Foto / Gambar Halaman -->
                        <div class="mb-6 rounded-3xl overflow-hidden shadow-sm border border-slate-200 bg-slate-50 p-2 sm:p-4 text-center">
                            <img src="{{ $page->image }}" alt="{{ $page->title }}" class="w-full h-auto max-h-[700px] object-contain rounded-2xl mx-auto shadow-2xs border border-slate-100">
                        </div>
                    @endif
                @endif

                <div class="prose max-w-none text-slate-800 leading-relaxed text-sm sm:text-base space-y-4">
                    {!! $page->content !!}
                </div>
            @endif
        </div>
    </main>

    @include('partials.public_footer')

    @include('partials.tts_widget')
</body>
</html>
