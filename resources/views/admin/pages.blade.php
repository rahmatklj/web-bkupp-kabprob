@extends('admin.layout')

@section('page_title', 'CRUD Halaman Profil & Layanan')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, currentPage: {} }">
    
    <!-- Tab Navigation Bar -->
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3">
        <a href="{{ route('admin.pages') }}" 
           class="px-4 py-2 rounded-xl font-extrabold text-xs transition-all flex items-center gap-2 bg-emerald-700 text-white shadow-sm">
            <i class="fas fa-file-alt"></i> Halaman Profil & Layanan
        </a>
        <a href="{{ route('admin.org-members') }}" 
           class="px-4 py-2 rounded-xl font-bold text-xs transition-all flex items-center gap-2 bg-white text-slate-600 hover:bg-slate-100 border border-slate-200">
            <i class="fas fa-sitemap text-emerald-600"></i> Bagan Struktur Organisasi
        </a>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h3 class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight">Daftar Halaman Profil, Layanan, & Konten Publik</h3>
            <p class="text-xs text-slate-500 mt-0.5">Kelola konten statis seperti Struktur Organisasi, Visi Misi, Tugas & Fungsi, Peta Bencana, dll.</p>
        </div>
        <button @click="showModal = true; editMode = false; currentPage = {}" 
                class="w-full sm:w-auto px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-xs flex items-center justify-center gap-2 transition-all">
            <i class="fas fa-plus"></i> Tambah Halaman Baru
        </button>
    </div>

    <!-- Pages Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto w-full">
        <table class="w-full text-left text-xs min-w-[550px]">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Judul Halaman</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Slug URL</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Status</th>
                    <th class="px-3 sm:px-6 py-3.5 text-right whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($pages as $page)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-3 sm:px-6 py-3.5 font-bold text-slate-800">
                            {{ $page->title }}
                        </td>
                        <td class="px-3 sm:px-6 py-3.5 font-mono text-[11px] text-slate-500 break-all">
                            /halaman/{{ $page->slug }}
                        </td>
                        <td class="px-3 sm:px-6 py-3.5 whitespace-nowrap">
                            <form action="{{ route('admin.pages.toggle', $page->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" title="Klik untuk ubah status publikasi" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold transition-all duration-200 cursor-pointer hover:scale-105 {{ $page->is_published ? 'bg-emerald-100 text-emerald-800 border border-emerald-300 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-300 hover:bg-slate-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $page->is_published ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                    {{ $page->is_published ? 'PUBLISHED' : 'DRAFT (OFF)' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-3 sm:px-6 py-3.5 text-right whitespace-nowrap space-x-1">
                            <button @click="showModal = true; editMode = true; currentPage = {{ json_encode($page) }}" 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus halaman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Form (Add / Edit Page) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm" x-text="editMode ? 'Edit Halaman Statis' : 'Tambah Halaman Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>

            <form :action="editMode ? '/admin/pages/' + currentPage.id : '{{ route('admin.pages.store') }}'" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Halaman</label>
                    <input type="text" name="title" required x-model="currentPage.title" placeholder="Contoh: Peta Rawan Bencana" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status Publikasi</label>
                    <select name="is_published" x-model="currentPage.is_published" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        <option :value="1">PUBLISHED (Aktif / Tampil di Web)</option>
                        <option :value="0">DRAFT (Nonaktif / Sembunyi)</option>
                    </select>
                </div>

                <div class="space-y-2 p-3.5 bg-emerald-50/70 border border-emerald-200 rounded-xl">
                    <label class="block font-extrabold text-emerald-900 text-xs">
                        <i class="fas fa-upload text-emerald-600 me-1"></i> Upload Foto, Gambar, atau Berkas Dokumen Halaman
                    </label>
                    <input type="file" name="image_file" accept="image/*,.pdf,.doc,.docx" 
                           class="w-full px-3 py-2 bg-white border border-emerald-300 rounded-xl text-xs font-semibold text-slate-700 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-700 file:text-white hover:file:bg-emerald-800 cursor-pointer">
                    
                    <template x-if="currentPage.image">
                        <div class="mt-2 p-2 bg-white rounded-lg border border-emerald-200 flex items-center gap-3">
                            <template x-if="currentPage.image.match(/\.(jpg|jpeg|png|webp)$/i)">
                                <img :src="currentPage.image" class="w-12 h-12 object-cover rounded-lg border border-slate-200 shrink-0">
                            </template>
                            <div class="truncate text-[11px]">
                                <span class="font-bold text-emerald-800 block">File Saat Ini:</span>
                                <span class="text-slate-600 truncate block font-mono" x-text="currentPage.image"></span>
                            </div>
                        </div>
                    </template>

                    <div class="text-[10px] text-slate-500 font-medium pt-1">
                        <p class="font-bold text-emerald-800">Atau Masukkan Link URL Gambar/File External:</p>
                        <input type="text" name="image" x-model="currentPage.image" placeholder="https://... (opsional jika memilih upload file)" class="w-full mt-1 px-3 py-1.5 border border-slate-300 rounded-lg text-xs bg-white font-mono">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Isi Konten Halaman (HTML / Teks)</label>
                    <textarea name="content" rows="6" required x-model="currentPage.content" placeholder="<h2>Judul</h2><p>Isi penjelasan...</p>" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none font-mono text-xs"></textarea>
                </div>

                <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-md">Simpan Halaman</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
