@extends('admin.layout')

@section('page_title', 'Kelola Link Website SP4N LAPOR!')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#sp4n_lapor_desc_editor',
            height: 200,
            menubar: 'file edit view insert format table help',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link table | removeformat code fullscreen',
            toolbar_mode: 'wrap',
            content_style: 'body { font-family: sans-serif; font-size: 13px; line-height: 1.7; color: #1e293b; padding: 10px; } p { margin-bottom: 0.75rem; }',
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

function syncSP4NTinyMCE() {
    if (typeof tinymce !== 'undefined' && tinymce.get('sp4n_lapor_desc_editor')) {
        tinymce.get('sp4n_lapor_desc_editor').save();
    }
}
</script>

<div class="space-y-6 max-w-4xl mx-auto">
    
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h3 class="text-lg font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                <i class="fas fa-bullhorn text-rose-600"></i> Kelola Link & Portal SP4N LAPOR!
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Atur link target website SP4N LAPOR! nasional, nama menu header, logo, dan keterangan portal pengaduan resmi.</p>
        </div>
        <a href="{{ route('lapor') }}" target="_blank" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs inline-flex items-center gap-2 transition-all shrink-0">
            <i class="fas fa-external-link-alt text-xs"></i> Uji Coba Link Web
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl flex items-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Main Settings Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        <form action="{{ route('admin.sp4n-lapor.update') }}" method="POST" enctype="multipart/form-data" @submit="syncSP4NTinyMCE()" class="space-y-5 text-xs">
            @csrf

            <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl space-y-2">
                <div class="flex items-center gap-2 text-rose-900 font-extrabold text-xs">
                    <i class="fas fa-link text-rose-600"></i> Target Link Website Resmi SP4N LAPOR!
                </div>
                <p class="text-slate-600 text-[11px] leading-relaxed">
                    Link ini digunakan saat pengunjung mengklik menu <strong>SP4N LAPOR!</strong> pada header website maupun tombol pada kartu layanan pengaduan.
                </p>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL / Link Target Portal SP4N LAPOR! <span class="text-rose-500">*</span></label>
                    <input type="url" name="lapor_sp4n_url" required value="{{ $laporSp4nUrl }}" placeholder="https://www.lapor.go.id/ atau link instansi" class="w-full px-4 py-3 border border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:outline-none font-mono text-xs text-slate-900 bg-white font-bold">
                    <p class="text-[10px] text-slate-500 mt-1">Contoh: <code>https://www.lapor.go.id/</code> atau <code>https://www.lapor.go.id/instansi/pemerintah-kabupaten-probolinggo</code></p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Teks Menu Header Topbar <span class="text-rose-500">*</span></label>
                    <input type="text" name="menu_title" required value="{{ $menuTitle }}" placeholder="SP4N LAPOR!" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-extrabold text-slate-900">
                    <p class="text-[10px] text-slate-500 mt-1">Nama yang tampil pada dropdown sub-menu <strong>HUBUNGI</strong> di bagian atas website publik.</p>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Banner Halaman Pengaduan</label>
                    <input type="text" name="lapor_sp4n_title" value="{{ $laporSp4nTitle }}" placeholder="SP4N LAPOR! Kabupaten Probolinggo" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold text-slate-900">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1 flex items-center justify-between">
                    <span>Keterangan Singkat Portal Pengaduan</span>
                    <span class="text-[9px] bg-rose-600 text-white font-extrabold px-2 py-0.5 rounded shadow-2xs">✨ TinyMCE Editor</span>
                </label>
                <textarea id="sp4n_lapor_desc_editor" name="lapor_sp4n_desc" rows="3" placeholder="Deskripsi singkat mengenai layanan pengaduan..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none leading-relaxed">{{ $laporSp4nDesc }}</textarea>
            </div>

            <!-- Logo Section -->
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-3">
                <label class="block font-extrabold text-slate-800 text-xs">
                    <i class="fas fa-image text-emerald-600 me-1"></i> Logo Portal SP4N LAPOR!
                </label>

                <div class="flex items-center gap-4">
                    @if(!empty($laporSp4nLogo))
                        <div class="w-16 h-16 rounded-xl bg-white border border-slate-200 p-2 flex items-center justify-center shrink-0 shadow-2xs">
                            <img src="{{ $laporSp4nLogo }}" alt="Logo SP4N LAPOR!" class="max-h-full max-w-full object-contain">
                        </div>
                    @endif
                    <div class="space-y-2 flex-1">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Unggah File Logo Baru (PNG / JPG / WEBP)</label>
                            <input type="file" name="lapor_sp4n_logo_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 cursor-pointer">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Atau Gunakan URL Gambar Logo</label>
                            <input type="text" name="lapor_sp4n_logo" value="{{ $laporSp4nLogo }}" placeholder="https://..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono text-xs bg-white">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-xs rounded-xl shadow-md transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Pengaturan Link SP4N LAPOR!
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
