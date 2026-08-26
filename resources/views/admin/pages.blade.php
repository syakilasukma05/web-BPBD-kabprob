@extends('admin.layout')

@section('page_title', 'Kelola Halaman Profil & Layanan')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: {{ isset($pageItem) ? 'true' : 'false' }}, 
    editMode: {{ isset($pageItem) ? 'true' : 'false' }}, 
    currentPage: {{ isset($pageItem) ? json_encode($pageItem, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) : '{}' }},
    ckEditor: null,

    initPageEditor(content = '') {
        this.$nextTick(() => {
            const clean = window.sanitizeCkHtml(content || '');
            if (this.ckEditor) {
                this.ckEditor.setData(clean);
                return;
            }
            window.initStandardCKEditor('page_content_textarea', clean, (data) => {
                this.currentPage.content = data;
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
        this.currentPage = {};
        this.initPageEditor('');
    },

    openEdit(pageData) {
        this.showModal = true;
        this.editMode = true;
        this.currentPage = Object.assign({}, pageData);
        this.initPageEditor(this.currentPage.content || '');
    }
}" x-init="
    @if(isset($pageItem))
        openEdit({!! json_encode($pageItem, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!});
    @endif
">
    
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Daftar Halaman Profil, Layanan, & Konten Publik</h3>
            <p class="text-xs text-slate-500 mt-1">Kelola konten statis seperti Struktur Organisasi, Visi Misi, Tugas & Fungsi, Peta Bencana, Standar Pelayanan, dll.</p>
        </div>
        <button type="button" @click="openCreate()" 
                class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2 transition hover:scale-[1.02]">
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
                            <a href="{{ route('admin.pages.edit', preg_replace('/[\"\']/', '', $page->id)) }}" 
                               @click.prevent="openEdit({{ json_encode($page, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }})" 
                               class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block" title="Edit Halaman"><i class="fas fa-edit"></i></a>
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
        <div class="bg-white rounded-2xl max-w-3xl w-full shadow-2xl overflow-hidden flex flex-col max-h-[90vh] my-auto">
            
            <!-- Fixed Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-white shrink-0">
                <h3 class="font-bold text-slate-800 text-sm" x-text="editMode ? 'Edit Halaman Statis' : 'Tambah Halaman Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition"><i class="fas fa-times"></i></button>
            </div>

            <!-- Scrollable Form Body -->
            <form :action="editMode ? '{{ url('admin/pages') }}/' + String(currentPage.id || '').replace(/['&quot;\\]/g, '') : '{{ route('admin.pages.store') }}'" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Judul Halaman</label>
                        <input type="text" name="title" required x-model="currentPage.title" placeholder="Contoh: Peta Rawan Bencana" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Isi Konten Halaman (CKEditor 5 Visual Editor)</label>
                        <textarea id="page_content_textarea" name="content" x-model="currentPage.content" class="w-full min-h-[350px]"></textarea>
                    </div>

                    <!-- Dokumen Pendukung Halaman (PDF) -->
                    <template x-if="editMode && currentPage.id">
                        <div class="pt-4 border-t border-slate-200 space-y-4">
                            <div class="flex items-center justify-between bg-slate-50 p-3 rounded-xl border border-slate-200">
                                <h4 class="font-bold text-slate-800 text-xs flex items-center gap-2">
                                    <i class="fas fa-file-pdf text-rose-600"></i> Dokumen Pendukung Halaman (PDF)
                                </h4>
                                <span class="text-[10px] text-slate-400">Max 10MB (.pdf)</span>
                            </div>

                            <!-- List PDF Existing -->
                            <div class="space-y-2">
                                <template x-for="doc in (currentPage.documents || [])" :key="doc.id">
                                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-2 overflow-hidden">
                                            <i class="fas fa-file-pdf text-rose-600 text-base shrink-0"></i>
                                            <span class="font-bold text-slate-800 truncate" x-text="doc.file_name"></span>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0">
                                            <a :href="'{{ url('page-documents') }}/' + doc.id + '/view'" target="_blank" class="px-2.5 py-1 bg-sky-50 text-sky-700 font-bold text-[11px] rounded-lg border border-sky-200 hover:bg-sky-100 transition">Preview</a>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
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
