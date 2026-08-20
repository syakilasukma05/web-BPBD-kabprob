@extends('admin.layout')

@section('page_title', 'Kelola Halaman Profil & Layanan')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, currentPage: {} }">
    
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Daftar Halaman Profil, Layanan, & Konten Publik</h3>
            <p class="text-xs text-slate-500">Kelola konten statis seperti Struktur Organisasi, Visi Misi, Tugas & Fungsi, Peta Bencana, Standar Pelayanan, dll.</p>
        </div>
        <button @click="showModal = true; editMode = false; currentPage = {}" 
                class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Halaman Baru
        </button>
    </div>

    <!-- Pages Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-4">Judul Halaman</th>
                    <th class="px-6 py-4">Slug URL</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($pages as $page)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ $page->title }}
                        </td>
                        <td class="px-6 py-4 font-mono text-[11px] text-slate-500">
                            /halaman/{{ $page->slug }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                                PUBLISHED
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <button @click="showModal = true; editMode = true; currentPage = {{ json_encode($page) }}" 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus halaman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Form (Add / Edit Page) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl overflow-hidden flex flex-col max-h-[85vh] my-auto">
            
            <!-- Fixed Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-white shrink-0">
                <h3 class="font-bold text-slate-800 text-sm" x-text="editMode ? 'Edit Halaman Statis' : 'Tambah Halaman Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition"><i class="fas fa-times"></i></button>
            </div>

            <!-- Scrollable Form Body -->
            <form :action="editMode ? '{{ url('admin/pages') }}/' + currentPage.id : '{{ route('admin.pages.store') }}'" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Judul Halaman</label>
                        <input type="text" name="title" required x-model="currentPage.title" placeholder="Contoh: Peta Rawan Bencana" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Isi Konten Halaman (HTML / Teks)</label>
                        <textarea name="content" rows="6" required x-model="currentPage.content" placeholder="<h2>Judul</h2><p>Isi penjelasan...</p>" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none font-mono text-xs"></textarea>
                    </div>
                </div>

                <!-- Fixed Footer with Batal and Simpan Buttons -->
                <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex justify-end gap-2 shrink-0">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-md transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Halaman
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
