@extends('admin.layout')

@section('page_title', 'Kelola Tautan Terkait & Logo Mitigasi')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: {{ isset($linkItem) ? 'true' : 'false' }}, 
    editMode: {{ isset($linkItem) ? 'true' : 'false' }}, 
    currentLink: {{ isset($linkItem) ? json_encode($linkItem, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) : '{}' }},
    fileError: '',

    handleFileValidation(event) {
        const file = event.target.files[0];
        if (!file) {
            this.fileError = '';
            return;
        }
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        const ext = file.name.split('.').pop().toLowerCase();
        const allowedExts = ['jpg', 'jpeg', 'png'];

        if (!allowedTypes.includes(file.type) && !allowedExts.includes(ext)) {
            this.fileError = '❌ Format file tidak didukung. Silakan upload gambar JPG atau PNG.';
            event.target.value = '';
        } else {
            this.fileError = '';
        }
    },

    validateFormBeforeSubmit(event) {
        if (this.fileError) {
            event.preventDefault();
            return false;
        }
    },

    openCreate() {
        this.showModal = true;
        this.editMode = false;
        this.fileError = '';
        this.currentLink = { is_active: true, order: 0 };
    },

    openEdit(linkData) {
        this.showModal = true;
        this.editMode = true;
        this.fileError = '';
        this.currentLink = Object.assign({}, linkData);
    }
}">
    
    <!-- Header Action -->
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Daftar Tautan Terkait Logo Instansi</h3>
            <p class="text-xs text-slate-500 mt-1">Atur logo & link instansi mitra (BNPB, BPBD Jatim, BMKG, PVMBG, Pemkab)</p>
        </div>
        <button type="button" @click="openCreate()" 
                class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2 transition hover:scale-[1.02]">
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
                        <td class="px-6 py-4 font-extrabold text-slate-800">#{{ $link->order }}</td>
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
                               @click.prevent="openEdit({{ json_encode($link, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }})" 
                               class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block" title="Edit Tautan"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.links.destroy', $link->id) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus tautan terkait ini?">
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

    <!-- Fullscreen Modal Form (Add / Edit Link) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4 bg-slate-900/70 backdrop-blur-sm overflow-hidden">
        <div class="bg-white rounded-2xl w-full max-w-5xl h-[90vh] shadow-2xl overflow-hidden flex flex-col border border-slate-200">
            
            <!-- Fixed Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-200 bg-slate-50 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">
                        <i class="fas fa-link"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-800 text-base" x-text="editMode ? 'Edit Tautan Terkait' : 'Tambah Tautan Terkait Baru'"></h3>
                </div>
                <button type="button" @click="showModal = false" class="p-2 text-slate-400 hover:text-slate-700 rounded-xl transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Scrollable Form Body -->
            <form :action="editMode ? '{{ url('admin/links') }}/' + String(currentLink.id || '').replace(/['&quot;\\]/g, '') : '{{ route('admin.links.store') }}'" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  @submit="validateFormBeforeSubmit($event)"
                  class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
                    <!-- Global Error Alert -->
                    @if($errors->has('image_file'))
                        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 font-bold text-xs flex items-center gap-2">
                            <i class="fas fa-exclamation-circle text-rose-600"></i>
                            <span>{{ $errors->first('image_file') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <!-- Nama Instansi / Mitra -->
                        <div class="md:col-span-8">
                            <label class="block font-bold text-slate-700 mb-1">Nama Instansi / Mitra <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" required x-model="currentLink.title" placeholder="Contoh: BMKG Indonesia" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none text-xs font-semibold">
                        </div>

                        <!-- Urutan (Order) -->
                        <div class="md:col-span-4">
                            <label class="block font-bold text-slate-700 mb-1">Urutan (Order) <span class="text-rose-500">*</span></label>
                            <input type="number" name="order" required min="0" x-model="currentLink.order" placeholder="0" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none text-xs font-semibold">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <!-- Target Website URL -->
                        <div class="md:col-span-8">
                            <label class="block font-bold text-slate-700 mb-1">Target Website URL <span class="text-rose-500">*</span></label>
                            <input type="text" name="url" required x-model="currentLink.url" placeholder="https://bmkg.go.id" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none text-xs font-semibold font-mono">
                        </div>

                        <!-- Status Active -->
                        <div class="md:col-span-4 flex items-end pb-1">
                            <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-200 w-full">
                                <input type="checkbox" name="is_active" value="1" :checked="currentLink.is_active" class="w-4 h-4 text-orange-600 rounded">
                                <span class="text-xs">Aktifkan Link Ini</span>
                            </label>
                        </div>
                    </div>

                    <!-- Logo Instansi / Mitra (Upload File Dari Perangkat Sahaja) -->
                    <div class="space-y-1 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <label class="block font-bold text-slate-700 mb-1">Upload File Logo Dari Perangkat (JPG / PNG)</label>
                        <input type="file" 
                               name="image_file" 
                               accept=".jpg,.jpeg,.png,image/jpeg,image/png" 
                               @change="handleFileValidation($event)"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-100 file:text-orange-700 hover:file:bg-orange-200 cursor-pointer border border-slate-300 rounded-xl bg-white">
                        
                        <p x-show="fileError" x-cloak class="text-xs font-bold text-rose-600 mt-1.5 flex items-center gap-1.5 bg-rose-50 p-2 rounded-lg border border-rose-200">
                            <span x-text="fileError"></span>
                        </p>
                        <p x-show="!fileError" class="text-[10px] text-slate-400 mt-1">*Hanya menerima format file: .JPG, .JPEG, .PNG</p>
                    </div>

                </div>

                <!-- Fixed Footer with Batal and Simpan Buttons -->
                <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex justify-end gap-2 shrink-0">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-md transition flex items-center gap-2 text-xs">
                        <i class="fas fa-save"></i> Simpan Link
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
