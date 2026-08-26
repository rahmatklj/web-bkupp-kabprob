<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pusat Dokumen Kinerja & SAKIP | DKUPP Kabupaten Probolinggo</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
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
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex flex-col min-h-screen selection:bg-emerald-600 selection:text-white" 
      x-data="{ mobileMenu: false, highContrast: false, fontSize: 100, activePdf: null, pdfTitle: '', pdfUrl: '', downloadUrl: '' }"
      :class="{ 'high-contrast': highContrast }"
      :style="`font-size: ${fontSize}%`">

    @include('partials.public_header')

    <main class="flex-grow py-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 w-full">
        
        <!-- Tombol Kembali ke Beranda Utama -->
        <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-emerald-600 text-slate-700 hover:text-white border border-slate-200 hover:border-emerald-600 rounded-full font-extrabold text-xs shadow-xs transition-all hover:scale-105 group">
                <i class="fas fa-arrow-left text-xs text-emerald-600 group-hover:text-white transition-transform group-hover:-translate-x-0.5"></i>
                <span>Kembali ke Beranda Utama</span>
            </a>
            <span class="text-xs text-slate-400 font-semibold hidden sm:inline">Pusat Dokumen Resmi DKUPP</span>
        </div>

        <!-- Header Title -->
        <div class="text-center max-w-2xl mx-auto space-y-2.5">
            <span class="px-3.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-[11px] font-extrabold uppercase tracking-wider">Akuntabilitas Kinerja</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pusat Dokumen Kinerja & SAKIP</h1>
            <p class="text-xs text-slate-500">Baca dan unduh dokumen resmi Perencanaan Kinerja, Pengukuran, Pelaporan (LKjIP), dan Evaluasi Kinerja DKUPP.</p>
        </div>

        <!-- Filter tabs -->
        <div class="flex flex-wrap justify-center gap-2">
            <a href="{{ route('dokumen') }}" class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all {{ !request('category') ? 'bg-emerald-700 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                <i class="fas fa-folder me-1"></i> Semua Dokumen
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('dokumen', ['category' => $cat]) }}" class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all {{ request('category') == $cat ? 'bg-emerald-700 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
            <div class="p-6 bg-slate-900 text-white flex justify-between items-center border-b border-slate-800">
                <div class="flex items-center gap-2">
                    <i class="fas fa-file-pdf text-rose-400 text-lg"></i>
                    <h3 class="font-extrabold text-sm uppercase tracking-wider">Daftar Dokumen Publik</h3>
                </div>
                <span class="text-[11px] text-slate-400 font-semibold">Total: {{ $documents->total() }} Dokumen</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($documents as $doc)
                    @php
                        $rawUrl = trim($doc->file_url);
                        $fullViewUrl = (str_starts_with($rawUrl, 'http://') || str_starts_with($rawUrl, 'https://')) ? $rawUrl : route('dokumen.view', $doc->id);
                        $downloadRoute = route('dokumen.download', $doc->id);
                    @endphp
                    <div class="p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50/80 transition-colors">
                        <div class="space-y-1.5 flex-1">
                            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 rounded-full text-[10px] font-extrabold uppercase">
                                {{ $doc->category }}
                            </span>
                            <h4 class="font-extrabold text-slate-900 text-sm sm:text-base leading-snug hover:text-emerald-700 transition-colors cursor-pointer"
                                @click="activePdf = {{ $doc->id }}; pdfTitle = '{{ addslashes($doc->title) }}'; pdfUrl = '{{ $fullViewUrl }}'; downloadUrl = '{{ $downloadRoute }}'">
                                {{ $doc->title }}
                            </h4>
                            <div class="flex items-center gap-4 text-[11px] text-slate-400 font-semibold">
                                <span><i class="far fa-clock me-1 text-slate-400"></i> Diunggah {{ $doc->created_at->format('d M Y') }}</span>
                                <span><i class="fas fa-download me-1 text-emerald-600"></i> Diunduh {{ $doc->download_count }}x</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto">
                            <!-- Buka & Baca PDF Button -->
                            <button @click="activePdf = {{ $doc->id }}; pdfTitle = '{{ addslashes($doc->title) }}'; pdfUrl = '{{ $fullViewUrl }}'; downloadUrl = '{{ $downloadRoute }}'" 
                                    class="flex-1 sm:flex-none px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl shadow-xs inline-flex items-center justify-center gap-2 transition-all hover:scale-105 active:scale-95">
                                <i class="fas fa-eye text-sm"></i> <span>Buka & Baca PDF</span>
                            </button>

                            <!-- Unduh PDF Button -->
                            <a href="{{ $downloadRoute }}" target="_blank" rel="noopener noreferrer" class="flex-1 sm:flex-none px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-extrabold rounded-xl shadow-xs inline-flex items-center justify-center gap-2 transition-all hover:scale-105 active:scale-95">
                                <i class="fas fa-download text-sm"></i> <span>Unduh</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-slate-400 text-xs">
                        Tidak ada dokumen pada kategori ini.
                    </div>
                @endforelse
            </div>
            
            @if($documents->hasPages())
                <div class="p-4 bg-slate-50 border-t border-slate-100">
                    {{ $documents->links() }}
                </div>
            @endif
        </div>

    </main>

    <!-- Modal Pop-Up PDF Reader (Responsif 100% Native Web PDF Viewer) -->
    <div x-show="activePdf !== null" 
         x-cloak 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 p-2 sm:p-6 flex items-center justify-center bg-slate-950/80 backdrop-blur-xs">
        
        <div @click.away="activePdf = null" class="bg-white rounded-3xl max-w-5xl w-full h-[92vh] border border-slate-200 shadow-2xl flex flex-col overflow-hidden relative">
            
            <!-- Modal Header -->
            <div class="p-4 sm:p-5 bg-slate-900 text-white flex items-center justify-between gap-4 border-b border-slate-800 shrink-0">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-10 h-10 rounded-2xl bg-rose-500/20 text-rose-400 flex items-center justify-center text-xl shrink-0">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="truncate">
                        <span class="text-[10px] font-extrabold text-emerald-400 uppercase tracking-widest block">Pratinjau Dokumen Resmi</span>
                        <h3 class="font-extrabold text-xs sm:text-sm text-white truncate" x-text="pdfTitle"></h3>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a :href="downloadUrl" target="_blank" rel="noopener noreferrer" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors shadow-xs">
                        <i class="fas fa-download text-xs"></i> <span class="hidden sm:inline">Unduh PDF</span>
                    </a>
                    <a :href="pdfUrl" target="_blank" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors">
                        <i class="fas fa-external-link-alt text-xs"></i> <span class="hidden sm:inline">Tab Baru</span>
                    </a>
                    <button @click="activePdf = null" class="w-9 h-9 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 flex items-center justify-center transition-colors">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body (Responsive PDF Viewer) -->
            <div class="flex-grow bg-slate-950 p-2 sm:p-3 relative overflow-hidden flex flex-col justify-center items-center">
                <template x-if="activePdf !== null">
                    <iframe :src="pdfUrl" 
                            class="w-full h-full min-h-[500px] rounded-2xl border border-slate-800 bg-white shadow-2xl"
                            frameborder="0"
                            allowfullscreen>
                    </iframe>
                </template>
            </div>

        </div>
    </div>

    @include('partials.public_footer')
    @include('partials.tts_widget')
</body>
</html>
