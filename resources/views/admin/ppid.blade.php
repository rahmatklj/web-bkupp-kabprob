@extends('admin.layout')

@section('page_title', 'Kelola Link Website PPID DKUPP')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#ppid_desc_editor',
            height: 220,
            menubar: 'file edit view insert format table help',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link table | removeformat code fullscreen',
            toolbar_mode: 'wrap',
            content_style: 'body { font-family: sans-serif; font-size: 13px; line-height: 1.8; color: #1e293b; padding: 10px; } p { margin-bottom: 0.75rem; }',
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

function syncPPIDTinyMCE() {
    if (typeof tinymce !== 'undefined' && tinymce.get('ppid_desc_editor')) {
        tinymce.get('ppid_desc_editor').save();
    }
}
</script>

<div class="max-w-4xl space-y-6">

    <!-- Header Card -->
    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl shrink-0">
                <i class="fas fa-info-circle"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 text-lg">Kelola Link Website PPID DKUPP</h3>
                <p class="text-xs text-slate-500 mt-0.5">Kelola tautan web resmi PPID Keterbukaan Informasi Publik yang tampil pada halaman utama beranda.</p>
            </div>
        </div>
        <a href="{{ route('home') }}" target="_blank" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl shrink-0 transition-colors flex items-center gap-1.5">
            <i class="fas fa-external-link-alt"></i> Preview Web Utama
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl flex items-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Simple Form Link Web PPID -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        <form action="{{ route('admin.ppid.update') }}" method="POST" enctype="multipart/form-data" @submit="syncPPIDTinyMCE()" class="space-y-5 text-xs">
            @csrf

            <div class="space-y-1.5 p-4 bg-blue-50/70 border border-blue-200 rounded-2xl">
                <label class="font-extrabold text-blue-900 block text-xs">
                    <i class="fas fa-link text-blue-600 me-1"></i> Link Website / URL PPID DKUPP <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="ppid_url" value="{{ $ppidUrl }}" required 
                       placeholder="https://... (paste link website eksternal) atau /halaman/ppid-dkupp" 
                       class="w-full px-4 py-3 rounded-xl border border-blue-300 font-bold text-slate-900 text-xs bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none font-mono">
                <p class="text-[10px] text-blue-700 font-medium pt-0.5">
                    <i class="fas fa-info-circle me-0.5"></i> Masukkan URL lengkap (misal <code>https://ppid.probolinggokab.go.id</code>) atau biarkan <code>/halaman/ppid-dkupp</code> untuk halaman bawaan.
                </p>
            </div>

            <!-- Upload File Logo & URL Logo PPID -->
            <div class="space-y-3 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                <label class="font-extrabold text-slate-800 block text-xs">
                    <i class="fas fa-image text-blue-600 me-1"></i> Logo Resmi Portal PPID DKUPP
                </label>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-upload text-blue-600 me-1"></i> Upload File Logo (Dari HP / Komputer)</label>
                        <input type="file" name="ppid_logo_file" accept="image/*" class="w-full px-3 py-2 border border-slate-300 rounded-xl bg-white text-slate-700 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-link text-slate-500 me-1"></i> Atau Masukkan URL Gambar Logo PPID</label>
                        <input type="text" name="ppid_logo" value="{{ old('ppid_logo', $ppidLogo ?? '') }}" placeholder="https://... atau /uploads/settings/..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-mono text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none bg-white">
                    </div>
                </div>

                @if(!empty($ppidLogo))
                    <div class="mt-2 p-3 bg-white rounded-xl border border-slate-200 inline-flex items-center gap-3">
                        <span class="text-[11px] font-bold text-slate-500">Preview Logo Saat Ini:</span>
                        <img src="{{ $ppidLogo }}" alt="Logo PPID" class="h-10 w-auto max-w-xs object-contain rounded border border-slate-200 bg-slate-50 p-1">
                    </div>
                @endif
            </div>

            <div>
                <label class="font-bold text-slate-700 block mb-1">Judul Banner PPID <span class="text-rose-500">*</span></label>
                <input type="text" name="ppid_title" value="{{ $ppidTitle }}" required 
                       placeholder="Contoh: PPID DKUPP Kabupaten Probolinggo" 
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-bold text-slate-800 text-xs">
            </div>

            <div>
                <label class="font-bold text-slate-700 block mb-1 flex items-center justify-between">
                    <span>Deskripsi Singkat Portal PPID</span>
                    <span class="text-[9px] bg-blue-600 text-white font-extrabold px-2 py-0.5 rounded shadow-2xs">✨ TinyMCE Editor</span>
                </label>
                <textarea id="ppid_desc_editor" name="ppid_desc" rows="3" placeholder="Penjelasan singkat mengenai layanan PPID..." 
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">{{ $ppidDesc }}</textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl font-extrabold text-xs shadow-md transition-all flex items-center gap-2">
                    <i class="fas fa-paper-plane"></i> Simpan Link & Logo Web PPID
                </button>
            </div>
        </form>
    </div>

    <!-- Live Preview Card Section -->
    <div class="space-y-3">
        <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase flex items-center gap-1.5">
            <i class="fas fa-eye text-emerald-600"></i> Pratinjau Tampilan Kartu di Beranda Web:
        </h4>
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4 text-center md:text-left">
                <div class="w-14 h-14 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center justify-center p-1.5 overflow-hidden shrink-0">
                    @if(filter_var($ppidLogo, FILTER_VALIDATE_URL) || str_starts_with($ppidLogo, '/') || str_contains($ppidLogo, '.'))
                        <img src="{{ $ppidLogo }}" alt="Logo PPID" class="w-full h-full object-contain">
                    @else
                        <i class="{{ $ppidLogo }} text-blue-600 text-2xl"></i>
                    @endif
                </div>
                <div>
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-widest">Portal Layanan PPID</span>
                    <h2 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $ppidTitle }}</h2>
                    <p class="text-xs text-slate-500 mt-1">{{ $ppidDesc }}</p>
                </div>
            </div>
            <a href="{{ $ppidUrl }}" target="_blank" 
               class="w-full md:w-auto min-w-[240px] justify-center px-6 py-3.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-2xl font-extrabold text-xs shrink-0 flex items-center gap-2 transition-colors">
                <i class="fas fa-globe text-base text-emerald-300"></i>
                <span>Buka Layanan PPID DKUPP</span>
                <i class="fas fa-external-link-alt text-xs"></i>
            </a>
        </div>
    </div>

</div>
@endsection
