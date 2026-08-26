@extends('admin.layout')

@section('page_title', 'CRUD Berita & Informasi DKUPP')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: false, 
    editMode: false, 
    currentNews: {}, 
    isCustomCategory: false,
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
                this.currentNews.category = data.category;
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
    
    <!-- Top Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h3 class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                <i class="fas fa-newspaper text-orange-600"></i> Kelola Artikel Berita & Pengumuman
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Kelola artikel berita publik yang tampil di homepage dan portal berita resmi DKUPP Kabupaten Probolinggo.</p>
        </div>
        <button @click="showModal = true; editMode = false; isCustomCategory = false; currentNews = { category: '{{ $categories[0] ?? 'Berita Utama' }}', published_at: '{{ date('Y-m-d') }}' }" 
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
                        <td class="px-6 py-4 text-slate-500 font-medium">
                            {{ optional($news->published_at)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-bold">
                            {{ $news->views }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <button @click="showModal = true; editMode = true; isCustomCategory = false; currentNews = {{ json_encode($news) }}" 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"><i class="fas fa-edit"></i></button>
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

    <!-- Modal Form (Add / Edit News) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl space-y-4 my-8">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2" x-text="editMode ? 'Edit Berita & Artikel' : 'Publish Berita Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>

            <form :action="editMode ? '/admin/news/' + currentNews.id : '{{ route('admin.news.store') }}'" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Berita <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required x-model="currentNews.title" placeholder="Judul berita terbaru..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none font-bold text-slate-900">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kategori Berita (Bisa Diketik & Pilih Master) -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1 flex items-center justify-between">
                            <span><i class="fas fa-tags text-orange-600 me-1"></i> Kategori Berita <span class="text-rose-500">*</span></span>
                            <span class="text-[10px] text-orange-700 font-extrabold uppercase bg-orange-100/70 px-2 py-0.5 rounded-md">Bisa Diketik & Pilih Master</span>
                        </label>
                        
                        <div class="space-y-2">
                            <!-- Direct Editable Input Field with Master Datalist Autocomplete -->
                            <div class="relative">
                                <input type="text" 
                                       name="category" 
                                       required 
                                       list="master_news_categories"
                                       x-model="currentNews.category" 
                                       placeholder="Pilih atau ketik kategori (misal: Berita Utama, Pengumuman...)" 
                                       class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none font-bold text-slate-900 text-xs bg-white shadow-2xs">
                                
                                <datalist id="master_news_categories">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}"></option>
                                    @endforeach
                                </datalist>
                            </div>

                            <!-- Quick Clickable Master Category Pills with + Tambah Master Inline -->
                            <div class="flex flex-wrap items-center gap-1.5 pt-0.5">
                                <span class="text-[10px] text-slate-400 font-bold self-center me-1">Master:</span>
                                <template x-for="quickCat in categoriesList" :key="quickCat">
                                    <button type="button" @click="currentNews.category = quickCat"
                                            :class="currentNews.category === quickCat ? 'bg-orange-600 text-white font-extrabold shadow-2xs border-orange-600' : 'bg-slate-100 text-slate-700 hover:bg-orange-100 hover:text-orange-800 border-slate-200'"
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
                                       @keydown.enter.prevent="submitQuickMaster('berita')" 
                                       placeholder="Ketik nama master kategori baru..." 
                                       class="w-full px-2.5 py-1.5 border border-emerald-300 rounded-lg text-xs font-bold uppercase focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white">
                                <button type="button" 
                                        @click="submitQuickMaster('berita')" 
                                        class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-lg whitespace-nowrap shadow-xs">
                                    Simpan
                                </button>
                                <button type="button" @click="showAddMaster = false" class="text-slate-400 hover:text-slate-600 px-1">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                            <p class="text-[10px] text-slate-400">Anda dapat mengetik secara bebas nama kategori baru atau mengklik pilihan master di atas.</p>
                        </div>
                    </div>

                    <!-- Published Date -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            <i class="fas fa-calendar-alt text-orange-600 me-1"></i> Tanggal Publish
                        </label>
                        <input type="date" name="published_at" x-model="currentNews.published_at" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none font-medium">
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

                <!-- Content Textarea -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Isi Berita (Content) <span class="text-rose-500">*</span></label>
                    <textarea name="content" rows="4" required x-model="currentNews.content" placeholder="Tuliskan berita lengkap..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none leading-relaxed text-slate-800"></textarea>
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
