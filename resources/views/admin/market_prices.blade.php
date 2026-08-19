@extends('admin.layout')

@section('page_title', 'Kelola Link Website Harga Pasar (Siskaperbapo)')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <i class="fas fa-globe text-amber-500 text-xl"></i>
                <h3 class="font-extrabold text-slate-900 text-lg">Kelola Link Website Harga Pasar</h3>
            </div>
            <p class="text-xs text-slate-500 mt-1">Atur 1 link website resmi yang akan terbuka saat publik mengakses menu Pemantauan Harga Pangan.</p>
        </div>
        <a href="{{ $marketWebUrl ?? 'https://siskaperbapo.jatimprov.go.id/' }}" target="_blank" rel="noopener noreferrer" 
           class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow transition-colors flex items-center gap-2 shrink-0">
            <i class="fas fa-external-link-alt"></i> Uji Coba Link Web Saat Ini
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl flex items-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Form Edit 1 Link Web Harga Pasar -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        <h4 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fas fa-edit text-emerald-600"></i> Edit Informasi Link Website Pemantauan Harga
        </h4>

        <form action="{{ route('admin.market-prices.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-xs">
            @csrf

            <!-- URL Web Input -->
            <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200 space-y-2">
                <label class="font-extrabold text-amber-900 block text-xs">
                    <i class="fas fa-link text-amber-600 me-1"></i> URL / Link Website Pemantauan Harga Pokok <span class="text-rose-500">*</span>
                </label>
                <input type="url" name="website_url" value="{{ old('website_url', $marketWebUrl ?? 'https://siskaperbapo.jatimprov.go.id/') }}" required
                       placeholder="https://siskaperbapo.jatimprov.go.id/ atau https://..."
                       class="w-full px-4 py-3 rounded-xl border border-amber-300 focus:ring-2 focus:ring-amber-600 focus:outline-none font-bold text-slate-900 text-sm bg-white shadow-2xs">
                <p class="text-[11px] text-amber-700 font-medium leading-relaxed">
                    <i class="fas fa-info-circle me-1"></i>Link ini digunakan langsung saat pengunjung mengklik menu <strong>Harga Pangan / Harga Pasar</strong> di website.
                </p>
            </div>

            <!-- Upload File Logo & URL Logo Siskaperbapo (Tergabung di Menu Monitoring Harga Pasar) -->
            <div class="space-y-3 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                <label class="font-extrabold text-slate-800 block text-xs">
                    <i class="fas fa-image text-emerald-600 me-1"></i> Logo Website Siskaperbapo / Pemantauan Harga Pasar
                </label>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-upload text-emerald-600 me-1"></i> Upload File Logo (Dari HP / Komputer)</label>
                        <input type="file" name="market_price_logo_file" accept="image/*" class="w-full px-3 py-2 border border-slate-300 rounded-xl bg-white text-slate-700 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-link text-slate-500 me-1"></i> Atau Masukkan URL Gambar Logo Siskaperbapo</label>
                        <input type="text" name="market_price_logo" value="{{ old('market_price_logo', $marketLogo ?? '') }}" placeholder="https://... atau /uploads/settings/..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-mono text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none bg-white">
                    </div>
                </div>

                @if(!empty($marketLogo))
                    <div class="mt-2 p-3 bg-white rounded-xl border border-slate-200 inline-flex items-center gap-3">
                        <span class="text-[11px] font-bold text-slate-500">Preview Logo Saat Ini:</span>
                        <img src="{{ $marketLogo }}" alt="Logo Siskaperbapo" class="h-10 w-auto max-w-xs object-contain rounded border border-slate-200 bg-slate-50 p-1">
                    </div>
                @endif
            </div>

            <!-- Judul Web Portal -->
            <div class="space-y-1.5">
                <label class="font-bold text-slate-700 block">Judul / Nama Portal Web</label>
                <input type="text" name="title" value="{{ old('title', $marketWebTitle ?? 'Portal Web Resmi Pemantauan Harga Bahan Pokok (Siskaperbapo)') }}" required
                       placeholder="Contoh: Portal Web Resmi Pemantauan Harga Bahan Pokok (Siskaperbapo)"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-800 font-medium focus:ring-2 focus:ring-emerald-600 focus:outline-none">
            </div>

            <!-- Deskripsi Singkat -->
            <div class="space-y-1.5">
                <label class="font-bold text-slate-700 block">Keterangan / Deskripsi Singkat</label>
                <textarea name="description" rows="3" 
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-800 focus:ring-2 focus:ring-emerald-600 focus:outline-none">{{ old('description', $marketWebDesc ?? 'Update harga komoditas pangan harian dari Pasar Kraksaan, Semampir, Paiton, dan Dringu Kabupaten Probolinggo & Jawa Timur.') }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="pt-3 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl font-extrabold text-xs shadow-md transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan & Perbarui Link + Logo Website
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
