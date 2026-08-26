@extends('admin.layout')

@section('page_title', 'CRUD Dokumen Kinerja (DOKUMEN)')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: false, 
    editMode: false, 
    currentDoc: {}, 
    previewPdfUrl: null, 
    previewPdfTitle: '',
    categoriesList: {{ json_encode($categories) }},
    newMasterInput: '',
    showAddMaster: false,
    pdfErrorMsg: null,
    validatePdfFile(e) {
        this.pdfErrorMsg = null;
        const file = e.target.files[0];
        if (file) {
            const ext = file.name.split('.').pop().toLowerCase();
            if (ext !== 'pdf' && file.type !== 'application/pdf') {
                const msg = '⚠️ GAGAL UPLOAD: Berkas yang Anda pilih berformat .' + ext.toUpperCase() + '! Sistem HANYA menerima berkas PDF (.pdf).';
                this.pdfErrorMsg = msg;
                alert(msg);
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
                this.currentDoc.category = data.category;
                this.newMasterInput = '';
                this.showAddMaster = false;
            }
        } catch (e) {
            console.error(e);
        }
    }
}">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h3 class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                <i class="fas fa-file-pdf text-rose-600"></i> Daftar Dokumen Publik & Kinerja
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Kelola berkas PDF unduhan & baca online di bawah menu DOKUMEN (Perencanaan, Pengukuran, Pelaporan, Evaluasi, & Kategori Kustom)</p>
        </div>
        <button @click="showModal = true; editMode = false; currentDoc = { category: 'Perencanaan Kinerja' }" 
                class="w-full sm:w-auto px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-xs flex items-center justify-center gap-2 transition-all shrink-0">
            <i class="fas fa-file-upload"></i> Upload Dokumen PDF Baru
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
                <span>Peringatan Gagal Upload Dokumen:</span>
            </div>
            <ul class="list-disc pl-7 text-[11px] font-semibold">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Documents Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto w-full">
        <table class="w-full text-left text-xs min-w-[650px]">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Judul Dokumen PDF</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Kategori</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Baca / Unduh File</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Total Unduh</th>
                    <th class="px-3 sm:px-6 py-3.5 text-right whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($documents as $doc)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-file-pdf text-rose-500 text-base shrink-0"></i>
                                <span>{{ $doc->title }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                                {{ $doc->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <!-- Tombol Baca PDF di Web -->
                                <button @click="previewPdfUrl = '{{ $doc->file_url }}'; previewPdfTitle = '{{ addslashes($doc->title) }}'" 
                                        class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-[11px] flex items-center gap-1 shadow-2xs transition-colors">
                                    <i class="fas fa-eye text-xs"></i> Baca PDF
                                </button>
                                
                                <!-- Link File External -->
                                <a href="{{ $doc->file_url }}" target="_blank" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[11px] font-semibold flex items-center gap-1 transition-colors" title="Buka Link File">
                                    <i class="fas fa-external-link-alt text-[10px]"></i> Link
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-bold">
                            {{ $doc->download_count }}x
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <button @click="showModal = true; editMode = true; currentDoc = {{ json_encode($doc) }}" 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Dokumen"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.documents.destroy', $doc->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus dokumen ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Dokumen"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-xs">
                            Belum ada dokumen PDF kinerja yang diunggah.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($documents->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $documents->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form (Add / Edit Document) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <i class="fas fa-file-pdf text-rose-600 text-lg"></i>
                    <h3 class="font-extrabold text-slate-800 text-sm" x-text="editMode ? 'Edit Dokumen Kinerja' : 'Upload Dokumen PDF Baru'"></h3>
                </div>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg"><i class="fas fa-times"></i></button>
            </div>

            <form :action="editMode ? '/admin/documents/' + currentDoc.id : '{{ route('admin.documents.store') }}'" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Dokumen PDF <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required x-model="currentDoc.title" placeholder="Contoh: Rencana Strategis (RENSTRA) DKUPP 2024–2026" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold text-slate-900">
                </div>

                <!-- Kategori Dokumen Kinerja (Bisa Diketik & Pilih Master) -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1 flex items-center justify-between">
                        <span>Kategori Dokumen Kinerja <span class="text-rose-500">*</span></span>
                        <span class="text-[10px] text-emerald-700 font-extrabold uppercase bg-emerald-100/70 px-2 py-0.5 rounded-md">Bisa Diketik & Pilih Master</span>
                    </label>
                    
                    <div class="space-y-2">
                        <!-- Direct Editable Input Field with Master Datalist Autocomplete -->
                        <div class="relative">
                            <input type="text" 
                                   name="category" 
                                   required 
                                   list="master_doc_categories"
                                   x-model="currentDoc.category" 
                                   placeholder="Pilih atau ketik kategori (misal: Perencanaan Kinerja, SAKIP...)" 
                                   class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold text-slate-900 text-xs bg-white shadow-2xs">
                            
                            <datalist id="master_doc_categories">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}"></option>
                                @endforeach
                            </datalist>
                        </div>

                        <!-- Quick Clickable Master Category Pills with + Tambah Master Inline -->
                        <div class="flex flex-wrap items-center gap-1.5 pt-0.5">
                            <span class="text-[10px] text-slate-400 font-bold self-center me-1">Master:</span>
                            <template x-for="quickCat in categoriesList" :key="quickCat">
                                <button type="button" @click="currentDoc.category = quickCat"
                                        :class="currentDoc.category === quickCat ? 'bg-emerald-700 text-white font-extrabold shadow-2xs border-emerald-700' : 'bg-slate-100 text-slate-700 hover:bg-emerald-100 hover:text-emerald-800 border-slate-200'"
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
                                   @keydown.enter.prevent="submitQuickMaster('dokumen')" 
                                   placeholder="Ketik nama master kategori baru..." 
                                   class="w-full px-2.5 py-1.5 border border-emerald-300 rounded-lg text-xs font-bold uppercase focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white">
                            <button type="button" 
                                    @click="submitQuickMaster('dokumen')" 
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

                <div class="space-y-3 p-4 bg-emerald-50/70 border border-emerald-200 rounded-2xl">
                    <div>
                        <label class="block font-extrabold text-emerald-900 mb-1">
                            <i class="fas fa-file-pdf text-rose-600 me-1"></i> Upload File PDF (Maksimal 25MB)
                        </label>
                        <input type="file" name="pdf_file" accept="application/pdf,.pdf" @change="validatePdfFile($event)" 
                               class="w-full px-3 py-2 border border-emerald-300 rounded-xl bg-white text-slate-700 text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-700 file:text-white hover:file:bg-emerald-800 cursor-pointer">
                        
                        <!-- PEMBERITAHUAN GAGAL UPLOAD NON-PDF -->
                        <template x-if="pdfErrorMsg">
                            <div class="mt-2.5 p-3 bg-rose-100 border border-rose-300 text-rose-900 rounded-xl text-xs font-bold flex items-center gap-2 shadow-xs">
                                <i class="fas fa-exclamation-triangle text-rose-600 text-base shrink-0"></i>
                                <span x-text="pdfErrorMsg"></span>
                            </div>
                        </template>

                        <p class="text-[10px] text-emerald-800 font-medium mt-1">
                            <i class="fas fa-check-circle me-1"></i>File PDF yang diunggah dapat dibaca online langsung di website tanpa perlu terdownload terlebih dahulu.
                        </p>
                    </div>
                    
                    <div class="pt-2 border-t border-emerald-200/80">
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-link text-slate-500 me-1"></i> Atau Masukkan URL File PDF External (Opsional)</label>
                        <input type="text" name="file_url" x-model="currentDoc.file_url" placeholder="https://.../file.pdf atau /uploads/documents/..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono text-xs bg-white">
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold rounded-xl shadow-md transition-colors flex items-center gap-1.5">
                        <i class="fas fa-save"></i> <span x-text="editMode ? 'Simpan Perubahan' : 'Upload Dokumen PDF'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Pratinjau PDF Reader untuk Admin -->
    <div x-show="previewPdfUrl !== null" 
         x-cloak 
         class="fixed inset-0 z-50 p-3 sm:p-6 flex items-center justify-center bg-slate-950/80 backdrop-blur-xs">
        
        <div @click.away="previewPdfUrl = null" class="bg-white rounded-3xl max-w-5xl w-full h-[90vh] border border-slate-200 shadow-2xl flex flex-col overflow-hidden relative">
            
            <!-- Modal Header -->
            <div class="p-4 bg-slate-900 text-white flex items-center justify-between gap-4 border-b border-slate-800 shrink-0">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-9 h-9 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center text-lg shrink-0">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="truncate">
                        <span class="text-[10px] font-extrabold text-emerald-400 uppercase tracking-widest block">Pratinjau Dokumen Admin</span>
                        <h3 class="font-extrabold text-xs sm:text-sm text-white truncate" x-text="previewPdfTitle"></h3>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a :href="previewPdfUrl" target="_blank" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors">
                        <i class="fas fa-external-link-alt text-xs"></i> <span>Buka Tab Baru</span>
                    </a>
                    <button @click="previewPdfUrl = null" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 flex items-center justify-center transition-colors">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body (PDF Viewer) -->
            <div class="flex-grow bg-slate-950 p-2 sm:p-3 relative overflow-hidden flex flex-col justify-center items-center">
                <template x-if="previewPdfUrl !== null">
                    <iframe :src="previewPdfUrl" 
                            class="w-full h-full rounded-2xl border border-slate-800 bg-white shadow-2xl"
                            frameborder="0"
                            allowfullscreen>
                    </iframe>
                </template>
            </div>

        </div>
    </div>

</div>
@endsection
