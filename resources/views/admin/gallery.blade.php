@extends('admin.layout')

@section('page_title', 'Kelola Galeri Foto & Video Kegiatan')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>

<script>
function initGalleryAddTinyMCE(content = '') {
    if (typeof tinymce === 'undefined') return;
    if (tinymce.get('gallery_add_caption')) tinymce.get('gallery_add_caption').destroy();

    tinymce.init({
        selector: '#gallery_add_caption',
        height: 260,
        menubar: 'file edit view insert format table help',
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link table | removeformat code fullscreen',
        toolbar_mode: 'wrap',
        content_style: 'body { font-family: sans-serif; font-size: 13px; line-height: 1.8; color: #1e293b; padding: 10px; } p { margin-bottom: 0.85rem; }',
        branding: false,
        promotion: false,
        setup: function (editor) {
            editor.on('init', function () {
                editor.setContent(content || '');
            });
            editor.on('change keyup NodeChange', function () {
                editor.save();
            });
        }
    });
}

function initGalleryEditTinyMCE(content = '') {
    if (typeof tinymce === 'undefined') return;
    if (tinymce.get('gallery_edit_caption')) tinymce.get('gallery_edit_caption').destroy();

    tinymce.init({
        selector: '#gallery_edit_caption',
        height: 260,
        menubar: 'file edit view insert format table help',
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link table | removeformat code fullscreen',
        toolbar_mode: 'wrap',
        content_style: 'body { font-family: sans-serif; font-size: 13px; line-height: 1.8; color: #1e293b; padding: 10px; } p { margin-bottom: 0.85rem; }',
        branding: false,
        promotion: false,
        setup: function (editor) {
            editor.on('init', function () {
                editor.setContent(content || '');
            });
            editor.on('change keyup NodeChange', function () {
                editor.save();
            });
        }
    });
}

function syncGalleryAddTinyMCE() {
    if (typeof tinymce !== 'undefined' && tinymce.get('gallery_add_caption')) {
        tinymce.get('gallery_add_caption').save();
    }
}

function syncGalleryEditTinyMCE() {
    if (typeof tinymce !== 'undefined' && tinymce.get('gallery_edit_caption')) {
        tinymce.get('gallery_edit_caption').save();
    }
}
</script>

<div class="space-y-6" x-data="{ 
    showAddModal: false, 
    showEditModal: false, 
    activeAlbumPreview: null,
    activePhotoIdx: 0,
    openAlbumPreview(album) {
        this.activeAlbumPreview = album;
        this.activePhotoIdx = 0;
    },
    nextPhoto() {
        if (!this.activeAlbumPreview || !this.activeAlbumPreview.images || !this.activeAlbumPreview.images.length) return;
        this.activePhotoIdx = (this.activePhotoIdx + 1) % this.activeAlbumPreview.images.length;
    },
    prevPhoto() {
        if (!this.activeAlbumPreview || !this.activeAlbumPreview.images || !this.activeAlbumPreview.images.length) return;
        this.activePhotoIdx = (this.activePhotoIdx - 1 + this.activeAlbumPreview.images.length) % this.activeAlbumPreview.images.length;
    },
    editItem: {},
    newItem: { category: 'Dokumentasi Kegiatan' },
    categoriesList: {{ json_encode($categories) }},
    newMasterInput: '',
    showAddMaster: false,
    imageErrorMsg: null,
    openAddModal() {
        this.showAddModal = true;
        this.newItem = { category: 'Dokumentasi Kegiatan', caption: '' };
        this.selectedFileCount = 0;
        this.selectedFileNames = [];
        setTimeout(() => {
            initGalleryAddTinyMCE('');
        }, 50);
    },
    openEditModal(item) {
        this.showEditModal = true;
        this.editItem = Object.assign({}, item);
        if (item.images && Array.isArray(item.images)) {
            this.editItem.file_path = item.images.join('\n');
        }
        setTimeout(() => {
            initGalleryEditTinyMCE(item.caption || '');
        }, 50);
    },
    async submitQuickMaster(type) {
        if (!this.newMasterInput.trim()) return;
        try {
            const res = await fetch('{{ route('admin.categories.quick-store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ type: type, name: this.newMasterInput })
            });
            const data = await res.json();
            if (data.success) {
                if (!this.categoriesList.includes(data.category)) {
                    this.categoriesList.push(data.category);
                }
                this.newItem.category = data.category;
                this.editItem.category = data.category;
                this.newMasterInput = '';
                this.showAddMaster = false;
            }
        } catch (e) {
            console.error(e);
        }
    },
    async deleteQuickMaster(catName, typeName) {
        if (!confirm(`Hapus master kategori '${catName}'?`)) return;
        try {
            const res = await fetch('{{ route('admin.categories.quick-destroy') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name: catName, type: typeName })
            });
            const data = await res.json();
            if (data.success) {
                this.categoriesList = this.categoriesList.filter(c => c !== catName);
                if (this.newItem && this.newItem.category === catName) this.newItem.category = '';
                if (this.editItem && this.editItem.category === catName) this.editItem.category = '';
            }
        } catch (e) {
            console.error(e);
        }
    },
    selectedFileCount: 0,
    selectedFileNames: [],
    validateImageFile(e) {
        this.imageErrorMsg = null;
        const files = e.target.files;
        if (files && files.length > 0) {
            this.selectedFileCount = files.length;
            this.selectedFileNames = [];
            let invalidFiles = [];
            for (let i = 0; i < files.length; i++) {
                const ext = files[i].name.split('.').pop().toLowerCase();
                if (!['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
                    invalidFiles.push(files[i].name);
                } else {
                    this.selectedFileNames.push(files[i].name);
                }
            }
            if (invalidFiles.length > 0) {
                const msg = '⚠️ GAGAL UPLOAD: ' + invalidFiles.length + ' berkas berformat bukan JPG/PNG/WEBP! Mohon pilih berkas foto saja.';
                this.imageErrorMsg = msg;
                showUploadErrorSwal(msg, 'JPG, PNG, atau WEBP');
                e.target.value = '';
                this.selectedFileCount = 0;
                this.selectedFileNames = [];
            }
        } else {
            this.selectedFileCount = 0;
            this.selectedFileNames = [];
        }
    }
}">

    <!-- Dedicated Tab Navigation Bar -->
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3">
        <a href="{{ route('admin.gallery', ['tab' => 'image']) }}" 
           class="px-5 py-2.5 rounded-xl font-extrabold text-xs transition-all flex items-center gap-2 {{ $tab == 'image' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
            <i class="fas fa-images text-sm"></i> Galeri Foto Dokumentasi
        </a>
        <a href="{{ route('admin.gallery', ['tab' => 'video']) }}" 
           class="px-5 py-2.5 rounded-xl font-extrabold text-xs transition-all flex items-center gap-2 {{ $tab == 'video' ? 'bg-red-600 text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
            <i class="fab fa-youtube text-sm"></i> Video Kegiatan (Link YouTube)
        </a>
    </div>

    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <div>
            <h3 class="font-extrabold text-slate-900 text-lg flex items-center gap-2">
                @if($tab == 'video')
                    <i class="fab fa-youtube text-red-600 text-xl"></i>
                    <span>Kelola Video Kegiatan YouTube</span>
                @else
                    <i class="fas fa-images text-emerald-600 text-xl"></i>
                    <span>Kelola Album Foto Dokumentasi Kegiatan</span>
                @endif
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ $tab == 'video' ? 'Masukkan link YouTube video kegiatan resmi DKUPP untuk ditampilkan di website.' : 'Unggah album foto-foto dokumentasi kegiatan dan pelayanan DKUPP. 1 Album bisa berisi banyak foto!' }}
            </p>
        </div>
        <button @click="openAddModal()" class="px-5 py-2.5 {{ $tab == 'video' ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-700 hover:bg-emerald-800' }} text-white font-bold text-xs rounded-xl shadow transition-colors flex items-center gap-2 shrink-0">
            <i class="fas fa-plus"></i> {{ $tab == 'video' ? 'Post Link Video YouTube' : '+ Buat Album Foto Baru' }}
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl flex items-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Gallery Grid List -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($galleries as $item)
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs group hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    @if($item->type == 'video' && $item->youtube_embed_url)
                        <div class="relative pt-[56.25%] bg-slate-900 overflow-hidden">
                            <iframe src="{{ $item->youtube_embed_url }}" class="absolute inset-0 w-full h-full" frameborder="0" allowfullscreen></iframe>
                        </div>
                    @else
                        <div class="relative h-40 bg-slate-100 overflow-hidden cursor-pointer group/img"
                             @click="openAlbumPreview({{ json_encode(['title' => $item->title, 'category' => $item->category ?: 'Dokumentasi Kegiatan', 'caption' => $item->caption, 'images' => $item->images]) }})">
                            <img src="{{ $item->cover_image }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover/img:scale-105 transition-transform duration-300">
                            <span class="absolute top-2 right-2 bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-extrabold px-2.5 py-1 rounded-full shadow-md z-10">
                                📸 {{ $item->photo_count }} Foto
                            </span>
                            <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center text-white p-1.5">
                                <span class="px-2.5 py-1 bg-emerald-600/90 rounded-lg text-xs font-extrabold shadow-sm flex items-center gap-1">
                                    <i class="fas fa-search-plus"></i> Buka Album ({{ $item->photo_count }})
                                </span>
                            </div>
                        </div>
                    @endif

                    <div class="p-4 space-y-1.5">
                        <span class="px-2 py-0.5 rounded text-[9px] font-extrabold bg-emerald-100 text-emerald-800 uppercase tracking-wider inline-block">
                            {{ $item->category ?: 'Dokumentasi Kegiatan' }}
                        </span>
                        <h4 class="font-extrabold text-slate-900 text-xs sm:text-sm line-clamp-2 leading-snug">{{ $item->title }}</h4>
                        @if($item->caption)
                            <div class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed prose prose-slate">{!! $item->caption !!}</div>
                        @endif
                    </div>
                </div>

                <div class="p-4 pt-0">
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-1">
                        <span class="text-[10px] text-slate-400 font-mono">{{ $item->created_at->format('d M Y') }}</span>
                        
                        <div class="flex items-center gap-1">
                            <!-- Tombol Edit Item Galeri -->
                            <button @click="openEditModal({{ json_encode(['id' => $item->id, 'title' => $item->title, 'category' => $item->category ?: 'Dokumentasi Kegiatan', 'caption' => $item->caption, 'type' => $item->type, 'file_path' => $item->file_path, 'youtube_url' => $item->youtube_url, 'images' => $item->images]) }})" 
                                    class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg text-xs" title="Edit Item Galeri">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- Tombol Hapus Item Galeri -->
                            <form action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item galeri ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg text-xs" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-8 rounded-3xl border border-slate-200 text-center text-slate-400 text-xs">
                Belum ada {{ $tab == 'video' ? 'video YouTube' : 'foto galeri' }} yang ditambahkan.
            </div>
        @endforelse
    </div>

    <!-- Modal Add Gallery Item -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
        <div @click.away="showAddModal = false" class="bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-7 max-w-3xl w-full space-y-4 shadow-2xl my-auto">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <i class="{{ $tab == 'video' ? 'fab fa-youtube text-red-600' : 'fas fa-image text-emerald-600' }} text-lg"></i>
                    <h3 class="font-extrabold text-slate-900 text-base">
                        {{ $tab == 'video' ? 'Post Link Video YouTube' : 'Tambah Foto Galeri' }}
                    </h3>
                </div>
                <button @click="showAddModal = false" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg"><i class="fas fa-times"></i></button>
            </div>

            <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" @submit="syncGalleryAddTinyMCE()" class="space-y-3.5 text-xs">
                @csrf
                <input type="hidden" name="type" value="{{ $tab }}">

                @if($tab == 'video')
                    <!-- Single YouTube URL Form -->
                    <div class="p-3.5 bg-red-50 rounded-2xl border border-red-200 space-y-1">
                        <label class="font-extrabold text-red-900 block text-xs">
                            <i class="fab fa-youtube text-red-600 me-1"></i> Link Video YouTube <span class="text-rose-500">*</span>
                        </label>
                        <input type="url" name="youtube_url" required placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/..." 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-red-300 font-bold text-slate-900 text-xs bg-white focus:ring-2 focus:ring-red-600 focus:outline-none">
                        <p class="text-[10px] text-red-700 font-medium">Cukup paste link video YouTube kegiatan resmi DKUPP.</p>
                    </div>

                    <div>
                        <label class="font-bold text-slate-700 block mb-1">Judul Video Kegiatan <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required placeholder="Contoh: Dokumentasi Pelatihan Koperasi & UMKM 2026" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300">
                    </div>
                @else
                    <div>
                        <label class="font-extrabold text-slate-800 block mb-1">
                            <i class="fas fa-folder text-emerald-600 me-1"></i> Nama / Judul Album Foto Kegiatan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="title" required placeholder="Contoh: Dok. Sidang Tera Ulang Pasar Kraksaan 2026" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-bold text-slate-900 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                @endif

                <!-- Kategori (Bisa Diketik & Pilih Master) -->
                <!-- Kategori Galeri Minimalis & Responsif -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="font-bold text-slate-700 text-xs flex items-center gap-1.5">
                            <i class="fas fa-tags text-emerald-600"></i> Kategori {{ $tab == 'video' ? 'Video' : 'Foto' }} <span class="text-rose-500">*</span>
                        </label>
                        <button type="button" @click="showAddMaster = !showAddMaster" 
                                class="px-2.5 py-1 text-[10px] rounded-lg bg-emerald-100 hover:bg-emerald-200 text-emerald-800 font-extrabold transition-all border border-emerald-300 flex items-center gap-1 cursor-pointer" 
                                title="Tambah / Kelola Kategori Baru">
                            <i class="fas fa-plus text-[9px]"></i> Manajemen Kategori
                        </button>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="relative">
                            <input type="text" 
                                   name="category" 
                                   required 
                                   list="master_gallery_categories_add"
                                   x-model="newItem.category" 
                                   placeholder="Pilih atau ketik kategori..." 
                                   class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold text-slate-900 text-xs bg-white shadow-2xs">
                            
                            <datalist id="master_gallery_categories_add">
                                <template x-for="cat in categoriesList" :key="cat">
                                    <option :value="cat"></option>
                                </template>
                            </datalist>
                        </div>

                        <!-- Inline Add & Manage Master Input Box -->
                        <div x-show="showAddMaster" x-cloak class="mt-2 p-3 bg-emerald-50/80 border border-emerald-200 rounded-xl space-y-2.5">
                            <div class="flex items-center gap-2">
                                <input type="text" 
                                       x-model="newMasterInput" 
                                       @keydown.enter.prevent="submitQuickMaster('galeri')" 
                                       placeholder="Ketik nama kategori baru..." 
                                       class="w-full px-2.5 py-1.5 border border-emerald-300 rounded-lg text-xs font-bold uppercase focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white">
                                <button type="button" 
                                        @click="submitQuickMaster('galeri')" 
                                        class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-lg whitespace-nowrap shadow-xs cursor-pointer">
                                    + Simpan
                                </button>
                                <button type="button" @click="showAddMaster = false" class="text-slate-400 hover:text-slate-600 px-1 cursor-pointer">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>

                            <!-- List of Master Categories with Delete Button -->
                            <div class="pt-2 border-t border-emerald-200/70 space-y-1">
                                <span class="text-[10px] text-slate-500 font-bold block">Kategori Tersimpan (Klik untuk pilih / Hapus jika tidak diperlukan):</span>
                                <div class="flex flex-wrap items-center gap-1.5 max-h-32 overflow-y-auto pt-0.5">
                                    <template x-for="cat in categoriesList" :key="cat">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-white border border-emerald-200 text-slate-800 shadow-2xs group hover:border-emerald-400 transition-all">
                                            <span @click="newItem.category = cat" class="cursor-pointer hover:text-emerald-700" x-text="cat"></span>
                                            <button type="button" @click.stop="deleteQuickMaster(cat, 'galeri')" class="text-slate-300 hover:text-rose-600 transition-colors ml-0.5 cursor-pointer" title="Hapus Kategori Ini">
                                                <i class="fas fa-times-circle text-[11px]"></i>
                                            </button>
                                        </span>
                                    </template>
                                    <template x-if="!categoriesList || categoriesList.length === 0">
                                        <span class="text-[10px] text-slate-400 italic">Belum ada kategori tersimpan.</span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($tab != 'video')
                    <div class="space-y-3 p-4 bg-emerald-50/80 border-2 border-dashed border-emerald-300 rounded-2xl relative shadow-2xs">
                        <div class="flex items-center justify-between">
                            <label class="block font-extrabold text-emerald-900 text-xs flex items-center gap-1.5">
                                <i class="fas fa-images text-emerald-600 text-sm"></i>
                                <span>Pilih / Upload Banyak Foto Sekaligus (Album Foto)</span>
                            </label>
                            <span class="text-[10px] bg-emerald-700 text-white font-extrabold px-2.5 py-0.5 rounded-md uppercase shadow-2xs">
                                📸 BISA UPLOAD BANYAK FOTO
                            </span>
                        </div>
                        
                        <input type="file" 
                               name="photos[]" 
                               multiple 
                               accept="image/jpeg,image/png,image/webp,.jpg,.jpeg,.png,.webp" 
                               @change="validateImageFile($event)" 
                               class="w-full px-3.5 py-2.5 bg-white border border-emerald-300 rounded-xl text-xs font-bold text-slate-700 file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-extrabold file:bg-emerald-700 file:text-white hover:file:bg-emerald-800 cursor-pointer shadow-2xs">
                        
                        <!-- LIVE MULTI-FILE SELECTED COUNTER BADGE -->
                        <template x-if="selectedFileCount > 0">
                            <div class="p-3 bg-emerald-700 text-white rounded-xl text-xs font-bold space-y-1 shadow-md">
                                <div class="flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fas fa-check-circle text-emerald-300 text-sm"></i>
                                        <span x-text="`BERHASIL MEMILIH ${selectedFileCount} FOTO UNTUK ALBUM INI`"></span>
                                    </span>
                                    <span class="text-[10px] bg-emerald-900 px-2 py-0.5 rounded font-mono">1x Klik Upload</span>
                                </div>
                                <div class="text-[10px] text-emerald-200 truncate font-mono" x-text="selectedFileNames.join(', ')"></div>
                            </div>
                        </template>

                        <!-- PEMBERITAHUAN GAGAL UPLOAD NON-JPG/PNG -->
                        <template x-if="imageErrorMsg">
                            <div class="p-3 bg-rose-100 border border-rose-300 text-rose-900 rounded-xl text-xs font-bold flex items-center gap-2 shadow-xs">
                                <i class="fas fa-exclamation-triangle text-rose-600 text-base shrink-0"></i>
                                <span x-text="imageErrorMsg"></span>
                            </div>
                        </template>

                        <p class="text-[10px] text-emerald-800 font-extrabold flex items-center gap-1 leading-snug">
                            <i class="fas fa-info-circle text-emerald-600 shrink-0"></i> 
                            <span>Petunjuk: Saat jendela buka file muncul, tekan & tahan tombol <strong>Ctrl</strong> atau <strong>Shift</strong> pada keyboard lalu klik banyak foto yang ingin dimasukkan ke album ini.</span>
                        </p>

                        <div class="text-[10px] text-slate-500 font-medium pt-2 border-t border-emerald-200/60">
                            <p class="font-bold text-emerald-800">Atau Masukkan Link URL Foto (1 URL per baris jika banyak):</p>
                            <textarea name="file_path" rows="2" placeholder="https://... (opsional)" class="w-full mt-1 px-3 py-1.5 border border-slate-300 rounded-lg text-xs bg-white font-mono"></textarea>
                        </div>
                    </div>
                @endif

                <div>
                    <label class="font-bold text-slate-700 block mb-1 flex items-center justify-between">
                        <span>Keterangan Singkat (Opsional)</span>
                        <span class="text-[9px] bg-emerald-700 text-white font-extrabold px-2 py-0.5 rounded shadow-2xs">✨ Rich Text Editor</span>
                    </label>
                    <textarea id="gallery_add_caption" name="caption" rows="3" placeholder="Penjelasan singkat mengenai kegiatan..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold">Batal</button>
                    <button type="submit" class="px-6 py-2.5 {{ $tab == 'video' ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-700 hover:bg-emerald-800' }} text-white rounded-xl font-extrabold shadow-md transition-colors flex items-center gap-1.5">
                        <i class="fas fa-paper-plane"></i> {{ $tab == 'video' ? 'Posting Link YouTube' : 'Simpan Foto' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Gallery Item -->
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
        <div @click.away="showEditModal = false" class="bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-7 max-w-3xl w-full space-y-4 shadow-2xl my-auto text-left">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <i class="{{ $tab == 'video' ? 'fab fa-youtube text-red-600' : 'fas fa-edit text-blue-600' }} text-lg"></i>
                    <h3 class="font-extrabold text-slate-900 text-base">
                        {{ $tab == 'video' ? 'Edit Video YouTube' : 'Edit Nama Album & Foto Kegiatan' }}
                    </h3>
                </div>
                <button @click="showEditModal = false" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg"><i class="fas fa-times"></i></button>
            </div>

            <form :action="'/admin/gallery/' + editItem.id" method="POST" enctype="multipart/form-data" @submit="syncGalleryEditTinyMCE()" class="space-y-3.5 text-xs">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="type" :value="editItem.type || '{{ $tab }}'">

                @if($tab == 'video')
                    <!-- Edit YouTube URL -->
                    <div class="p-3.5 bg-red-50 rounded-2xl border border-red-200 space-y-1">
                        <label class="font-extrabold text-red-900 block text-xs">
                            <i class="fab fa-youtube text-red-600 me-1"></i> Link Video YouTube <span class="text-rose-500">*</span>
                        </label>
                        <input type="url" name="youtube_url" required x-model="editItem.youtube_url" placeholder="https://www.youtube.com/watch?v=..." 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-red-300 font-bold text-slate-900 text-xs bg-white focus:ring-2 focus:ring-red-600 focus:outline-none">
                    </div>

                    <div>
                        <label class="font-bold text-slate-700 block mb-1">Judul Video Kegiatan <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required x-model="editItem.title" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300">
                    </div>
                @else
                    <div>
                        <label class="font-extrabold text-slate-800 block mb-1">
                            <i class="fas fa-folder text-blue-600 me-1"></i> Nama / Judul Album Foto Kegiatan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="title" required x-model="editItem.title" placeholder="Contoh: Dok. Sidang Tera Ulang Pasar Kraksaan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-bold text-slate-900 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                @endif

                <!-- Kategori Edit Galeri Minimalis & Responsif -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="font-bold text-slate-700 text-xs flex items-center gap-1.5">
                            <i class="fas fa-tags text-blue-600"></i> Kategori {{ $tab == 'video' ? 'Video' : 'Foto' }} <span class="text-rose-500">*</span>
                        </label>
                        <button type="button" @click="showAddMaster = !showAddMaster" 
                                class="px-2.5 py-1 text-[10px] rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-800 font-extrabold transition-all border border-blue-300 flex items-center gap-1 cursor-pointer" 
                                title="Tambah / Kelola Kategori Baru">
                            <i class="fas fa-plus text-[9px]"></i> Manajemen Kategori
                        </button>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="relative">
                            <input type="text" 
                                   name="category" 
                                   required 
                                   list="master_gallery_categories_edit"
                                   x-model="editItem.category" 
                                   placeholder="Pilih atau ketik kategori..." 
                                   class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none font-bold text-slate-900 text-xs bg-white shadow-2xs">
                            
                            <datalist id="master_gallery_categories_edit">
                                <template x-for="cat in categoriesList" :key="cat">
                                    <option :value="cat"></option>
                                </template>
                            </datalist>
                        </div>

                        <!-- Inline Add & Manage Master Input Box -->
                        <div x-show="showAddMaster" x-cloak class="mt-2 p-3 bg-blue-50/80 border border-blue-200 rounded-xl space-y-2.5">
                            <div class="flex items-center gap-2">
                                <input type="text" 
                                       x-model="newMasterInput" 
                                       @keydown.enter.prevent="submitQuickMaster('galeri')" 
                                       placeholder="Ketik nama kategori baru..." 
                                       class="w-full px-2.5 py-1.5 border border-blue-300 rounded-lg text-xs font-bold uppercase focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                                <button type="button" 
                                        @click="submitQuickMaster('galeri')" 
                                        class="px-3 py-1.5 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded-lg whitespace-nowrap shadow-xs cursor-pointer">
                                    + Simpan
                                </button>
                                <button type="button" @click="showAddMaster = false" class="text-slate-400 hover:text-slate-600 px-1 cursor-pointer">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>

                            <!-- List of Master Categories with Delete Button -->
                            <div class="pt-2 border-t border-blue-200/70 space-y-1">
                                <span class="text-[10px] text-slate-500 font-bold block">Kategori Tersimpan (Klik untuk pilih / Hapus jika tidak diperlukan):</span>
                                <div class="flex flex-wrap items-center gap-1.5 max-h-32 overflow-y-auto pt-0.5">
                                    <template x-for="cat in categoriesList" :key="cat">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-white border border-blue-200 text-slate-800 shadow-2xs group hover:border-blue-400 transition-all">
                                            <span @click="editItem.category = cat" class="cursor-pointer hover:text-blue-700" x-text="cat"></span>
                                            <button type="button" @click.stop="deleteQuickMaster(cat, 'galeri')" class="text-slate-300 hover:text-rose-600 transition-colors ml-0.5 cursor-pointer" title="Hapus Kategori Ini">
                                                <i class="fas fa-times-circle text-[11px]"></i>
                                            </button>
                                        </span>
                                    </template>
                                    <template x-if="!categoriesList || categoriesList.length === 0">
                                        <span class="text-[10px] text-slate-400 italic">Belum ada kategori tersimpan.</span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($tab != 'video')
                    <div class="space-y-2.5 p-3.5 bg-blue-50/50 border border-blue-200 rounded-xl">
                        <label class="block font-extrabold text-blue-900 text-xs flex items-center justify-between">
                            <span><i class="fas fa-plus-circle text-blue-600 me-1"></i> Tambah Foto Baru ke Dalam Album Ini</span>
                            <span class="text-[10px] bg-blue-600 text-white font-extrabold px-2 py-0.5 rounded-md uppercase">Upload Banyak Foto</span>
                        </label>
                        
                        <input type="file" name="photos[]" multiple accept="image/jpeg,image/png,image/webp,.jpg,.jpeg,.png,.webp" @change="validateImageFile($event)" 
                               class="w-full px-3 py-2 bg-white border border-blue-300 rounded-xl text-xs font-semibold text-slate-700 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white cursor-pointer">
                        
                        <!-- PEMBERITAHUAN GAGAL UPLOAD NON-JPG/PNG -->
                        <template x-if="imageErrorMsg">
                            <div class="mt-2.5 p-3 bg-rose-100 border border-rose-300 text-rose-900 rounded-xl text-xs font-bold flex items-center gap-2 shadow-xs">
                                <i class="fas fa-exclamation-triangle text-rose-600 text-base shrink-0"></i>
                                <span x-text="imageErrorMsg"></span>
                            </div>
                        </template>

                        <div class="pt-2 border-t border-blue-200/60">
                            <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700 text-xs">
                                <input type="checkbox" name="replace_photos" value="1" class="w-4 h-4 text-rose-600 rounded">
                                <span class="text-rose-700">Hapus/Timpa seluruh foto lama dalam album ini dengan foto baru yang diunggah</span>
                            </label>
                        </div>

                        <div class="text-[10px] text-slate-500 font-medium pt-2 border-t border-blue-200/60">
                            <p class="font-bold text-slate-700">Edit Link URL Foto (1 URL per baris):</p>
                            <textarea name="file_path" rows="2" x-model="editItem.file_path" placeholder="https://..." class="w-full mt-1 px-3 py-1.5 border border-slate-300 rounded-lg text-xs bg-white font-mono"></textarea>
                        </div>
                    </div>
                @endif

                <div>
                    <label class="font-bold text-slate-700 block mb-1 flex items-center justify-between">
                        <span>Keterangan Singkat (Opsional)</span>
                        <span class="text-[9px] bg-blue-600 text-white font-extrabold px-2 py-0.5 rounded shadow-2xs">✨ Rich Text Editor</span>
                    </label>
                    <textarea id="gallery_edit_caption" name="caption" rows="3" x-model="editItem.caption" placeholder="Penjelasan singkat mengenai kegiatan..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-extrabold shadow-md transition-colors flex items-center gap-1.5">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Admin Photo Album Preview Modal -->
    <div x-show="activeAlbumPreview !== null" 
         x-cloak 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 p-3 sm:p-6 flex items-center justify-center bg-slate-950/90 backdrop-blur-md">
        
        <div @click.away="activeAlbumPreview = null" class="bg-slate-900 rounded-3xl max-w-5xl w-full max-h-[92vh] border border-slate-800 shadow-2xl flex flex-col overflow-hidden relative text-left">
            <div class="p-4 sm:p-5 bg-slate-950 text-white flex items-center justify-between gap-4 border-b border-slate-800 shrink-0">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg shrink-0">
                        <i class="fas fa-images"></i>
                    </div>
                    <div class="truncate">
                        <span class="text-[10px] font-extrabold text-emerald-400 uppercase tracking-widest flex items-center gap-2">
                            <span>Album Dokumentasi</span>
                            <template x-if="activeAlbumPreview && activeAlbumPreview.images && activeAlbumPreview.images.length > 0">
                                <span class="bg-emerald-950 text-emerald-400 px-2 py-0.5 rounded-full text-[9px] font-bold border border-emerald-800" x-text="`Foto ${activePhotoIdx + 1} dari ${activeAlbumPreview.images.length}`"></span>
                            </template>
                        </span>
                        <h3 class="font-extrabold text-xs sm:text-sm text-white truncate" x-text="activeAlbumPreview ? activeAlbumPreview.title : ''"></h3>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a :href="activeAlbumPreview && activeAlbumPreview.images ? activeAlbumPreview.images[activePhotoIdx] : '#'" target="_blank" download class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors shadow-xs">
                        <i class="fas fa-download text-[10px]"></i> <span class="hidden sm:inline">Unduh Foto</span>
                    </a>
                    <button @click="activeAlbumPreview = null" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 flex items-center justify-center transition-colors cursor-pointer">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Main Photo Viewing Slider Area -->
            <div class="flex-grow bg-slate-950 p-2 sm:p-4 relative overflow-hidden flex flex-col justify-center items-center select-none">
                <!-- Prev Arrow Button -->
                <template x-if="activeAlbumPreview && activeAlbumPreview.images && activeAlbumPreview.images.length > 1">
                    <button @click="prevPhoto()" class="absolute left-3 z-10 w-11 h-11 rounded-full bg-slate-900/80 hover:bg-emerald-600 text-white border border-slate-700 hover:border-emerald-500 flex items-center justify-center text-lg transition-all shadow-xl hover:scale-110 active:scale-95 cursor-pointer">
                        <i class="fas fa-chevron-left me-0.5"></i>
                    </button>
                </template>

                <!-- Active Image -->
                <template x-if="activeAlbumPreview && activeAlbumPreview.images && activeAlbumPreview.images.length > 0">
                    <img :src="activeAlbumPreview.images[activePhotoIdx]" :alt="activeAlbumPreview.title" class="max-h-[60vh] sm:max-h-[65vh] w-auto max-w-full rounded-2xl object-contain shadow-2xl border border-slate-800 mx-auto transition-all duration-300">
                </template>

                <!-- Next Arrow Button -->
                <template x-if="activeAlbumPreview && activeAlbumPreview.images && activeAlbumPreview.images.length > 1">
                    <button @click="nextPhoto()" class="absolute right-3 z-10 w-11 h-11 rounded-full bg-slate-900/80 hover:bg-emerald-600 text-white border border-slate-700 hover:border-emerald-500 flex items-center justify-center text-lg transition-all shadow-xl hover:scale-110 active:scale-95 cursor-pointer">
                        <i class="fas fa-chevron-right ms-0.5"></i>
                    </button>
                </template>
            </div>

            <!-- Bottom Photo Album Thumbnail Strip -->
            <div class="p-3 bg-slate-950 border-t border-slate-800 shrink-0 space-y-2" x-show="activeAlbumPreview && activeAlbumPreview.images && activeAlbumPreview.images.length > 0">
                <div class="flex items-center gap-2 overflow-x-auto custom-scrollbar pb-1">
                    <template x-for="(img, idx) in (activeAlbumPreview ? activeAlbumPreview.images : [])" :key="idx">
                        <button @click="activePhotoIdx = idx" 
                                :class="activePhotoIdx === idx ? 'ring-2 ring-emerald-500 scale-105 opacity-100' : 'opacity-50 hover:opacity-100 hover:scale-105'" 
                                class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl overflow-hidden shrink-0 border border-slate-800 transition-all cursor-pointer bg-slate-900">
                            <img :src="img" class="w-full h-full object-cover">
                        </button>
                    </template>
                </div>
                <div class="text-[11px] text-slate-400 font-medium text-center truncate prose prose-invert max-w-none" x-html="activeAlbumPreview ? activeAlbumPreview.caption : ''"></div>
            </div>
        </div>
    </div>
</div>
@endsection
