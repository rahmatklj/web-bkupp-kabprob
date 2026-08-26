@extends('admin.layout')

@section('page_title', 'CRUD Banner Hero Slider')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: false, 
    editMode: false, 
    currentSlider: {},
    imageErrorMsg: null,
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
    
    <!-- Header Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h3 class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                <i class="fas fa-images text-orange-600"></i> Daftar Banner Hero Slider
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Ubah judul, subtitle, gambar background, dan urutan banner homepage DKUPP</p>
        </div>
        <button @click="showModal = true; editMode = false; imageErrorMsg = null; currentSlider = { is_active: true, order: 0 }" 
                class="w-full sm:w-auto px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-xs flex items-center justify-center gap-2 transition-all">
            <i class="fas fa-plus"></i> Tambah Banner Baru
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
                <span>Peringatan Gagal Upload Banner Slider:</span>
            </div>
            <ul class="list-disc pl-7 text-[11px] font-semibold">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Sliders List Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto w-full">
        <table class="w-full text-left text-xs min-w-[550px]">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Urutan</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Banner</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Judul Banner</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap text-center">Status</th>
                    <th class="px-3 sm:px-6 py-3.5 text-right whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($sliders as $slider)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-3 sm:px-6 py-3.5 font-bold text-slate-800">
                            <span class="px-2 py-0.5 bg-slate-100 rounded-md text-slate-600 text-[11px] font-mono">#{{ $slider->order }}</span>
                        </td>
                        <td class="px-3 sm:px-6 py-3.5">
                            <img src="{{ $slider->image_url }}" alt="Preview" class="h-10 w-20 object-cover rounded-lg border border-slate-200 shadow-xs">
                        </td>
                        <td class="px-3 sm:px-6 py-3.5">
                            <h4 class="font-bold text-slate-800 text-xs leading-snug line-clamp-2">{{ $slider->title }}</h4>
                            <p class="text-slate-400 text-[11px] mt-0.5 truncate max-w-[200px]">{{ $slider->subtitle }}</p>
                        </td>
                        <td class="px-3 sm:px-6 py-3.5 whitespace-nowrap text-center">
                            <form action="{{ route('admin.sliders.toggle', $slider->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" title="Klik untuk ubah status" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold transition-all duration-200 cursor-pointer hover:scale-105 {{ $slider->is_active ? 'bg-emerald-100 text-emerald-800 border border-emerald-300 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-300 hover:bg-slate-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $slider->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                    {{ $slider->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-3 sm:px-6 py-3.5 text-right whitespace-nowrap space-x-1">
                            <button @click="showModal = true; editMode = true; imageErrorMsg = null; currentSlider = {{ json_encode($slider) }}" 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus banner ini?')">
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

    <!-- Modal Form (Add / Edit) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 my-8">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm" x-text="editMode ? 'Edit Banner Hero Slider' : 'Tambah Banner Slider Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>

            <form :action="editMode ? '/admin/sliders/' + currentSlider.id : '{{ route('admin.sliders.store') }}'" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Utama Banner <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required x-model="currentSlider.title" placeholder="Contoh: Selamat Datang di Portal Resmi" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none font-bold">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Subtitle / Keterangan</label>
                    <input type="text" name="subtitle" x-model="currentSlider.subtitle" placeholder="Contoh: Dinas Koperasi, Usaha Mikro, Perdagangan dan Perindustrian..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                </div>

                <!-- Foto Upload & URL Input -->
                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl space-y-3">
                    <label class="block font-extrabold text-slate-800 text-xs">
                        <i class="fas fa-image text-orange-600 me-1"></i> Foto / Gambar Background Banner
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
                            <input type="text" name="image_url" x-model="currentSlider.image_url" placeholder="https://... atau /uploads/sliders/..." 
                                   class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none font-mono text-xs bg-white">
                        </div>
                    </div>

                    <template x-if="currentSlider.image_url">
                        <div class="mt-2 p-2 bg-white rounded-xl border border-slate-200 inline-flex items-center gap-3">
                            <span class="text-[10px] font-bold text-slate-500">Preview Banner:</span>
                            <img :src="currentSlider.image_url" alt="Preview" class="h-10 w-20 object-cover rounded-lg border border-slate-200">
                        </div>
                    </template>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Teks Tombol CTA</label>
                        <input type="text" name="button_text" x-model="currentSlider.button_text" placeholder="Lapor Bencana / Selengkapnya" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">URL Link Tombol</label>
                        <input type="text" name="button_url" x-model="currentSlider.button_url" placeholder="/kontak" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Urutan Tampil (Order)</label>
                        <input type="number" name="order" x-model="currentSlider.order" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>
                    <div class="flex items-center pt-5">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                            <input type="checkbox" name="is_active" value="1" :checked="currentSlider.is_active" class="w-4 h-4 text-orange-600 rounded">
                            Aktifkan Slide
                        </label>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-md">Simpan Banner</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
