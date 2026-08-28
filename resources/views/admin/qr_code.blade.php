@extends('admin.layout')

@section('page_title', 'Kelola Kode QR & Survei SKM')

@section('content')
<div class="max-w-4xl space-y-6 mx-auto" x-data="{
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

    <!-- Header Card -->
    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl shrink-0">
                <i class="fas fa-qrcode"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 text-lg">Kelola Kode QR & Hasil SKM</h3>
                <p class="text-xs text-slate-500 mt-0.5">Kelola gambar Kode QR footer dan poster Hasil SKM.</p>
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

    <!-- Form Kode QR -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        <form action="{{ route('admin.qr-code.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-xs">
            @csrf

            <!-- Label Kode QR -->
            <div class="space-y-1.5">
                <label class="font-extrabold text-slate-800 block text-xs">
                    <i class="fas fa-tag text-emerald-600 me-1"></i> Label / Judul Kode QR Footer <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="qr_code_label" value="{{ old('qr_code_label', $qrCodeLabel) }}" required placeholder="Contoh: Scan QR Portal Pelayanan & Hasil SKM"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 font-bold text-slate-800 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
            </div>

            <!-- Upload File Poster Hasil SKM -->
            <div class="space-y-3 p-4 bg-blue-50/70 rounded-2xl border border-blue-200">
                <label class="font-extrabold text-blue-900 block text-xs">
                    <i class="fas fa-chart-bar text-blue-600 me-1"></i> File Foto / Poster Hasil SKM (Tampil di Sidebar Beranda) <span class="text-rose-500">*</span>
                </label>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-upload text-blue-600 me-1"></i> Upload Foto Poster Hasil SKM (Dari HP / Komputer)</label>
                        <input type="file" name="skm_file" accept="image/jpeg,image/png,.jpg,.jpeg,.png,.svg" @change="validateImageFile($event)" class="w-full px-3 py-2 border border-slate-300 rounded-xl bg-white text-slate-700 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-link text-slate-500 me-1"></i> Atau Masukkan URL Gambar Poster Hasil SKM</label>
                        <input type="text" name="skm_image" value="{{ old('skm_image', $skmImage ?? '') }}" placeholder="https://... atau /uploads/settings/..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-mono text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none bg-white">
                    </div>
                </div>

                @if(!empty($skmImage))
                    <div class="mt-2 p-3 bg-white rounded-xl border border-slate-200 inline-flex flex-col gap-2">
                        <span class="text-[11px] font-bold text-slate-500">Preview Poster Hasil SKM Saat Ini (Tampil di Sidebar Beranda):</span>
                        <img src="{{ $skmImage }}" alt="Poster Hasil SKM" class="h-44 w-auto object-contain rounded border border-slate-200 bg-slate-900 p-2">
                    </div>
                @endif
            </div>

            <!-- Upload File Kode QR & URL Footer -->
            <div class="space-y-3 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                <label class="font-extrabold text-slate-800 block text-xs">
                    <i class="fas fa-qrcode text-emerald-600 me-1"></i> File Foto / Gambar Kode QR Footer
                </label>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-upload text-emerald-600 me-1"></i> Upload File Foto Kode QR (Dari HP / Komputer)</label>
                        <input type="file" name="qr_file" accept="image/jpeg,image/png,.jpg,.jpeg,.png,.svg" @change="validateImageFile($event)" class="w-full px-3 py-2 border border-slate-300 rounded-xl bg-white text-slate-700 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                        
                        <!-- PEMBERITAHUAN GAGAL UPLOAD NON-JPG/PNG -->
                        <template x-if="imageErrorMsg">
                            <div class="mt-2.5 p-3 bg-rose-100 border border-rose-300 text-rose-900 rounded-xl text-xs font-bold flex items-center gap-2 shadow-xs">
                                <i class="fas fa-exclamation-triangle text-rose-600 text-base shrink-0"></i>
                                <span x-text="imageErrorMsg"></span>
                            </div>
                        </template>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-link text-slate-500 me-1"></i> Atau Masukkan URL Gambar Kode QR</label>
                        <input type="text" name="qr_code_image" value="{{ old('qr_code_image', $qrCodeImage ?? '') }}" placeholder="https://... atau /uploads/settings/..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-mono text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none bg-white">
                    </div>
                </div>

                @if(!empty($qrCodeImage))
                    <div class="mt-2 p-3 bg-white rounded-xl border border-slate-200 inline-flex flex-col gap-2">
                        <span class="text-[11px] font-bold text-slate-500">Preview Kode QR Footer Saat Ini:</span>
                        <img src="{{ $qrCodeImage }}" alt="Kode QR Footer" class="h-44 w-44 object-contain rounded border border-slate-200 bg-slate-50 p-2">
                    </div>
                @endif
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl font-extrabold text-xs shadow-md transition-all flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Kode QR & Hasil SKM
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
