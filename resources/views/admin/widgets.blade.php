@extends('admin.layout')

@section('page_title', 'Kelola Widget Sidebar Homepage')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: {{ isset($widgetItem) ? 'true' : 'false' }}, 
    editMode: {{ isset($widgetItem) ? 'true' : 'false' }}, 
    currentWidget: {{ isset($widgetItem) ? json_encode($widgetItem, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) : '{}' }},
    fileError: '',
    imagePreview: '',

    handleFileValidation(event) {
        const file = event.target.files[0];
        if (!file) {
            this.fileError = '';
            this.imagePreview = '';
            return;
        }
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        const ext = file.name.split('.').pop().toLowerCase();
        const allowedExts = ['jpg', 'jpeg', 'png'];

        if (!allowedTypes.includes(file.type) && !allowedExts.includes(ext)) {
            this.fileError = '❌ Format file tidak didukung. Silakan upload gambar JPG atau PNG.';
            this.imagePreview = '';
            event.target.value = '';
        } else {
            this.fileError = '';
            const reader = new FileReader();
            reader.onload = (e) => {
                this.imagePreview = e.target.result;
            };
            reader.readAsDataURL(file);
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
        this.imagePreview = '';
        this.currentWidget = { is_active: true, order: 0 };
        this.resetFileInput();
    },

    openEdit(widgetData) {
        this.showModal = true;
        this.editMode = true;
        this.fileError = '';
        this.imagePreview = '';
        this.currentWidget = Object.assign({}, widgetData);
        this.resetFileInput();
    },

    resetFileInput() {
        this.$nextTick(() => {
            const input = document.querySelector('input[name=\'image_file\']');
            if (input) input.value = '';
        });
    }
}">
    
    <!-- Header Action -->
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Daftar Widget Sidebar (Kanan)</h3>
            <p class="text-xs text-slate-500 mt-1">Atur spanduk, banner call center, dan gambar informasi publik pada kolom sidebar kanan homepage</p>
        </div>
        <button type="button" @click="openCreate()" 
                class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2 transition hover:scale-[1.02]">
            <i class="fas fa-plus"></i> Tambah Widget Baru
        </button>
    </div>

    <!-- Widgets Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-4">Urutan</th>
                    <th class="px-6 py-4">Preview Banner</th>
                    <th class="px-6 py-4">Judul Widget</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($widgets as $widget)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-extrabold text-slate-800">#{{ $widget->order }}</td>
                        <td class="px-6 py-4">
                            <img src="{{ $widget->image_url }}" alt="Preview" class="h-14 w-28 object-cover rounded-lg border border-slate-200 shadow-xs">
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <h4 class="font-bold text-slate-800 text-xs">{{ $widget->title }}</h4>
                            <p class="text-slate-400 font-mono text-[10px] mt-0.5">{{ $widget->target_url ?? 'Tidak ada link' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ $widget->is_active ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                {{ $widget->is_active ? 'AKTIF' : 'NONAKTIF' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <a href="{{ route('admin.widgets.edit', preg_replace('/[\"\']/', '', $widget->id)) }}" 
                               @click.prevent="openEdit({{ json_encode($widget, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }})" 
                               class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block" title="Edit Widget"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.widgets.destroy', $widget->id) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus widget sidebar ini?">
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

    <!-- Fullscreen Modal Form (Add / Edit Widget) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4 bg-slate-900/70 backdrop-blur-sm overflow-hidden">
        <div class="bg-white rounded-2xl w-full max-w-5xl h-[90vh] shadow-2xl overflow-hidden flex flex-col border border-slate-200">
            
            <!-- Fixed Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-200 bg-slate-50 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-800 text-base" x-text="editMode ? 'Edit Widget Sidebar' : 'Tambah Widget Sidebar Baru'"></h3>
                </div>
                <button type="button" @click="showModal = false" class="p-2 text-slate-400 hover:text-slate-700 rounded-xl transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Scrollable Form Body -->
            <form :action="editMode ? '{{ url('admin/widgets') }}/' + String(currentWidget.id || '').replace(/['&quot;\\]/g, '') : '{{ route('admin.widgets.store') }}'" 
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
                    @if(isset($errors) && $errors->has('image_file'))
                        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 font-bold text-xs flex items-center gap-2">
                            <i class="fas fa-exclamation-circle text-rose-600"></i>
                            <span>{{ $errors->first('image_file') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <!-- Judul Widget -->
                        <div class="md:col-span-8">
                            <label class="block font-bold text-slate-700 mb-1">Judul Widget <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" required x-model="currentWidget.title" placeholder="Contoh: Call Center Emergency 24 Jam" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none text-xs font-semibold">
                        </div>

                        <!-- Urutan (Order) -->
                        <div class="md:col-span-4">
                            <label class="block font-bold text-slate-700 mb-1">Urutan (Order) <span class="text-rose-500">*</span></label>
                            <input type="number" name="order" required min="0" x-model="currentWidget.order" placeholder="0" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none text-xs font-semibold">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <!-- Target URL Link Saat Diklik -->
                        <div class="md:col-span-8">
                            <label class="block font-bold text-slate-700 mb-1">Target URL Link Saat Diklik (Opsional)</label>
                            <input type="text" name="target_url" x-model="currentWidget.target_url" placeholder="/kontak atau https://..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none text-xs font-semibold font-mono">
                        </div>

                        <!-- Status Active -->
                        <div class="md:col-span-4 flex items-end pb-1">
                            <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-200 w-full">
                                <input type="checkbox" name="is_active" value="1" :checked="currentWidget.is_active" class="w-4 h-4 text-orange-600 rounded">
                                <span class="text-xs">Aktifkan Widget Ini</span>
                            </label>
                        </div>
                    </div>

                    <!-- Gambar Banner Widget (Upload File Dari Perangkat Sahaja) -->
                    <div class="space-y-1 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <label class="block font-bold text-slate-700 mb-1">Upload Gambar Banner Widget (JPG / PNG)</label>
                        <input type="file" 
                               name="image_file" 
                               accept=".jpg,.jpeg,.png,image/jpeg,image/png" 
                               @change="handleFileValidation($event)"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-100 file:text-orange-700 hover:file:bg-orange-200 cursor-pointer border border-slate-300 rounded-xl bg-white">
                        
                        <p x-show="fileError" x-cloak class="text-xs font-bold text-rose-600 mt-1.5 flex items-center gap-1.5 bg-rose-50 p-2 rounded-lg border border-rose-200">
                            <span x-text="fileError"></span>
                        </p>
                        <p x-show="!fileError" class="text-[10px] text-slate-400 mt-1">*Hanya menerima format file: .JPG, .JPEG, .PNG</p>

                        <!-- Preview Gambar Baru jika user memilih file baru -->
                        <div x-show="imagePreview" x-cloak class="mt-3 pt-3 border-t border-slate-200">
                            <span class="text-[10px] font-bold text-orange-600 uppercase tracking-wider block mb-1.5">
                                <i class="fas fa-check-circle me-1"></i> Preview Gambar Baru Terpilih:
                            </span>
                            <div class="flex items-center gap-3 bg-white p-2.5 rounded-xl border border-orange-200 shadow-xs">
                                <img :src="imagePreview" alt="Preview Baru" class="h-14 w-28 object-cover rounded-lg border border-slate-200 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <span class="text-xs font-bold text-slate-800 block">File gambar baru siap diupload</span>
                                    <span class="text-[10px] text-slate-500">Gambar ini akan menggantikan banner lama setelah Anda menekan tombol Simpan Widget.</span>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi & Preview Gambar Existing / Lama Saat Edit -->
                        <div x-show="!imagePreview && editMode && currentWidget.image_url" x-cloak class="mt-3 pt-3 border-t border-slate-200">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1.5">
                                <i class="fas fa-image me-1 text-slate-400"></i> Gambar Banner Saat Ini:
                            </span>
                            <div class="flex items-center gap-3 bg-white p-2.5 rounded-xl border border-slate-200 shadow-xs">
                                <img :src="currentWidget.image_url" alt="Banner Saat Ini" class="h-14 w-28 object-cover rounded-lg border border-slate-200 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <span class="text-xs font-bold text-slate-800 font-mono truncate block" x-text="currentWidget.image_url ? currentWidget.image_url.split('?')[0].split('/').pop() : ''"></span>
                                    <span class="text-[10px] text-slate-500 block mt-0.5">*Gambar lama ini tetap tersimpan dan tidak akan terhapus jika Anda membiarkan pilihan file kosong.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Fixed Footer with Batal and Simpan Buttons -->
                <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex justify-end gap-2 shrink-0">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-md transition flex items-center gap-2 text-xs">
                        <i class="fas fa-save"></i> Simpan Widget
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
