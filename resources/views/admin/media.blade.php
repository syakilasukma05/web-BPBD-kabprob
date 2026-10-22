@extends('admin.layout')

@section('page_title', 'Kelola Galeri Media (Foto & Video)')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: {{ isset($mediaItem) ? 'true' : 'false' }}, 
    editMode: {{ isset($mediaItem) ? 'true' : 'false' }}, 
    currentItem: {{ isset($mediaItem) ? json_encode($mediaItem, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) : '{ type: \'foto\', is_active: true, order: 0 }' }},
    fileError: '',
    ckEditor: null,

    initMediaEditor(content = '') {
        this.$nextTick(() => {
            const clean = window.sanitizeCkHtml(content || '');
            const textarea = document.getElementById('media_caption_textarea');
            if (textarea) {
                textarea.value = clean;
            }

            if (textarea && textarea.ckeditorInstance) {
                try {
                    textarea.ckeditorInstance.setData(clean);
                    this.ckEditor = textarea.ckeditorInstance;
                    return;
                } catch(e) {
                    try { textarea.ckeditorInstance.destroy(); } catch(err) {}
                    textarea.ckeditorInstance = null;
                    this.ckEditor = null;
                }
            }

            if (this.ckEditor) {
                try {
                    this.ckEditor.setData(clean);
                    return;
                } catch(e) {
                    this.ckEditor = null;
                }
            }

            window.initStandardCKEditor('media_caption_textarea', clean, (data) => {
                this.currentItem.caption = data;
            }).then(editor => {
                this.ckEditor = editor;
            }).catch(err => {
                console.warn('CKEditor init warning:', err);
            });
        });
    },

    handleFileValidation(event) {
        const files = event.target.files;
        if (!files || files.length === 0) {
            this.fileError = '';
            return;
        }
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        const allowedExts = ['jpg', 'jpeg', 'png'];

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const ext = file.name.split('.').pop().toLowerCase();
            if (!allowedTypes.includes(file.type) && !allowedExts.includes(ext)) {
                this.fileError = '❌ Format file tidak didukung. Silakan upload gambar JPG atau PNG.';
                event.target.value = '';
                return;
            }
        }
        this.fileError = '';
    },

    validateFormBeforeSubmit(event) {
        if (this.fileError) {
            event.preventDefault();
            return false;
        }

        const textarea = document.getElementById('media_caption_textarea');
        if (this.ckEditor && typeof this.ckEditor.getData === 'function') {
            const editorData = this.ckEditor.getData();
            if (textarea) {
                textarea.value = editorData;
            }
            this.currentItem.caption = editorData;
        } else if (textarea && this.currentItem.caption) {
            textarea.value = this.currentItem.caption;
        }
    },

    openCreate() {
        this.showModal = true;
        this.editMode = false;
        this.fileError = '';
        this.currentItem = { 
            id: null,
            title: '',
            type: '{{ $type }}' || 'foto', 
            is_active: true, 
            order: 0, 
            category: '',
            caption: '',
            video_url: '',
            file_url: '',
            cover_photo: '',
            photo_urls: [],
            total_photos: 0
        };
        this.initMediaEditor('');
    },

    openEdit(itemData) {
        this.showModal = true;
        this.editMode = true;
        this.fileError = '';
        
        let photosList = [];
        if (itemData.photo_urls && Array.isArray(itemData.photo_urls) && itemData.photo_urls.length > 0) {
            photosList = itemData.photo_urls;
        } else if (itemData.photos && Array.isArray(itemData.photos) && itemData.photos.length > 0) {
            photosList = itemData.photos;
        } else if (itemData.cover_photo) {
            photosList = [itemData.cover_photo];
        } else if (itemData.file_url) {
            photosList = [itemData.file_url];
        }

        this.currentItem = {
            id: itemData.id,
            title: itemData.title || '',
            order: itemData.order !== undefined && itemData.order !== null ? itemData.order : 0,
            type: itemData.type || 'foto',
            category: itemData.category || '',
            is_active: itemData.is_active === true || itemData.is_active === 1 || itemData.is_active === '1',
            caption: itemData.caption || '',
            video_url: itemData.video_url || '',
            file_url: itemData.file_url || '',
            cover_photo: itemData.cover_photo || '',
            photo_urls: photosList,
            total_photos: photosList.length
        };
        
        this.initMediaEditor(this.currentItem.caption);
    }
}" x-init="
    @if(isset($mediaItem))
        openEdit({!! json_encode($mediaItem, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!});
    @endif
">

    <!-- Header Title & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Kelola Galeri Media</h3>
            <p class="text-xs text-slate-500 mt-1">Kelola album foto dokumentasi kegiatan kebencanaan dan video publikasi resmi BPBD Kabupaten Probolinggo.</p>
        </div>
        <button type="button" @click="openCreate()" 
                class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center justify-center gap-2 transition hover:scale-[1.02]">
            <i class="fas fa-plus"></i> Tambah Media Baru
        </button>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900"><i class="fas fa-times"></i></button>
        </div>
    @endif

    <!-- Statistic & Filter Tabs -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.media', ['type' => 'foto']) }}" 
           class="p-5 rounded-2xl border transition-all flex items-center justify-between {{ $type === 'foto' ? 'bg-amber-500 text-white border-amber-600 shadow-md' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl {{ $type === 'foto' ? 'bg-white/20 text-white' : 'bg-amber-50 text-amber-600' }} flex items-center justify-center text-xl font-bold">
                    <i class="fas fa-images"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-sm">Album Galeri Foto</h4>
                    <span class="text-xs opacity-90">{{ number_format($stats['total_foto']) }} Album Dokumentasi</span>
                </div>
            </div>
            <i class="fas fa-chevron-right text-xs opacity-70"></i>
        </a>

        <a href="{{ route('admin.media', ['type' => 'video']) }}" 
           class="p-5 rounded-2xl border transition-all flex items-center justify-between {{ $type === 'video' ? 'bg-rose-600 text-white border-rose-700 shadow-md' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl {{ $type === 'video' ? 'bg-white/20 text-white' : 'bg-rose-50 text-rose-600' }} flex items-center justify-center text-xl font-bold">
                    <i class="fas fa-video"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-sm">Galeri Video</h4>
                    <span class="text-xs opacity-90">{{ number_format($stats['total_video']) }} Konten Video / YouTube</span>
                </div>
            </div>
            <i class="fas fa-chevron-right text-xs opacity-70"></i>
        </a>

        <div class="p-5 rounded-2xl bg-white border border-slate-200 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-globe"></i>
            </div>
            <div>
                <h4 class="font-extrabold text-sm text-slate-800">Tampilan Portal Publik</h4>
                <a href="{{ route('public.galeri') }}" target="_blank" class="text-xs text-blue-600 font-bold hover:underline flex items-center gap-1 mt-0.5">
                    Lihat Galeri Publik <i class="fas fa-external-link-alt text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Media Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($mediaItems as $item)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden flex flex-col justify-between group hover:shadow-md transition duration-200">
                <div class="space-y-3">
                    <!-- Media Preview Area -->
                    <div class="relative h-48 bg-slate-900 overflow-hidden">
                        @if($item->type === 'video')
                            @if($item->youtube_embed_url)
                                <iframe src="{{ $item->youtube_embed_url }}" class="w-full h-full border-0" allowfullscreen></iframe>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <i class="fas fa-play-circle text-4xl"></i>
                                </div>
                            @endif
                        @else
                            <img src="{{ $item->cover_photo }}" 
                                 alt="{{ $item->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @endif

                        <!-- Type & Status Badges -->
                        <div class="absolute top-3 left-3 flex gap-1.5 flex-wrap">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold text-white {{ $item->type === 'video' ? 'bg-rose-600' : 'bg-amber-600' }} uppercase">
                                <i class="fas {{ $item->type === 'video' ? 'fa-video' : 'fa-image' }} me-1"></i> {{ strtoupper($item->type) }}
                            </span>
                            @if($item->type === 'foto' && $item->total_photos > 1)
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold text-white bg-indigo-600 shadow-xs">
                                    <i class="fas fa-layer-group me-1"></i> {{ $item->total_photos }} Foto
                                </span>
                            @endif
                        </div>

                        <div class="absolute top-3 right-3">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold {{ $item->is_active ? 'bg-emerald-500 text-white' : 'bg-slate-600 text-white' }}">
                                {{ $item->is_active ? 'Tampil' : 'Draft' }}
                            </span>
                        </div>
                    </div>

                    <!-- Content Details -->
                    <div class="px-4 pt-2 space-y-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">{{ $item->category }}</span>
                        <h4 class="font-bold text-slate-800 text-xs line-clamp-2 leading-snug">{{ $item->title }}</h4>
                        @if($item->caption)
                            <div class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed font-normal prose prose-xs">
                                {!! strip_tags($item->caption) !!}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="p-4 border-t border-slate-100 mt-4 flex items-center justify-between bg-slate-50/50">
                    <span class="text-[10px] text-slate-400 font-mono">Urutan: {{ $item->order }}</span>

                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.media.edit', preg_replace('/[\"\']/', '', $item->id)) }}" 
                           @click.prevent="openEdit({{ json_encode($item, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }})" 
                           class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block" title="Edit Media">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('admin.media.destroy', $item->id) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus galeri media ini?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Media">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400">
                <i class="fas fa-photo-video text-5xl mb-3 text-slate-300"></i>
                <p class="text-xs font-semibold">Belum ada data media ({{ $type }}) yang tersimpan.</p>
            </div>
        @endforelse
    </div>

    @if($mediaItems->hasPages())
        <div class="mt-6">
            {{ $mediaItems->links() }}
        </div>
    @endif

    <!-- Fullscreen Modal Form (Add / Edit Media Album) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4 bg-slate-900/70 backdrop-blur-sm overflow-hidden">
        <div class="bg-white rounded-2xl w-full max-w-5xl h-[92vh] shadow-2xl overflow-hidden flex flex-col border border-slate-200">
            
            <!-- Fixed Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-200 bg-slate-50 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">
                        <i class="fas fa-photo-video"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-800 text-base" x-text="editMode ? 'Edit Item / Album Media' : 'Tambah Album / Media Baru'"></h3>
                </div>
                <button type="button" @click="showModal = false" class="p-2 text-slate-400 hover:text-slate-700 rounded-xl transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Scrollable Form Body -->
            <form :action="editMode ? '{{ url('admin/media') }}/' + String(currentItem.id || '').replace(/['&quot;\\]/g, '') : '{{ route('admin.media.store') }}'" 
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
                    @if($errors->has('media_files'))
                        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 font-bold text-xs flex items-center gap-2">
                            <i class="fas fa-exclamation-circle text-rose-600"></i>
                            <span>{{ $errors->first('media_files') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <!-- Judul Media / Album -->
                        <div class="md:col-span-8">
                            <label class="block font-bold text-slate-700 mb-1">Judul Media / Nama Album <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" required x-model="currentItem.title" placeholder="Contoh: WASPADA MUSIM KEMARAU / Dokumentasi Simulasi Kebencanaan" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none text-xs font-semibold">
                        </div>

                        <!-- Urutan Tampil -->
                        <div class="md:col-span-4">
                            <label class="block font-bold text-slate-700 mb-1">Urutan Tampil (Order) <span class="text-rose-500">*</span></label>
                            <input type="number" name="order" required min="0" x-model="currentItem.order" placeholder="0" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none text-xs font-semibold">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <!-- Tipe Media -->
                        <div class="md:col-span-4">
                            <label class="block font-bold text-slate-700 mb-1">Tipe Media <span class="text-rose-500">*</span></label>
                            <select name="type" x-model="currentItem.type" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none text-xs font-semibold bg-white">
                                <option value="foto">Foto / Album Perangkat</option>
                                <option value="video">Video / YouTube Embed</option>
                            </select>
                        </div>

                        <!-- Kategori Fleksibel (Datalist Input) -->
                        <div class="md:col-span-4">
                            <label class="block font-bold text-slate-700 mb-1">Kategori Media <span class="text-rose-500">*</span></label>
                            <input type="text" 
                                   name="category" 
                                   list="category-list" 
                                   x-model="currentItem.category" 
                                   placeholder="Pilih atau ketik kategori baru..." 
                                   class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none text-xs font-semibold">
                            
                            <datalist id="category-list">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}"></option>
                                @endforeach
                            </datalist>
                        </div>

                        <!-- Status Publik -->
                        <div class="md:col-span-4 flex items-end pb-1">
                            <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-200 w-full">
                                <input type="checkbox" name="is_active" value="1" x-model="currentItem.is_active" class="w-4 h-4 text-orange-600 rounded">
                                <span class="text-xs">Tampilkan di Portal Publik</span>
                            </label>
                        </div>
                    </div>

                    <!-- Input Khusus Foto (Multiple Upload Album Dari Perangkat) -->
                    <div x-show="currentItem.type === 'foto'" class="space-y-3 bg-amber-50/60 p-4 rounded-xl border border-amber-200">
                        <label class="block font-bold text-slate-800 text-xs">Upload File Foto Album Dari Perangkat (Bisa Pilih Banyak Foto)</label>
                        <input type="file" 
                               name="media_files[]" 
                               multiple 
                               accept=".jpg,.jpeg,.png,image/jpeg,image/png" 
                               @change="handleFileValidation($event)"
                               class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-600 file:text-white hover:file:bg-amber-700 cursor-pointer border border-amber-300 rounded-xl bg-white">
                        
                        <p x-show="fileError" x-cloak class="text-xs font-bold text-rose-600 mt-1.5 flex items-center gap-1.5 bg-rose-50 p-2 rounded-lg border border-rose-200">
                            <span x-text="fileError"></span>
                        </p>
                        <p x-show="!fileError" class="text-[10px] text-slate-500 mt-1">*Hanya format: .JPG, .JPEG, .PNG (Dapat memilih lebih dari satu foto sekaligus untuk dibuat sebagai album).</p>

                        <!-- Preview Foto Existing yang Sedang Tersimpan -->
                        <template x-if="editMode && currentItem.photo_urls && currentItem.photo_urls.length > 0">
                            <div class="pt-3 border-t border-amber-200/80 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                        <i class="fas fa-images text-amber-600"></i>
                                        Foto Saat Ini Tersimpan (<span x-text="currentItem.photo_urls.length"></span> Foto):
                                    </span>
                                    <span class="text-[10px] text-slate-500 italic">File foto lama tetap tersimpan jika tidak memilih file baru di atas.</span>
                                </div>
                                <div class="flex flex-wrap gap-2.5 max-h-36 overflow-y-auto p-2 bg-white rounded-xl border border-amber-200">
                                    <template x-for="(photoUrl, pIdx) in currentItem.photo_urls" :key="pIdx">
                                        <div class="relative group w-16 h-16 sm:w-20 sm:h-20 rounded-lg overflow-hidden border border-slate-200 shadow-2xs shrink-0 bg-slate-100">
                                            <img :src="photoUrl" alt="Foto Album" class="w-full h-full object-cover">
                                            <span class="absolute bottom-0.5 right-0.5 bg-slate-900/80 text-[9px] font-mono font-bold text-white px-1 rounded" x-text="pIdx + 1"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Input Khusus Video -->
                    <div x-show="currentItem.type === 'video'" class="space-y-3 bg-rose-50/60 p-4 rounded-xl border border-rose-200">
                        <label class="block font-bold text-slate-800 text-xs">URL Video YouTube / Embed Link</label>
                        <input type="url" name="video_url" x-model="currentItem.video_url" placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/..." class="w-full px-3.5 py-2.5 border border-rose-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white text-xs font-mono">
                        <span class="text-[10px] text-slate-500 block">*Sistem otomatis mengkonversi link YouTube standar menjadi embed player interaktif.</span>

                        <!-- Info Video Existing jika ada URL tersimpan -->
                        <template x-if="editMode && currentItem.video_url">
                            <div class="pt-2 border-t border-rose-200 flex items-center justify-between text-[11px] text-slate-600">
                                <span class="flex items-center gap-1.5 font-bold text-rose-700">
                                    <i class="fab fa-youtube text-base text-rose-600"></i> Video Terdaftar:
                                </span>
                                <a :href="currentItem.video_url" target="_blank" class="text-rose-600 hover:underline font-mono truncate max-w-xs" x-text="currentItem.video_url"></a>
                            </div>
                        </template>
                    </div>

                    <!-- Keterangan / Deskripsi (CKEditor Visual Editor) -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Keterangan / Deskripsi Detail (Visual Editor)</label>
                        <textarea id="media_caption_textarea" name="caption" rows="4" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none"></textarea>
                    </div>

                </div>

                <!-- Fixed Footer with Batal and Simpan Buttons -->
                <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex justify-end gap-2 shrink-0">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-md transition flex items-center gap-2 text-xs">
                        <i class="fas fa-save"></i> Simpan Media
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
