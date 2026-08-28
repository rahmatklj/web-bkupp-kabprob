@extends('admin.layout')

@section('page_title', 'Kelola Integrasi & Link Portal Publik')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof tinymce !== 'undefined') {
        const ids = ['market_desc_editor', 'sp4n_desc_editor', 'wa_portal_default_msg_editor', 'wa_portal_desc_editor', 'ppid_portal_desc_editor'];
        ids.forEach(id => {
            tinymce.init({
                selector: '#' + id,
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
        });
    }
});

function syncPortalLinksTinyMCE() {
    if (typeof tinymce !== 'undefined') {
        tinymce.triggerSave();
    }
}
</script>
<div class="space-y-6 max-w-5xl mx-auto" x-data="{ 
    activeTab: '{{ request('tab', $activeTab ?? 'harga-pasar') }}',
    imageErrorMsg: null,
    validateImageFile(e) {
        this.imageErrorMsg = null;
        const file = e.target.files[0];
        if (file) {
            const ext = file.name.split('.').pop().toLowerCase();
            if (!['jpg', 'jpeg', 'png'].includes(ext)) {
                const msg = '⚠️ GAGAL UPLOAD: Berkas yang Anda pilih berformat .' + ext.toUpperCase() + '! Sistem HANYA menerima foto berformat JPG & PNG (.jpg, .jpeg, .png).';
                this.imageErrorMsg = msg;
                showUploadErrorSwal(msg, 'JPG atau PNG');
                e.target.value = '';
            }
        }
    }
}">
    
    <!-- Top Main Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 text-white p-6 sm:p-7 rounded-3xl shadow-lg border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 flex items-center justify-center text-2xl shrink-0 shadow-inner">
                <i class="fas fa-globe"></i>
            </div>
            <div>
                <h2 class="text-xl font-extrabold tracking-tight">Kelola Integrasi & Link Portal Publik</h2>
                <p class="text-xs text-slate-300 mt-1">Pusat pengaturan 5 portal layanan utama DKUPP: Monitoring Harga Pasar, SIMADU SAE, SP4N LAPOR!, WhatsApp Pengaduan, dan PPID.</p>
            </div>
        </div>
        <a href="{{ route('home') }}" target="_blank" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2 shrink-0">
            <i class="fas fa-external-link-alt"></i> Pratinjau Web Utama
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl flex items-center gap-2 shadow-xs">
            <i class="fas fa-check-circle text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Tabs Navigation Bar -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200">
        <!-- Tab 1: Harga Pasar -->
        <button @click="activeTab = 'harga-pasar'"
                :class="activeTab === 'harga-pasar' ? 'bg-amber-600 text-white shadow-md font-extrabold border-amber-600' : 'bg-white text-slate-700 hover:bg-slate-100 border-slate-200 font-semibold'"
                class="px-4 py-3 rounded-2xl border text-xs flex items-center gap-2 transition-all shrink-0">
            <i class="fas fa-shopping-basket text-sm"></i>
            <span>Monitoring Harga Pasar</span>
        </button>

        <!-- Tab 2: SIMADU SAE -->
        <button @click="activeTab = 'simadu'"
                :class="activeTab === 'simadu' ? 'bg-emerald-600 text-white shadow-md font-extrabold border-emerald-600' : 'bg-white text-slate-700 hover:bg-slate-100 border-slate-200 font-semibold'"
                class="px-4 py-3 rounded-2xl border text-xs flex items-center gap-2 transition-all shrink-0">
            <i class="fas fa-store text-sm"></i>
            <span>SIMADU SAE (UMKM)</span>
        </button>

        <!-- Tab 3: SP4N LAPOR! -->
        <button @click="activeTab = 'sp4n-lapor'"
                :class="activeTab === 'sp4n-lapor' ? 'bg-rose-600 text-white shadow-md font-extrabold border-rose-600' : 'bg-white text-slate-700 hover:bg-slate-100 border-slate-200 font-semibold'"
                class="px-4 py-3 rounded-2xl border text-xs flex items-center gap-2 transition-all shrink-0">
            <i class="fas fa-bullhorn text-sm"></i>
            <span>SP4N LAPOR!</span>
        </button>

        <!-- Tab 4: WhatsApp Pengaduan -->
        <button @click="activeTab = 'whatsapp'"
                :class="activeTab === 'whatsapp' ? 'bg-emerald-700 text-white shadow-md font-extrabold border-emerald-700' : 'bg-white text-slate-700 hover:bg-slate-100 border-slate-200 font-semibold'"
                class="px-4 py-3 rounded-2xl border text-xs flex items-center gap-2 transition-all shrink-0">
            <i class="fab fa-whatsapp text-sm"></i>
            <span>WhatsApp Pengaduan</span>
        </button>

        <!-- Tab 5: PPID DKUPP -->
        <button @click="activeTab = 'ppid'"
                :class="activeTab === 'ppid' ? 'bg-sky-600 text-white shadow-md font-extrabold border-sky-600' : 'bg-white text-slate-700 hover:bg-slate-100 border-slate-200 font-semibold'"
                class="px-4 py-3 rounded-2xl border text-xs flex items-center gap-2 transition-all shrink-0">
            <i class="fas fa-info-circle text-sm"></i>
            <span>PPID DKUPP</span>
        </button>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 1: MONITORING HARGA PASAR -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'harga-pasar'" x-cloak class="space-y-6">
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg font-bold">
                        <i class="fas fa-shopping-basket"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Kelola Portal Monitoring Harga Pasar</h3>
                        <p class="text-xs text-slate-500">Atur link website resmi dan logo portal Pemantauan Harga Pangan yang tampil pada kartu beranda utama.</p>
                    </div>
                </div>
                @if(!empty($marketWebUrl))
                    <a href="{{ $marketWebUrl }}" target="_blank" class="px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold text-xs rounded-xl border border-amber-200 flex items-center gap-2 transition-colors shrink-0">
                        <i class="fas fa-external-link-alt"></i> Uji Coba Link
                    </a>
                @endif
            </div>

            <!-- Form Edit Link Web Harga Pasar -->
            <form action="{{ route('admin.market-prices.store') }}" method="POST" enctype="multipart/form-data" @submit="syncPortalLinksTinyMCE()" class="space-y-5 text-xs">
                @csrf

                <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200 space-y-2">
                    <label class="font-extrabold text-amber-900 block text-xs">
                        <i class="fas fa-link text-amber-600 me-1"></i> URL / Link Website Pemantauan Harga Pokok <span class="text-rose-500">*</span>
                    </label>
                    <input type="url" name="website_url" value="{{ old('website_url', $marketWebUrl ?? 'https://siskaperbapo.jatimprov.go.id/') }}" required
                           placeholder="https://siskaperbapo.jatimprov.go.id/ atau https://..."
                           class="w-full px-4 py-3 rounded-xl border border-amber-300 focus:ring-2 focus:ring-amber-600 focus:outline-none font-bold text-slate-900 text-sm bg-white shadow-2xs">
                    <p class="text-[11px] text-amber-800 font-medium leading-relaxed">
                        <i class="fas fa-info-circle me-1"></i>Link ini digunakan langsung saat pengunjung mengklik kartu <strong>Monitoring Harga Pasar</strong> di beranda.
                    </p>
                </div>

                <div class="space-y-3 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                    <label class="font-extrabold text-slate-800 block text-xs">
                        <i class="fas fa-image text-amber-600 me-1"></i> Logo Website Pemantauan Harga Pasar
                    </label>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-upload text-amber-600 me-1"></i> Upload File Logo (Dari HP / Komputer)</label>
                            <input type="file" name="market_price_logo_file" accept="image/jpeg,image/png,.jpg,.jpeg,.png" @change="validateImageFile($event)" class="w-full px-3 py-2 border border-slate-300 rounded-xl bg-white text-slate-700 text-xs focus:ring-2 focus:ring-amber-500 focus:outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-600 file:text-white hover:file:bg-amber-700 cursor-pointer">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-link text-slate-500 me-1"></i> Atau Masukkan URL Gambar Logo</label>
                            <input type="text" name="market_price_logo" value="{{ old('market_price_logo', $marketLogo ?? '') }}" placeholder="https://... atau /uploads/settings/..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-mono text-xs focus:ring-2 focus:ring-amber-600 focus:outline-none bg-white">
                        </div>
                    </div>

                    @if(!empty($marketLogo))
                        <div class="mt-2 p-3 bg-white rounded-xl border border-slate-200 inline-flex items-center gap-3">
                            <span class="text-[11px] font-bold text-slate-500">Preview Logo Saat Ini:</span>
                            <img src="{{ $marketLogo }}" alt="Logo Monitoring Harga Pasar" class="h-10 w-auto max-w-xs object-contain rounded border border-slate-200 bg-slate-50 p-1">
                        </div>
                    @endif
                </div>

                <div class="space-y-1.5">
                    <label class="font-bold text-slate-700 block">Judul / Nama Portal Web</label>
                    <input type="text" name="title" value="{{ old('title', $marketWebTitle ?? 'Portal Web Resmi Pemantauan Harga Bahan Pokok (Simadu)') }}" required
                           placeholder="Contoh: Portal Web Resmi Pemantauan Harga Bahan Pokok (Simadu)"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-800 font-medium focus:ring-2 focus:ring-amber-600 focus:outline-none">
                </div>

                <div class="space-y-1.5">
                    <label class="font-bold text-slate-700 block flex items-center justify-between">
                        <span>Keterangan / Deskripsi Singkat</span>
                        <span class="text-[9px] bg-amber-600 text-white font-extrabold px-2 py-0.5 rounded shadow-2xs">✨ TinyMCE Editor</span>
                    </label>
                    <textarea id="market_desc_editor" name="description" rows="3" 
                              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-800 focus:ring-2 focus:ring-amber-600 focus:outline-none">{{ old('description', $marketWebDesc ?? 'Update harga komoditas pangan harian dari Pasar Kraksaan, Semampir, Paiton, dan Dringu Kabupaten Probolinggo & Jawa Timur.') }}</textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-extrabold text-xs shadow-md transition-colors flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Pengaturan Monitoring Harga Pasar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 2: SIMADU SAE (UMKM) -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'simadu'" x-cloak class="space-y-6">
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg font-bold">
                        <i class="fas fa-store"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Kelola Portal SIMADU SAE UMKM</h3>
                        <p class="text-xs text-slate-500">Atur link website resmi dan logo SIMADU SAE yang tampil pada kartu beranda utama.</p>
                    </div>
                </div>
                @if(!empty($simaduUrl))
                    <a href="{{ $simaduUrl }}" target="_blank" class="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs rounded-xl border border-emerald-200 flex items-center gap-2 transition-colors shrink-0">
                        <i class="fas fa-external-link-alt"></i> Uji Coba Link SIMADU
                    </a>
                @endif
            </div>

            <form action="{{ route('admin.umkm.settings') }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-xs">
                @csrf

                <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200 space-y-2">
                    <label class="font-extrabold text-emerald-900 block text-xs">
                        <i class="fas fa-link text-emerald-600 me-1"></i> Target Link Katalog Website SIMADU SAE UMKM <span class="text-rose-500">*</span>
                    </label>
                    <input type="url" name="simadu_sae_url" value="{{ old('simadu_sae_url', $simaduUrl ?? 'https://simadu.probolinggokab.go.id/') }}" required
                           placeholder="https://simadu.probolinggokab.go.id/ atau https://..."
                           class="w-full px-4 py-3 rounded-xl border border-emerald-300 focus:ring-2 focus:ring-emerald-600 focus:outline-none font-bold text-slate-900 text-sm bg-white shadow-2xs">
                    <p class="text-[11px] text-emerald-700 font-medium leading-relaxed">
                        <i class="fas fa-info-circle me-1"></i>Link ini digunakan langsung saat pengunjung mengklik kartu <strong>SIMADU SAE</strong> di beranda.
                    </p>
                </div>

                <div class="space-y-3 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                    <label class="font-extrabold text-slate-800 block text-xs">
                        <i class="fas fa-image text-emerald-600 me-1"></i> Logo Resmi Portal SIMADU SAE UMKM
                    </label>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-upload text-emerald-600 me-1"></i> Upload File Logo (Dari HP / Komputer)</label>
                            <input type="file" name="simadu_sae_logo_file" accept="image/jpeg,image/png,.jpg,.jpeg,.png" @change="validateImageFile($event)" class="w-full px-3 py-2 border border-slate-300 rounded-xl bg-white text-slate-700 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-link text-slate-500 me-1"></i> Atau Masukkan URL Gambar Logo SIMADU SAE</label>
                            <input type="text" name="simadu_sae_logo" value="{{ old('simadu_sae_logo', $simaduLogo ?? '') }}" placeholder="https://... atau /uploads/settings/..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-mono text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none bg-white">
                        </div>
                    </div>

                    @if(!empty($simaduLogo))
                        <div class="mt-2 p-3 bg-white rounded-xl border border-slate-200 inline-flex items-center gap-3">
                            <span class="text-[11px] font-bold text-slate-500">Preview Logo Saat Ini:</span>
                            <img src="{{ $simaduLogo }}" alt="Logo SIMADU SAE" class="h-10 w-auto max-w-xs object-contain rounded border border-slate-200 bg-slate-50 p-1">
                        </div>
                    @endif
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-extrabold text-xs shadow-md transition-colors flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Link & Logo SIMADU SAE
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 3: SP4N LAPOR! -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'sp4n-lapor'" x-cloak class="space-y-6">
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-lg font-bold">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Kelola Link & Portal SP4N LAPOR!</h3>
                        <p class="text-xs text-slate-500">Atur link target website SP4N LAPOR! nasional, nama menu header, logo, dan keterangan portal pengaduan resmi.</p>
                    </div>
                </div>
                @if(!empty($laporSp4nUrl))
                    <a href="{{ $laporSp4nUrl }}" target="_blank" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-800 font-bold text-xs rounded-xl border border-rose-200 flex items-center gap-2 transition-colors shrink-0">
                        <i class="fas fa-external-link-alt"></i> Uji Coba Link SP4N
                    </a>
                @endif
            </div>

            <form action="{{ route('admin.sp4n-lapor.update') }}" method="POST" enctype="multipart/form-data" @submit="syncPortalLinksTinyMCE()" class="space-y-5 text-xs">
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
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Teks Menu Header Topbar <span class="text-rose-500">*</span></label>
                        <input type="text" name="menu_title" required value="{{ $menuTitle }}" placeholder="SP4N LAPOR!" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-extrabold text-slate-900">
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
                    <textarea id="sp4n_desc_editor" name="lapor_sp4n_desc" rows="3" placeholder="Deskripsi singkat mengenai layanan pengaduan..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none leading-relaxed">{{ $laporSp4nDesc }}</textarea>
                </div>

                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-3">
                    <label class="block font-extrabold text-slate-800 text-xs">
                        <i class="fas fa-image text-rose-600 me-1"></i> Logo Portal SP4N LAPOR!
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
                                <input type="file" name="lapor_sp4n_logo_file" accept="image/jpeg,image/png,.jpg,.jpeg,.png" @change="validateImageFile($event)" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-100 file:text-rose-800 hover:file:bg-rose-200 cursor-pointer">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Atau Gunakan URL Gambar Logo</label>
                                <input type="text" name="lapor_sp4n_logo" value="{{ $laporSp4nLogo }}" placeholder="https://..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:outline-none font-mono text-xs bg-white">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                    <button type="submit" class="px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-colors flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Pengaturan SP4N LAPOR!
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 4: WHATSAPP PENGADUAN -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'whatsapp'" x-cloak class="space-y-6">
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg font-bold">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Kelola WA Pengaduan Hallo SAE (Portal Akses Cepat Beranda)</h3>
                        <p class="text-xs text-slate-500">Atur nomor WhatsApp pengaduan Lapor Hallo SAE, pesan pembuka otomatis, dan judul kartu akses cepat di beranda.</p>
                    </div>
                </div>
                @php
                    $waClean = preg_replace('/[^0-9]/', '', $waNumber ?? '081234567890');
                    if (str_starts_with($waClean, '0')) {
                        $waClean = '62' . substr($waClean, 1);
                    }
                    $testWaUrl = 'https://wa.me/' . $waClean . '?text=' . urlencode($waMessage ?? 'Halo DKUPP Kabupaten Probolinggo');
                @endphp
                <a href="{{ $testWaUrl }}" target="_blank" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-xs inline-flex items-center gap-2 transition-all shrink-0">
                    <i class="fab fa-whatsapp text-sm"></i> Uji Coba Chat WA
                </a>
            </div>

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
                                <input type="file" name="whatsapp_logo_file" accept="image/jpeg,image/png,.jpg,.jpeg,.png" @change="validateImageFile($event)" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 cursor-pointer">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Atau Masukkan URL Gambar Logo / Kelas Ikon FontAwesome</label>
                                <input type="text" name="whatsapp_logo" value="{{ $waLogo }}" placeholder="fab fa-whatsapp atau https://... atau /uploads/..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono text-xs bg-white">
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1 flex items-center justify-between">
                        <span>Draf Pesan Pembuka Otomatis (Prefilled Text) <span class="text-rose-500">*</span></span>
                        <span class="text-[9px] bg-emerald-700 text-white font-extrabold px-2 py-0.5 rounded shadow-2xs">✨ TinyMCE Editor</span>
                    </label>
                    <textarea id="wa_portal_default_msg_editor" name="whatsapp_default_message" rows="3" required placeholder="Halo DKUPP Kabupaten Probolinggo, saya ingin menyampaikan pengaduan..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none leading-relaxed font-medium text-slate-800">{{ $waMessage }}</textarea>
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
                    <label class="block font-bold text-slate-700 mb-1 flex items-center justify-between">
                        <span>Keterangan Singkat Kartu Akses Cepat</span>
                        <span class="text-[9px] bg-emerald-700 text-white font-extrabold px-2 py-0.5 rounded shadow-2xs">✨ TinyMCE Editor</span>
                    </label>
                    <textarea id="wa_portal_desc_editor" name="whatsapp_desc" rows="3" placeholder="Pengaduan & konsultasi cepat terhubung langsung ke WhatsApp resmi DKUPP..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none leading-relaxed text-slate-700">{{ $waDesc }}</textarea>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                    <button type="submit" class="px-6 py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-xs rounded-xl shadow-md transition-colors flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Pengaturan WhatsApp Pengaduan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 5: PPID DKUPP -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'ppid'" x-cloak class="space-y-6">
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center text-lg font-bold">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Kelola Link Website PPID DKUPP</h3>
                        <p class="text-xs text-slate-500">Kelola tautan web resmi PPID Keterbukaan Informasi Publik yang tampil pada halaman utama beranda.</p>
                    </div>
                </div>
                @if(!empty($ppidUrl))
                    <a href="{{ $ppidUrl }}" target="_blank" class="px-4 py-2 bg-sky-50 hover:bg-sky-100 text-sky-800 font-bold text-xs rounded-xl border border-sky-200 flex items-center gap-2 transition-colors shrink-0">
                        <i class="fas fa-external-link-alt"></i> Uji Coba Link PPID
                    </a>
                @endif
            </div>

            <form action="{{ route('admin.ppid.update') }}" method="POST" enctype="multipart/form-data" @submit="syncPortalLinksTinyMCE()" class="space-y-5 text-xs">
                @csrf

                <div class="space-y-1.5 p-4 bg-sky-50 border border-sky-200 rounded-2xl">
                    <label class="font-extrabold text-sky-900 block text-xs">
                        <i class="fas fa-link text-sky-600 me-1"></i> Link Website / URL PPID DKUPP <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="ppid_url" value="{{ $ppidUrl }}" required 
                           placeholder="https://... (paste link website eksternal) atau /halaman/ppid-dkupp" 
                           class="w-full px-4 py-3 rounded-xl border border-sky-300 font-bold text-slate-900 text-xs bg-white focus:ring-2 focus:ring-sky-600 focus:outline-none font-mono">
                    <p class="text-[10px] text-sky-800 font-medium pt-0.5">
                        <i class="fas fa-info-circle me-0.5"></i> Masukkan URL lengkap (misal <code>https://ppid.probolinggokab.go.id</code>) atau biarkan <code>/halaman/ppid-dkupp</code> untuk halaman bawaan.
                    </p>
                </div>

                <div class="space-y-3 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                    <label class="font-extrabold text-slate-800 block text-xs">
                        <i class="fas fa-image text-sky-600 me-1"></i> Logo Resmi Portal PPID DKUPP
                    </label>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-upload text-sky-600 me-1"></i> Upload File Logo (Dari HP / Komputer)</label>
                            <input type="file" name="ppid_logo_file" accept="image/jpeg,image/png,.jpg,.jpeg,.png" @change="validateImageFile($event)" class="w-full px-3 py-2 border border-slate-300 rounded-xl bg-white text-slate-700 text-xs focus:ring-2 focus:ring-sky-500 focus:outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-sky-600 file:text-white hover:file:bg-sky-700 cursor-pointer">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-link text-slate-500 me-1"></i> Atau Masukkan URL Gambar Logo PPID</label>
                            <input type="text" name="ppid_logo" value="{{ old('ppid_logo', $ppidLogo ?? '') }}" placeholder="https://... atau /uploads/settings/..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-mono text-xs focus:ring-2 focus:ring-sky-600 focus:outline-none bg-white">
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
                        <span class="text-[9px] bg-sky-600 text-white font-extrabold px-2 py-0.5 rounded shadow-2xs">✨ TinyMCE Editor</span>
                    </label>
                    <textarea id="ppid_portal_desc_editor" name="ppid_desc" rows="3" placeholder="Penjelasan singkat mengenai layanan PPID..." 
                              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">{{ $ppidDesc }}</textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white rounded-xl font-extrabold text-xs shadow-md transition-colors flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Link & Logo Web PPID
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
