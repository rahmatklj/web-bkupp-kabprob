@extends('admin.layout')

@section('page_title', 'Kelola Galeri Foto & Video Kegiatan')

@section('content')
<div class="space-y-6" x-data="{ 
    showAddModal: false, 
    showEditModal: false, 
    editItem: {},
    newItem: { category: 'Dokumentasi Kegiatan' },
    categoriesList: {{ json_encode($categories) }},
    newMasterInput: '',
    showAddMaster: false,
    imageErrorMsg: null,
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
    validateImageFile(e) {
        this.imageErrorMsg = null;
        const file = e.target.files[0];
        if (file) {
            const ext = file.name.split('.').pop().toLowerCase();
            if (!['jpg', 'jpeg', 'png'].includes(ext)) {
                const msg = '⚠️ GAGAL UPLOAD: Berkas yang Anda pilih berformat .' + ext.toUpperCase() + '! Sistem HANYA menerima foto berformat JPG & PNG (.jpg, .jpeg, .png).';
                this.imageErrorMsg = msg;
                alert(msg);
                e.target.value = '';
            }
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
                    <span>Kelola Galeri Foto Dokumentasi</span>
                @endif
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ $tab == 'video' ? 'Masukkan link YouTube video kegiatan resmi DKUPP untuk ditampilkan di website.' : 'Unggah foto-foto dokumentasi kegiatan dan pelayanan DKUPP.' }}
            </p>
        </div>
        <button @click="showAddModal = true; newItem = { category: 'Dokumentasi Kegiatan' }" class="px-5 py-2.5 {{ $tab == 'video' ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-700 hover:bg-emerald-800' }} text-white font-bold text-xs rounded-xl shadow transition-colors flex items-center gap-2 shrink-0">
            <i class="fas fa-plus"></i> {{ $tab == 'video' ? 'Post Link Video YouTube' : 'Tambah Foto Galeri' }}
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
                <div class="relative aspect-video bg-slate-900 overflow-hidden">
                    @if($item->type == 'video')
                        @php
                            preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $item->youtube_url, $matches);
                            $ytId = $matches[1] ?? '';
                        @endphp
                        @if($ytId)
                            <img src="https://img.youtube.com/vi/{{ $ytId }}/hqdefault.jpg" class="w-full h-full object-cover">
                            <a href="{{ $item->youtube_url }}" target="_blank" rel="noopener noreferrer" class="absolute inset-0 bg-slate-900/40 flex items-center justify-center text-white text-3xl hover:bg-slate-900/60 transition-colors">
                                <i class="fab fa-youtube text-rose-500 drop-shadow-md"></i>
                            </a>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-200">
                                <i class="fab fa-youtube text-red-500 text-3xl"></i>
                            </div>
                        @endif
                    @else
                        <img src="{{ $item->file_path ?: 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=600&auto=format&fit=crop' }}" 
                             alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @endif
                </div>

                <div class="p-4 space-y-2 flex-grow flex flex-col justify-between">
                    <div>
                        <span class="px-2 py-0.5 rounded text-[9px] font-extrabold bg-emerald-100 text-emerald-800 uppercase tracking-wider inline-block mb-1">
                            {{ $item->category ?: 'Dokumentasi Kegiatan' }}
                        </span>
                        <h4 class="font-extrabold text-slate-900 text-xs line-clamp-2 leading-snug">{{ $item->title }}</h4>
                        @if($item->caption)
                            <p class="text-[10px] text-slate-500 mt-1 line-clamp-2">{{ $item->caption }}</p>
                        @endif
                    </div>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-1">
                        <span class="text-[10px] text-slate-400 font-mono">{{ $item->created_at->format('d M Y') }}</span>
                        
                        <div class="flex items-center gap-1">
                            <!-- Tombol Edit Item Galeri -->
                            <button @click="showEditModal = true; editItem = {{ json_encode(['id' => $item->id, 'title' => $item->title, 'category' => $item->category ?: 'Dokumentasi Kegiatan', 'caption' => $item->caption, 'type' => $item->type, 'file_path' => $item->file_path, 'youtube_url' => $item->youtube_url]) }}" 
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
        <div @click.away="showAddModal = false" class="bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-7 max-w-md w-full space-y-4 shadow-2xl my-auto">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <i class="{{ $tab == 'video' ? 'fab fa-youtube text-red-600' : 'fas fa-image text-emerald-600' }} text-lg"></i>
                    <h3 class="font-extrabold text-slate-900 text-base">
                        {{ $tab == 'video' ? 'Post Link Video YouTube' : 'Tambah Foto Galeri' }}
                    </h3>
                </div>
                <button @click="showAddModal = false" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg"><i class="fas fa-times"></i></button>
            </div>

            <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5 text-xs">
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
                        <label class="font-bold text-slate-700 block mb-1">Judul Foto <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required placeholder="Contoh: Dok. Sidang Tera Ulang Pasar Kraksaan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300">
                    </div>
                @endif

                <!-- Kategori (Bisa Diketik & Pilih Master) -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1 flex items-center justify-between">
                        <span><i class="fas fa-tags text-emerald-600 me-1"></i> Kategori {{ $tab == 'video' ? 'Video' : 'Foto' }} <span class="text-rose-500">*</span></span>
                        <span class="text-[10px] text-emerald-700 font-extrabold uppercase bg-emerald-100/70 px-2 py-0.5 rounded-md">Bisa Diketik & Pilih Master</span>
                    </label>
                    
                    <div class="space-y-2">
                        <div class="relative">
                            <input type="text" 
                                   name="category" 
                                   required 
                                   list="master_gallery_categories_add"
                                   x-model="newItem.category" 
                                   placeholder="Pilih atau ketik kategori (misal: Dokumentasi Kegiatan, Tera Ulang...)" 
                                   class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold text-slate-900 text-xs bg-white shadow-2xs">
                            
                            <datalist id="master_gallery_categories_add">
                                <template x-for="cat in categoriesList" :key="cat">
                                    <option :value="cat"></option>
                                </template>
                            </datalist>
                        </div>

                        <!-- Quick Clickable Master Category Pills -->
                        <div class="flex flex-wrap items-center gap-1.5 pt-0.5">
                            <span class="text-[10px] text-slate-400 font-bold self-center me-1">Master:</span>
                            <template x-for="quickCat in categoriesList" :key="quickCat">
                                <button type="button" @click="newItem.category = quickCat"
                                        :class="newItem.category === quickCat ? 'bg-emerald-700 text-white font-extrabold shadow-2xs border-emerald-700' : 'bg-slate-100 text-slate-700 hover:bg-emerald-100 hover:text-emerald-800 border-slate-200'"
                                        class="px-2.5 py-1 text-[10px] rounded-lg border transition-all cursor-pointer">
                                    <span x-text="quickCat"></span>
                                </button>
                            </template>

                            <!-- Quick Add Master Button -->
                            <button type="button" @click="showAddMaster = !showAddMaster" 
                                    class="px-2 py-1 text-[10px] rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-bold transition-all shadow-2xs flex items-center gap-1 cursor-pointer" 
                                    title="Tambah Master Kategori Baru secara Manual">
                                <i class="fas fa-plus text-[9px]"></i> Tambah Master
                            </button>
                        </div>

                        <!-- Inline Add Master Input Box -->
                        <div x-show="showAddMaster" x-cloak class="mt-2 p-2 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-2">
                            <input type="text" 
                                   x-model="newMasterInput" 
                                   @keydown.enter.prevent="submitQuickMaster('layanan')" 
                                   placeholder="Ketik nama master kategori baru..." 
                                   class="w-full px-2.5 py-1.5 border border-emerald-300 rounded-lg text-xs font-bold uppercase focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white">
                            <button type="button" 
                                    @click="submitQuickMaster('layanan')" 
                                    class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-lg whitespace-nowrap shadow-xs">
                                Simpan
                            </button>
                            <button type="button" @click="showAddMaster = false" class="text-slate-400 hover:text-slate-600 px-1">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                @if($tab != 'video')
                    <div class="space-y-2 p-3.5 bg-emerald-50/70 border border-emerald-200 rounded-xl">
                        <label class="block font-extrabold text-emerald-900 text-xs">
                            <i class="fas fa-upload text-emerald-600 me-1"></i> Upload File Foto / Gambar Galeri
                        </label>
                        <input type="file" name="file_upload" accept="image/jpeg,image/png,.jpg,.jpeg,.png" @change="validateImageFile($event)" 
                               class="w-full px-3 py-2 bg-white border border-emerald-300 rounded-xl text-xs font-semibold text-slate-700 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-700 file:text-white">
                        
                        <!-- PEMBERITAHUAN GAGAL UPLOAD NON-JPG/PNG -->
                        <template x-if="imageErrorMsg">
                            <div class="mt-2.5 p-3 bg-rose-100 border border-rose-300 text-rose-900 rounded-xl text-xs font-bold flex items-center gap-2 shadow-xs">
                                <i class="fas fa-exclamation-triangle text-rose-600 text-base shrink-0"></i>
                                <span x-text="imageErrorMsg"></span>
                            </div>
                        </template>

                        <div class="text-[10px] text-slate-500 font-medium pt-1">
                            <p class="font-bold text-emerald-800">Atau Masukkan Link URL Foto:</p>
                            <input type="text" name="file_path" placeholder="https://... (opsional)" class="w-full mt-1 px-3 py-1.5 border border-slate-300 rounded-lg text-xs bg-white">
                        </div>
                    </div>
                @endif

                <div>
                    <label class="font-bold text-slate-700 block mb-1">Keterangan Singkat (Opsional)</label>
                    <textarea name="caption" rows="2" placeholder="Penjelasan singkat mengenai kegiatan..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300"></textarea>
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
        <div @click.away="showEditModal = false" class="bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-7 max-w-md w-full space-y-4 shadow-2xl my-auto text-left">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <i class="{{ $tab == 'video' ? 'fab fa-youtube text-red-600' : 'fas fa-edit text-blue-600' }} text-lg"></i>
                    <h3 class="font-extrabold text-slate-900 text-base">
                        {{ $tab == 'video' ? 'Edit Video YouTube' : 'Edit Foto Galeri' }}
                    </h3>
                </div>
                <button @click="showEditModal = false" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg"><i class="fas fa-times"></i></button>
            </div>

            <form :action="'/admin/gallery/' + editItem.id" method="POST" enctype="multipart/form-data" class="space-y-3.5 text-xs">
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
                        <label class="font-bold text-slate-700 block mb-1">Judul Foto <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required x-model="editItem.title" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300">
                    </div>
                @endif

                <!-- Kategori (Edit Form) -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1 flex items-center justify-between">
                        <span><i class="fas fa-tags text-blue-600 me-1"></i> Kategori {{ $tab == 'video' ? 'Video' : 'Foto' }} <span class="text-rose-500">*</span></span>
                        <span class="text-[10px] text-blue-700 font-extrabold uppercase bg-blue-100/70 px-2 py-0.5 rounded-md">Bisa Diketik & Pilih Master</span>
                    </label>
                    
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

                        <!-- Quick Clickable Master Category Pills -->
                        <div class="flex flex-wrap items-center gap-1.5 pt-0.5">
                            <span class="text-[10px] text-slate-400 font-bold self-center me-1">Master:</span>
                            <template x-for="quickCat in categoriesList" :key="quickCat">
                                <button type="button" @click="editItem.category = quickCat"
                                        :class="editItem.category === quickCat ? 'bg-blue-600 text-white font-extrabold shadow-2xs border-blue-600' : 'bg-slate-100 text-slate-700 hover:bg-blue-100 hover:text-blue-800 border-slate-200'"
                                        class="px-2.5 py-1 text-[10px] rounded-lg border transition-all cursor-pointer">
                                    <span x-text="quickCat"></span>
                                </button>
                            </template>

                            <!-- Quick Add Master Button -->
                            <button type="button" @click="showAddMaster = !showAddMaster" 
                                    class="px-2 py-1 text-[10px] rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-bold transition-all shadow-2xs flex items-center gap-1 cursor-pointer" 
                                    title="Tambah Master Kategori Baru secara Manual">
                                <i class="fas fa-plus text-[9px]"></i> Tambah Master
                            </button>
                        </div>

                        <!-- Inline Add Master Input Box -->
                        <div x-show="showAddMaster" x-cloak class="mt-2 p-2 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-2">
                            <input type="text" 
                                   x-model="newMasterInput" 
                                   @keydown.enter.prevent="submitQuickMaster('layanan')" 
                                   placeholder="Ketik nama master kategori baru..." 
                                   class="w-full px-2.5 py-1.5 border border-emerald-300 rounded-lg text-xs font-bold uppercase focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white">
                            <button type="button" 
                                    @click="submitQuickMaster('layanan')" 
                                    class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-lg whitespace-nowrap shadow-xs">
                                Simpan
                            </button>
                            <button type="button" @click="showAddMaster = false" class="text-slate-400 hover:text-slate-600 px-1">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                @if($tab != 'video')
                    <div class="space-y-2 p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                        <label class="block font-extrabold text-slate-800 text-xs">
                            <i class="fas fa-image text-blue-600 me-1"></i> Ganti File Foto / Gambar (Opsional)
                        </label>
                        <input type="file" name="file_upload" accept="image/jpeg,image/png,.jpg,.jpeg,.png" @change="validateImageFile($event)" 
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-700 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white">
                        
                        <!-- PEMBERITAHUAN GAGAL UPLOAD NON-JPG/PNG -->
                        <template x-if="imageErrorMsg">
                            <div class="mt-2.5 p-3 bg-rose-100 border border-rose-300 text-rose-900 rounded-xl text-xs font-bold flex items-center gap-2 shadow-xs">
                                <i class="fas fa-exclamation-triangle text-rose-600 text-base shrink-0"></i>
                                <span x-text="imageErrorMsg"></span>
                            </div>
                        </template>

                        <div class="text-[10px] text-slate-500 font-medium pt-1">
                            <p class="font-bold text-slate-700">Atau Edit Link URL Foto:</p>
                            <input type="text" name="file_path" x-model="editItem.file_path" placeholder="https://... (opsional)" class="w-full mt-1 px-3 py-1.5 border border-slate-300 rounded-lg text-xs bg-white">
                        </div>
                    </div>
                @endif

                <div>
                    <label class="font-bold text-slate-700 block mb-1">Keterangan Singkat (Opsional)</label>
                    <textarea name="caption" rows="2" x-model="editItem.caption" placeholder="Penjelasan singkat mengenai kegiatan..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300"></textarea>
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

</div>
@endsection
