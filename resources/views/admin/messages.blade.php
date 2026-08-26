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
