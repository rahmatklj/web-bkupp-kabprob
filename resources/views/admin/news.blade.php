@extends('admin.layout')

@section('page_title', 'CRUD Berita & Informasi DKUPP')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>

<script>
function initNewsTinyMCE(contentStr) {
    if (typeof tinymce === 'undefined') return;
    if (tinymce.get('news_editor')) {
        tinymce.get('news_editor').destroy();
    }
    tinymce.init({
        selector: '#news_editor',
        height: 380,
        menubar: 'file edit view insert format table help',
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | removeformat code fullscreen',
        toolbar_mode: 'wrap',
        content_style: 'body { font-family: sans-serif; font-size: 14px; line-height: 1.8; color: #1e293b; padding: 10px; } p { margin-bottom: 1.25rem; }',
        branding: false,
        promotion: false,
        setup: function (editor) {
            editor.on('init', function () {
                editor.setContent(contentStr || '');
            });
            editor.on('change keyup NodeChange', function () {
                editor.save();
            });
        }
    });
}

function syncNewsContent() {
    if (typeof tinymce !== 'undefined' && tinymce.get('news_editor')) {
        tinymce.get('news_editor').save();
    }
}
</script>

<div class="space-y-6" x-data="{ 
    showModal: false, 
    editMode: false, 
    currentNews: {}, 
    isCustomCategory: false,
    categoriesList: {{ json_encode($categories) }},
    newMasterInput: '',
    showAddMaster: false,
    imageErrorMsg: null,
    openAddModal() {
        this.showModal = true;
        this.editMode = false;
        this.isCustomCategory = false;
        this.currentNews = { category: '{{ $categories[0] ?? 'Berita Utama' }}', published_at: '{{ date('Y-m-d') }}', content: '', is_published: 1 };
        setTimeout(() => {
            initNewsTinyMCE('');
        }, 50);
    },
    openEditModal(news) {
        this.showModal = true;
        this.editMode = true;
        this.isCustomCategory = false;
        this.currentNews = Object.assign({}, news);
        setTimeout(() => {
            initNewsTinyMCE(news.content || '');
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
                this.currentNews.category = data.category;
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
                if (this.currentNews && this.currentNews.category === catName) this.currentNews.category = '';
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
                showUploadErrorSwal(msg, 'JPG atau PNG');
                e.target.value = '';
            }
        }
    }
}">
    
    <!-- Top Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h3 class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                <i class="fas fa-newspaper text-orange-600"></i> Kelola Artikel Berita & Pengumuman
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Kelola artikel berita publik yang tampil di homepage dan portal berita resmi DKUPP Kabupaten Probolinggo.</p>
        </div>
        <button @click="openAddModal()" 
                class="w-full sm:w-auto px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-xs flex items-center justify-center gap-2 transition-all">
            <i class="fas fa-plus"></i> Publish Berita Baru
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl flex items-center gap-2 shadow-xs">
            <i class="fas fa-check-circle text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-100 border border-rose-300 text-rose-800 text-xs font-bold rounded-2xl flex items-center gap-2 shadow-xs">
            <i class="fas fa-exclamation-triangle text-base"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-100 border border-rose-300 text-rose-800 text-xs font-bold rounded-2xl space-y-1 shadow-xs">
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-base"></i>
                <span>Peringatan Gagal Publish Berita:</span>
            </div>
            <ul class="list-disc pl-7 text-[11px] font-semibold">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- News Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto w-full">
        <table class="w-full text-left text-xs min-w-[650px]">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Thumbnail</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Judul Berita</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Kategori</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Status</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Tanggal Publish</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Views</th>
                    <th class="px-3 sm:px-6 py-3.5 text-right whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($newsList as $news)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <img src="{{ $news->image_url ?? 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb' }}" alt="News" class="h-12 w-16 object-cover rounded-lg border border-slate-200">
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <h4 class="font-bold text-slate-800 text-xs line-clamp-2">{{ $news->title }}</h4>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-orange-100 text-orange-800">
                                {{ $news->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.news.toggle', $news->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" title="Klik untuk ubah status publikasi (Aktif / Draft)" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold transition-all duration-200 cursor-pointer hover:scale-105 {{ $news->is_published ? 'bg-emerald-100 text-emerald-800 border border-emerald-300 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-300 hover:bg-slate-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $news->is_published ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                    {{ $news->is_published ? 'PUBLISHED' : 'DRAFT (OFF)' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-medium">
                            {{ optional($news->published_at)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-bold">
                            {{ $news->views }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <button @click="openEditModal({{ json_encode($news) }})" 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Artikel Berita"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.news.destroy', $news->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus berita ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">
            {{ $newsList->links() }}
        </div>
    </div>

    <!-- Modal Form (Add / Edit News with TinyMCE) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-3xl max-w-3xl w-full p-5 sm:p-7 shadow-2xl space-y-4 my-auto">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-extrabold text-slate-800 text-base flex items-center gap-2" x-text="editMode ? 'Edit Berita & Artikel' : 'Publish Berita Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 p-1"><i class="fas fa-times"></i></button>
            </div>

            <form :action="editMode ? '/admin/news/' + currentNews.id : '{{ route('admin.news.store') }}'" method="POST" enctype="multipart/form-data" @submit="syncNewsContent()" class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Berita <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required x-model="currentNews.title" placeholder="Judul berita terbaru..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none font-bold text-slate-900">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kategori Berita Minimalis & Responsif -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="font-bold text-slate-700 text-xs flex items-center gap-1.5">
                                <i class="fas fa-tags text-orange-600"></i> Kategori Berita <span class="text-rose-500">*</span>
                            </label>
                            <button type="button" @click="showAddMaster = !showAddMaster" 
                                    class="px-2.5 py-1 text-[10px] rounded-lg bg-orange-100 hover:bg-orange-200 text-orange-800 font-extrabold transition-all border border-orange-200 flex items-center gap-1 cursor-pointer" 
                                    title="Tambah / Kelola Kategori Baru">
                                <i class="fas fa-plus text-[9px]"></i> Manajemen Kategori
                            </button>
                        </div>
                        
                        <div class="space-y-2">
                            <!-- Direct Editable Input Field with Master Datalist Autocomplete -->
                            <div class="relative">
                                <input type="text" 
                                       name="category" 
                                       required 
                                       list="master_news_categories"
                                       x-model="currentNews.category" 
                                       placeholder="Pilih atau ketik kategori berita..." 
                                       class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none font-bold text-slate-900 text-xs bg-white shadow-2xs">
                                
                                <datalist id="master_news_categories">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}"></option>
                                    @endforeach
                                </datalist>
                            </div>

                            <!-- Inline Add & Manage Master Input Box -->
                            <div x-show="showAddMaster" x-cloak class="mt-2 p-3 bg-orange-50/80 border border-orange-200 rounded-xl space-y-2.5">
                                <div class="flex items-center gap-2">
                                    <input type="text" 
                                           x-model="newMasterInput" 
                                           @keydown.enter.prevent="submitQuickMaster('berita')" 
                                           placeholder="Ketik nama kategori baru..." 
                                           class="w-full px-2.5 py-1.5 border border-orange-300 rounded-lg text-xs font-bold uppercase focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white">
                                    <button type="button" 
                                            @click="submitQuickMaster('berita')" 
                                            class="px-3 py-1.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-lg whitespace-nowrap shadow-xs cursor-pointer">
                                        + Simpan
                                    </button>
                                    <button type="button" @click="showAddMaster = false" class="text-slate-400 hover:text-slate-600 px-1 cursor-pointer">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>

                                <!-- List of Master Categories with Delete Button -->
                                <div class="pt-2 border-t border-orange-200/70 space-y-1">
                                    <span class="text-[10px] text-slate-500 font-bold block">Kategori Tersimpan (Klik untuk pilih / Hapus jika tidak diperlukan):</span>
                                    <div class="flex flex-wrap items-center gap-1.5 max-h-32 overflow-y-auto pt-0.5">
                                        <template x-for="cat in categoriesList" :key="cat">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-white border border-orange-200 text-slate-800 shadow-2xs group hover:border-orange-400 transition-all">
                                                <span @click="currentNews.category = cat" class="cursor-pointer hover:text-orange-600" x-text="cat"></span>
                                                <button type="button" @click.stop="deleteQuickMaster(cat, 'berita')" class="text-slate-300 hover:text-rose-600 transition-colors ml-0.5 cursor-pointer" title="Hapus Kategori Ini">
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

                    <!-- Published Date & Status -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">
                                <i class="fas fa-calendar-alt text-orange-600 me-1"></i> Tanggal Publish
                            </label>
                            <input type="date" name="published_at" x-model="currentNews.published_at" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none font-medium text-xs">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">
                                <i class="fas fa-eye text-orange-600 me-1"></i> Status Publikasi
                            </label>
                            <select name="is_published" x-model="currentNews.is_published" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none font-bold text-slate-800 text-xs">
                                <option :value="1">PUBLISHED (Aktif / Tampil di Web)</option>
                                <option :value="0">DRAFT (Nonaktif / Sembunyi)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Image Upload & URL Input -->
                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl space-y-3">
                    <label class="block font-extrabold text-slate-800 text-xs">
                        <i class="fas fa-image text-orange-600 me-1"></i> Foto / Gambar Thumbnail Artikel
                    </label>

                    <div class="space-y-2">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">
                                <i class="fas fa-upload text-orange-600 me-1"></i> Unggah File Foto (Dari HP / Komputer)
                            </label>
                            <input type="file" name="image_file" accept="image/jpeg,image/png,.jpg,.jpeg,.png" @change="validateImageFile($event)" 
                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-600 file:text-white hover:file:bg-orange-700 cursor-pointer">
                            
                            <!-- PEMBERITAHUAN GAGAL UPLOAD NON-JPG/PNG -->
                            <template x-if="imageErrorMsg">
                                <div class="mt-2.5 p-3 bg-rose-100 border border-rose-300 text-rose-900 rounded-xl text-xs font-bold flex items-center gap-2 shadow-xs">
                                    <i class="fas fa-exclamation-triangle text-rose-600 text-base shrink-0"></i>
                                    <span x-text="imageErrorMsg"></span>
                                </div>
                            </template>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">
                                <i class="fas fa-link text-slate-400 me-1"></i> Atau Masukkan URL Gambar (Opsional)
                            </label>
                            <input type="text" name="image_url" x-model="currentNews.image_url" placeholder="https://... atau /uploads/news/..." 
                                   class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none font-mono text-xs bg-white">
                        </div>
                    </div>

                    <template x-if="currentNews.image_url">
                        <div class="mt-2 p-2 bg-white rounded-xl border border-slate-200 inline-flex items-center gap-3">
                            <span class="text-[10px] font-bold text-slate-500">Preview:</span>
                            <img :src="currentNews.image_url" alt="Preview" class="h-10 w-14 object-cover rounded-lg border border-slate-200">
                        </div>
                    </template>
                </div>

                <!-- Content Textarea dengan TinyMCE Rich Text Editor -->
                <div class="space-y-1.5">
                    <label class="block font-extrabold text-slate-800 text-xs flex items-center justify-between">
                        <span><i class="fas fa-pen-nib text-orange-600 me-1"></i> Isi Berita (Content Lengkap) <span class="text-rose-500">*</span></span>
                        <span class="text-[10px] bg-orange-100 text-orange-800 font-extrabold px-2.5 py-0.5 rounded-md uppercase">✨ Rich Text Editor TinyMCE</span>
                    </label>
                    <textarea id="news_editor" name="content" rows="10" x-model="currentNews.content" placeholder="Tuliskan isi berita lengkap..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none leading-relaxed text-slate-800"></textarea>
                    <p class="text-[10px] text-slate-400 font-medium">Gunakan editor TinyMCE di atas untuk memberikan format tulisan (Bold, Italic, Judul Sub-Bab, Tabel, Gambar, Daftar List, Link, dll).</p>
                </div>

                <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-extrabold rounded-xl shadow-md transition-colors flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i> Simpan Artikel
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
