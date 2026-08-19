@extends('admin.layout')

@section('page_title', 'Dashboard Overview')

@section('content')
<div class="space-y-6">
    
    <!-- Welcome Banner Card -->
    <div class="bg-gradient-to-r from-emerald-800 to-emerald-900 rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10 space-y-2">
            <span class="px-3 py-1 bg-emerald-700/50 backdrop-blur-sm border border-emerald-500/30 text-emerald-200 text-xs font-bold rounded-full uppercase tracking-wider">
                Control Panel System
            </span>
            <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
            <p class="text-xs text-emerald-100 max-w-xl leading-relaxed">
                Anda masuk sebagai <strong class="uppercase text-amber-300 font-extrabold">{{ auth()->user()->role }}</strong>. 
                @if(auth()->user()->isSuperAdmin())
                    Anda memiliki akses penuh untuk mengelola seluruh konten, menu header, slider, dokumen, berita, dan hak akses pengguna.
                @else
                    Hak akses Anda terbatas pada pengelolaan berkas <strong>Dokumen Kinerja</strong> dan artikel <strong>Informasi/Berita</strong>.
                @endif
            </p>
        </div>
        <div class="absolute right-4 -bottom-6 opacity-10 text-white text-9xl pointer-events-none">
            <i class="fas fa-user-shield"></i>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500">Dokumen Kinerja</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $stats['documents'] }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-newspaper"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500">Total Berita</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $stats['news'] }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-images"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500">Banner Hero</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $stats['sliders'] }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-eye"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500">Total Pembaca</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $stats['total_views'] }}</h3>
            </div>
        </div>

    </div>

    <!-- Quick Access Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Latest Documents Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fas fa-file-alt text-blue-600"></i> Dokumen Kinerja Terkini
                </h3>
                <a href="{{ route('admin.documents') }}" class="text-xs text-blue-600 font-bold hover:underline">Kelola Dokumen &rarr;</a>
            </div>
            <div class="space-y-3">
                @foreach($latestDocs as $doc)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs">
                        <div>
                            <h4 class="font-bold text-slate-800 line-clamp-1">{{ $doc->title }}</h4>
                            <span class="text-[10px] font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded">{{ $doc->category }}</span>
                        </div>
                        <a href="{{ $doc->file_url }}" target="_blank" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Latest News Management Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fas fa-newspaper text-purple-600"></i> Berita Terkini
                </h3>
                <a href="{{ route('admin.news') }}" class="text-xs text-purple-600 font-bold hover:underline">Kelola Berita &rarr;</a>
            </div>
            <div class="space-y-3">
                @foreach($latestNews as $news)
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <img src="{{ $news->image_url ?? 'https://images.unsplash.com/photo-1504711434969-e33886168f5c' }}" alt="News" class="w-12 h-12 rounded-lg object-cover">
                        <div class="flex-grow">
                            <h4 class="text-xs font-bold text-slate-800 line-clamp-1">{{ $news->title }}</h4>
                            <p class="text-[10px] text-slate-400">{{ optional($news->published_at)->format('d M Y') }} • {{ $news->views }} views</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
