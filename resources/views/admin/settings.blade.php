@extends('admin.layout')

@section('page_title', 'Pengaturan Website & Identitas Instansi')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#site_desc_editor',
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

function syncSettingsTinyMCE() {
    if (typeof tinymce !== 'undefined' && tinymce.get('site_desc_editor')) {
        tinymce.get('site_desc_editor').save();
    }
}
</script>

<div class="max-w-4xl space-y-6" x-data="{
    brandingErrorMsg: null,
    validatePdfAndImageFile(e) {
        this.brandingErrorMsg = null;
        const file = e.target.files[0];
        if (file) {
            const ext = file.name.split('.').pop().toLowerCase();
            if (!['jpg', 'jpeg', 'png', 'webp', 'svg', 'pdf'].includes(ext)) {
                const msg = '⚠️ GAGAL UPLOAD: Berkas yang Anda pilih berformat .' + ext.toUpperCase() + '! Sistem HANYA menerima foto (JPG, PNG, WEBP, SVG) atau dokumen PDF (.pdf).';
                this.brandingErrorMsg = msg;
                showUploadErrorSwal(msg, 'Gambar atau PDF');
                e.target.value = '';
            }
        }
    }
}">
    
    <div>
        <h3 class="text-lg font-extrabold text-slate-800">Identitas & Branding Instansi</h3>
        <p class="text-xs text-slate-500">Semua perubahan di sini akan secara otomatis memperbarui judul SEO, nama resmi dinas, deskripsi website, logo branding, serta profil Kepala Dinas.</p>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl flex items-center gap-2 shadow-xs">
            <i class="fas fa-check-circle text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" @submit="syncSettingsTinyMCE()" class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-6 text-xs">
        @csrf

        <!-- Site Title & Description -->
        <div class="space-y-4">
            <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase pb-2 border-b border-slate-100 text-emerald-700">
                1. Judul & Meta SEO
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Website (SEO Title)</label>
                    <input type="text" name="site_title" value="{{ $settings['site_title'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Kabupaten / Daerah</label>
                    <input type="text" name="regency_name" value="{{ $settings['regency_name'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Resmi Instansi Dinas</label>
                <input type="text" name="agency_name" value="{{ $settings['agency_name'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1 flex items-center justify-between">
                    <span>Deskripsi Singkat Footer</span>
                    <span class="text-[9px] bg-emerald-700 text-white font-extrabold px-2 py-0.5 rounded shadow-2xs">✨ TinyMCE Editor</span>
                </label>
                <textarea id="site_desc_editor" name="site_description" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ $settings['site_description'] ?? '' }}</textarea>
            </div>
        </div>

        <!-- Logos & Branding -->
        <div class="space-y-5">
            <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase pb-2 border-b border-slate-100 text-emerald-700 flex items-center gap-1.5">
                <i class="fas fa-image"></i> 2. Gambar Logo & Branding (Bisa Upload PDF & Foto JPG/PNG/WEBP/SVG)
            </h4>

            <!-- 1. Logo Topbar Frontend -->
            <div class="grid grid-cols-1 gap-5">
                <!-- Logo Topbar Frontend -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                    <label class="block font-extrabold text-slate-800 text-xs">
                        <i class="fas fa-file-upload text-emerald-600 me-1"></i> Logo Topbar Frontend (PDF / Gambar)
                    </label>
                    
                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-upload text-emerald-600 me-1"></i> Unggah File Logo / PDF</label>
                        <input type="file" name="logo_frontend_file" accept="image/jpeg,image/png,image/webp,image/svg+xml,.pdf,.jpg,.jpeg,.png,.webp,.svg" @change="validatePdfAndImageFile($event)"
                               class="w-full px-3 py-1.5 border border-slate-300 rounded-xl bg-white text-slate-700 text-xs file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-link text-slate-400 me-1"></i> Atau Masukkan URL Logo Topbar</label>
                        <input type="text" name="logo_frontend" value="{{ $settings['logo_frontend'] ?? '' }}" placeholder="https://... atau /uploads/settings/..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white font-mono text-[11px]">
                    </div>

                    @if(!empty($settings['logo_frontend']))
                        <div class="pt-1 flex items-center gap-2">
                            <span class="text-[10px] text-slate-400 font-bold">Preview:</span>
                            @if(str_contains(strtolower($settings['logo_frontend']), '.pdf'))
                                <a href="{{ $settings['logo_frontend'] }}" target="_blank" class="px-2 py-0.5 bg-rose-100 text-rose-800 font-extrabold rounded text-[10px] flex items-center gap-1">
                                    <i class="fas fa-file-pdf"></i> Lihat PDF
                                </a>
                            @else
                                <img src="{{ $settings['logo_frontend'] }}" class="h-8 max-w-[120px] object-contain border rounded p-0.5 bg-white">
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- 2. Logo BerAKHLAK & QR Code Survey -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Logo BerAKHLAK -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                    <label class="block font-extrabold text-slate-800 text-xs">
                        <i class="fas fa-file-upload text-emerald-600 me-1"></i> Logo BerAKHLAK Top Right (PDF / Gambar)
                    </label>
                    
                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-upload text-emerald-600 me-1"></i> Unggah File Logo BerAKHLAK / PDF</label>
                        <input type="file" name="logo_berakhlak_file" accept="image/jpeg,image/png,image/webp,image/svg+xml,.pdf,.jpg,.jpeg,.png,.webp,.svg" @change="validatePdfAndImageFile($event)"
                               class="w-full px-3 py-1.5 border border-slate-300 rounded-xl bg-white text-slate-700 text-xs file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-link text-slate-400 me-1"></i> Atau Masukkan URL Logo BerAKHLAK</label>
                        <input type="text" name="logo_berakhlak" value="{{ $settings['logo_berakhlak'] ?? '' }}" placeholder="https://... atau /uploads/settings/..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white font-mono text-[11px]">
                    </div>

                    @if(!empty($settings['logo_berakhlak']))
                        <div class="pt-1 flex items-center gap-2">
                            <span class="text-[10px] text-slate-400 font-bold">Preview:</span>
                            @if(str_contains(strtolower($settings['logo_berakhlak']), '.pdf'))
                                <a href="{{ $settings['logo_berakhlak'] }}" target="_blank" class="px-2 py-0.5 bg-rose-100 text-rose-800 font-extrabold rounded text-[10px] flex items-center gap-1">
                                    <i class="fas fa-file-pdf"></i> Lihat PDF
                                </a>
                            @else
                                <img src="{{ $settings['logo_berakhlak'] }}" class="h-8 max-w-[120px] object-contain border rounded p-0.5 bg-white">
                            @endif
                        </div>
                    @endif
                </div>

                <!-- QR Code Survey -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                    <label class="block font-extrabold text-slate-800 text-xs">
                        <i class="fas fa-qrcode text-emerald-600 me-1"></i> Image QR Code Survey (PDF / Gambar)
                    </label>
                    
                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-upload text-emerald-600 me-1"></i> Unggah File Gambar QR / PDF</label>
                        <input type="file" name="qr_code_survey_file" accept="image/jpeg,image/png,image/webp,image/svg+xml,.pdf,.jpg,.jpeg,.png,.webp,.svg" @change="validatePdfAndImageFile($event)"
                               class="w-full px-3 py-1.5 border border-slate-300 rounded-xl bg-white text-slate-700 text-xs file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-link text-slate-400 me-1"></i> Atau Masukkan URL Gambar QR</label>
                        <input type="text" name="qr_code_survey" value="{{ $settings['qr_code_survey'] ?? '' }}" placeholder="https://... atau /uploads/settings/..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white font-mono text-[11px]">
                    </div>

                    @if(!empty($settings['qr_code_survey']))
                        <div class="pt-1 flex items-center gap-2">
                            <span class="text-[10px] text-slate-400 font-bold">Preview:</span>
                            @if(str_contains(strtolower($settings['qr_code_survey']), '.pdf'))
                                <a href="{{ $settings['qr_code_survey'] }}" target="_blank" class="px-2 py-0.5 bg-rose-100 text-rose-800 font-extrabold rounded text-[10px] flex items-center gap-1">
                                    <i class="fas fa-file-pdf"></i> Lihat PDF
                                </a>
                            @else
                                <img src="{{ $settings['qr_code_survey'] }}" class="h-8 max-w-[120px] object-contain border rounded p-0.5 bg-white">
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- 3. Kepala DKUPP & Foto Kepala -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Kepala DKUPP</label>
                    <input type="text" name="kadin_name" value="{{ $settings['kadin_name'] ?? '' }}" placeholder="contoh: SUGENG WIYANTO,S.sos,M.M" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold text-slate-900">
                </div>

                <!-- Foto Kepala DKUPP -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                    <label class="block font-extrabold text-slate-800 text-xs">
                        <i class="fas fa-user-tie text-emerald-600 me-1"></i> Foto Kepala DKUPP (PDF / Gambar)
                    </label>
                    
                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-upload text-emerald-600 me-1"></i> Unggah File Foto Kepala / PDF</label>
                        <input type="file" name="kadin_photo_file" accept="image/jpeg,image/png,image/webp,image/svg+xml,.pdf,.jpg,.jpeg,.png,.webp,.svg" @change="validatePdfAndImageFile($event)"
                               class="w-full px-3 py-1.5 border border-slate-300 rounded-xl bg-white text-slate-700 text-xs file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-link text-slate-400 me-1"></i> Atau Masukkan URL Foto Kepala</label>
                        <input type="text" name="kadin_photo" value="{{ $settings['kadin_photo'] ?? '' }}" placeholder="https://... atau /uploads/settings/..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white font-mono text-[11px]">
                    </div>

                    @if(!empty($settings['kadin_photo']))
                        <div class="pt-1 flex items-center gap-2">
                            <span class="text-[10px] text-slate-400 font-bold">Preview:</span>
                            @if(str_contains(strtolower($settings['kadin_photo']), '.pdf'))
                                <a href="{{ $settings['kadin_photo'] }}" target="_blank" class="px-2 py-0.5 bg-rose-100 text-rose-800 font-extrabold rounded text-[10px] flex items-center gap-1">
                                    <i class="fas fa-file-pdf"></i> Lihat PDF
                                </a>
                            @else
                                <img src="{{ $settings['kadin_photo'] }}" class="h-10 w-10 rounded-full object-cover border border-slate-300">
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold rounded-xl shadow-md transition-all flex items-center gap-2">
                <i class="fas fa-save"></i> Simpan Semua Pengaturan
            </button>
        </div>

    </form>

</div>
@endsection
