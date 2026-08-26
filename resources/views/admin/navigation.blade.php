@extends('admin.layout')

@section('page_title', 'Kelola Struktur Navigasi')

@section('content')
<div x-data="{ 
    showModal: {{ isset($menuItem) ? 'true' : 'false' }}, 
    editMode: {{ isset($menuItem) ? 'true' : 'false' }}, 
    currentMenu: {{ isset($menuItem) ? json_encode($menuItem, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) : '{ id: null, title: \'\', url: \'\', parent_id: \'\', order: 0, target: \'_self\', is_active: true }' }},
    
    openCreate(parentId = '') {
        this.editMode = false;
        this.currentMenu = { 
            id: null, 
            title: '', 
            url: '', 
            type: 'internal',
            parent_id: parentId, 
            order: 0, 
            target: '_self', 
            is_active: true 
        };
        this.showModal = true;
    },

    openEdit(menuData) {
        this.editMode = true;
        this.currentMenu = Object.assign({}, menuData);
        this.showModal = true;
    }
}" x-init="
    @if(isset($menuItem))
        openEdit({!! json_encode($menuItem, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!});
    @endif
" class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h3 class="text-xl font-extrabold text-slate-800 tracking-tight flex items-center gap-2.5">
                <i class="fas fa-sitemap text-cyan-600 text-lg"></i> Struktur Navigasi Website Header
            </h3>
            <p class="text-xs text-slate-500 mt-1">Kelola struktur hirarki menu utama, buat submenu, atur urutan posisi, dan status aktif navigasi publik.</p>
        </div>
        <button type="button" @click="openCreate('')" 
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md shadow-blue-600/20 transition-all hover:scale-[1.02]">
            <i class="fas fa-plus"></i> Tambah Menu Utama Baru
        </button>
    </div>

    <!-- Menus Tree Card List -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Hirarki Menu & Submenu Header</span>
            <span class="text-xs text-slate-400 font-medium">Total Menu Utama: {{ count($menus) }}</span>
        </div>

        @foreach($menus as $menu)
            <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs hover:border-slate-300 transition-all">
                
                <!-- Main Menu Row -->
                <div class="bg-slate-50 p-4 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-800 text-xs font-extrabold flex items-center justify-center border border-blue-200 shadow-2xs">
                            #{{ $menu->order }}
                        </span>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-extrabold text-slate-800 text-sm tracking-wide">{{ $menu->title }}</h4>
                                @if($menu->is_active)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">Aktif</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-600">Non-Aktif</span>
                                @endif
                                @if($menu->children && count($menu->children) > 0)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-600 border border-blue-100">
                                        {{ count($menu->children) }} Submenu
                                    </span>
                                @endif
                            </div>
                            <span class="text-[11px] text-slate-400 font-mono">{{ $menu->url }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" @click="openCreate('{{ $menu->id }}')" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl border border-emerald-200 transition">
                            <i class="fas fa-plus text-[10px]"></i> Submenu
                        </button>
                        <a href="{{ route('admin.navigation.edit', preg_replace('/[\"\']/', '', $menu->id)) }}" 
                           @click.prevent="openEdit({{ json_encode($menu, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }})" 
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                            <i class="fas fa-edit text-xs"></i> Edit Struktur
                        </a>
                        <form action="{{ route('admin.navigation.destroy', $menu->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus menu utama ini beserta seluruh submenunya?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition" title="Hapus Menu"><i class="fas fa-trash-alt text-xs"></i></button>
                        </form>
                    </div>
                </div>

                <!-- Submenus List Container -->
                @if($menu->children && count($menu->children) > 0)
                    <div class="p-3 bg-white divide-y divide-slate-100 pl-6 sm:pl-10">
                        @foreach($menu->children as $child)
                            <div class="py-2.5 flex items-center justify-between text-xs hover:bg-slate-50/50 px-3 rounded-xl transition">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-level-up-alt rotate-90 text-slate-400"></i>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-700">{{ $child->title }}</span>
                                            @if($child->is_active)
                                                <span class="text-[9px] px-1.5 py-0.2 rounded bg-emerald-50 text-emerald-600 border border-emerald-100">Aktif</span>
                                            @else
                                                <span class="text-[9px] px-1.5 py-0.2 rounded bg-slate-100 text-slate-500">Non-Aktif</span>
                                            @endif
                                        </div>
                                        <span class="text-[11px] text-slate-400 font-mono">({{ $child->url }})</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.navigation.edit', preg_replace('/[\"\']/', '', $child->id)) }}" 
                                       @click.prevent="openEdit({{ json_encode($child, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }})" 
                                       class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg transition flex items-center gap-1">
                                        <i class="fas fa-pen text-[10px] text-blue-600"></i> Edit Struktur
                                    </a>
                                    <form action="{{ route('admin.navigation.destroy', $child->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus sub-menu ini?')">
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

    <!-- Modal Form (Edit Struktur Menu Navigasi - Pure Structure, No CKEditor) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden flex flex-col my-auto border border-slate-100">
            
            <!-- Modal Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-white shrink-0">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2" x-text="editMode ? 'Edit Struktur Menu Navigasi' : 'Tambah Menu Navigasi Baru'"></h3>
                <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition"><i class="fas fa-times"></i></button>
            </div>

            <!-- Modal Form Body -->
            <form :action="editMode ? '{{ url('admin/navigation') }}/' + String(currentMenu.id || '').replace(/['&quot;\\]/g, '') : '{{ route('admin.navigation.store') }}'" method="POST" class="flex flex-col flex-1">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div class="p-6 space-y-4 text-xs overflow-y-auto">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Tampilan Menu *</label>
                        <input type="text" name="title" required x-model="currentMenu.title" placeholder="Contoh: Visi dan Misi" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Target URL / Slug Link *</label>
                        <input type="text" name="url" required x-model="currentMenu.url" placeholder="Contoh: /halaman/visi-dan-misi atau https://www.lapor.go.id/" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none font-mono">
                        <p class="text-[10px] text-slate-400 mt-1">*Gunakan format <code>/halaman/nama-slug</code> untuk halaman CMS internal, atau URL lengkap (misal: <code>https://www.lapor.go.id/</code>) untuk link eksternal.</p>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tipe Navigasi Menu *</label>
                        <select name="type" x-model="currentMenu.type" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none font-bold text-slate-800">
                            <option value="internal">INTERNAL PAGE (Halaman Statis CMS)</option>
                            <option value="external">EXTERNAL LINK (Link Eksternal Langsung)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Parent Menu (Induk)</label>
                            <select name="parent_id" x-model="currentMenu.parent_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">-- Menu Utama (Tanpa Induk) --</option>
                                @foreach($allParents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Urutan Posisi (Order)</label>
                            <input type="number" name="order" x-model="currentMenu.order" min="0" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Target Buka Link</label>
                            <select name="target" x-model="currentMenu.target" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="_self">_self (Halaman Sama)</option>
                                <option value="_blank">_blank (Tab Baru)</option>
                            </select>
                        </div>

                        <div class="flex items-center pt-5">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" x-model="currentMenu.is_active" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                                <span class="font-bold text-slate-700 text-xs">Aktifkan Menu Ini</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex justify-end gap-2 shrink-0">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Struktur Menu
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
