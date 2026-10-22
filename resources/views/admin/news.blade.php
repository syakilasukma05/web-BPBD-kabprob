@extends('admin.layout')

@section('page_title', 'Kelola Berita & Informasi Kebencanaan')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: {{ isset($newsItem) ? 'true' : 'false' }}, 
    editMode: {{ isset($newsItem) ? 'true' : 'false' }}, 
    currentNews: {{ isset($newsItem) ? json_encode($newsItem, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) : '{}' }},
    imageError: '',
    ckEditor: null,

    validateNewsImageFile(event) {
        const file = event.target.files[0];
        if (!file) return;
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        const ext = file.name.split('.').pop().toLowerCase();
        const allowedExts = ['jpg', 'jpeg', 'png'];

        if (!allowedTypes.includes(file.type) && !allowedExts.includes(ext)) {
            this.imageError = 'Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.';
            event.target.value = '';
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            this.imageError = 'Ukuran file gambar terlalu besar. Maksimum 5 MB.';
            event.target.value = '';
            return;
        }

        this.imageError = '';
    },

    initNewsEditor(content = '') {
        this.$nextTick(() => {
            const clean = window.sanitizeCkHtml(content || '');
            const textarea = document.getElementById('news_content_textarea');
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

            window.initStandardCKEditor('news_content_textarea', clean, (data) => {
                this.currentNews.content = data;
            }).then(editor => {
                this.ckEditor = editor;
            }).catch(err => {
                console.warn('CKEditor init warning:', err);
            });
        });
    },

    openCreate() {
        this.imageError = '';
        this.showModal = true;
        this.editMode = false;
        this.currentNews = { 
            category: 'Peringatan Dini', 
            published_at: '{{ date('Y-m-d') }}',
            status: 'aktif',
            order: {{ $nextOrder ?? 1 }}
        };
        this.initNewsEditor('');
    },

    openEdit(newsData) {
        this.imageError = '';
        this.showModal = true;
        this.editMode = true;
        this.currentNews = Object.assign({ status: 'aktif', order: 1 }, newsData);
        this.initNewsEditor(this.currentNews.content || '');
    }
}" x-init="
    @if(isset($newsItem))
        openEdit({!! json_encode($newsItem, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!});
    @endif
">
    
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Daftar Berita & Pengumuman</h3>
            <p class="text-xs text-slate-500 mt-1">Kelola artikel berita publik yang tampil di homepage dan portal berita BPBD</p>
        </div>
        <button type="button" @click="openCreate()" 
                class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2 transition hover:scale-[1.02]">
            <i class="fas fa-plus"></i> Publish Berita Baru
        </button>
    </div>

    <!-- News Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-4">Thumbnail</th>
                    <th class="px-6 py-4">Judul Berita</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Tanggal Publish</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Views</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($newsList as $news)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <img src="{{ $news->image_url ?? 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb' }}" alt="News" class="h-12 w-16 object-cover rounded-lg border border-slate-200">
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <h4 class="font-bold text-slate-800 text-xs line-clamp-2">{{ $news->title }}</h4>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-orange-100 text-orange-800">
                                {{ $news->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-medium">
                            {{ optional($news->published_at)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if(($news->status ?? 'aktif') === 'nonaktif')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200 inline-flex items-center gap-1">
                                    <i class="fas fa-eye-slash text-[9px] text-slate-400"></i> Nonaktif
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200 inline-flex items-center gap-1">
                                    <i class="fas fa-check-circle text-[9px] text-emerald-600"></i> Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-bold">
                            {{ $news->views }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <a href="{{ route('admin.news.edit', preg_replace('/[\"\']/', '', $news->id)) }}" 
                               @click.prevent="openEdit({{ json_encode($news, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }})" 
                               class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block" title="Edit Berita"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.news.destroy', $news->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus berita ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">
            {{ $newsList->links() }}
        </div>
    </div>

    <!-- Modal Form (Add / Edit News) - FULL SCREEN MODAL -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/75 backdrop-blur-md flex flex-col justify-between overflow-hidden">
        <div class="bg-white w-full h-full flex flex-col overflow-hidden">
            
            <!-- Fixed Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-200 bg-white shrink-0 shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-sm">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-800 text-base" x-text="editMode ? 'Edit Berita & Artikel' : 'Publish Berita Baru'"></h3>
                        <p class="text-[11px] text-slate-500">Isi formulir berita di bawah ini untuk dipublikasikan ke portal publik BPBD.</p>
                    </div>
                </div>
                <button type="button" @click="showModal = false" class="p-2 text-slate-400 hover:text-slate-700 transition rounded-xl hover:bg-slate-100"><i class="fas fa-times text-xl"></i></button>
            </div>

            <!-- Scrollable Form Body -->
            <form :action="editMode ? '{{ url('admin/news') }}/' + String(currentNews.id || '').replace(/['&quot;\\]/g, '') : '{{ route('admin.news.store') }}'" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div class="p-6 sm:p-8 space-y-6 text-xs overflow-y-auto flex-1 max-w-5xl mx-auto w-full">
                    <!-- Judul Berita -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5 text-xs">Judul Berita <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required x-model="currentNews.title" placeholder="Judul berita terbaru..." class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none font-bold text-slate-800 text-sm">
                    </div>

                    <!-- Kategori, Tanggal Publish, Nomor Urut -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Kategori (Combobox Manual + List) -->
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">Kategori Berita <span class="text-rose-500">*</span></label>
                            <input type="text" 
                                   name="category" 
                                   list="news_category_datalist" 
                                   required 
                                   x-model="currentNews.category" 
                                   placeholder="Ketik atau pilih kategori..." 
                                   class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white font-medium text-slate-800 text-xs">
                            <datalist id="news_category_datalist">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}"></option>
                                @endforeach
                            </datalist>
                            <p class="text-[10px] text-slate-400 mt-1">Bisa diketik manual untuk membuat kategori baru.</p>
                        </div>

                        <!-- Tanggal Publish -->
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">Tanggal Publish</label>
                            <input type="date" name="published_at" x-model="currentNews.published_at" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white font-medium text-slate-800 text-xs">
                        </div>

                        <!-- Nomor Urut -->
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">Nomor Urut</label>
                            <input type="number" name="order" min="1" x-model="currentNews.order" placeholder="1" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white font-bold text-slate-800 text-xs">
                            <p class="text-[10px] text-slate-400 mt-1">Angka lebih kecil tampil lebih atas pada portal.</p>
                        </div>
                    </div>

                    <!-- Status Tampilan -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">Status Tampilan</label>
                        <div class="flex items-center gap-6 pt-1 bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                            <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                                <input type="radio" name="status" value="aktif" x-model="currentNews.status" class="text-orange-600 focus:ring-orange-500">
                                <span class="text-emerald-700 font-extrabold flex items-center gap-1.5">
                                    <i class="fas fa-check-circle text-emerald-600"></i> Aktif (Tampil di Website Publik)
                                </span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                                <input type="radio" name="status" value="nonaktif" x-model="currentNews.status" class="text-orange-600 focus:ring-orange-500">
                                <span class="text-slate-500 font-extrabold flex items-center gap-1.5">
                                    <i class="fas fa-eye-slash text-slate-400"></i> Nonaktif (Disimpan Sebagai Draf)
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Thumbnail Gambar Berita (Hanya Upload File Gambar) -->
                    <div class="space-y-3 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                        <label class="block font-bold text-slate-700 text-xs flex items-center justify-between">
                            <span>Upload File Gambar Thumbnail</span>
                            <span class="text-[10px] text-orange-600 font-semibold">Format JPG, JPEG, PNG (Max 5MB)</span>
                        </label>
                        
                        <template x-if="imageError">
                            <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold rounded-xl flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-exclamation-triangle text-rose-600"></i>
                                    <span x-text="imageError"></span>
                                </div>
                                <button type="button" @click="imageError = ''" class="text-rose-400 hover:text-rose-600"><i class="fas fa-times"></i></button>
                            </div>
                        </template>

                        <input type="file" name="image_file" accept=".jpg,.jpeg,.png,image/jpeg,image/png" @change="validateNewsImageFile($event)"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-100 file:text-orange-700 hover:file:bg-orange-200 cursor-pointer bg-white border border-slate-300 rounded-xl p-1">
                        <p class="text-[10px] text-slate-400">Pilih file gambar dari perangkat dengan format JPG, JPEG, atau PNG.</p>

                        <!-- Preview Thumbnail Existing -->
                        <template x-if="editMode && currentNews.image_url">
                            <div class="pt-3 border-t border-slate-200 flex items-center gap-4">
                                <span class="text-[11px] text-slate-600 font-bold">Thumbnail Saat Ini:</span>
                                <img :src="currentNews.image_url" class="h-14 w-20 object-cover rounded-xl border border-slate-300 shadow-xs">
                            </div>
                        </template>
                    </div>

                    <!-- Link Target / Sumber Berita -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Link Target / Sumber Berita (Opsional)</label>
                        <input type="text" name="link_url" x-model="currentNews.link_url" placeholder="Contoh: https://detik.com/... atau https://instagram.com/..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none font-mono text-xs">
                        <p class="text-[10px] text-slate-400 mt-1">*Jika diisi, pengunjung yang mengklik berita akan diarahkan langsung ke link ini.</p>
                    </div>

                    <!-- CKEditor Content -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">Isi Berita (CKEditor 5 Visual Editor)</label>
                        <textarea id="news_content_textarea" name="content" x-model="currentNews.content" class="w-full min-h-[350px]"></textarea>
                    </div>
                </div>

                <!-- Fixed Footer with Batal and Simpan Buttons -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3 shrink-0">
                    <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Artikel
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
