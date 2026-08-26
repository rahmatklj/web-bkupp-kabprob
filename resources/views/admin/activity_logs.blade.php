@extends('admin.layout')

@section('page_title', 'Log Aktivitas Sistem (Activity Audit Trail)')

@section('content')
<div class="space-y-6">

    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <div>
            <h3 class="font-extrabold text-slate-900 text-lg flex items-center gap-2">
                <i class="fas fa-history text-indigo-600 text-xl"></i>
                <span>Catatan Log Aktivitas Sistem (Audit Trail)</span>
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
                Memantau seluruh riwayat aktivitas admin & staf, mulai dari login, pembuatan data, pengeditan, hingga penghapusan.
            </p>
        </div>

        @if(auth()->user()->isSuperAdmin())
            <form action="{{ route('admin.activity-logs.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membersihkan seluruh catatan log aktivitas?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-xl border border-rose-200 transition-colors flex items-center gap-1.5 shrink-0">
                    <i class="fas fa-trash-alt"></i> Bersihkan Log
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl flex items-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('admin.activity-logs') }}" class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama user, modul, atau kata kunci..." 
                       class="px-3.5 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none w-full sm:w-72 bg-slate-50">

                <select name="action" class="px-3.5 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-slate-50 font-bold">
                    <option value="">-- Semua Jenis Aksi --</option>
                    <option value="CREATE" {{ request('action') == 'CREATE' ? 'selected' : '' }}>CREATE (Tambah)</option>
                    <option value="UPDATE" {{ request('action') == 'UPDATE' ? 'selected' : '' }}>UPDATE (Edit)</option>
                    <option value="DELETE" {{ request('action') == 'DELETE' ? 'selected' : '' }}>DELETE (Hapus)</option>
                    <option value="LOGIN" {{ request('action') == 'LOGIN' ? 'selected' : '' }}>LOGIN (Masuk)</option>
                    <option value="LOGOUT" {{ request('action') == 'LOGOUT' ? 'selected' : '' }}>LOGOUT (Keluar)</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-xl transition-colors">
                    <i class="fas fa-search me-1"></i> Filter
                </button>

                @if(request()->hasAny(['q', 'action']))
                    <a href="{{ route('admin.activity-logs') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl">
                        Reset
                    </a>
                @endif
            </div>

            <span class="text-slate-400 font-mono text-[11px] shrink-0">
                Total Logs: {{ $logs->total() }} record
            </span>
        </form>
    </div>

    <!-- Activity Logs Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-extrabold border-b border-slate-200 uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-4 py-3.5">Waktu</th>
                        <th class="px-4 py-3.5">Pengguna / Admin</th>
                        <th class="px-4 py-3.5 text-center">Aksi</th>
                        <th class="px-4 py-3.5">Modul</th>
                        <th class="px-4 py-3.5">Deskripsi Aktivitas</th>
                        <th class="px-4 py-3.5 text-center">Alamat IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3.5 whitespace-nowrap text-slate-500 font-mono text-[11px]">
                                <div>{{ $log->created_at ? $log->created_at->format('d M Y') : '-' }}</div>
                                <div class="text-[10px] text-slate-400 font-bold">{{ $log->created_at ? $log->created_at->format('H:i:s') : '-' }} WIB</div>
                            </td>

                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="font-extrabold text-slate-900">{{ $log->user_name }}</div>
                                <span class="inline-block text-[9px] px-2 py-0.5 rounded-md font-bold uppercase {{ $log->user_role == 'Super Admin' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $log->user_role }}
                                </span>
                            </td>

                            <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                @php
                                    $actionClasses = [
                                        'CREATE' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'UPDATE' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'DELETE' => 'bg-rose-100 text-rose-800 border-rose-200',
                                        'LOGIN' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'LOGOUT' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    ][$log->action] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black border uppercase tracking-wider {{ $actionClasses }}">
                                    {{ $log->action }}
                                </span>
                            </td>

                            <td class="px-4 py-3.5 whitespace-nowrap font-bold text-slate-800">
                                <span class="px-2 py-1 bg-slate-100 rounded-lg text-slate-700 border border-slate-200">
                                    {{ $log->module }}
                                </span>
                            </td>

                            <td class="px-4 py-3.5 leading-relaxed text-slate-800">
                                {{ $log->description }}
                            </td>

                            <td class="px-4 py-3.5 whitespace-nowrap text-center font-mono text-[10px] text-slate-400">
                                {{ $log->ip_address ?: '127.0.0.1' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-slate-400 text-xs">
                                Belum ada data log aktivitas yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 bg-slate-50 border-t border-slate-200">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
