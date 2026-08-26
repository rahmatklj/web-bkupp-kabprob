@extends('admin.layout')

@section('page_title', 'Kelola Pengaduan WhatsApp Resmi')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h3 class="text-lg font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                <i class="fab fa-whatsapp text-emerald-600 text-xl"></i> Kelola Pengaduan & Layanan WhatsApp
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Atur nomor WhatsApp resmi CS DKUPP, pesan pembuka otomatis, dan judul kartu akses cepat pengaduan di website.</p>
        </div>
        
        @php
            $waClean = preg_replace('/[^0-9]/', '', $waNumber ?? '081234567890');
            if (str_starts_with($waClean, '0')) {
                $waClean = '62' . substr($waClean, 1);
            }
            $testWaUrl = 'https://wa.me/' . $waClean . '?text=' . urlencode($waMessage ?? 'Halo DKUPP Kabupaten Probolinggo');
        @endphp

        <a href="{{ $testWaUrl }}" target="_blank" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs inline-flex items-center gap-2 transition-all shrink-0">
            <i class="fab fa-whatsapp text-sm"></i> Uji Coba Chat WA
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl flex items-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Live Preview Card -->
    <div class="p-5 bg-gradient-to-r from-emerald-900 to-slate-900 text-white rounded-3xl shadow-md space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-[10px] uppercase font-extrabold tracking-wider bg-emerald-500/20 text-emerald-300 px-3 py-1 rounded-full border border-emerald-500/30">
                <i class="fas fa-eye me-1"></i> Preview Kartu Akses Cepat Publik
            </span>
            <span class="text-xs text-slate-300 font-mono">Tampil di Beranda Utama</span>
        </div>
        <div class="bg-white text-slate-900 p-4 rounded-2xl border border-slate-200 flex items-center justify-between gap-4 max-w-md">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl shrink-0 shadow-2xs overflow-hidden p-1">
                    @if(!empty($waLogo) && (str_starts_with($waLogo, 'data:') || filter_var($waLogo, FILTER_VALIDATE_URL) || str_starts_with($waLogo, '/') || str_contains($waLogo, '.')))
                        <img src="{{ $waLogo }}" alt="WhatsApp Logo" class="w-full h-full object-contain">
                    @else
                        <i class="{{ $waLogo ?: 'fab fa-whatsapp' }}"></i>
                    @endif
                </div>
                <div>
                    <h4 class="font-extrabold text-sm text-slate-900 leading-snug">{{ $waTitle }}</h4>
                    <p class="text-[11px] text-slate-500 line-clamp-1">{{ $waDesc }}</p>
                </div>
            </div>
            <a href="{{ $testWaUrl }}" target="_blank" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs shrink-0 flex items-center gap-1">
                <span>Chat</span>
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
    </div>

    <!-- Main Settings Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        <form action="{{ route('admin.whatsapp.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-xs">
            @csrf

            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl space-y-3">
                <div class="flex items-center gap-2 text-emerald-900 font-extrabold text-xs">
                    <i class="fab fa-whatsapp text-emerald-600 text-base"></i> Nomor HP / WhatsApp Resmi CS <span class="text-rose-500">*</span>
                </div>
                <p class="text-slate-600 text-[11px] leading-relaxed">
                    Masukkan nomor handphone pengaduan DKUPP (Bisa diawali angka <strong>08...</strong> atau <strong>628...</strong>). Sistem akan otomatis membuatkan link <code>wa.me</code> resmi.
                </p>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nomor Handphone WhatsApp <span class="text-rose-500">*</span></label>
                    <input type="text" name="whatsapp_number" required value="{{ $waNumber }}" placeholder="081234567890 atau 6281234567890" class="w-full px-4 py-3 border border-emerald-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono text-sm text-slate-900 bg-white font-extrabold">
                </div>
            </div>

            <!-- Upload File Logo / Icon WhatsApp -->
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-3">
                <label class="block font-extrabold text-slate-800 text-xs">
                    <i class="fas fa-image text-emerald-600 me-1"></i> Logo / ikon Pengaduan WhatsApp
                </label>

                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-white border border-slate-200 p-2 flex items-center justify-center shrink-0 shadow-2xs overflow-hidden">
                        @if(!empty($waLogo) && (str_starts_with($waLogo, 'data:') || filter_var($waLogo, FILTER_VALIDATE_URL) || str_starts_with($waLogo, '/') || str_contains($waLogo, '.')))
                            <img src="{{ $waLogo }}" alt="Logo WA" class="max-h-full max-w-full object-contain">
                        @else
                            <i class="{{ $waLogo ?: 'fab fa-whatsapp' }} text-emerald-600 text-2xl"></i>
                        @endif
                    </div>
                    <div class="space-y-2 flex-1">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Unggah Berkas Logo Baru (PNG / JPG / WEBP / SVG)</label>
                            <input type="file" name="whatsapp_logo_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 cursor-pointer">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Atau Masukkan URL Gambar Logo / Kelas Ikon FontAwesome</label>
                            <input type="text" name="whatsapp_logo" value="{{ $waLogo }}" placeholder="fab fa-whatsapp atau https://... atau /uploads/..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono text-xs bg-white">
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Draf Pesan Pembuka Otomatis (Prefilled Text) <span class="text-rose-500">*</span></label>
                <textarea name="whatsapp_default_message" rows="3" required placeholder="Halo DKUPP Kabupaten Probolinggo, saya ingin menyampaikan pengaduan..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none leading-relaxed font-medium text-slate-800">{{ $waMessage }}</textarea>
                <p class="text-[10px] text-slate-500 mt-1">Pesan ini akan otomatis terisi di kolom chat WhatsApp pengunjung saat mereka mengklik tombol <strong>Chat WhatsApp</strong>.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Kartu Akses Cepat <span class="text-rose-500">*</span></label>
                    <input type="text" name="whatsapp_title" required value="{{ $waTitle }}" placeholder="Pengaduan WhatsApp" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-extrabold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Custom Link WhatsApp (Opsional Override)</label>
                    <input type="text" name="whatsapp_url_custom" value="{{ $waUrl }}" placeholder="https://wa.me/628..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono text-xs text-slate-700">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Keterangan Singkat Kartu Akses Cepat</label>
                <textarea name="whatsapp_desc" rows="2" placeholder="Pengaduan & konsultasi cepat terhubung langsung ke WhatsApp resmi DKUPP..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none leading-relaxed text-slate-700">{{ $waDesc }}</textarea>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-xs rounded-xl shadow-md transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Pengaturan WhatsApp Pengaduan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
