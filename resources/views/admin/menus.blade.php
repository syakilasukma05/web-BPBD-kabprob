@extends('admin.layout')

@section('page_title', 'Kelola Menu Navigasi')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, currentMenu: {} }">
    
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Daftar Menu & Sub-Menu Navigasi Header</h3>
            <p class="text-xs text-slate-500">Atur struktur menu header (HOME, PROFIL, LAYANAN, DOKUMEN, INFORMASI, HUBUNGI, LOGIN)</p>
        </div>
        <button @click="showModal = true; editMode = false; currentMenu = { is_active: true, target: '_self', order: 0, page_content: '', image_url: '' }" 
                class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Menu Baru
        </button>
    </div>

    <!-- Menus Tree Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        @foreach($menus as $menu)
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-xs">
                <div class="bg-slate-50 p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-md bg-orange-100 text-orange-800 text-[11px] font-bold flex items-center justify-center">#{{ $menu->order }}</span>
                        <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase">{{ $menu->title }}</h4>
                        <span class="text-[11px] text-slate-400 font-mono">({{ $menu->url }})</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="showModal = true; editMode = true; currentMenu = {{ json_encode(array_merge($menu->toArray(), ['page_content' => $menu->page_content ?? '', 'image_url' => $menu->image_url ?? ''])) }}" 
                                class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition"><i class="fas fa-edit text-xs"></i></button>
                        <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus menu utama ini beserta submenus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition"><i class="fas fa-trash-alt text-xs"></i></button>
                        </form>
                    </div>
                </div>

                @if($menu->children && count($menu->children) > 0)
                    <div class="p-3 bg-white divide-y divide-slate-100 pl-8">
                        @foreach($menu->children as $child)
                            <div class="py-2 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-level-up-alt rotate-90 text-slate-300"></i>
                                    <span class="font-semibold text-slate-700">{{ $child->title }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">({{ $child->url }})</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button @click="showModal = true; editMode = true; currentMenu = {{ json_encode(array_merge($child->toArray(), ['page_content' => $child->page_content ?? '', 'image_url' => $child->image_url ?? ''])) }}" 
                                            class="p-1 text-blue-600 hover:bg-blue-50 rounded-md"><i class="fas fa-edit text-xs"></i></button>
                                    <form action="{{ route('admin.menus.destroy', $child->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus sub-menu ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-rose-500 hover:bg-rose-50 rounded-md"><i class="fas fa-times text-xs"></i></button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Modal Form (Add / Edit Menu) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden flex flex-col max-h-[85vh] my-auto">
            
            <!-- Fixed Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-white shrink-0">
                <h3 class="font-bold text-slate-800 text-sm" x-text="editMode ? 'Edit Menu Navigasi' : 'Tambah Menu Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition"><i class="fas fa-times"></i></button>
            </div>

            <!-- Scrollable Form Body -->
            <form :action="editMode ? '{{ url('admin/menus') }}/' + currentMenu.id : '{{ route('admin.menus.store') }}'" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Parent Menu (Kosongkan jika Menu Utama)</label>
                        <select name="parent_id" x-model="currentMenu.parent_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                            <option value="">-- Main Menu Utama --</option>
                            @foreach($allParents as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Judul Menu</label>
                        <input type="text" name="title" required x-model="currentMenu.title" placeholder="Contoh: PROFIL / Struktur Organisasi" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Target URL Link</label>
                        <input type="text" name="url" x-model="currentMenu.url" placeholder="/halaman/visi-misi atau https://..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        
                        <!-- Quick Page Link Picker -->
                        @if(isset($pages) && count($pages) > 0)
                            <div class="mt-1.5 flex items-center gap-2">
                                <span class="text-[10px] font-bold text-slate-500">Atau Pilih Dari Halaman Halaman Terdaftar:</span>
                                <select @change="
                                            if($event.target.value) { 
                                                currentMenu.url = '/halaman/' + $event.target.value; 
                                                const pMap = {{ json_encode($pages->pluck('content', 'slug')) }};
                                                const imgMap = {{ json_encode($pages->pluck('image_url', 'slug')) }};
                                                if(pMap[$event.target.value]) currentMenu.page_content = pMap[$event.target.value];
                                                if(imgMap[$event.target.value]) currentMenu.image_url = imgMap[$event.target.value];
                                            }" 
                                        class="px-2 py-1 text-[11px] border border-slate-200 rounded-lg bg-slate-50 font-medium">
                                    <option value="">-- Pilih Halaman --</option>
                                    @foreach($pages as $pg)
                                        <option value="{{ $pg->slug }}">{{ $pg->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <!-- Input Content Text Area for Page -->
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-2">
                        <label class="block font-extrabold text-slate-800 text-xs flex items-center gap-1.5">
                            <i class="fas fa-edit text-orange-600"></i>
                            Isi Konten Teks Halaman (Opsional)
                        </label>
                        <p class="text-[10px] text-slate-500">Isi teks di sini jika ingin membuat/mengubah isi halaman langsung dari menu ini.</p>
                        <textarea name="page_content" 
                                  x-model="currentMenu.page_content"
                                  rows="6" 
                                  placeholder="Tuliskan isi teks berita / profil / pelayanan di sini (HTML / Teks Biasa)..." 
                                  class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none text-xs font-sans leading-relaxed"></textarea>
                    </div>

                    <!-- Upload File & Link URL Gambar Halaman -->
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-3">
                        <label class="block font-extrabold text-slate-800 text-xs flex items-center gap-1.5">
                            <i class="fas fa-image text-orange-600"></i>
                            Gambar Halaman / Peta / Bagan (Opsional)
                        </label>
                        <p class="text-[10px] text-slate-500">Pilih salah satu cara menambahkan gambar untuk halaman ini:</p>

                        <div class="space-y-2.5">
                            <div>
                                <label class="block font-bold text-slate-700 text-[11px] mb-1">1. Unggah File Gambar (Choose File):</label>
                                <input type="file" 
                                       name="image_file" 
                                       accept="image/*" 
                                       class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-100 file:text-orange-700 hover:file:bg-orange-200 cursor-pointer">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 text-[11px] mb-1">2. Atau Masukkan Link URL Gambar:</label>
                                <input type="url" 
                                       name="image_url" 
                                       x-model="currentMenu.image_url" 
                                       placeholder="https://... (URL Gambar)" 
                                       class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none text-xs">
                            </div>

                            <template x-if="currentMenu.image_url">
                                <div class="pt-1.5">
                                    <span class="text-[10px] font-bold text-slate-500 block mb-1">Pratinjau Gambar Saat Ini:</span>
                                    <img :src="currentMenu.image_url" class="h-24 w-auto rounded-lg border border-slate-200 object-contain bg-white p-1">
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Urutan (Order)</label>
                            <input type="number" name="order" x-model="currentMenu.order" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        </div>
                        <div class="flex items-center pt-5">
                            <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                                <input type="checkbox" name="is_active" value="1" :checked="currentMenu.is_active" class="w-4 h-4 text-orange-600 rounded">
                                Aktifkan Menu
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Fixed Footer with Batal and Simpan Buttons -->
                <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex justify-end gap-2 shrink-0">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-md transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Menu
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
