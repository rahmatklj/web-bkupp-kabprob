@extends('admin.layout')

@section('page_title', 'CRUD Berita & Informasi Kebencanaan')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, currentNews: {} }">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h3 class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight">Daftar Berita & Pengumuman</h3>
            <p class="text-xs text-slate-500 mt-0.5">Kelola artikel berita publik yang tampil di homepage dan portal berita DKUPP</p>
        </div>
        <button @click="showModal = true; editMode = false; currentNews = { category: 'Berita Utama', published_at: '{{ date('Y-m-d') }}' }" 
                class="w-full sm:w-auto px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-xs flex items-center justify-center gap-2 transition-all">
            <i class="fas fa-plus"></i> Publish Berita Baru
        </button>
    </div>

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
                            <button @click="showModal = true; editMode = true; currentNews = {{ json_encode($news) }}" 
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
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm" x-text="editMode ? 'Edit Berita & Artikel' : 'Publish Berita Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>

            <form :action="editMode ? '/admin/news/' + currentNews.id : '{{ route('admin.news.store') }}'" method="POST" class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Berita</label>
                    <input type="text" name="title" required x-model="currentNews.title" placeholder="Judul berita terbaru..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                        <select name="category" x-model="currentNews.category" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                            <option value="Peringatan Dini">Peringatan Dini</option>
                            <option value="Tanggap Darurat">Tanggap Darurat</option>
                            <option value="Mitigasi Bencana">Mitigasi Bencana</option>
                            <option value="Kegiatan Posko">Kegiatan Posko</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Publish</label>
                        <input type="date" name="published_at" x-model="currentNews.published_at" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Thumbnail Gambar</label>
                    <input type="text" name="image_url" x-model="currentNews.image_url" placeholder="https://..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Isi Berita (Content)</label>
                    <textarea name="content" rows="4" x-model="currentNews.content" placeholder="Tuliskan berita lengkap..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none"></textarea>
                </div>

                <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-md">Simpan Artikel</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
