@extends('admin.layout')

@section('page_title', 'Kelola Link Website & Logo SIMADU SAE')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto" x-data="{
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
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <i class="fas fa-store text-emerald-600 text-xl"></i>
                <h3 class="font-extrabold text-slate-900 text-lg">Kelola Portal SIMADU SAE UMKM</h3>
            </div>
            <p class="text-xs text-slate-500 mt-1">Atur link website resmi dan logo SIMADU SAE yang tampil pada kartu beranda utama.</p>
        </div>
        @if(!empty($simaduUrl))
            <a href="{{ $simaduUrl }}" target="_blank" rel="noopener noreferrer" 
               class="px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow transition-colors flex items-center gap-2 shrink-0">
                <i class="fas fa-external-link-alt"></i> Uji Coba Link SIMADU SAE
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl flex items-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Form Edit Link & Logo Portal SIMADU SAE -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        <h4 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fas fa-edit text-emerald-600"></i> Edit Informasi Link Website & Logo Portal
        </h4>

        <form action="{{ route('admin.umkm.settings') }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-xs">
            @csrf

            <!-- URL Web Input -->
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

            <!-- Upload File Logo & URL Logo SIMADU SAE -->
            <div class="space-y-3 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                <label class="font-extrabold text-slate-800 block text-xs">
                    <i class="fas fa-image text-emerald-600 me-1"></i> Logo Resmi Portal SIMADU SAE UMKM
                </label>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-upload text-emerald-600 me-1"></i> Upload File Logo (Dari HP / Komputer)</label>
                        <input type="file" name="simadu_sae_logo_file" accept="image/jpeg,image/png,.jpg,.jpeg,.png" @change="validateImageFile($event)" class="w-full px-3 py-2 border border-slate-300 rounded-xl bg-white text-slate-700 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                        
                        <!-- PEMBERITAHUAN GAGAL UPLOAD NON-JPG/PNG -->
                        <template x-if="imageErrorMsg">
                            <div class="mt-2.5 p-3 bg-rose-100 border border-rose-300 text-rose-900 rounded-xl text-xs font-bold flex items-center gap-2 shadow-xs">
                                <i class="fas fa-exclamation-triangle text-rose-600 text-base shrink-0"></i>
                                <span x-text="imageErrorMsg"></span>
                            </div>
                        </template>
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

            <!-- Submit Button -->
            <div class="pt-3 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl font-extrabold text-xs shadow-md transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Link & Logo SIMADU SAE
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
