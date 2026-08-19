@extends('admin.layout')

@section('page_title', 'Pengaturan Website & Identitas Instansi')

@section('content')
<div class="max-w-4xl space-y-6">
    
    <div>
        <h3 class="text-lg font-bold text-slate-800">Identitas & Kontak Instansi</h3>
        <p class="text-xs text-slate-500">Semua perubahan di sini akan secara otomatis memperbarui logo, nama dinas, email, telepon, dan footer publik</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-6 text-xs">
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
                <label class="block font-bold text-slate-700 mb-1">Deskripsi Singkat Footer</label>
                <textarea name="site_description" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ $settings['site_description'] ?? '' }}</textarea>
            </div>
        </div>

        <!-- Logos & Branding -->
        <div class="space-y-4">
            <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase pb-2 border-b border-slate-100 text-emerald-700">
                2. Gambar Logo & Branding
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Logo Topbar Frontend</label>
                    <input type="text" name="logo_frontend" value="{{ $settings['logo_frontend'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Logo Footer Backend</label>
                    <input type="text" name="logo_backend" value="{{ $settings['logo_backend'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Logo BerAKHLAK Top Right</label>
                    <input type="text" name="logo_berakhlak" value="{{ $settings['logo_berakhlak'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Image QR Code Survey</label>
                    <input type="text" name="qr_code_survey" value="{{ $settings['qr_code_survey'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Kepala DKUPP</label>
                    <input type="text" name="kadin_name" value="{{ $settings['kadin_name'] ?? '' }}" placeholder="contoh: Drs. H. Taufik Alami, M.Si" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Foto Kepala DKUPP</label>
                    <input type="text" name="kadin_photo" value="{{ $settings['kadin_photo'] ?? '' }}" placeholder="https://... atau /images/kadin.jpg" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="space-y-4">
            <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase pb-2 border-b border-slate-100 text-emerald-700">
                3. Informasi Alamat & Kontak
            </h4>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Alamat Kantor Lengkap</label>
                <input type="text" name="address" value="{{ $settings['address'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nomor Telepon Kantor</label>
                    <input type="text" name="phone" value="{{ $settings['phone'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email Resmi Kantor</label>
                    <input type="email" name="email" value="{{ $settings['email'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Teks Copyright Footer</label>
                <input type="text" name="copyright_text" value="{{ $settings['copyright_text'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>
        </div>

        <!-- 4. Link Media Sosial Resmi -->
        <div class="space-y-4">
            <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase pb-2 border-b border-slate-100 text-emerald-700">
                4. Link Media Sosial Resmi DKUPP
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1"><i class="fab fa-instagram text-pink-600 me-1"></i> URL Instagram</label>
                    <input type="text" name="instagram_url" value="{{ $settings['instagram_url'] ?? '' }}" placeholder="https://www.instagram.com/dkuppkabprobolinggo/" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1"><i class="fab fa-facebook text-blue-600 me-1"></i> URL Facebook</label>
                    <input type="text" name="facebook_url" value="{{ $settings['facebook_url'] ?? '' }}" placeholder="https://www.facebook.com/..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1"><i class="fab fa-youtube text-red-600 me-1"></i> URL YouTube</label>
                    <input type="text" name="youtube_url" value="{{ $settings['youtube_url'] ?? '' }}" placeholder="https://www.youtube.com/..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1"><i class="fab fa-tiktok text-slate-900 me-1"></i> URL TikTok</label>
                    <input type="text" name="tiktok_url" value="{{ $settings['tiktok_url'] ?? '' }}" placeholder="https://www.tiktok.com/@..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1"><i class="fab fa-whatsapp text-emerald-600 me-1"></i> Link WhatsApp CS</label>
                    <input type="text" name="whatsapp_url" value="{{ $settings['whatsapp_url'] ?? '' }}" placeholder="https://wa.me/6281234567890" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition-all">
                <i class="fas fa-save me-1.5"></i> Simpan Semua Pengaturan
            </button>
        </div>

    </form>

</div>
@endsection
