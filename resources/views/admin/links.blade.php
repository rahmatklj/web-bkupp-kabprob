@extends('admin.layout')

@section('page_title', 'Kelola Tautan Terkait Logo Instansi')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, currentLink: {} }">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h3 class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight">Daftar Tautan Terkait Logo Instansi</h3>
            <p class="text-xs text-slate-500 mt-0.5">Atur logo & link instansi mitra (Kemenkop UKM, Kemendag, Pemkab Probolinggo, Diskominfo)</p>
        </div>
        <button @click="showModal = true; editMode = false; currentLink = { is_active: true, order: 0 }" 
                class="w-full sm:w-auto px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-xs flex items-center justify-center gap-2 transition-all">
            <i class="fas fa-plus"></i> Tambah Tautan Baru
        </button>
    </div>

    <!-- Links Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto w-full">
        <table class="w-full text-left text-xs min-w-[550px]">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Urutan</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Logo Instansi</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Nama Instansi / Mitra</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Target URL Link</th>
                    <th class="px-3 sm:px-6 py-3.5 text-right whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($links as $link)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-3 sm:px-6 py-3.5 font-bold text-slate-800">
                            <span class="px-2 py-0.5 bg-slate-100 rounded-md text-slate-600 text-[11px] font-mono">#{{ $link->order }}</span>
                        </td>
                        <td class="px-3 sm:px-6 py-3.5">
                            <img src="{{ $link->image_url }}" alt="Logo" class="h-8 w-auto max-w-[90px] object-contain">
                        </td>
                        <td class="px-3 sm:px-6 py-3.5 font-bold text-slate-800">
                            {{ $link->title }}
                        </td>
                        <td class="px-3 sm:px-6 py-3.5 font-mono text-[11px] text-slate-500 break-all">
                            <a href="{{ $link->url }}" target="_blank" class="text-orange-600 hover:underline flex items-center gap-1">
                                <i class="fas fa-external-link-alt text-[10px]"></i> {{ $link->url }}
                            </a>
                        </td>
                        <td class="px-3 sm:px-6 py-3.5 text-right whitespace-nowrap space-x-1">
                            <button @click="showModal = true; editMode = true; currentLink = {{ json_encode($link) }}" 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.links.destroy', $link->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tautan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Form (Add / Edit Link) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm" x-text="editMode ? 'Edit Tautan Terkait' : 'Tambah Tautan Terkait Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>

            <form :action="editMode ? '/admin/links/' + currentLink.id : '{{ route('admin.links.store') }}'" method="POST" class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Instansi / Mitra</label>
                    <input type="text" name="title" required x-model="currentLink.title" placeholder="Contoh: BMKG Indonesia" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Logo Gambar</label>
                    <input type="text" name="image_url" required x-model="currentLink.image_url" placeholder="https://..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Target Website URL</label>
                    <input type="text" name="url" required x-model="currentLink.url" placeholder="https://bmkg.go.id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Urutan (Order)</label>
                        <input type="number" name="order" x-model="currentLink.order" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>
                    <div class="flex items-center pt-5">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                            <input type="checkbox" name="is_active" value="1" :checked="currentLink.is_active" class="w-4 h-4 text-orange-600 rounded">
                            Aktifkan Link
                        </label>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-md">Simpan Link</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
