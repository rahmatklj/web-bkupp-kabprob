@extends('admin.layout')

@section('page_title', 'Pesan Masuk & Pengaduan Masyarakat')

@section('content')
<div class="space-y-6">
    
    @if(session('success'))
        <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl flex items-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Form Edit Link Website Pengaduan SP4N LAPOR! (Tergabung di Menu Laporan Masuk) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-bullhorn text-rose-600 text-xl"></i>
                    <h3 class="font-extrabold text-slate-900 text-base">Pengaturan Target Link Pengaduan SP4N LAPOR!</h3>
                </div>
                <p class="text-xs text-slate-500 mt-1">Kelola link website resmi portal pengaduan SP4N LAPOR! Republik Indonesia.</p>
            </div>
            @if(!empty($laporSp4nUrl))
                <a href="{{ $laporSp4nUrl }}" target="_blank" rel="noopener noreferrer" 
                   class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow transition-colors flex items-center gap-2 shrink-0">
                    <i class="fas fa-external-link-alt"></i> Uji Coba Link SP4N LAPOR!
                </a>
            @endif
        </div>

        <form action="{{ route('admin.messages.lapor-sp4n') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf

            <div class="p-4 bg-rose-50 rounded-2xl border border-rose-200 space-y-2">
                <label class="font-extrabold text-rose-900 block text-xs">
                    <i class="fas fa-link text-rose-600 me-1"></i> Target Link Website Pengaduan SP4N LAPOR! <span class="text-rose-500">*</span>
                </label>
                <input type="url" name="lapor_sp4n_url" value="{{ old('lapor_sp4n_url', $laporSp4nUrl ?? 'https://www.lapor.go.id/') }}" required
                       placeholder="https://www.lapor.go.id/"
                       class="w-full px-4 py-3 rounded-xl border border-rose-300 focus:ring-2 focus:ring-rose-600 focus:outline-none font-bold text-slate-900 text-sm bg-white shadow-2xs">
                <p class="text-[11px] text-rose-700 font-medium leading-relaxed">
                    <i class="fas fa-info-circle me-1"></i>Link ini digunakan langsung saat pengunjung mengklik kartu <strong>SP4N LAPOR!</strong> di beranda.
                </p>
            </div>

            <!-- Upload File Logo & URL Logo SP4N LAPOR! -->
            <div class="space-y-3 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                <label class="font-extrabold text-slate-800 block text-xs">
                    <i class="fas fa-image text-rose-600 me-1"></i> Logo Resmi Portal SP4N LAPOR!
                </label>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-upload text-rose-600 me-1"></i> Upload File Logo (Dari HP / Komputer)</label>
                        <input type="file" name="lapor_sp4n_logo_file" accept="image/*" class="w-full px-3 py-2 border border-slate-300 rounded-xl bg-white text-slate-700 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-rose-600 file:text-white hover:file:bg-rose-700 cursor-pointer">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1"><i class="fas fa-link text-slate-500 me-1"></i> Atau Masukkan URL Gambar Logo SP4N LAPOR!</label>
                        <input type="text" name="lapor_sp4n_logo" value="{{ old('lapor_sp4n_logo', $laporSp4nLogo ?? '') }}" placeholder="https://... atau /uploads/settings/..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-mono text-xs focus:ring-2 focus:ring-rose-600 focus:outline-none bg-white">
                    </div>
                </div>

                @if(!empty($laporSp4nLogo))
                    <div class="mt-2 p-3 bg-white rounded-xl border border-slate-200 inline-flex items-center gap-3">
                        <span class="text-[11px] font-bold text-slate-500">Preview Logo Saat Ini:</span>
                        <img src="{{ $laporSp4nLogo }}" alt="Logo SP4N LAPOR!" class="h-10 w-auto max-w-xs object-contain rounded border border-slate-200 bg-slate-50 p-1">
                    </div>
                @endif
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-rose-700 hover:bg-rose-800 text-white rounded-xl font-extrabold text-xs shadow-md transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Link & Logo SP4N LAPOR!
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
        <h3 class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight">Daftar Pesan & Pengaduan Masuk</h3>
        <p class="text-xs text-slate-500 mt-0.5">Pesan, pertanyaan, dan pengaduan layanan masyarakat melalui Kontak DKUPP Kabupaten Probolinggo</p>
    </div>

    <!-- Messages Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto w-full">
        <table class="w-full text-left text-xs min-w-[550px]">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Pelapor</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Kontak Email</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Isi Pesan / Pengaduan</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Status Pesan</th>
                    <th class="px-3 sm:px-6 py-3.5 text-right whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($messages as $msg)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-3 sm:px-6 py-3.5 font-bold text-slate-800 whitespace-nowrap">
                            {{ $msg->name }}
                        </td>
                        <td class="px-3 sm:px-6 py-3.5 font-mono text-[11px] text-slate-600 whitespace-nowrap">
                            {{ $msg->email }}
                        </td>
                        <td class="px-3 sm:px-6 py-3.5 max-w-xs">
                            <h4 class="font-bold text-slate-800 text-xs leading-snug line-clamp-1">{{ $msg->subject ?? 'Pesan / Pengaduan' }}</h4>
                            <p class="text-slate-500 text-[11px] line-clamp-2 mt-0.5 leading-normal">{{ $msg->message }}</p>
                        </td>
                        <td class="px-3 sm:px-6 py-3.5 whitespace-nowrap">
                            <form action="{{ route('admin.messages.status', $msg->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()" 
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold border border-slate-200 focus:outline-none cursor-pointer
                                               {{ $msg->status === 'selesai' ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : ($msg->status === 'diproses' ? 'bg-amber-100 text-amber-800 border-amber-300' : 'bg-rose-100 text-rose-800 border-rose-300') }}">
                                    <option value="baru" {{ $msg->status === 'baru' ? 'selected' : '' }}>BARU</option>
                                    <option value="diproses" {{ $msg->status === 'diproses' ? 'selected' : '' }}>DIPROSES</option>
                                    <option value="selesai" {{ $msg->status === 'selesai' ? 'selected' : '' }}>SELESAI</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-3 sm:px-6 py-3.5 text-right whitespace-nowrap">
                            <form action="{{ route('admin.messages.destroy', $msg->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pesan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400 font-medium">
                            <i class="fas fa-inbox text-3xl mb-2 block text-slate-300"></i>
                            Belum ada pesan masuk atau pengaduan dari masyarakat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($messages->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $messages->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
