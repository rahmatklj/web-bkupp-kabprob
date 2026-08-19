@extends('admin.layout')

@section('page_title', 'Struktur Organisasi')

@section('content')
<div x-data="{ 
    showModal: false, 
    editMode: false, 
    currentMember: { id: null, name: '', position: '', type: 'personel', parent_id: '', photo: '', order: 1, is_active: true } 
}" class="space-y-6">

    <!-- Tab Navigation Bar -->
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3">
        <a href="{{ route('admin.pages') }}" 
           class="px-4 py-2 rounded-xl font-bold text-xs transition-all flex items-center gap-2 bg-white text-slate-600 hover:bg-slate-100 border border-slate-200">
            <i class="fas fa-file-alt text-emerald-600"></i> Halaman Profil & Layanan
        </a>
        <a href="{{ route('admin.org-members') }}" 
           class="px-4 py-2 rounded-xl font-extrabold text-xs transition-all flex items-center gap-2 bg-emerald-700 text-white shadow-sm">
            <i class="fas fa-sitemap"></i> Bagan Struktur Organisasi
        </a>
    </div>

    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <div>
            <h3 class="font-extrabold text-slate-900 text-lg sm:text-xl">Struktur Organisasi</h3>
            <p class="text-xs text-slate-500">Kelola hierarki pejabat, nama, foto avatar, dan bagan struktur organisasi DKUPP.</p>
        </div>
        <button @click="editMode = false; currentMember = { id: null, name: '', position: '', type: 'personel', parent_id: '', photo: '', order: {{ count($members) + 1 }}, is_active: true }; showModal = true" 
                class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-2 shrink-0">
            <i class="fas fa-plus"></i> Tambah Anggota
        </button>
    </div>

    <!-- Alert Flash -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto w-full min-w-[700px]">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-extrabold uppercase tracking-wider border-b border-slate-200 text-[10px]">
                    <tr>
                        <th class="p-4">Nama & Jabatan</th>
                        <th class="p-4">Tipe</th>
                        <th class="p-4">Atasan (Parent)</th>
                        <th class="p-4 text-center">Urutan</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($members as $m)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    @if($m->photo)
                                        <img src="{{ $m->photo }}" alt="{{ $m->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-slate-200 shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-sm shrink-0 border border-slate-200">
                                            <i class="fas {{ $m->type == 'kelompok_fungsional' ? 'fa-users' : 'fa-user' }}"></i>
                                        </div>
                                    @endif
                                    <div class="space-y-0.5">
                                        <h4 class="font-extrabold text-slate-900 text-xs sm:text-sm">{{ $m->name ?: '-' }}</h4>
                                        <span class="text-[10px] text-slate-500 font-bold uppercase block tracking-wider">{{ $m->position }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                @if($m->type == 'kelompok_fungsional')
                                    <span class="px-2.5 py-1 bg-purple-50 text-purple-700 border border-purple-100 rounded-lg text-[10px] font-extrabold inline-block">
                                        Kelompok Fungsional
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded-lg text-[10px] font-extrabold inline-block">
                                        Personel
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 whitespace-nowrap text-slate-600">
                                @if($m->parent)
                                    <span class="font-bold text-slate-700 uppercase text-[11px]">{{ $m->parent->position }}</span>
                                @else
                                    <span class="text-slate-400 italic">Tidak Ada (Root)</span>
                                @endif
                            </td>
                            <td class="p-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 font-mono font-bold rounded-md text-xs">
                                    {{ $m->order }}
                                </span>
                            </td>
                            <td class="p-4 text-center whitespace-nowrap">
                                <form action="{{ route('admin.org-members.toggle', $m->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" 
                                            class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase transition-all flex items-center justify-center gap-1 mx-auto {{ $m->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-rose-100 text-rose-800 hover:bg-rose-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $m->is_active ? 'bg-emerald-600' : 'bg-rose-600' }}"></span>
                                        <span>{{ $m->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </button>
                                </form>
                            </td>
                            <td class="p-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button @click="editMode = true; currentMember = { id: {{ $m->id }}, name: '{{ addslashes($m->name) }}', position: '{{ addslashes($m->position) }}', type: '{{ $m->type }}', parent_id: '{{ $m->parent_id }}', photo: '{{ addslashes($m->photo) }}', order: {{ $m->order }}, is_active: {{ $m->is_active ? 'true' : 'false' }} }; showModal = true" 
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-colors" title="Edit Anggota">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.org-members.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus anggota struktur ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition-colors" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 italic">
                                Belum ada anggota struktur organisasi. Klik "Tambah Anggota" untuk menambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form (Add / Edit Member) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
        <div @click.away="showModal = false" class="bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-6 max-w-lg w-full max-h-[90vh] overflow-y-auto space-y-4 shadow-2xl my-auto">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3 sticky top-0 bg-white z-10">
                <h3 class="font-extrabold text-slate-900 text-base" x-text="editMode ? 'Edit Anggota Struktur' : 'Tambah Anggota Struktur Baru'"></h3>
                <button @click="showModal = false" class="p-1 text-slate-400 hover:text-slate-600 rounded-lg"><i class="fas fa-times text-base"></i></button>
            </div>

            <form :action="editMode ? '/admin/org-members/' + currentMember.id : '{{ route('admin.org-members.store') }}'" method="POST" class="space-y-3.5 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Pejabat / Pegawai</label>
                    <input type="text" name="name" x-model="currentMember.name" placeholder="Contoh: Drs. H. Taufik Alami, M.Si (Isi '-' jika kelompok fungsional)" 
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Jabatan <span class="text-rose-500">*</span></label>
                    <input type="text" name="position" required x-model="currentMember.position" placeholder="Contoh: KEPALA DINAS / SEKRETARIS / KABID KOPERASI" 
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-extrabold text-slate-900 uppercase">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tipe Anggota</label>
                        <select name="type" x-model="currentMember.type" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-semibold text-slate-800">
                            <option value="personel">Personel (Pejabat)</option>
                            <option value="kelompok_fungsional">Kelompok Fungsional</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Atasan (Parent Node)</label>
                        <select name="parent_id" x-model="currentMember.parent_id" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-semibold text-slate-800">
                            <option value="">Tidak Ada (Root - Kepala)</option>
                            @foreach($members as $parentOpt)
                                <option value="{{ $parentOpt->id }}">{{ $parentOpt->position }} ({{ $parentOpt->name ?: 'Fungsional' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Foto Pejabat (Avatar)</label>
                    <input type="text" name="photo" x-model="currentMember.photo" placeholder="https://... atau /images/pejabat.jpg" 
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-medium">
                    <p class="text-[10px] text-slate-400 mt-1">Masukkan URL foto atau gambar wajah pejabat. Foto ini akan tampil pada bagan struktur.</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Urutan Display</label>
                        <input type="number" name="order" x-model="currentMember.order" min="1" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold">
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" x-model="currentMember.is_active" class="w-4 h-4 text-emerald-600 rounded">
                            <span class="font-bold text-slate-700">Aktifkan Anggota</span>
                        </label>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2 sticky bottom-0 bg-white pb-1">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold shadow-md transition-colors" x-text="editMode ? 'Simpan Perubahan' : 'Tambah Anggota'"></button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
