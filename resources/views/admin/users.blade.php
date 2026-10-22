@extends('admin.layout')

@section('page_title', 'Manajemen Pengguna & Hak Akses')

@section('content')
<div class="space-y-6" x-data="{ showModal: {{ isset($userItem) ? 'true' : 'false' }}, editMode: {{ isset($userItem) ? 'true' : 'false' }}, currentUser: {{ isset($userItem) ? json_encode($userItem, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) : '{}' }}, showPassword: false }">
    
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Daftar Akun Pengguna System</h3>
            <p class="text-xs text-slate-500">Super Admin memiliki akses penuh, sedangkan Anggota Staf hanya dapat mengelola Dokumen & Informasi/Berita</p>
        </div>
        <button @click="showModal = true; editMode = false; currentUser = { role: 'anggota' }; showPassword = false" 
                class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2">
            <i class="fas fa-user-plus"></i> Tambah Pengguna Baru
        </button>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-4">Nama User</th>
                    <th class="px-6 py-4">Email Administrator</th>
                    <th class="px-6 py-4">Role Akses</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($users as $user)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full {{ $user->isSuperAdmin() ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }} font-bold text-xs flex items-center justify-center">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4 font-mono text-[11px] text-slate-600">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ $user->isSuperAdmin() ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ strtoupper($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <a href="{{ route('admin.users.edit', preg_replace('/[\"\']/', '', $user->id)) }}" 
                               @click.prevent="showModal = true; editMode = true; currentUser = {{ json_encode($user, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }}; showPassword = false" 
                               class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block" title="Edit User"><i class="fas fa-edit"></i></a>
                            @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus pengguna ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Form (Add / Edit User - Fullscreen Style) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 md:p-8 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-3xl w-full max-w-5xl h-full max-h-[92vh] shadow-2xl overflow-hidden flex flex-col my-auto border border-slate-100">
            
            <!-- Fixed Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-white shrink-0">
                <h3 class="font-bold text-slate-800 text-base" x-text="editMode ? 'Edit User & Role' : 'Tambah Pengguna Baru'"></h3>
                <button @click="showModal = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition"><i class="fas fa-times text-xs"></i></button>
            </div>

            <!-- Scrollable Form Body -->
            <form :action="editMode ? '{{ url('admin/users') }}/' + String(currentUser.id || '').replace(/['&quot;\\]/g, '') : '{{ route('admin.users.store') }}'" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div class="p-6 sm:p-8 space-y-6 text-xs sm:text-sm overflow-y-auto flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" required x-model="currentUser.name" placeholder="Staf BPBD" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Email Administrator</label>
                            <input type="email" name="email" required x-model="currentUser.email" placeholder="staf@bpbd.probolinggokab.go.id" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Password <span x-show="editMode" class="text-[10px] text-slate-400 font-normal">(Kosongkan jika tidak diubah)</span></label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" name="password" :required="!editMode" placeholder="••••••••" class="w-full pl-3.5 pr-10 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none p-1 transition" :title="showPassword ? 'Sembunyikan Password' : 'Tampilkan Password'">
                                    <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Role / Hak Akses</label>
                            <select name="role" x-model="currentUser.role" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                                <option value="super_admin">Super Admin (Ubah Semua)</option>
                                <option value="anggota">Anggota (Ubah Dokumen & Informasi Saja)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Fixed Footer with Batal and Simpan Buttons -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-2 shrink-0">
                    <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-md transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
