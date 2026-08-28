@extends('admin.layout')

@section('page_title', 'CRUD Menu Navigasi Topbar')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: false, 
    editMode: false, 
    currentMenu: {},
    linkType: 'url',
    showNewParentInput: false,
    newParentTitle: '',
    detectLinkType(url) {
        if (!url) return 'url';
        const cleanUrl = url.toLowerCase();
        if (cleanUrl.endsWith('.pdf') || cleanUrl.includes('/uploads/menus/') && cleanUrl.includes('.pdf')) return 'pdf';
        if (cleanUrl.match(/\.(jpg|jpeg|png|webp|gif)$/) || cleanUrl.includes('/uploads/menus/')) return 'image';
        return 'url';
    },
    openModalAdd() {
        this.editMode = false;
        this.currentMenu = { is_active: true, target: '_self', order: 0, url: '', parent_id: '' };
        this.showNewParentInput = false;
        this.newParentTitle = '';
        this.linkType = 'url';
        this.showModal = true;
    },
    openModalEdit(menu) {
        this.editMode = true;
        this.currentMenu = Object.assign({}, menu);
        this.showNewParentInput = false;
        this.newParentTitle = '';
        this.linkType = this.detectLinkType(menu.url);
        this.showModal = true;
    }
}">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h3 class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight">Daftar Menu & Sub-Menu Navigasi Header</h3>
            <p class="text-xs text-slate-500 mt-0.5">Atur struktur menu header, tautan internal/eksternal, serta unggah dokumen PDF atau Foto untuk menu.</p>
        </div>
        <button @click="openModalAdd()" 
                class="w-full sm:w-auto px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-xs flex items-center justify-center gap-2 transition-all">
            <i class="fas fa-plus"></i> Tambah Menu Baru
        </button>
    </div>

    <!-- Menus Tree Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        @foreach($menus as $menu)
            @php
                $isPdf = str_contains(strtolower($menu->url), '.pdf');
                $isImg = preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $menu->url);
            @endphp
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-xs">
                <div class="bg-slate-50 p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="w-6 h-6 rounded-md bg-orange-100 text-orange-800 text-[11px] font-bold flex items-center justify-center">#{{ $menu->order }}</span>
                        <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase">{{ $menu->title }}</h4>
                        
                        @if($isPdf)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-rose-100 text-rose-700 font-bold text-[10px]">
                                <i class="fas fa-file-pdf"></i> PDF
                            </span>
                        @elseif($isImg)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-sky-100 text-sky-700 font-bold text-[10px]">
                                <i class="fas fa-image"></i> FOTO
                            </span>
                        @endif

                        <a href="{{ $menu->url }}" target="_blank" class="text-[11px] text-slate-400 font-mono hover:text-orange-600 hover:underline">({{ Str::limit($menu->url, 40) }})</a>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="openModalEdit({{ json_encode($menu) }})" 
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
                            @php
                                $childIsPdf = str_contains(strtolower($child->url), '.pdf');
                                $childIsImg = preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $child->url);
                            @endphp
                            <div class="py-2 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <i class="fas fa-level-up-alt rotate-90 text-slate-300"></i>
                                    <span class="font-semibold text-slate-700">{{ $child->title }}</span>
                                    
                                    @if($childIsPdf)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-rose-100 text-rose-700 font-bold text-[10px]">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </span>
                                    @elseif($childIsImg)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-sky-100 text-sky-700 font-bold text-[10px]">
                                            <i class="fas fa-image"></i> FOTO
                                        </span>
                                    @endif

                                    <a href="{{ $child->url }}" target="_blank" class="text-[10px] text-slate-400 font-mono hover:text-orange-600 hover:underline">({{ Str::limit($child->url, 40) }})</a>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button @click="openModalEdit({{ json_encode($child) }})" 
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
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 my-8">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm" x-text="editMode ? 'Edit Menu Navigasi' : 'Tambah Menu Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>

            <form :action="editMode ? '/admin/menus/' + currentMenu.id : '{{ route('admin.menus.store') }}'" 
                  method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block font-bold text-slate-700">Parent Menu (Kosongkan jika Menu Utama)</label>
                        <button type="button" @click="showNewParentInput = !showNewParentInput" 
                                class="text-[11px] font-extrabold text-orange-600 hover:text-orange-700 hover:underline flex items-center gap-1 transition-colors">
                            <i class="fas" :class="showNewParentInput ? 'fa-list-ul' : 'fa-plus-circle'"></i>
                            <span x-text="showNewParentInput ? 'Pilih Dari Daftar Parent' : '+ Buat Parent Menu Baru'"></span>
                        </button>
                    </div>

                    <!-- Dropdown Pilihan Parent Menu -->
                    <div x-show="!showNewParentInput">
                        <select name="parent_id" x-model="currentMenu.parent_id" 
                                @change="if ($event.target.value === '__new__') { showNewParentInput = true; currentMenu.parent_id = ''; }"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                            <option value="">-- Main Menu Utama --</option>
                            @foreach($allParents as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                            @endforeach
                            <option value="__new__" class="font-extrabold text-orange-600 bg-orange-50">+ Buat Parent Menu Utama Baru...</option>
                        </select>
                    </div>

                    <!-- Input Ketik Nama Parent Menu Utama Baru -->
                    <div x-show="showNewParentInput" class="space-y-1.5 p-3 bg-orange-50/70 border border-orange-200 rounded-xl mt-1">
                        <label class="block font-bold text-orange-800 text-[11px]">
                            <i class="fas fa-folder-plus text-orange-600 me-1"></i> Nama Parent Menu Utama Baru:
                        </label>
                        <input type="text" name="new_parent_title" x-model="newParentTitle" 
                               placeholder="Contoh: PUBLIKASI & MEDIA / PERIZINAN KOPERASI" 
                               class="w-full px-3 py-2 border border-orange-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white font-bold text-slate-800">
                        <p class="text-[10px] text-orange-700 font-medium leading-snug">
                            <i class="fas fa-info-circle me-1"></i> Sistem akan otomatis membuat Parent Menu Utama baru ini di database terlebih dahulu.
                        </p>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Menu</label>
                    <input type="text" name="title" required x-model="currentMenu.title" placeholder="Contoh: PROFIL / Struktur Organisasi" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                </div>

                <!-- Radio Tab Selector Tipe Link -->
                <div>
                    <label class="block font-bold text-slate-700 mb-2">Tipe Tujuan Menu Navigasi</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" @click="linkType = 'url'" 
                                :class="linkType === 'url' ? 'bg-orange-600 text-white font-bold border-orange-600 shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border-slate-200'" 
                                class="px-3 py-2 border rounded-xl flex items-center justify-center gap-1.5 transition text-xs">
                            <i class="fas fa-link"></i> Link URL Halaman
                        </button>
                        <button type="button" @click="linkType = 'pdf'" 
                                :class="linkType === 'pdf' ? 'bg-rose-600 text-white font-bold border-rose-600 shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border-slate-200'" 
                                class="px-3 py-2 border rounded-xl flex items-center justify-center gap-1.5 transition text-xs">
                            <i class="fas fa-file-pdf"></i> Upload Dokumen PDF (.pdf)
                        </button>
                    </div>
                </div>

                <!-- Mode 1: Manual URL -->
                <div x-show="linkType === 'url'">
                    <label class="block font-bold text-slate-700 mb-1">Target URL Link</label>
                    <input type="text" name="url" x-model="currentMenu.url" placeholder="/halaman/visi-misi atau https://..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    <p class="text-[10px] text-slate-400 mt-1">Ketik URL rujukan halaman internal atau website eksternal.</p>
                </div>

                <!-- Mode 2: Upload PDF (Filter Ketat Khusus PDF Only) -->
                <div x-show="linkType === 'pdf'" class="space-y-2">
                    <label class="block font-bold text-slate-700 mb-1 flex items-center justify-between">
                        <span>Unggah Dokumen PDF <span class="text-rose-600">*</span></span>
                        <span class="text-[10px] bg-rose-100 text-rose-800 font-extrabold px-2 py-0.5 rounded-md uppercase">Filter Khusus PDF (.pdf)</span>
                    </label>
                    
                    <input type="file" 
                           name="menu_file" 
                           accept="application/pdf,.pdf" 
                           @change="
                               if ($event.target.files.length > 0) {
                                   const file = $event.target.files[0];
                                   const ext = file.name.split('.').pop().toLowerCase();
                                   if (ext !== 'pdf' && file.type !== 'application/pdf') {
                                       showUploadErrorSwal('⚠️ GAGAL UPLOAD: Anda memilih berkas berformat .' + ext.toUpperCase() + '! Mohon unggah berkas berformat PDF (.pdf) saja.', 'PDF (.pdf)');
                                       $event.target.value = '';
                                   }
                               }
                           "
                           class="w-full px-3 py-1.5 border border-rose-300 rounded-xl text-xs bg-rose-50/40 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-rose-600 file:text-white hover:file:bg-rose-700 cursor-pointer">
                    
                    <p class="text-[10px] text-rose-700 font-bold flex items-center gap-1 mt-1">
                        <i class="fas fa-shield-alt"></i> Filter Ketat: Sistem menolak semua format selain berkas PDF (.pdf) (Maksimal 25 MB).
                    </p>
                    
                    <template x-if="currentMenu.url && currentMenu.url.includes('.pdf')">
                        <div class="p-2.5 bg-rose-50 border border-rose-200 rounded-xl flex items-center justify-between">
                            <div class="flex items-center gap-2 text-rose-700 font-semibold truncate">
                                <i class="fas fa-file-pdf text-base"></i>
                                <span class="truncate text-[11px]" x-text="currentMenu.url"></span>
                            </div>
                            <a :href="currentMenu.url" target="_blank" class="px-2.5 py-1 bg-rose-600 text-white rounded-lg text-[10px] font-bold hover:bg-rose-700 shrink-0">Buka Berkas PDF</a>
                        </div>
                    </template>
                </div>

                <!-- Input Hidden Simpan URL Terakhir bila tidak ganti file -->
                <input type="hidden" name="url" x-model="currentMenu.url">

                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Urutan (Order)</label>
                        <input type="number" name="order" x-model="currentMenu.order" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Buka di (Target)</label>
                        <select name="target" x-model="currentMenu.target" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                            <option value="_self">Tab Sama (_self)</option>
                            <option value="_blank">Tab Baru (_blank)</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center pt-2">
                    <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                        <input type="checkbox" name="is_active" value="1" :checked="currentMenu.is_active" class="w-4 h-4 text-orange-600 rounded">
                        Aktifkan Menu Navigasi
                    </label>
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
