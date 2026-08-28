@extends('admin.layout')

@section('page_title', 'CRUD Standar Pelayanan Publik & SOP')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>

<script>
function initServiceTinyMCE(reqContent = '', procContent = '') {
    if (typeof tinymce === 'undefined') return;
    
    ['req_editor', 'proc_editor'].forEach(id => {
        if (tinymce.get(id)) {
            tinymce.get(id).destroy();
        }
    });

    tinymce.init({
        selector: '#req_editor',
        height: 280,
        menubar: 'file edit view insert format table help',
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link table | removeformat code fullscreen',
        toolbar_mode: 'wrap',
        content_style: 'body { font-family: sans-serif; font-size: 13px; line-height: 1.8; color: #1e293b; padding: 10px; } p { margin-bottom: 1rem; }',
        branding: false,
        promotion: false,
        setup: function (editor) {
            editor.on('init', function () {
                editor.setContent(reqContent || '');
            });
            editor.on('change keyup NodeChange', function () {
                editor.save();
            });
        }
    });

    tinymce.init({
        selector: '#proc_editor',
        height: 280,
        menubar: 'file edit view insert format table help',
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link table | removeformat code fullscreen',
        toolbar_mode: 'wrap',
        content_style: 'body { font-family: sans-serif; font-size: 13px; line-height: 1.8; color: #1e293b; padding: 10px; } p { margin-bottom: 1rem; }',
        branding: false,
        promotion: false,
        setup: function (editor) {
            editor.on('init', function () {
                editor.setContent(procContent || '');
            });
            editor.on('change keyup NodeChange', function () {
                editor.save();
            });
        }
    });
}

function syncServiceTinyMCE() {
    if (typeof tinymce !== 'undefined') {
        if (tinymce.get('req_editor')) tinymce.get('req_editor').save();
        if (tinymce.get('proc_editor')) tinymce.get('proc_editor').save();
    }
}
</script>

<div class="space-y-6" x-data="{ 
    showModal: false, 
    editMode: false, 
    currentService: {}, 
    previewSop: null,
    openPicker: false,
    searchIcon: '',
    categoriesList: {{ json_encode($masterCategories ?? ['USAHA MIKRO', 'KOPERASI', 'PERDAGANGAN', 'PERINDUSTRIAN', 'METROLOGI LEGAL', 'PELAYANAN UMUM']) }},
    newMasterInput: '',
    showAddMaster: false,
    pdfErrorMsg: null,
    openAddModal() {
        this.showModal = true;
        this.editMode = false;
        this.currentService = { category: 'USAHA MIKRO', icon: 'fa-shopping-basket', cost: 'Gratis (Rp 0)', service_time: '1 Hari Kerja', location: 'Loket MPP Kraksaan', requirements: '', procedure: '', is_active: 1 };
        setTimeout(() => {
            initServiceTinyMCE('', '');
        }, 50);
    },
    openEditModal(srv) {
        this.showModal = true;
        this.editMode = true;
        this.currentService = Object.assign({}, srv);
        setTimeout(() => {
            initServiceTinyMCE(srv.requirements || '', srv.procedure || '');
        }, 50);
    },
    validatePdfFile(e) {
        this.pdfErrorMsg = null;
        const file = e.target.files[0];
        if (file) {
            const ext = file.name.split('.').pop().toLowerCase();
            if (ext !== 'pdf' && file.type !== 'application/pdf') {
                const msg = '⚠️ GAGAL UPLOAD: Berkas yang Anda pilih berformat .' + ext.toUpperCase() + '! Sistem HANYA menerima dokumen SOP berformat PDF (.pdf).';
                this.pdfErrorMsg = msg;
                showUploadErrorSwal(msg, 'PDF (.pdf)');
                e.target.value = '';
            }
        }
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
                this.currentService.category = data.category;
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
                if (this.currentService && this.currentService.category === catName) this.currentService.category = '';
            }
        } catch (e) {
            console.error(e);
        }
    },
    iconsList: [
        { name: 'fa-shopping-basket', label: 'Keranjang Pasar', cat: 'Pasar' },
        { name: 'fa-store', label: 'Toko / UMKM', cat: 'Pasar' },
        { name: 'fa-shopping-cart', label: 'Keranjang Belanja', cat: 'Pasar' },
        { name: 'fa-tags', label: 'Komoditas / Tag', cat: 'Pasar' },
        { name: 'fa-balance-scale', label: 'Metrologi & Timbangan', cat: 'Pasar' },
        { name: 'fa-receipt', label: 'Struk / Retribusi', cat: 'Pasar' },
        { name: 'fa-box', label: 'Produk Kemasan', cat: 'Pasar' },
        { name: 'fa-warehouse', label: 'Gudang & Logistik', cat: 'Pasar' },
        { name: 'fa-handshake', label: 'Kemitraan / SOP', cat: 'Koperasi' },
        { name: 'fa-users', label: 'Masyarakat / Koperasi', cat: 'Koperasi' },
        { name: 'fa-user-tie', label: 'Pengurus / Staf', cat: 'Koperasi' },
        { name: 'fa-building', label: 'Gedung / Lembaga', cat: 'Koperasi' },
        { name: 'fa-briefcase', label: 'Usaha & Bisnis', cat: 'Koperasi' },
        { name: 'fa-chart-line', label: 'Grafik & Kinerja', cat: 'Koperasi' },
        { name: 'fa-coins', label: 'Keuangan & Modal', cat: 'Koperasi' },
        { name: 'fa-piggy-bank', label: 'Simpan Pinjam', cat: 'Koperasi' },
        { name: 'fa-file-alt', label: 'Dokumen / Surat', cat: 'SOP' },
        { name: 'fa-clipboard-check', label: 'Verifikasi & SOP', cat: 'SOP' },
        { name: 'fa-certificate', label: 'Sertifikat & NIB', cat: 'SOP' },
        { name: 'fa-stamp', label: 'Stempel / Legalisasi', cat: 'SOP' },
        { name: 'fa-gavel', label: 'Hukum & Aturan', cat: 'SOP' },
        { name: 'fa-shield-alt', label: 'Perlindungan Konsumen', cat: 'SOP' },
        { name: 'fa-check-circle', label: 'Persetujuan ACC', cat: 'SOP' },
        { name: 'fa-headset', label: 'Call Center CS', cat: 'Info' },
        { name: 'fa-comments', label: 'Pengaduan Publik', cat: 'Info' },
        { name: 'fa-bullhorn', label: 'Pengumuman Resmi', cat: 'Info' },
        { name: 'fa-envelope', label: 'Surat & Email', cat: 'Info' },
        { name: 'fa-phone-alt', label: 'Telepon Hotline', cat: 'Info' },
        { name: 'fa-mobile-alt', label: 'Aplikasi Mobile', cat: 'Info' },
        { name: 'fa-globe', label: 'Website Portal', cat: 'Info' },
        { name: 'fa-info-circle', label: 'Pusat Informasi', cat: 'Info' },
        { name: 'fa-industry', label: 'Pabrik & Industri', cat: 'Industri' },
        { name: 'fa-seedling', label: 'Industri Kreatif', cat: 'Industri' },
        { name: 'fa-truck', label: 'Distribusi / Cargo', cat: 'Industri' },
        { name: 'fa-tools', label: 'Peralatan / Teknis', cat: 'Industri' },
        { name: 'fa-microchip', label: 'Teknologi & Mesin', cat: 'Industri' },
        { name: 'fa-cogs', label: 'Sistem & Pengolahan', cat: 'Industri' }
    ]
}">
    
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h3 class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                <i class="fas fa-handshake text-emerald-600"></i> Kelola Standar Pelayanan Publik & Persyaratan SOP
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Tambah, edit, dan atur rincian Persyaratan Dokumen, Prosedur SOP, Biaya, Waktu, & Lokasi Pelayanan</p>
        </div>
        <button @click="openAddModal()" 
                class="w-full sm:w-auto px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-xs flex items-center justify-center gap-2 transition-all shrink-0">
            <i class="fas fa-plus"></i> Tambah Layanan Baru
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
                <span>Peringatan Gagal Upload Dokumen Layanan:</span>
            </div>
            <ul class="list-disc pl-7 text-[11px] font-semibold">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Services Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto w-full">
        <table class="w-full text-left text-xs min-w-[850px]">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Layanan & Kategori</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Status</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Ringkasan Layanan</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Biaya, Waktu & Lokasi</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Persyaratan & SOP</th>
                    <th class="px-3 sm:px-6 py-3.5 text-right whitespace-nowrap">Aksi Edit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($services as $srv)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-base shrink-0 shadow-2xs">
                                    <i class="fas {{ $srv->icon }}"></i>
                                </div>
                                <div>
                                    <span class="px-2 py-0.5 rounded text-[9px] font-extrabold bg-emerald-100 text-emerald-800 uppercase tracking-wider block w-fit mb-0.5">
                                        {{ $srv->category }}
                                    </span>
                                    <h4 class="font-extrabold text-slate-900 text-xs sm:text-sm leading-snug">{{ $srv->title }}</h4>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.services.toggle', $srv->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" title="Klik untuk ubah status publikasi (Aktif / Draft)" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold transition-all duration-200 cursor-pointer hover:scale-105 {{ $srv->is_active ? 'bg-emerald-100 text-emerald-800 border border-emerald-300 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-300 hover:bg-slate-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $srv->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                    {{ $srv->is_active ? 'PUBLISHED' : 'DRAFT (OFF)' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-slate-600 max-w-xs">
                            <p class="line-clamp-2 text-xs leading-relaxed">{{ $srv->summary }}</p>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-700 space-y-0.5">
                            <div class="text-emerald-700 font-extrabold text-xs"><i class="fas fa-tag me-1 text-[10px]"></i> {{ $srv->cost }}</div>
                            <div class="text-slate-500 text-[11px]"><i class="far fa-clock me-1 text-[10px]"></i> {{ $srv->service_time }}</div>
                            <div class="text-slate-400 text-[10px]"><i class="fas fa-map-marker-alt me-1 text-[9px] text-rose-500"></i> {{ $srv->location ?: 'Loket MPP Kraksaan' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <!-- Tombol Preview Pop-Up SOP -->
                                <button @click="previewSop = {{ json_encode($srv) }}" 
                                        class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-extrabold inline-flex items-center gap-1 shadow-2xs transition-colors">
                                    <i class="fas fa-eye text-[9px]"></i> Cek Pop-Up SOP
                                </button>

                                @if(!empty($srv->external_url))
                                    <a href="{{ $srv->external_url }}" target="_blank" class="px-2 py-1 bg-amber-50 border border-amber-200 text-amber-800 rounded-lg text-[10px] font-bold inline-flex items-center gap-1 hover:bg-amber-100 transition-colors">
                                        <i class="fas fa-external-link-alt text-[9px]"></i> Portal Web
                                    </a>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1 whitespace-nowrap">
                            <button @click="openEditModal({{ json_encode($srv) }})" 
                                    class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-2xs transition-colors inline-flex items-center gap-1" title="Edit Layanan & SOP">
                                <i class="fas fa-edit text-xs"></i> Edit SOP & Layanan
                            </button>
                            <form action="{{ route('admin.services.destroy', $srv->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus layanan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Layanan"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-xs">
                            Belum ada standar pelayanan publik yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($services->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $services->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form (Tambah / Edit Layanan & Persyaratan SOP dengan TinyMCE) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div @click.away="showModal = false" class="bg-white rounded-3xl max-w-4xl w-full p-6 sm:p-8 shadow-2xl space-y-5 my-auto relative max-h-[92vh] overflow-y-auto text-left">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <i class="fas fa-handshake text-emerald-600 text-xl"></i>
                    <h3 class="font-extrabold text-slate-800 text-base" x-text="editMode ? 'Edit Standar Pelayanan & Persyaratan SOP' : 'Tambah Layanan Publik Baru'"></h3>
                </div>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg"><i class="fas fa-times"></i></button>
            </div>

            <form :action="editMode ? '/admin/services/' + currentService.id : '{{ route('admin.services.store') }}'" method="POST" enctype="multipart/form-data" @submit="syncServiceTinyMCE()" class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Judul Pelayanan <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required x-model="currentService.title" placeholder="Contoh: Pendampingan & Perizinan Koperasi" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-extrabold text-slate-900 text-sm">
                    </div>

                    <!-- Kategori Sektor Layanan Minimalis & Responsif -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="font-bold text-slate-700 text-xs flex items-center gap-1.5">
                                <i class="fas fa-handshake text-emerald-600"></i> Kategori Sektor Layanan <span class="text-rose-500">*</span>
                            </label>
                            <button type="button" @click="showAddMaster = !showAddMaster" 
                                    class="px-2.5 py-1 text-[10px] rounded-lg bg-emerald-100 hover:bg-emerald-200 text-emerald-800 font-extrabold transition-all border border-emerald-300 flex items-center gap-1 cursor-pointer" 
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
                                       list="master_service_categories"
                                       x-model="currentService.category" 
                                       placeholder="Pilih atau ketik kategori sektor..." 
                                       class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-extrabold text-slate-900 uppercase text-xs bg-white shadow-2xs">
                                
                                <datalist id="master_service_categories">
                                    @foreach($masterCategories ?? ['USAHA MIKRO', 'KOPERASI', 'PERDAGANGAN', 'PERINDUSTRIAN', 'METROLOGI LEGAL', 'PELAYANAN UMUM'] as $mCat)
                                        <option value="{{ $mCat }}"></option>
                                    @endforeach
                                </datalist>
                            </div>

                            <!-- Inline Add & Manage Master Input Box -->
                            <div x-show="showAddMaster" x-cloak class="mt-2 p-3 bg-emerald-50/80 border border-emerald-200 rounded-xl space-y-2.5">
                                <div class="flex items-center gap-2">
                                    <input type="text" 
                                           x-model="newMasterInput" 
                                           @keydown.enter.prevent="submitQuickMaster('layanan')" 
                                           placeholder="Ketik nama kategori baru..." 
                                           class="w-full px-2.5 py-1.5 border border-emerald-300 rounded-lg text-xs font-bold uppercase focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white">
                                    <button type="button" 
                                            @click="submitQuickMaster('layanan')" 
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
                                                <span @click="currentService.category = cat" class="cursor-pointer hover:text-emerald-700" x-text="cat"></span>
                                                <button type="button" @click.stop="deleteQuickMaster(cat, 'layanan')" class="text-slate-300 hover:text-rose-600 transition-colors ml-0.5 cursor-pointer" title="Hapus Kategori Ini">
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
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div class="relative">
                        <label class="block font-bold text-slate-700 mb-1 flex items-center justify-between">
                            <span>Ikon FontAwesome</span>
                            <span class="text-[10px] text-emerald-600 font-bold cursor-pointer" @click="openPicker = !openPicker">Pilih <i class="fas fa-hand-pointer"></i></span>
                        </label>
                        
                        <div class="relative flex items-center">
                            <!-- Live Preview Icon Badge -->
                            <span class="absolute left-2.5 w-6 h-6 rounded-md bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs shrink-0 pointer-events-none shadow-2xs">
                                <i class="fas" :class="currentService.icon || 'fa-shopping-basket'"></i>
                            </span>

                            <input type="text" name="icon" x-model="currentService.icon" placeholder="fa-shopping-basket" 
                                   @focus="openPicker = true"
                                   class="w-full pl-10 pr-14 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono text-xs font-bold text-slate-800">
                            
                            <!-- Toggle Selection Button -->
                            <button type="button" @click="openPicker = !openPicker" 
                                    class="absolute right-1 px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-[10px] shadow-2xs transition-all flex items-center gap-1">
                                <span>Pilih</span>
                                <i class="fas fa-chevron-down text-[8px]" :class="{ 'rotate-180': openPicker }"></i>
                            </button>
                        </div>

                        <!-- Dropdown Icon Picker Modal Grid -->
                        <div x-show="openPicker" x-cloak @click.away="openPicker = false" 
                             class="absolute left-0 top-full mt-1.5 w-72 sm:w-80 bg-white rounded-2xl shadow-2xl border border-slate-200 p-3.5 z-50 space-y-2.5">
                            
                            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                <span class="font-extrabold text-slate-800 text-[11px] flex items-center gap-1.5">
                                    <i class="fas fa-icons text-emerald-600"></i> Pilih Ikon Layanan
                                </span>
                                <button type="button" @click="openPicker = false" class="text-slate-400 hover:text-slate-600 text-xs p-1"><i class="fas fa-times"></i></button>
                            </div>

                            <!-- Search input for icons -->
                            <div class="relative">
                                <i class="fas fa-search absolute left-2.5 top-2.5 text-slate-400 text-xs"></i>
                                <input type="text" x-model="searchIcon" placeholder="Cari ikon (misal: basket, store, users)..." 
                                       class="w-full pl-8 pr-3 py-1.5 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-slate-50">
                            </div>

                            <!-- Grid Options -->
                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-1.5 max-h-52 overflow-y-auto pt-1 pr-1">
                                <template x-for="item in iconsList.filter(i => !searchIcon || i.name.toLowerCase().includes(searchIcon.toLowerCase()) || i.label.toLowerCase().includes(searchIcon.toLowerCase()))" :key="item.name">
                                    <button type="button" @click="currentService.icon = item.name; openPicker = false" 
                                            :class="currentService.icon === item.name ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-slate-50 text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 border-slate-200'"
                                            class="p-2 border rounded-xl flex flex-col items-center justify-center gap-1 transition text-center group cursor-pointer hover:scale-105">
                                        <i class="fas text-base" :class="item.name"></i>
                                        <span class="text-[9px] font-bold line-clamp-1 leading-tight" x-text="item.label"></span>
                                    </button>
                                </template>
                            </div>

                            <p class="text-[9px] text-slate-400 text-center border-t border-slate-100 pt-2">
                                Klik salah satu ikon di atas atau ketik nama kelas FontAwesome secara manual.
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Biaya Layanan</label>
                        <input type="text" name="cost" x-model="currentService.cost" placeholder="Gratis (Rp 0) / Sesuai Retribusi" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold text-slate-800">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Waktu Proses</label>
                        <input type="text" name="service_time" x-model="currentService.service_time" placeholder="3-5 Hari Kerja / 1-2 Hari Kerja" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold text-slate-800">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Lokasi Pelayanan</label>
                        <input type="text" name="location" x-model="currentService.location" placeholder="Loket MPP Kraksaan" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold text-slate-800">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-eye text-emerald-600 me-1"></i> Status Publikasi</label>
                        <select name="is_active" x-model="currentService.is_active" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold text-slate-800 text-xs">
                            <option :value="1">PUBLISHED (Aktif / Tampil di Web)</option>
                            <option :value="0">DRAFT (Nonaktif / Sembunyi)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Keterangan Singkat Layanan <span class="text-rose-500">*</span></label>
                    <textarea name="summary" rows="2" required x-model="currentService.summary" placeholder="Penjelasan singkat mengenai cakupan pelayanan..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none leading-relaxed"></textarea>
                </div>

                <!-- Bagian Edit Persyaratan Dokumen & Prosedur SOP (FOKUS UTAMA) -->
                <div class="space-y-4 p-4 sm:p-5 bg-emerald-50/70 border border-emerald-200 rounded-2xl">
                    <div class="flex items-center justify-between border-b border-emerald-200/80 pb-2">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-list-check text-emerald-700 text-base"></i>
                            <h4 class="font-extrabold text-emerald-950 text-xs uppercase tracking-wider">Kelola Isi Persyaratan & Prosedur SOP</h4>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-700 bg-white px-2 py-0.5 rounded border border-emerald-300">Tampil di Pop-Up SOP Web</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-extrabold text-emerald-900 mb-1 flex items-center justify-between">
                                <span class="flex items-center gap-1"><i class="fas fa-file-invoice text-emerald-600"></i> Persyaratan Dokumen (SOP)</span>
                                <span class="text-[9px] bg-emerald-700 text-white font-extrabold px-2 py-0.5 rounded shadow-2xs">✨ Rich Text Editor</span>
                            </label>
                            <textarea id="req_editor" name="requirements" rows="6" x-model="currentService.requirements" 
                                      placeholder="1. Permohonan Tera/Tera Ulang UTTP..." 
                                      class="w-full px-3.5 py-2.5 border border-emerald-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-medium leading-relaxed bg-white text-slate-900"></textarea>
                            <p class="text-[10px] text-slate-500 mt-1 font-medium">
                                <i class="fas fa-info-circle text-emerald-600 me-1"></i>Format poin, nomor, dan tebal teks kini menggunakan Rich Text Editor.
                            </p>
                        </div>

                        <div>
                            <label class="block font-extrabold text-emerald-900 mb-1 flex items-center justify-between">
                                <span class="flex items-center gap-1"><i class="fas fa-route text-emerald-600"></i> Prosedur & Alur Pelayanan</span>
                                <span class="text-[9px] bg-emerald-700 text-white font-extrabold px-2 py-0.5 rounded shadow-2xs">✨ Rich Text Editor</span>
                            </label>
                            <textarea id="proc_editor" name="procedure" rows="6" x-model="currentService.procedure" 
                                      placeholder="1. Mengajukan surat permohonan ke Loket MPP..." 
                                      class="w-full px-3.5 py-2.5 border border-emerald-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-medium leading-relaxed bg-white text-slate-900"></textarea>
                            <p class="text-[10px] text-slate-500 mt-1 font-medium">
                                <i class="fas fa-info-circle text-emerald-600 me-1"></i>Format poin, nomor, dan tebal teks kini menggunakan Rich Text Editor.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                    <div>
                        <label class="block font-extrabold text-slate-800 text-xs mb-1">
                            <i class="fas fa-file-pdf text-rose-600 me-1"></i> Upload File Dokumen SOP Pelayanan (Maksimal 25MB - Khusus File PDF .pdf)
                        </label>
                        <input type="file" name="pdf_file" accept="application/pdf,.pdf" @change="validatePdfFile($event)" 
                               class="w-full px-3 py-2 border border-slate-300 rounded-xl bg-white text-slate-700 text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-700 file:text-white hover:file:bg-emerald-800 cursor-pointer">
                        
                        <!-- PEMBERITAHUAN GAGAL UPLOAD NON-PDF -->
                        <template x-if="pdfErrorMsg">
                            <div class="mt-2.5 p-3 bg-rose-100 border border-rose-300 text-rose-900 rounded-xl text-xs font-bold flex items-center gap-2 shadow-xs">
                                <i class="fas fa-exclamation-triangle text-rose-600 text-base shrink-0"></i>
                                <span x-text="pdfErrorMsg"></span>
                            </div>
                        </template>

                        <p class="text-[10px] text-slate-500 font-medium mt-1">
                            <i class="fas fa-check-circle me-1 text-emerald-600"></i>File PDF SOP yang diunggah akan otomatis dapat dibaca dan diunduh langsung oleh masyarakat.
                        </p>
                    </div>

                    <div class="pt-2 border-t border-slate-200">
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-link text-slate-500 me-1"></i> Atau Masukkan Link URL Website / External SOP (Opsional)</label>
                        <input type="text" name="external_url" x-model="currentService.external_url" placeholder="https://.../sop.pdf atau /uploads/services/..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono text-xs bg-white">
                        <p class="text-[10px] text-slate-500 mt-1">Kosongkan kolom ini jika ingin tombol di website publik berfungsi membuka **Pop-Up Persyaratan & SOP** di atas.</p>
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold rounded-xl shadow-md transition-colors flex items-center gap-1.5">
                        <i class="fas fa-save"></i> <span x-text="editMode ? 'Simpan Perubahan SOP' : 'Simpan Layanan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Pop-Up Pratinjau SOP Admin -->
    <div x-show="previewSop !== null" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.away="previewSop = null" class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 border border-slate-200 shadow-2xl space-y-6 relative max-h-[90vh] overflow-y-auto text-left">
            <button @click="previewSop = null" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors">
                <i class="fas fa-times"></i>
            </button>

            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl shrink-0 shadow-inner">
                    <i class="fas" :class="previewSop?.icon || 'fa-handshake'"></i>
                </div>
                <div>
                    <span class="text-[10px] font-extrabold text-emerald-700 uppercase tracking-widest block" x-text="previewSop?.category"></span>
                    <h2 class="text-xl font-extrabold text-slate-900" x-text="previewSop?.title"></h2>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-200 text-xs">
                <div>
                    <span class="text-slate-500 block">Biaya Layanan:</span>
                    <strong class="text-emerald-800 font-extrabold text-xs sm:text-sm" x-text="previewSop?.cost"></strong>
                </div>
                <div>
                    <span class="text-slate-500 block">Waktu Proses:</span>
                    <strong class="text-slate-800 font-extrabold text-xs sm:text-sm" x-text="previewSop?.service_time"></strong>
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <span class="text-slate-500 block">Lokasi Pelayanan:</span>
                    <strong class="text-slate-800 font-extrabold text-xs sm:text-sm" x-text="previewSop?.location || 'Loket MPP Kraksaan'"></strong>
                </div>
            </div>

            <div class="space-y-3">
                <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-list-check text-emerald-700"></i> Persyaratan Dokumen
                </h3>
                <div class="prose text-xs text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-200 whitespace-pre-line" x-text="previewSop?.requirements || 'Belum ada rincian persyaratan dokumen.'"></div>
            </div>

            <div class="space-y-3">
                <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-route text-emerald-700"></i> Prosedur & Alur Pelayanan
                </h3>
                <div class="prose text-xs text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-200 whitespace-pre-line" x-text="previewSop?.procedure || 'Belum ada rincian prosedur alur pelayanan.'"></div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex justify-end">
                <button @click="previewSop = null" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs">
                    Tutup Pratinjau
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
