@extends('admin.layout')

@section('page_title', 'Kelola Media Sosial Resmi')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    
    <!-- Header Title Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h3 class="text-lg font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                <i class="fas fa-share-alt text-sky-600 text-xl"></i> Kelola Media Sosial Resmi
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Kelola tautan akun media sosial resmi DKUPP Kabupaten Probolinggo (Facebook, Instagram, YouTube, TikTok, dan WhatsApp).</p>
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
    <div class="p-5 bg-[#09182b] text-white rounded-3xl shadow-md space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <span class="text-[10px] uppercase font-extrabold tracking-wider bg-sky-500/20 text-sky-300 px-3 py-1 rounded-full border border-sky-500/30">
                <i class="fas fa-eye me-1"></i> Preview Tampilan Tombol Media Sosial di Footer
            </span>
            <span class="text-xs text-slate-400 font-mono">Tampil di Footer Website</span>
        </div>
        
        <div class="bg-[#0f243e] p-5 rounded-2xl border border-slate-700/70 text-slate-300 space-y-3">
            <span class="text-xs font-extrabold text-slate-200 uppercase tracking-widest block">
                Media Sosial Resmi
            </span>
            
            <div class="flex flex-wrap items-center gap-3 pt-1">
                <a href="{{ $settings['facebook_url'] ?? '#' }}" target="_blank" title="Facebook"
                   class="w-10 h-10 rounded-full border border-slate-600 bg-slate-800/60 hover:border-emerald-400 hover:bg-emerald-400/20 text-slate-300 hover:text-emerald-400 flex items-center justify-center transition-all shadow-xs">
                    <i class="fab fa-facebook-f text-sm"></i>
                </a>
                <a href="{{ $settings['instagram_url'] ?? '#' }}" target="_blank" title="Instagram"
                   class="w-10 h-10 rounded-full border border-slate-600 bg-slate-800/60 hover:border-emerald-400 hover:bg-emerald-400/20 text-slate-300 hover:text-emerald-400 flex items-center justify-center transition-all shadow-xs">
                    <i class="fab fa-instagram text-sm"></i>
                </a>
                <a href="{{ $settings['youtube_url'] ?? '#' }}" target="_blank" title="YouTube"
                   class="w-10 h-10 rounded-full border border-slate-600 bg-slate-800/60 hover:border-emerald-400 hover:bg-emerald-400/20 text-slate-300 hover:text-emerald-400 flex items-center justify-center transition-all shadow-xs">
                    <i class="fab fa-youtube text-sm"></i>
                </a>
                <a href="{{ $settings['tiktok_url'] ?? '#' }}" target="_blank" title="TikTok"
                   class="w-10 h-10 rounded-full border border-slate-600 bg-slate-800/60 hover:border-emerald-400 hover:bg-emerald-400/20 text-slate-300 hover:text-emerald-400 flex items-center justify-center transition-all shadow-xs">
                    <i class="fab fa-tiktok text-sm"></i>
                </a>
                <a href="{{ $settings['whatsapp_url'] ?? '#' }}" target="_blank" title="WhatsApp"
                   class="w-10 h-10 rounded-full border border-slate-600 bg-slate-800/60 hover:border-emerald-400 hover:bg-emerald-400/20 text-slate-300 hover:text-emerald-400 flex items-center justify-center transition-all shadow-xs">
                    <i class="fab fa-whatsapp text-sm"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Form Setting Media Sosial -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-6">
        <form action="{{ route('admin.social-media.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-4">
                <h4 class="font-extrabold text-slate-900 text-sm border-b border-slate-100 pb-2.5 uppercase tracking-wider text-sky-700 flex items-center gap-2">
                    <i class="fas fa-edit"></i> Form Kelola Tautan Akun Media Sosial
                </h4>

                <!-- Facebook -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-xs text-slate-700 flex items-center gap-2">
                        <i class="fab fa-facebook-f text-blue-600"></i> URL Halaman / Profil Facebook Resmi
                    </label>
                    <input type="url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-sky-600 focus:outline-none text-xs text-slate-800 font-medium"
                           placeholder="https://www.facebook.com/dkuppkabprobolinggo">
                </div>

                <!-- Instagram -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-xs text-slate-700 flex items-center gap-2">
                        <i class="fab fa-instagram text-pink-600"></i> URL Profil Instagram Resmi
                    </label>
                    <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-sky-600 focus:outline-none text-xs text-slate-800 font-medium"
                           placeholder="https://www.instagram.com/dkuppkabprobolinggo/">
                </div>

                <!-- YouTube -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-xs text-slate-700 flex items-center gap-2">
                        <i class="fab fa-youtube text-red-600"></i> URL Channel YouTube Resmi
                    </label>
                    <input type="url" name="youtube_url" value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-sky-600 focus:outline-none text-xs text-slate-800 font-medium"
                           placeholder="https://www.youtube.com/@dkuppkabprobolinggo">
                </div>

                <!-- TikTok -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-xs text-slate-700 flex items-center gap-2">
                        <i class="fab fa-tiktok text-slate-900"></i> URL Akun TikTok Resmi
                    </label>
                    <input type="url" name="tiktok_url" value="{{ old('tiktok_url', $settings['tiktok_url'] ?? '') }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-sky-600 focus:outline-none text-xs text-slate-800 font-medium"
                           placeholder="https://www.tiktok.com/@dkuppkabprobolinggo">
                </div>

                <!-- WhatsApp CS Web DKUPP -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-xs text-slate-700 flex items-center gap-2">
                        <i class="fab fa-whatsapp text-emerald-600"></i> URL WhatsApp Resmi Web DKUPP (Tampil di Footer Media Sosial & Kontak Web)
                    </label>
                    <input type="url" name="dkupp_whatsapp_url" value="{{ old('dkupp_whatsapp_url', $settings['dkupp_whatsapp_url'] ?? '') }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-sky-600 focus:outline-none text-xs text-slate-800 font-medium"
                           placeholder="https://wa.me/6281234567890">
                    <p class="text-[11px] text-slate-400">Tautan obrolan WhatsApp CS Resmi DKUPP yang khusus tampil pada ikon WhatsApp di footer media sosial dan halaman kontak.</p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white rounded-xl font-bold text-xs shadow-md hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                    <i class="fas fa-save text-sm"></i> Simpan Tautan Media Sosial
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
