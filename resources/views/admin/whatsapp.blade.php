@extends('admin.layout')

@section('page_title', 'Kelola Pengaduan WhatsApp Resmi')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#wa_default_msg_editor, #wa_desc_editor',
            height: 140,
            menubar: false,
            statusbar: false,
            plugins: ['autolink', 'lists', 'link', 'wordcount'],
            toolbar: 'undo redo | bold italic underline | bullist numlist | link removeformat',
            content_style: 'body { font-family: system-ui, -apple-system, sans-serif; font-size: 13px; line-height: 1.6; color: #1e293b; padding: 8px; } p { margin-bottom: 0.5rem; }',
            branding: false,
            promotion: false,
            setup: function (editor) {
                editor.on('change keyup NodeChange', function () {
                    editor.save();
                });
            }
        });
    }
});

function syncWATinyMCE() {
    if (typeof tinymce !== 'undefined') {
        if (tinymce.get('wa_default_msg_editor')) tinymce.get('wa_default_msg_editor').save();
        if (tinymce.get('wa_desc_editor')) tinymce.get('wa_desc_editor').save();
    }
}
</script>

<div class="space-y-5 max-w-4xl mx-auto">
    
    <!-- Minimalist Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div class="space-y-0.5">
            <h3 class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                <i class="fab fa-whatsapp text-emerald-600 text-xl"></i> Pengaduan & Layanan WhatsApp
            </h3>
            <p class="text-xs text-slate-500">Kelola nomor WhatsApp CS DKUPP, draf pesan otomatis, dan kartu akses cepat.</p>
        </div>
        
        @php
            $waClean = preg_replace('/[^0-9]/', '', $waNumber ?? '081234567890');
            if (str_starts_with($waClean, '0')) {
                $waClean = '62' . substr($waClean, 1);
            }
            $testWaUrl = 'https://wa.me/' . $waClean . '?text=' . urlencode(strip_tags($waMessage ?? 'Halo DKUPP Kabupaten Probolinggo'));
        @endphp

        <a href="{{ $testWaUrl }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs inline-flex items-center justify-center gap-2 transition-all shrink-0">
            <i class="fab fa-whatsapp text-sm"></i> Uji Coba Chat WA
        </a>
    </div>

    @if(session('success'))
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-xl flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-600 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Live Preview Card -->
    <div class="p-4 sm:p-5 bg-slate-900 text-white rounded-2xl shadow-sm space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-[10px] uppercase font-extrabold tracking-wider bg-emerald-500/20 text-emerald-300 px-2.5 py-0.5 rounded-full border border-emerald-500/30">
                <i class="fas fa-eye me-1"></i> Preview Tampilan Publik
            </span>
            <span class="text-[11px] text-slate-400 font-mono hidden sm:inline">Beranda Utama</span>
        </div>
        <div class="bg-white text-slate-900 p-3.5 sm:p-4 rounded-xl border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0 overflow-hidden p-1 border border-emerald-100">
                    @if(!empty($waLogo) && (str_starts_with($waLogo, 'data:') || filter_var($waLogo, FILTER_VALIDATE_URL) || str_starts_with($waLogo, '/') || str_contains($waLogo, '.')))
                        <img src="{{ $waLogo }}" alt="WhatsApp Logo" class="w-full h-full object-contain">
                    @else
                        <i class="{{ $waLogo ?: 'fab fa-whatsapp' }}"></i>
                    @endif
                </div>
                <div>
                    <h4 class="font-extrabold text-xs sm:text-sm text-slate-900 leading-snug">{{ $waTitle }}</h4>
                    <div class="text-[11px] text-slate-500 line-clamp-1">{!! strip_tags($waDesc) !!}</div>
                </div>
            </div>
            <a href="{{ $testWaUrl }}" target="_blank" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shrink-0 flex items-center justify-center gap-1.5 self-end sm:self-auto">
                <span>Chat</span>
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
    </div>

    <!-- Main Settings Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4 sm:p-6">
        <form action="{{ route('admin.whatsapp.update') }}" method="POST" enctype="multipart/form-data" @submit="syncWATinyMCE()" class="space-y-4 text-xs">
            @csrf

            <!-- Section 1: Nomor WhatsApp CS -->
            <div class="p-3.5 sm:p-4 bg-emerald-50/70 border border-emerald-200/80 rounded-xl space-y-2">
                <label class="block font-extrabold text-slate-800 text-xs">
                    <i class="fab fa-whatsapp text-emerald-600 text-sm me-1"></i> Nomor WhatsApp CS / Pengaduan Resmi <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="whatsapp_number" required value="{{ $waNumber }}" placeholder="081234567890 atau 6281234567890" class="w-full px-3.5 py-2.5 border border-emerald-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono text-xs sm:text-sm text-slate-900 bg-white font-bold">
                <p class="text-[11px] text-slate-500">Format nomor bisa diawali <strong>08...</strong> atau <strong>628...</strong>. Sistem akan otomatis memformat link <code>wa.me</code>.</p>
            </div>

            <!-- Section 2: Upload File Logo / Icon -->
            <div class="p-3.5 sm:p-4 bg-slate-50/80 border border-slate-200 rounded-xl space-y-3">
                <label class="block font-bold text-slate-800 text-xs">
                    <i class="fas fa-image text-emerald-600 me-1"></i> Logo / Ikon Pengaduan WhatsApp
                </label>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 p-1.5 flex items-center justify-center shrink-0 shadow-2xs overflow-hidden">
                        @if(!empty($waLogo) && (str_starts_with($waLogo, 'data:') || filter_var($waLogo, FILTER_VALIDATE_URL) || str_starts_with($waLogo, '/') || str_contains($waLogo, '.')))
                            <img src="{{ $waLogo }}" alt="Logo WA" class="max-h-full max-w-full object-contain">
                        @else
                            <i class="{{ $waLogo ?: 'fab fa-whatsapp' }} text-emerald-600 text-xl"></i>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 w-full">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Unggah Logo Baru (PNG/JPG/WEBP)</label>
                            <input type="file" name="whatsapp_logo_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">URL Logo / Class FontAwesome</label>
                            <input type="text" name="whatsapp_logo" value="{{ $waLogo }}" placeholder="fab fa-whatsapp" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono text-xs bg-white">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Draf Pesan Pembuka Otomatis -->
            <div class="space-y-1.5">
                <label class="block font-bold text-slate-700 text-xs">
                    Draf Pesan Pembuka Otomatis (Prefilled Text) <span class="text-rose-500">*</span>
                </label>
                <textarea id="wa_default_msg_editor" name="whatsapp_default_message" rows="3" required placeholder="Halo DKUPP Kabupaten Probolinggo, saya ingin menyampaikan pengaduan..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none leading-relaxed text-slate-800">{{ $waMessage }}</textarea>
                <p class="text-[10px] text-slate-500">Pesan ini otomatis terisi di kolom chat WhatsApp pengunjung saat mengeklik tombol <strong>Chat WhatsApp</strong>.</p>
            </div>

            <!-- Section 4: Judul & Custom Link -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700 text-xs">Judul Kartu Akses Cepat <span class="text-rose-500">*</span></label>
                    <input type="text" name="whatsapp_title" required value="{{ $waTitle }}" placeholder="Pengaduan WhatsApp" class="w-full px-3.5 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold text-slate-900">
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700 text-xs">Custom Link WhatsApp (Opsional)</label>
                    <input type="text" name="whatsapp_url_custom" value="{{ $waUrl }}" placeholder="https://wa.me/628..." class="w-full px-3.5 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono text-xs text-slate-700">
                </div>
            </div>

            <!-- Section 5: Keterangan Singkat -->
            <div class="space-y-1.5">
                <label class="block font-bold text-slate-700 text-xs">Keterangan Singkat Kartu Akses Cepat</label>
                <textarea id="wa_desc_editor" name="whatsapp_desc" rows="3" placeholder="Pengaduan & konsultasi cepat terhubung langsung ke WhatsApp resmi DKUPP..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none leading-relaxed text-slate-700">{{ $waDesc }}</textarea>
            </div>

            <!-- Section 6: Action Footer -->
            <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100">
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-xs rounded-xl shadow-xs transition-colors flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fas fa-save"></i>
                    <span>Simpan Pengaturan WhatsApp</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
