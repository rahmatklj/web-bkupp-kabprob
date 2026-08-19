@extends('admin.layout')

@section('page_title', 'CRUD Dokumen Kinerja (DOKUMEN)')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, currentDoc: {} }">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h3 class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight">Daftar Dokumen Publik & Kinerja</h3>
            <p class="text-xs text-slate-500 mt-0.5">Kelola berkas unduhan di bawah menu DOKUMEN (Perencanaan, Pengukuran, Pelaporan, Evaluasi)</p>
        </div>
        <button @click="showModal = true; editMode = false; currentDoc = { category: 'Perencanaan Kinerja' }" 
                class="w-full sm:w-auto px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-xs flex items-center justify-center gap-2 transition-all">
            <i class="fas fa-plus"></i> Upload Dokumen Baru
        </button>
    </div>

    <!-- Documents Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto w-full">
        <table class="w-full text-left text-xs min-w-[600px]">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Judul Dokumen</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Kategori</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Link File / Unduh</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Total Unduh</th>
                    <th class="px-3 sm:px-6 py-3.5 text-right whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($documents as $doc)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ $doc->title }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-blue-100 text-blue-800">
                                {{ $doc->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono text-[11px]">
                            <a href="{{ $doc->file_url }}" target="_blank" class="text-orange-600 hover:underline flex items-center gap-1 font-semibold">
                                <i class="fas fa-external-link-alt text-[10px]"></i> Link File
                            </a>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-bold">
                            {{ $doc->download_count }}x
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <button @click="showModal = true; editMode = true; currentDoc = {{ json_encode($doc) }}" 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.documents.destroy', $doc->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus dokumen ini?')">
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
            {{ $documents->links() }}
        </div>
    </div>

    <!-- Modal Form (Add / Edit Document) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm" x-text="editMode ? 'Edit Dokumen Kinerja' : 'Tambah Dokumen Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>

            <form :action="editMode ? '/admin/documents/' + currentDoc.id : '{{ route('admin.documents.store') }}'" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Dokumen</label>
                    <input type="text" name="title" required x-model="currentDoc.title" placeholder="Contoh: Rencana Kinerja Tahun 2026" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kategori Dokumen</label>
                    <select name="category" x-model="currentDoc.category" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        <option value="Perencanaan Kinerja">Perencanaan Kinerja</option>
                        <option value="Pengukuran Kinerja">Pengukuran Kinerja</option>
                        <option value="Pelaporan Kinerja">Pelaporan Kinerja</option>
                        <option value="Evaluasi Kinerja">Evaluasi Kinerja</option>
                    </select>
                </div>

                <div class="space-y-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-upload text-emerald-600 me-1"></i> Upload File PDF (Dari HP / Komputer)</label>
                        <input type="file" name="pdf_file" accept=".pdf" class="w-full px-3 py-2 border border-slate-300 rounded-xl bg-white text-slate-700 text-xs focus:ring-2 focus:ring-orange-500 focus:outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-link text-slate-500 me-1"></i> Atau Masukkan URL File PDF External</label>
                        <input type="text" name="file_url" x-model="currentDoc.file_url" placeholder="https://.../file.pdf atau /uploads/documents/..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none font-mono text-xs">
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-md">Simpan Dokumen</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
