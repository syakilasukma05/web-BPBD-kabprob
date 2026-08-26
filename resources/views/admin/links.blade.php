@extends('admin.layout')

@section('page_title', 'Kelola Tautan Terkait & Logo Mitigasi')

@section('content')
<div class="space-y-6" x-data="{ showModal: {{ isset($linkItem) ? 'true' : 'false' }}, editMode: {{ isset($linkItem) ? 'true' : 'false' }}, currentLink: {{ isset($linkItem) ? json_encode($linkItem, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) : '{}' }} }">
    
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Daftar Tautan Terkait Logo Instansi</h3>
            <p class="text-xs text-slate-500">Atur logo & link instansi mitra (BNPB, BPBD Jatim, BMKG, PVMBG, Pemkab)</p>
        </div>
        <button @click="showModal = true; editMode = false; currentLink = { is_active: true, order: 0 }" 
                class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Tautan Baru
        </button>
    </div>

    <!-- Links Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-4">Urutan</th>
                    <th class="px-6 py-4">Logo Instansi</th>
                    <th class="px-6 py-4">Nama Instansi / Mitra</th>
                    <th class="px-6 py-4">Target URL Link</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($links as $link)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800">#{{ $link->order }}</td>
                        <td class="px-6 py-4">
                            <img src="{{ $link->image_url }}" alt="Logo" class="h-10 w-auto max-w-[100px] object-contain">
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ $link->title }}
                        </td>
                        <td class="px-6 py-4 font-mono text-[11px] text-slate-500">
                            <a href="{{ $link->url }}" target="_blank" class="text-orange-600 hover:underline flex items-center gap-1">
                                <i class="fas fa-external-link-alt text-[10px]"></i> {{ $link->url }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <a href="{{ route('admin.links.edit', preg_replace('/[\"\']/', '', $link->id)) }}" 
                               @click.prevent="showModal = true; editMode = true; currentLink = {{ json_encode($link, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }}" 
                               class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block" title="Edit Tautan"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.links.destroy', $link->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tautan ini?')">
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

    <!-- Modal Form (Add / Edit Link) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden flex flex-col max-h-[85vh] my-auto">
            
            <!-- Fixed Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-white shrink-0">
                <h3 class="font-bold text-slate-800 text-sm" x-text="editMode ? 'Edit Tautan Terkait' : 'Tambah Tautan Terkait Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition"><i class="fas fa-times"></i></button>
            </div>

            <!-- Scrollable Form Body -->
            <form :action="editMode ? '{{ url('admin/links') }}/' + String(currentLink.id || '').replace(/['&quot;\\]/g, '') : '{{ route('admin.links.store') }}'" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Instansi / Mitra</label>
                        <input type="text" name="title" required x-model="currentLink.title" placeholder="Contoh: BMKG Indonesia" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>

                    <!-- Logo Instansi / Link Gambar (Upload & URL) -->
                    <div class="space-y-2 bg-slate-50 p-3 rounded-xl border border-slate-200">
                        <label class="block font-bold text-slate-700">Logo Instansi / Mitra</label>
                        <div>
                            <span class="text-[10px] text-slate-500 font-semibold block mb-1">Opsi 1: Upload File Logo Dari Perangkat</span>
                            <input type="file" name="image_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-100 file:text-orange-700 hover:file:bg-orange-200 cursor-pointer">
                        </div>
                        <div class="pt-1 border-t border-slate-200">
                            <span class="text-[10px] text-slate-500 font-semibold block mb-1">Opsi 2: Atau Pakai Link / URL Gambar Logo</span>
                            <input type="text" name="image_url" x-model="currentLink.image_url" placeholder="https://..." class="w-full px-3 py-1.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white">
                        </div>
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
                </div>

                <!-- Fixed Footer with Batal and Simpan Buttons -->
                <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex justify-end gap-2 shrink-0">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-md transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Link
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
