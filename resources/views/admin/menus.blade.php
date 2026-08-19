@extends('admin.layout')

@section('page_title', 'CRUD Menu Navigasi Topbar')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, currentMenu: {} }">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h3 class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight">Daftar Menu & Sub-Menu Navigasi Header</h3>
            <p class="text-xs text-slate-500 mt-0.5">Atur struktur menu header (HOME, PROFIL, LAYANAN, DOKUMEN, INFORMASI, HUBUNGI)</p>
        </div>
        <button @click="showModal = true; editMode = false; currentMenu = { is_active: true, target: '_self', order: 0 }" 
                class="w-full sm:w-auto px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-xs flex items-center justify-center gap-2 transition-all">
            <i class="fas fa-plus"></i> Tambah Menu Baru
        </button>
    </div>

    <!-- Menus Tree Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        @foreach($menus as $menu)
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-xs">
                <div class="bg-slate-50 p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-md bg-orange-100 text-orange-800 text-[11px] font-bold flex items-center justify-center">#{{ $menu->order }}</span>
                        <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase">{{ $menu->title }}</h4>
                        <span class="text-[11px] text-slate-400 font-mono">({{ $menu->url }})</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="showModal = true; editMode = true; currentMenu = {{ json_encode($menu) }}" 
                                class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition"><i class="fas fa-edit text-xs"></i></button>
                        <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus menu utama ini beserta submenus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition"><i class="fas fa-trash-alt text-xs"></i></button>
                        </form>
                    </div>
                </div>

                @if($menu->children && count($menu->children) > 0)
                    <div class="p-3 bg-white divide-y divide-slate-100 pl-8">
                        @foreach($menu->children as $child)
                            <div class="py-2 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-level-up-alt rotate-90 text-slate-300"></i>
                                    <span class="font-semibold text-slate-700">{{ $child->title }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">({{ $child->url }})</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button @click="showModal = true; editMode = true; currentMenu = {{ json_encode($child) }}" 
                                            class="p-1 text-blue-600 hover:bg-blue-50 rounded-md"><i class="fas fa-edit text-xs"></i></button>
                                    <form action="{{ route('admin.menus.destroy', $child->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus sub-menu ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-rose-500 hover:bg-rose-50 rounded-md"><i class="fas fa-times text-xs"></i></button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Modal Form (Add / Edit Menu) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm" x-text="editMode ? 'Edit Menu Navigasi' : 'Tambah Menu Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>

            <form :action="editMode ? '/admin/menus/' + currentMenu.id : '{{ route('admin.menus.store') }}'" method="POST" class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Parent Menu (Kosongkan jika Menu Utama)</label>
                    <select name="parent_id" x-model="currentMenu.parent_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        <option value="">-- Main Menu Utama --</option>
                        @foreach($allParents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Menu</label>
                    <input type="text" name="title" required x-model="currentMenu.title" placeholder="Contoh: PROFIL / Struktur Organisasi" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Target URL Link</label>
                    <input type="text" name="url" required x-model="currentMenu.url" placeholder="/halaman/visi-misi atau https://..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Urutan (Order)</label>
                        <input type="number" name="order" x-model="currentMenu.order" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>
                    <div class="flex items-center pt-5">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                            <input type="checkbox" name="is_active" value="1" :checked="currentMenu.is_active" class="w-4 h-4 text-orange-600 rounded">
                            Aktifkan Menu
                        </label>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-md">Simpan Menu</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
