@extends('admin.layout')

@section('page_title', 'Kelola Informasi Alamat & Kontak Resmi')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#address_editor',
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

function syncContactTinyMCE() {
    if (typeof tinymce !== 'undefined' && tinymce.get('address_editor')) {
        tinymce.get('address_editor').save();
    }
}
</script>

<div class="space-y-6 max-w-4xl mx-auto"
     x-data="{ 
         searchQuery: '{{ old('google_map_search', $settings['google_map_search'] ?? 'Kantor Bupati Probolinggo') }}',
         embedUrl: '{{ old('google_map_embed', $settings['google_map_embed'] ?? 'https://maps.google.com/maps?q=Kantor%20Bupati%20Probolinggo&t=&z=16&ie=UTF8&iwloc=&output=embed') }}',
         updateMap() {
             if (this.searchQuery.trim() !== '') {
                 var query = this.searchQuery.trim();
                 this.embedUrl = 'https://maps.google.com/maps?q=' + encodeURIComponent(query) + '&t=&z=16&ie=UTF8&iwloc=&output=embed';
             }
         }
     }"
     x-init="updateMap()">
    
    <!-- Header Title Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h3 class="text-lg font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-emerald-600 text-xl"></i> Kelola Informasi Alamat & Kontak Resmi
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Kelola alamat lengkap kantor, nomor telepon, email resmi, teks copyright footer, dan lokasi peta Google Maps.</p>
        </div>
        <a href="{{ route('home') }}" target="_blank" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl shadow-xs inline-flex items-center gap-2 transition-all shrink-0">
            <i class="fas fa-external-link-alt text-xs"></i> Lihat Tampilan Website
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl flex items-center gap-2 shadow-xs">
            <i class="fas fa-check-circle text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Live Interactive Preview Box -->
    <div class="p-5 bg-[#09182b] text-white rounded-3xl shadow-md space-y-3">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <span class="text-[10px] uppercase font-extrabold tracking-wider bg-emerald-500/20 text-emerald-300 px-3 py-1 rounded-full border border-emerald-500/30">
                <i class="fas fa-eye me-1"></i> Preview Tampilan Footer Kontak Publik
            </span>
            <span class="text-xs text-slate-400 font-mono">Tampil di Footer Website</span>
        </div>
        
        <div class="bg-[#0f243e] p-4.5 rounded-2xl border border-slate-700/70 text-slate-300 space-y-3 text-xs">
            <div class="font-extrabold text-white text-sm border-b border-slate-700/80 pb-1.5 flex items-center gap-2">
                <i class="fas fa-building text-emerald-400"></i> Alamat Kantor DKUPP
            </div>
            <div class="space-y-2">
                <p class="flex items-start gap-2">
                    <i class="fas fa-map-marker-alt text-emerald-400 mt-0.5 shrink-0"></i>
                    <span>{{ $settings['address'] ?? 'Jl. Raya Panglima Sudirman No. 134 / Loket MPP Kraksaan, Kabupaten Probolinggo' }}</span>
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1 border-t border-slate-800 text-[11px]">
                    <p class="flex items-center gap-2">
                        <i class="fas fa-phone-alt text-emerald-400 shrink-0"></i>
                        <span>{{ $settings['phone'] ?? '(0335) 844554' }}</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <i class="fas fa-envelope text-emerald-400 shrink-0"></i>
                        <span class="break-all">{{ $settings['email'] ?? 'dkupp@probolinggokab.go.id' }}</span>
                    </p>
                </div>
            </div>
            <div class="pt-2 border-t border-slate-800 text-[10px] text-slate-400 text-center font-medium">
                {{ $settings['footer_copyright'] ?? 'DKUPP - Kabupaten Probolinggo © 2026. All Rights Reserved.' }}
            </div>
        </div>
    </div>

    <!-- Form Setting Alamat & Kontak -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-6">
        <form action="{{ route('admin.contact-info.update') }}" method="POST" @submit="syncContactTinyMCE()" class="space-y-6">
            @csrf
            
            <div class="space-y-4">
                <h4 class="font-extrabold text-slate-900 text-sm border-b border-slate-100 pb-2.5 uppercase tracking-wider text-emerald-700 flex items-center gap-2">
                    <i class="fas fa-edit"></i> Edit Informasi Alamat & Kontak Utama
                </h4>

                <!-- Alamat Kantor Lengkap -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-xs text-slate-700 flex items-center justify-between">
                        <span>Alamat Kantor Lengkap <span class="text-rose-500">*</span></span>
                        <span class="text-[9px] bg-emerald-700 text-white font-extrabold px-2 py-0.5 rounded shadow-2xs">✨ TinyMCE Editor</span>
                    </label>
                    <textarea id="address_editor" name="address" rows="3" required
                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none text-xs text-slate-800 font-medium leading-relaxed"
                              placeholder="Masukkan Alamat Kantor Lengkap...">{{ old('address', $settings['address'] ?? '') }}</textarea>
                    <p class="text-[11px] text-slate-400">Alamat fisik kantor yang akan ditampilkan pada footer website publik.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nomor Telepon Kantor -->
                    <div class="space-y-1.5">
                        <label class="block font-bold text-xs text-slate-700">Nomor Telepon Kantor / Hotline <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="text" name="phone" value="{{ old('phone', $settings['phone'] ?? '') }}" required
                                   class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none text-xs text-slate-800 font-medium"
                                   placeholder="Contoh: (0335) 844554">
                            <i class="fas fa-phone-alt absolute left-3 top-3 text-slate-400 text-xs"></i>
                        </div>
                    </div>

                    <!-- Email Resmi Kantor -->
                    <div class="space-y-1.5">
                        <label class="block font-bold text-xs text-slate-700">Email Resmi Kantor <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="email" name="email" value="{{ old('email', $settings['email'] ?? '') }}" required
                                   class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none text-xs text-slate-800 font-medium"
                                   placeholder="Contoh: dkupp@probolinggokab.go.id">
                            <i class="fas fa-envelope absolute left-3 top-3 text-slate-400 text-xs"></i>
                        </div>
                    </div>
                </div>

                <!-- Teks Copyright Footer -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-xs text-slate-700">Teks Copyright Footer <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="footer_copyright" value="{{ old('footer_copyright', $settings['footer_copyright'] ?? '') }}" required
                               class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none text-xs text-slate-800 font-medium"
                               placeholder="Contoh: DKUPP Kab. Probolinggo - Kabupaten Probolinggo © 2026. All Rights Reserved.">
                        <i class="fas fa-copyright absolute left-3 top-3 text-slate-400 text-xs"></i>
                    </div>
                    <p class="text-[11px] text-slate-400">Teks hak cipta yang muncul di bilah paling bawah (*bottom bar*) website.</p>
                </div>
            </div>

            <!-- Section Google Maps Search & Embed (Matching Image 2) -->
            <div class="space-y-4 pt-6 border-t border-slate-100">
                <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase flex items-center gap-2 text-amber-700">
                    <i class="fas fa-map-marked-alt text-amber-600 text-sm"></i> Pencarian Lokasi Peta (Google Maps)
                </h4>
                <p class="text-[11px] text-slate-400 -mt-2">Ketik nama tempat/alamat, lalu pilih dari hasil pencarian untuk memperbarui lokasi peta secara otomatis.</p>

                <div class="flex items-center gap-2">
                    <input type="text" x-model="searchQuery" @keydown.enter.prevent="updateMap()"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-amber-500 focus:outline-none text-xs text-slate-800 font-medium"
                           placeholder="Masukkan Nama Tempat / Alamat (Contoh: Kantor Bupati Probolinggo)">
                    <button type="button" @click="updateMap()"
                            class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 shrink-0 transition-all shadow-xs">
                        <i class="fas fa-search text-xs"></i> Cari
                    </button>
                </div>

                <input type="hidden" name="google_map_search" :value="searchQuery">
                <input type="hidden" name="google_map_embed" :value="embedUrl">

                <div class="space-y-2 pt-2">
                    <span class="block font-bold text-xs text-slate-600">Preview Peta Saat Ini:</span>
                    <div class="w-full h-72 sm:h-96 rounded-2xl overflow-hidden border border-slate-200 shadow-inner bg-slate-100 relative">
                        <iframe :src="embedUrl" class="w-full h-full border-0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-md hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                    <i class="fas fa-save text-sm"></i> Simpan Perubahan Alamat & Kontak
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
