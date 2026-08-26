@extends('admin.layout')

@section('page_title', 'Kelola Master Kategori Website')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: false, 
    editMode: false, 
    openPicker: false,
    searchIcon: '',
    currentCat: { type: 'layanan', icon: 'fa-tags' },
    iconsList: [
        { name: 'fa-store', label: 'Toko / UMKM' },
        { name: 'fa-shopping-basket', label: 'Pasar & Perdagangan' },
        { name: 'fa-users', label: 'Koperasi & Keanggotaan' },
        { name: 'fa-industry', label: 'Perindustrian & IKM' },
        { name: 'fa-balance-scale', label: 'Metrologi Legal & Tera' },
        { name: 'fa-handshake', label: 'Kemitraan & Pelayanan' },
        { name: 'fa-file-pdf', label: 'Dokumen PDF' },
        { name: 'fa-file-alt', label: 'Laporan & Berkas' },
        { name: 'fa-newspaper', label: 'Berita & Artikel' },
        { name: 'fa-bullhorn', label: 'Pengumuman' },
        { name: 'fa-box', label: 'Produk & Kemasan' },
        { name: 'fa-utensils', label: 'Kuliner & Makanan' },
        { name: 'fa-tshirt', label: 'Fashion & Batik' },
        { name: 'fa-seedling', label: 'Pertanian' },
        { name: 'fa-cog', label: 'Pengaturan & Umum' },
        { name: 'fa-info-circle', label: 'Informasi Publik' }
    ]
}">
    
    <!-- Top Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h3 class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                <i class="fas fa-tags text-emerald-600"></i> Master Kategori Website
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Kelola data induk master kategori untuk Sektor Layanan Publik, Dokumen Kinerja, Berita & Artikel, dan Produk UMKM.</p>
        </div>
        <button @click="showModal = true; editMode = false; currentCat = { type: 'layanan', icon: 'fa-tags', order: 0 }" 
                class="w-full sm:w-auto px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-xs flex items-center justify-center gap-2 transition-all shrink-0">
            <i class="fas fa-plus"></i> Tambah Master Kategori Baru
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl flex items-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filter Tabs per Modul -->
    <div class="flex flex-wrap items-center gap-2 bg-white p-2 rounded-2xl border border-slate-200 shadow-2xs">
        <a href="{{ route('admin.categories', ['type' => 'all']) }}" 
           class="px-3.5 py-2 rounded-xl text-xs font-extrabold transition-all {{ ($type ?? 'all') === 'all' ? 'bg-slate-900 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
            Semua Modul
        </a>
        <a href="{{ route('admin.categories', ['type' => 'layanan']) }}" 
           class="px-3.5 py-2 rounded-xl text-xs font-extrabold transition-all {{ ($type ?? '') === 'layanan' ? 'bg-emerald-700 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="fas fa-handshake me-1"></i> Sektor Layanan Publik
        </a>
        <a href="{{ route('admin.categories', ['type' => 'dokumen']) }}" 
           class="px-3.5 py-2 rounded-xl text-xs font-extrabold transition-all {{ ($type ?? '') === 'dokumen' ? 'bg-rose-700 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="fas fa-file-pdf me-1"></i> Dokumen Kinerja
        </a>
        <a href="{{ route('admin.categories', ['type' => 'berita']) }}" 
           class="px-3.5 py-2 rounded-xl text-xs font-extrabold transition-all {{ ($type ?? '') === 'berita' ? 'bg-orange-600 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="fas fa-newspaper me-1"></i> Berita & Artikel
        </a>
        <a href="{{ route('admin.categories', ['type' => 'umkm']) }}" 
           class="px-3.5 py-2 rounded-xl text-xs font-extrabold transition-all {{ ($type ?? '') === 'umkm' ? 'bg-indigo-700 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="fas fa-store me-1"></i> Produk UMKM
        </a>
    </div>

    <!-- Master Categories Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto w-full">
        <table class="w-full text-left text-xs min-w-[650px]">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Ikon & Nama Kategori</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Modul / Tipe</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Deskripsi</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Urutan</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Status</th>
                    <th class="px-3 sm:px-6 py-3.5 text-right whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($categories as $cat)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-700 text-sm shrink-0">
                                    <i class="fas {{ $cat->icon ?: 'fa-tag' }}"></i>
                                </div>
                                <span class="uppercase font-extrabold text-slate-900">{{ $cat->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($cat->type === 'layanan')
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-emerald-100 text-emerald-800">Sektor Layanan</span>
                            @elseif($cat->type === 'dokumen')
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-rose-100 text-rose-800">Dokumen Kinerja</span>
                            @elseif($cat->type === 'berita')
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-orange-100 text-orange-800">Berita & Artikel</span>
                            @elseif($cat->type === 'umkm')
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-indigo-100 text-indigo-800">Produk UMKM</span>
                            @else
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-slate-100 text-slate-800">Umum</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500 max-w-xs">
                            <span class="line-clamp-1">{{ $cat->description ?: '-' }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-700">
                            {{ $cat->order }}
                        </td>
                        <td class="px-6 py-4">
                            @if($cat->is_active)
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-slate-100 text-slate-500">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <button @click="showModal = true; editMode = true; currentCat = {{ json_encode($cat) }}" 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Kategori"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus master kategori {{ addslashes($cat->name) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Kategori"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-xs">
                            Belum ada data master kategori.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($categories->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form (Add / Edit Master Category) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <i class="fas fa-tag text-emerald-600 text-lg"></i>
                    <h3 class="font-extrabold text-slate-800 text-sm" x-text="editMode ? 'Edit Master Kategori' : 'Tambah Master Kategori Baru'"></h3>
                </div>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg"><i class="fas fa-times"></i></button>
            </div>

            <form :action="editMode ? '/admin/categories/' + currentCat.id : '{{ route('admin.categories.store') }}'" method="POST" class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Modul / Peruntukan Kategori <span class="text-rose-500">*</span></label>
                    <select name="type" x-model="currentCat.type" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold text-slate-900 bg-white">
                        <option value="layanan">Sektor Layanan Publik & SOP</option>
                        <option value="dokumen">Dokumen Kinerja / Publik</option>
                        <option value="berita">Berita & Artikel Informasi</option>
                        <option value="umkm">Katalog Produk UMKM</option>
                        <option value="umum">Umum</option>
                    </select>
                </div>
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Master Kategori <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required x-model="currentCat.name" placeholder="Contoh: PERDAGANGAN, KOPERASI, LPPD..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-extrabold text-slate-900 uppercase">
                </div>

                <!-- FontAwesome Icon Picker -->
                <div class="relative">
                    <label class="block font-bold text-slate-700 mb-1 flex items-center justify-between">
                        <span>Ikon FontAwesome</span>
                        <span class="text-[10px] text-emerald-600 font-bold cursor-pointer" @click="openPicker = !openPicker">Pilih Ikon <i class="fas fa-hand-pointer"></i></span>
                    </label>
                    
                    <div class="relative flex items-center">
                        <div class="absolute left-3 w-7 h-7 rounded-lg bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-700 shrink-0">
                            <i class="fas" :class="currentCat.icon || 'fa-tag'"></i>
                        </div>
                        <input type="text" name="icon" x-model="currentCat.icon" placeholder="Contoh: fa-store, fa-handshake" class="w-full pl-12 pr-20 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono text-xs bg-white">
                        <button type="button" @click="openPicker = !openPicker" class="absolute right-2 px-2.5 py-1 bg-emerald-100 hover:bg-emerald-200 text-emerald-800 rounded-lg text-[10px] font-extrabold transition-colors">
                            Pilih <i class="fas fa-chevron-down text-[9px] ms-0.5"></i>
                        </button>
                    </div>

                    <!-- Popover Dropdown Picker -->
                    <div x-show="openPicker" x-cloak @click.away="openPicker = false" class="absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl border border-slate-200 shadow-2xl p-3 z-50 space-y-2">
                        <input type="text" x-model="searchIcon" placeholder="Cari nama ikon..." class="w-full px-3 py-1.5 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <div class="grid grid-cols-4 gap-1.5 max-h-44 overflow-y-auto p-1">
                            <template x-for="ic in iconsList.filter(i => i.name.includes(searchIcon) || i.label.toLowerCase().includes(searchIcon.toLowerCase()))" :key="ic.name">
                                <button type="button" @click="currentCat.icon = ic.name; openPicker = false" 
                                        :class="currentCat.icon === ic.name ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 border-slate-200 text-slate-700'"
                                        class="p-2 border rounded-xl flex flex-col items-center justify-center gap-1 transition-all text-center group">
                                    <i class="fas text-sm" :class="ic.name"></i>
                                    <span class="text-[9px] font-bold line-clamp-1" x-text="ic.label"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Kategori (Opsional)</label>
                    <textarea name="description" x-model="currentCat.description" rows="2" placeholder="Penjelasan singkat cakupan kategori..." class="w-full px-3.5 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Urutan (Order)</label>
                        <input type="number" name="order" x-model="currentCat.order" class="w-full px-3.5 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold text-slate-900">
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                            <input type="checkbox" name="is_active" value="1" :checked="currentCat.is_active !== false" class="w-4 h-4 text-emerald-600 rounded">
                            Aktifkan Kategori
                        </label>
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl shadow-md">Simpan Master Kategori</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
