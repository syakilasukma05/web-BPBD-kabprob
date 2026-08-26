@extends('admin.layout')

@section('page_title', 'Kelola Berita & Informasi Kebencanaan')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: {{ isset($newsItem) ? 'true' : 'false' }}, 
    editMode: {{ isset($newsItem) ? 'true' : 'false' }}, 
    currentNews: {{ isset($newsItem) ? json_encode($newsItem, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) : '{}' }},
    ckEditor: null,

    initNewsEditor(content = '') {
        this.$nextTick(() => {
            const clean = window.sanitizeCkHtml(content || '');
            if (this.ckEditor) {
                this.ckEditor.setData(clean);
                return;
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
        this.showModal = true;
        this.editMode = false;
        this.currentNews = { category: 'Peringatan Dini', published_at: '{{ date('Y-m-d') }}' };
        this.initNewsEditor('');
    },

    openEdit(newsData) {
        this.showModal = true;
        this.editMode = true;
        this.currentNews = Object.assign({}, newsData);
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

    <!-- Modal Form (Add / Edit News) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-3xl w-full shadow-2xl overflow-hidden flex flex-col max-h-[90vh] my-auto">
            
            <!-- Fixed Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-white shrink-0">
                <h3 class="font-bold text-slate-800 text-sm" x-text="editMode ? 'Edit Berita & Artikel' : 'Publish Berita Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition"><i class="fas fa-times"></i></button>
            </div>

            <!-- Scrollable Form Body -->
            <form :action="editMode ? '{{ url('admin/news') }}/' + String(currentNews.id || '').replace(/['&quot;\\]/g, '') : '{{ route('admin.news.store') }}'" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Judul Berita</label>
                        <input type="text" name="title" required x-model="currentNews.title" placeholder="Judul berita terbaru..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                            <select name="category" x-model="currentNews.category" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                                <option value="Peringatan Dini">Peringatan Dini</option>
                                <option value="Tanggap Darurat">Tanggap Darurat</option>
                                <option value="Mitigasi Bencana">Mitigasi Bencana</option>
                                <option value="Kegiatan Posko">Kegiatan Posko</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Tanggal Publish</label>
                            <input type="date" name="published_at" x-model="currentNews.published_at" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        </div>
                    </div>

                    <!-- Thumbnail Gambar Berita (Upload & URL) -->
                    <div class="space-y-2 bg-slate-50 p-3 rounded-xl border border-slate-200">
                        <label class="block font-bold text-slate-700">Gambar Thumbnail Berita</label>
                        <div>
                            <span class="text-[10px] text-slate-500 font-semibold block mb-1">Opsi 1: Upload File Gambar Dari Perangkat</span>
                            <input type="file" name="image_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-100 file:text-orange-700 hover:file:bg-orange-200 cursor-pointer">
                        </div>
                        <div class="pt-1 border-t border-slate-200">
                            <span class="text-[10px] text-slate-500 font-semibold block mb-1">Opsi 2: Atau Pakai Link / URL Gambar (Web)</span>
                            <input type="text" name="image_url" x-model="currentNews.image_url" placeholder="https://..." class="w-full px-3 py-1.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Link Target / Sumber Berita (Opsional)</label>
                        <input type="text" name="link_url" x-model="currentNews.link_url" placeholder="Contoh: https://detik.com/... atau https://instagram.com/..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none font-mono text-[11px]">
                        <p class="text-[10px] text-slate-400 mt-1">*Jika diisi, pengunjung yang mengklik berita akan diarahkan langsung ke link ini.</p>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Isi Berita (CKEditor 5 Visual Editor)</label>
                        <textarea id="news_content_textarea" name="content" x-model="currentNews.content" class="w-full min-h-[300px]"></textarea>
                    </div>
                </div>

                <!-- Fixed Footer with Batal and Simpan Buttons -->
                <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex justify-end gap-2 shrink-0">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-md transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Artikel
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
