@extends('admin.layout')

@section('page_title', 'CRUD Pengguna & Hak Akses (Users & Roles)')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, currentUser: {}, showPassword: false, passwordInput: '' }">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h3 class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight">Daftar Akun Pengguna System</h3>
            <p class="text-xs text-slate-500 mt-0.5">Super Admin memiliki akses penuh, sedangkan Anggota Staf mengelola Dokumen & Berita</p>
        </div>
        <button @click="showModal = true; editMode = false; showPassword = false; passwordInput = ''; currentUser = { role: 'anggota' }" 
                class="w-full sm:w-auto px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs flex items-center justify-center gap-2 transition-all cursor-pointer">
            <i class="fas fa-user-plus"></i> Tambah Pengguna Baru
        </button>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto w-full">
        <table class="w-full text-left text-xs min-w-[650px]">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Nama User</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Username</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Email Administrator</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">No. WhatsApp</th>
                    <th class="px-3 sm:px-6 py-3.5 whitespace-nowrap">Role Akses</th>
                    <th class="px-3 sm:px-6 py-3.5 text-right whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($users as $user)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full {{ $user->isSuperAdmin() ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }} font-bold text-xs flex items-center justify-center shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span>{{ $user->name }}</span>
                        </td>
                        <td class="px-6 py-4 font-mono text-[11px] text-slate-600">
                            {{ $user->username ?? '-' }}
                        </td>
                        <td class="px-6 py-4 font-mono text-[11px] text-slate-600">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 font-mono text-[11px] text-slate-600">
                            @if($user->phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" class="text-emerald-600 font-bold hover:underline flex items-center gap-1">
                                    <i class="fab fa-whatsapp"></i> {{ $user->phone }}
                                </a>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ $user->isSuperAdmin() ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $user->isSuperAdmin() ? 'Super Admin' : 'Anggota Staf' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <button @click="showModal = true; editMode = true; showPassword = false; passwordInput = ''; currentUser = {{ json_encode($user) }}" 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition cursor-pointer" title="Edit Akun Pengguna">
                                <i class="fas fa-edit"></i>
                            </button>
                            @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer" title="Hapus Pengguna">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Form (Edit Akun Pengguna - Persis Foto ke-2) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-5 border border-slate-100 my-auto">
            
            <!-- Modal Header -->
            <div class="flex items-start justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-base shrink-0 shadow-2xs">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base tracking-tight" x-text="editMode ? 'Edit Akun Pengguna' : 'Tambah Akun Pengguna Baru'"></h3>
                        <p class="text-[11px] text-slate-500 font-medium">Atur profil, WhatsApp, Kode Referral, dan kata sandi pengguna</p>
                    </div>
                </div>
                <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-700 w-8 h-8 rounded-full hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer">
                    <i class="fas fa-times text-base"></i>
                </button>
            </div>

            <form :action="editMode ? '/admin/users/' + currentUser.id : '{{ route('admin.users.store') }}'" method="POST" class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <!-- Field 1: Nama Lengkap Pengguna -->
                <div class="space-y-1">
                    <label class="block font-bold text-slate-800 text-xs">Nama Lengkap Pengguna <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required x-model="currentUser.name" placeholder="Sukma Anggota Staf" 
                           class="w-full px-3.5 py-2.5 bg-slate-50/80 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none font-medium text-slate-800 text-xs transition-all shadow-2xs">
                </div>

                <!-- Field 2: Grid Username Login & Role Hak Akses -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="space-y-1">
                        <label class="block font-bold text-slate-800 text-xs">Username Login</label>
                        <input type="text" name="username" x-model="currentUser.username" placeholder="sukma" 
                               class="w-full px-3.5 py-2.5 bg-slate-50/80 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none font-medium text-slate-800 text-xs transition-all shadow-2xs">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-bold text-slate-800 text-xs">Role Hak Akses <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600">
                                <i class="fas fa-user-shield text-xs"></i>
                            </div>
                            <select name="role" x-model="currentUser.role" 
                                    class="w-full pl-8 pr-8 py-2.5 bg-slate-50/80 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none font-bold text-slate-800 text-xs transition-all shadow-2xs cursor-pointer">
                                <option value="anggota">Anggota Staf</option>
                                <option value="super_admin">Super Admin (Ubah Semua)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Field 3: Email Resmi Administrator -->
                <div class="space-y-1">
                    <div class="flex justify-between items-center">
                        <label class="font-bold text-slate-800 text-xs">Email Resmi Administrator <span class="text-rose-500">*</span></label>
                        <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-1" x-show="currentUser.email && currentUser.email.endsWith('@gmail.com')">
                            <i class="fas fa-check text-[9px]"></i> Berakhiran @gmail.com
                        </span>
                    </div>
                    <input type="email" name="email" required x-model="currentUser.email" placeholder="sukma@gmail.com" 
                           class="w-full px-3.5 py-2.5 bg-slate-50/80 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none font-medium text-slate-800 text-xs transition-all shadow-2xs">
                    <p class="text-[10px] text-blue-600 font-medium flex items-center gap-1 pt-0.5">
                        <i class="fas fa-info-circle text-xs"></i> Email wajib berakhiran <strong class="font-extrabold text-blue-700">@gmail.com</strong>
                    </p>
                </div>

                <!-- Field 4: Grid No. WhatsApp & Kode Referral -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="space-y-1">
                        <div class="flex justify-between items-center">
                            <label class="font-bold text-slate-800 text-[11px] flex items-center gap-1">
                                <i class="fab fa-whatsapp text-emerald-600"></i> No. WhatsApp (Validasi 1)
                            </label>
                            <span class="text-[9px] font-bold text-emerald-600" x-show="currentUser.phone && currentUser.phone.length >= 10">
                                ✓ Valid (Depan 0, 11-13 Digit)
                            </span>
                        </div>
                        <input type="text" name="phone" x-model="currentUser.phone" placeholder="089876543210" 
                               class="w-full px-3 py-2 bg-slate-50/80 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none font-medium text-slate-800 text-xs transition-all shadow-2xs">
                        <p class="text-[9px] text-slate-500 flex items-center gap-1">
                            <i class="fas fa-info-circle text-emerald-600 text-[10px]"></i> Diawali <span class="font-bold text-slate-700">0</span> & panjang 11-13 digit angka
                        </p>
                    </div>

                    <div class="space-y-1">
                        <div class="flex justify-between items-center">
                            <label class="font-bold text-slate-800 text-[11px] flex items-center gap-1">
                                <i class="fas fa-key text-blue-600"></i> Kode Referral (Validasi 2)
                            </label>
                            <span class="text-[9px] font-bold text-emerald-600" x-show="currentUser.referral_code">
                                ✓ Valid (3 Huruf + 3 Angka)
                            </span>
                        </div>
                        <input type="text" name="referral_code" x-model="currentUser.referral_code" placeholder="SUK202" 
                               class="w-full px-3 py-2 bg-slate-50/80 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none font-bold text-slate-800 text-xs transition-all shadow-2xs">
                        <p class="text-[9px] text-slate-500 flex items-center gap-1">
                            <i class="fas fa-info-circle text-blue-600 text-[10px]"></i> Tepat 3 huruf & 3 angka (6 karakter, misal: <strong class="text-blue-600">ADI123</strong>)
                        </p>
                    </div>
                </div>

                <!-- Field 5: Password Keamanan -->
                <div class="space-y-1">
                    <label class="block font-bold text-slate-800 text-xs mb-1">
                        Password Keamanan <span class="text-slate-400 font-normal text-[10px]">(Kosongkan jika tidak diubah)</span>
                    </label>
                    <div class="relative flex items-center">
                        <input :type="showPassword ? 'text' : 'password'" name="password" x-model="passwordInput" :required="!editMode" placeholder="Contoh: Dishub#2026!" 
                               class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50/80 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none font-medium text-slate-800 text-xs transition-all shadow-2xs">
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-3 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer" title="Lihat / Sembunyikan Password">
                            <i class="fas text-sm" :class="showPassword ? 'fa-eye-slash text-blue-600' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <!-- Field 6: Interactive Password Combination Requirements Box (Gmail Style) -->
                <div class="p-3.5 bg-slate-50/90 border border-slate-200/80 rounded-2xl space-y-2 text-slate-700">
                    <h5 class="text-[10px] font-extrabold text-slate-700 tracking-wider uppercase">SYARAT KOMBINASI PASSWORD RUMIT & AMAN (GMAIL STYLE):</h5>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 text-[11px] font-medium">
                        <div class="flex items-center gap-1.5" :class="passwordInput && passwordInput.length >= 8 ? 'text-emerald-700 font-bold' : 'text-slate-500'">
                            <i class="far" :class="passwordInput && passwordInput.length >= 8 ? 'fa-check-circle text-emerald-600' : 'fa-circle text-slate-300'"></i> 
                            <span>Min. 8 Karakter</span>
                        </div>
                        <div class="flex items-center gap-1.5" :class="passwordInput && /[A-Z]/.test(passwordInput) ? 'text-emerald-700 font-bold' : 'text-slate-500'">
                            <i class="far" :class="passwordInput && /[A-Z]/.test(passwordInput) ? 'fa-check-circle text-emerald-600' : 'fa-circle text-slate-300'"></i> 
                            <span>Huruf Besar (A-Z)</span>
                        </div>
                        <div class="flex items-center gap-1.5" :class="passwordInput && /[a-z]/.test(passwordInput) ? 'text-emerald-700 font-bold' : 'text-slate-500'">
                            <i class="far" :class="passwordInput && /[a-z]/.test(passwordInput) ? 'fa-check-circle text-emerald-600' : 'fa-circle text-slate-300'"></i> 
                            <span>Huruf Kecil (a-z)</span>
                        </div>
                        <div class="flex items-center gap-1.5" :class="passwordInput && /[0-9]/.test(passwordInput) ? 'text-emerald-700 font-bold' : 'text-slate-500'">
                            <i class="far" :class="passwordInput && /[0-9]/.test(passwordInput) ? 'fa-check-circle text-emerald-600' : 'fa-circle text-slate-300'"></i> 
                            <span>Angka (0-9)</span>
                        </div>
                        <div class="col-span-2 flex items-center gap-1.5" :class="passwordInput && /[@#$%!*&^]/.test(passwordInput) ? 'text-emerald-700 font-bold' : 'text-slate-500'">
                            <i class="far" :class="passwordInput && /[@#$%!*&^]/.test(passwordInput) ? 'fa-check-circle text-emerald-600' : 'fa-circle text-slate-300'"></i> 
                            <span>Kode Unik / Simbol (@, #, $, %, !, *)</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95 cursor-pointer flex items-center gap-2">
                        <i class="fas fa-user-check text-xs"></i>
                        <span>Simpan Data User</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
