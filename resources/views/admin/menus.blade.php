@extends('admin.layout')

@section('page_title', 'CMS Editor Konten Halaman')

@section('content')
<style>
    .ck-editor__editable_inline {
        min-height: 450px !important;
        max-height: 700px !important;
        font-size: 14px;
        line-height: 1.7;
        padding: 1.5rem !important;
        border-bottom-left-radius: 1rem !important;
        border-bottom-right-radius: 1rem !important;
        background-color: #ffffff !important;
    }
    .ck-toolbar {
        border-top-left-radius: 1rem !important;
        border-top-right-radius: 1rem !important;
        background-color: #f8fafc !important;
        border-color: #e2e8f0 !important;
        padding: 0.6rem !important;
    }
    .ck-editor {
        border-radius: 1rem !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        border-color: #cbd5e1 !important;
    }
    
    .ck-content h1 {
        font-size: 1.875rem !important;
        font-weight: 800 !important;
        margin-top: 1.25rem !important;
        margin-bottom: 0.75rem !important;
        color: #0f172a !important;
    }
    .ck-content h2 {
        font-size: 1.5rem !important;
        font-weight: 800 !important;
        margin-top: 1.25rem !important;
        margin-bottom: 0.75rem !important;
        color: #1e293b !important;
    }
    .ck-content h3 {
        font-size: 1.25rem !important;
        font-weight: 700 !important;
        margin-top: 1rem !important;
        margin-bottom: 0.5rem !important;
        color: #334155 !important;
    }
    .ck-content p {
        margin-bottom: 0.85rem !important;
        line-height: 1.7 !important;
        color: #334155 !important;
    }
    .ck-content ul {
        list-style-type: disc !important;
        padding-left: 1.5rem !important;
        margin-bottom: 0.85rem !important;
    }
    .ck-content ol {
        list-style-type: decimal !important;
        padding-left: 1.5rem !important;
        margin-bottom: 0.85rem !important;
    }
    .ck-content table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-bottom: 1rem !important;
    }
    .ck-content table td, .ck-content table th {
        border: 1px solid #cbd5e1 !important;
        padding: 0.5rem 0.75rem !important;
    }
    .ck-content blockquote {
        border-left: 4px solid #2563eb !important;
        padding-left: 1rem !important;
        font-style: italic !important;
        color: #475569 !important;
        margin-bottom: 1rem !important;
    }
</style>

<div x-data="{ 
    currentView: 'list', 
    activeTab: 1, 
    imageError: '',
    pdfError: '',
    linkError: '',
    linkForm: {
        id: null,
        title: '',
        url: '',
        description: '',
        target: '_blank',
        status: 'aktif'
    },
    currentMenu: {
        id: null,
        page_id: null,
        title: '',
        url: '',
        page_content: '',
        image_url: '',
        image_name: '',
        image_size: null,
        documents: [],
        links: []
    },
    ckEditor: null,

    validateUrlInput(url) {
        this.linkError = '';
        if (!url || !url.trim()) {
            this.linkError = 'Format URL tidak valid.';
            return false;
        }
        const trimmed = url.trim();
        if (/^(javascript|file|data):/i.test(trimmed)) {
            this.linkError = 'Format URL tidak valid.';
            return false;
        }
        if (!/^https?:\/\//i.test(trimmed)) {
            this.linkError = 'Format URL tidak valid.';
            return false;
        }
        try {
            const parsed = new URL(trimmed);
            if (parsed.protocol !== 'http:' && parsed.protocol !== 'https:') {
                this.linkError = 'Format URL tidak valid.';
                return false;
            }
        } catch (e) {
            this.linkError = 'Format URL tidak valid.';
            return false;
        }
        return true;
    },

    resetLinkForm() {
        this.linkError = '';
        this.linkForm = {
            id: null,
            title: '',
            url: '',
            description: '',
            target: '_blank',
            status: 'aktif'
        };
    },

    editLink(linkData) {
        this.linkError = '';
        this.linkForm = {
            id: linkData.id,
            title: linkData.title || '',
            url: linkData.url || '',
            description: linkData.description || '',
            target: linkData.target || '_blank',
            status: linkData.status || 'aktif'
        };
        this.activeTab = 4;
    },

    validateImageFile(event) {
        const file = event.target.files[0];
        this.imageError = '';
        if (!file) return;

        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/pjpeg', 'image/x-png'];
        const allowedExts = ['jpg', 'jpeg', 'png'];
        const ext = file.name.split('.').pop().toLowerCase();

        if (!allowedTypes.includes(file.type.toLowerCase()) && !allowedExts.includes(ext)) {
            this.imageError = 'Format file tidak valid. Upload hanya JPG, JPEG, atau PNG.';
            event.target.value = '';
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            this.imageError = 'Ukuran file gambar tidak boleh melebihi 5 MB.';
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            this.currentMenu.image_url = e.target.result;
            this.currentMenu.image_name = file.name;
            this.currentMenu.image_size = file.size;
        };
        reader.readAsDataURL(file);
    },

    validatePdfFile(event) {
        const file = event.target.files[0];
        this.pdfError = '';
        if (!file) return;

        const allowedTypes = ['application/pdf', 'application/x-pdf', 'application/acrobat', 'applications/vnd.pdf', 'text/pdf', 'text/x-pdf'];
        const ext = file.name.split('.').pop().toLowerCase();

        if (ext !== 'pdf' && !allowedTypes.includes(file.type.toLowerCase())) {
            this.pdfError = 'Format dokumen tidak valid. Hanya file PDF yang diperbolehkan.';
            event.target.value = '';
            return;
        }

        if (file.size > 10 * 1024 * 1024) {
            this.pdfError = 'Ukuran file terlalu besar. Maksimal 10 MB.';
            event.target.value = '';
            return;
        }
    },
    
    decodeHtml(html) {
        if (!html) return '';
        let current = String(html);
        if (current.includes('&lt;') || current.includes('&gt;') || current.includes('&amp;')) {
            const txt = document.createElement('textarea');
            txt.innerHTML = current;
            current = txt.value;
        }
        return current.trim();
    },

    initCKEditor(content = '') {
        const setEditorData = (editor, data) => {
            if (!editor) return;
            try {
                editor.setData(data || '');
            } catch(e) {
                console.warn('CKEditor setData error:', e);
            }
        };

        this.$nextTick(() => {
            const rawContent = (content !== undefined && content !== null) ? String(content) : (this.currentMenu.page_content || '');
            const cleanContent = window.sanitizeCkHtml(this.decodeHtml(rawContent));
            const textarea = document.getElementById('ckeditor_page_content');
            if (textarea) {
                textarea.value = cleanContent;
            }

            if (textarea && textarea.ckeditorInstance) {
                this.ckEditor = textarea.ckeditorInstance;
                setEditorData(this.ckEditor, cleanContent);
                return;
            }

            if (this.ckEditor && typeof this.ckEditor.setData === 'function') {
                setEditorData(this.ckEditor, cleanContent);
                return;
            }

            window.initStandardCKEditor('ckeditor_page_content', cleanContent, (data) => {
                this.currentMenu.page_content = data;
            }).then(editor => {
                this.ckEditor = editor;
                setEditorData(editor, cleanContent);
            }).catch(err => {
                console.warn('CKEditor init warning:', err);
            });
        });
    },

    openEdit(menuData) {
        this.imageError = '';
        this.pdfError = '';
        this.linkError = '';
        this.currentMenu = Object.assign({}, menuData);
        this.currentMenu.type = menuData.type || (menuData.is_external || /^https?:\/\//i.test(menuData.url || '') ? 'external' : 'internal');
        this.currentMenu.is_external = (this.currentMenu.type === 'external');
        this.currentMenu.page_id = menuData.page_id || null;
        this.currentMenu.image_name = menuData.image_name || '';
        this.currentMenu.image_size = menuData.image_size || null;
        this.currentMenu.documents = menuData.documents || [];
        this.currentMenu.links = menuData.links || [];
        this.resetLinkForm();
        
        const rawContent = menuData.page_content || '';
        const cleanContent = this.decodeHtml(rawContent);
        this.currentMenu.page_content = cleanContent;
        this.currentView = 'editor';
        this.activeTab = 1;
        if (!this.currentMenu.is_external) {
            this.initCKEditor(cleanContent);
        }
    },

    submitContent() {
        if (this.currentMenu.type === 'external' || this.currentMenu.is_external) {
            const extForm = document.getElementById('cmsExternalMenuForm');
            if (extForm) {
                extForm.submit();
                return;
            }
        }

        const el = document.getElementById('ckeditor_page_content');
        const editor = this.ckEditor || (el ? el.ckeditorInstance : null);

        if (editor && typeof editor.getData === 'function') {
            const data = editor.getData();
            if (data && data.trim() !== '') {
                if (el) el.value = data;
                this.currentMenu.page_content = data;
            } else if (this.currentMenu.page_content && this.currentMenu.page_content.trim() !== '') {
                if (el) el.value = this.currentMenu.page_content;
            } else {
                if (el) el.value = '';
                this.currentMenu.page_content = '';
            }
        } else if (el && this.currentMenu.page_content) {
            el.value = this.currentMenu.page_content;
        }

        this.$nextTick(() => {
            const form = document.getElementById('cmsMenuEditorForm');
            if (form) form.submit();
        });
    }
}" x-init="
    @if(isset($menuItem))
        openEdit({!! json_encode(array_merge($menuItem->toArray(), ['page_content' => $menuItem->page_content ?? '', 'image_url' => $menuItem->image_url ?? '', 'image_name' => $menuItem->image_name ?? '', 'image_size' => $menuItem->image_size ?? null, 'page_id' => $menuItem->page_id ?? null, 'documents' => $menuItem->documents ?? [], 'links' => $menuItem->links ?? []]), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!});
    @endif
">

    <!-- VIEW 1: DAFTAR KONTEN HALAMAN (LIST VIEW) -->
    <div x-show="currentView === 'list'" x-cloak class="space-y-6">
        <!-- List Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800 tracking-tight flex items-center gap-2.5">
                    <i class="fas fa-file-signature text-emerald-600 text-lg"></i> CMS Editor Konten Halaman
                </h3>
                <p class="text-xs text-slate-500 mt-1">Kelola dan edit isi konten halaman publik (teks, gambar, tabel, media) menggunakan CKEditor 5.</p>
            </div>
            <a href="{{ route('admin.navigation') }}" 
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-md transition-all hover:scale-[1.02]">
                <i class="fas fa-sitemap"></i> Kelola Struktur Navigasi
            </a>
        </div>

        <!-- Menus & Pages Content List Card Tree -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Daftar Konten Halaman Publikasi</span>
                <span class="text-xs text-slate-400 font-medium">Total Menu Utama: {{ count($menus) }}</span>
            </div>

            @foreach($menus as $menu)
                <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs hover:border-slate-300 transition-all">
                    
                    <!-- Main Menu Container Header Row -->
                    <div class="bg-slate-50 p-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-slate-200 text-slate-700 text-xs font-extrabold flex items-center justify-center border border-slate-300 shadow-2xs">
                                #{{ $menu->order }}
                            </span>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="font-extrabold text-slate-800 text-sm tracking-wide">{{ $menu->title }}</h4>
                                    @if($menu->is_parent_container)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700 border border-slate-300">Parent Menu (Dropdown Header)</span>
                                    @endif
                                </div>
                                <span class="text-[11px] text-slate-400 font-mono">{{ $menu->url }}</span>
                            </div>
                        </div>

                        <!-- Only show Edit Konten button if main menu itself is an actual content page (not a parent container) -->
                        @if(!$menu->is_parent_container)
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.menus.edit', preg_replace('/[\"\']/', '', $menu->id)) }}" 
                                   @click.prevent='openEdit({!! json_encode(array_merge($menu->toArray(), ['page_content' => $menu->page_content ?? '', 'image_url' => $menu->image_url ?? '', 'image_name' => $menu->image_name ?? '', 'image_size' => $menu->image_size ?? null, 'page_id' => $menu->page_id ?? null, 'documents' => $menu->documents ?? [], 'links' => $menu->links ?? []]), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!})' 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/20 transition-all transform hover:scale-[1.02]">
                                    <i class="fas fa-edit text-xs"></i> Edit Konten Halaman
                                </a>
                            </div>
                        @else
                            <span class="text-[10px] text-slate-400 font-semibold italic">Gunakan submenu di bawah untuk edit konten</span>
                        @endif
                    </div>

                    <!-- Submenus Content Rows -->
                    @if($menu->children && count($menu->children) > 0)
                        <div class="p-3 bg-white divide-y divide-slate-100 pl-6 sm:pl-10">
                            @foreach($menu->children as $child)
                                <div class="py-3 flex items-center justify-between text-xs hover:bg-slate-50/50 px-3 rounded-xl transition">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-level-up-alt rotate-90 text-slate-400"></i>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-extrabold text-slate-800 text-xs">{{ $child->title }}</span>
                                                @php
                                                    $childTitleLower = strtolower($child->title ?? '');
                                                    $isSpecialContentPage = \Illuminate\Support\Str::contains($childTitleLower, ['kajian risiko', 'krb', 'peringatan dini cuaca', 'bmkg', 'sp4n', 'lapor', 'kontak']);
                                                    $hasContent = !empty(trim(strip_tags($child->page_content ?? ''))) 
                                                        || !empty($child->image_url) 
                                                        || (!empty($child->documents) && count($child->documents) > 0)
                                                        || (!empty($child->links) && count($child->links) > 0);
                                                @endphp
                                                @if($child->is_external && !$isSpecialContentPage)
                                                    <span class="text-[9px] px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 font-bold border border-sky-200">Tautan Eksternal</span>
                                                @elseif($hasContent)
                                                    <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-extrabold border border-emerald-200 inline-flex items-center gap-1">
                                                        <i class="fas fa-check-circle text-[9px] text-emerald-600"></i> Konten Terisi
                                                    </span>
                                                @elseif($child->is_editable_page || $isSpecialContentPage)
                                                    <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 font-extrabold border border-amber-200 inline-flex items-center gap-1">
                                                        <i class="fas fa-edit text-[9px] text-amber-600"></i> Siap Diisi
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="text-[11px] text-slate-400 font-mono">({{ $child->url }})</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.menus.edit', preg_replace('/[\"\']/', '', $child->id)) }}" 
                                           @click.prevent='openEdit({!! json_encode(array_merge($child->toArray(), ['page_content' => $child->page_content ?? '', 'image_url' => $child->image_url ?? '', 'image_name' => $child->image_name ?? '', 'image_size' => $child->image_size ?? null, 'page_id' => $child->page_id ?? null, 'documents' => $child->documents ?? [], 'links' => $child->links ?? []]), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!})' 
                                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition transform hover:scale-[1.02]">
                                            <i class="fas fa-pen-to-square text-xs"></i> Edit Konten Halaman
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- VIEW 2: EDITOR KONTEN HALAMAN (PURE CMS CKEDITOR FORM) -->
    <div x-show="currentView === 'editor'" x-cloak class="space-y-6">
        
        <!-- Top Toolbar Actions -->
        <div class="flex items-center justify-between bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <button type="button" @click="currentView = 'list'" 
                        class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali Ke Daftar Konten
                </button>
                <div>
                    <h3 class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight" x-text="'Edit Isi Halaman: ' + currentMenu.title"></h3>
                    <p class="text-xs text-slate-400 font-mono" x-text="currentMenu.url"></p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" @click="submitContent()" 
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Konten Halaman
                </button>
            </div>
        </div>

        <!-- External Link Dedicated Form (If menu is external link type) -->
        <div x-show="currentMenu.type === 'external' || currentMenu.is_external">
            <form id="cmsExternalMenuForm" :action="'{{ url('admin/menus') }}/' + String(currentMenu.id || '').replace(/['&quot;\\]/g, '')" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="type" value="external">

                <div class="flex items-center justify-between bg-sky-50 p-4 rounded-2xl border border-sky-200">
                    <div>
                        <h4 class="font-extrabold text-sky-900 text-sm flex items-center gap-2">
                            <i class="fas fa-external-link-alt text-sky-600"></i> Form Pengaturan Tautan Eksternal (External Link)
                        </h4>
                        <p class="text-xs text-sky-700 mt-0.5">Menu ini bertipe Tautan Eksternal. Pengunjung akan langsung diarahkan ke URL tujuan saat mengklik menu ini di navigasi.</p>
                    </div>
                    <span class="text-[10px] bg-sky-100 text-sky-800 font-extrabold px-3 py-1 rounded-full border border-sky-200 uppercase">External Link</span>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Judul Link <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required x-model="currentMenu.title" placeholder="Contoh: SP4N Lapor" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white font-bold text-slate-800 text-sm">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">URL Tujuan <span class="text-rose-500">*</span></label>
                        <input type="text" name="url" required x-model="currentMenu.url" placeholder="Contoh: https://www.lapor.go.id/" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white font-mono text-xs">
                        <p class="text-[10px] text-slate-400 mt-1">Pastikan URL diawali dengan <code>http://</code> atau <code>https://</code>.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">Target Buka Link</label>
                            <div class="flex items-center gap-4 pt-1">
                                <label class="flex items-center gap-2 cursor-pointer font-medium text-slate-700">
                                    <input type="radio" name="target" value="_blank" x-model="currentMenu.target" class="text-sky-600 focus:ring-sky-500">
                                    <span>Tab baru (_blank)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer font-medium text-slate-700">
                                    <input type="radio" name="target" value="_self" x-model="currentMenu.target" class="text-sky-600 focus:ring-sky-500">
                                    <span>Tab saat ini (_self)</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">Status Tampil</label>
                            <div class="flex items-center gap-4 pt-1">
                                <label class="flex items-center gap-2 cursor-pointer font-medium text-slate-700">
                                    <input type="radio" name="status" value="aktif" :checked="currentMenu.is_active" @change="currentMenu.is_active = true" class="text-sky-600 focus:ring-sky-500">
                                    <span class="text-emerald-700 font-bold">Aktif</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer font-medium text-slate-700">
                                    <input type="radio" name="status" value="nonaktif" :checked="!currentMenu.is_active" @change="currentMenu.is_active = false" class="text-sky-600 focus:ring-sky-500">
                                    <span class="text-slate-500">Nonaktif</span>
                                </label>
                                <input type="hidden" name="is_active" :value="currentMenu.is_active ? '1' : '0'">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Konten Halaman
                    </button>
                </div>
            </form>
        </div>

        <!-- Main CMS Form Container (Internal Page Editor) -->
        <div x-show="currentMenu.type !== 'external' && !currentMenu.is_external">
            <form id="cmsMenuEditorForm" :action="'{{ url('admin/menus') }}/' + String(currentMenu.id || '').replace(/['&quot;\\]/g, '')" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="title" x-model="currentMenu.title">
                <input type="hidden" name="url" x-model="currentMenu.url">
                <input type="hidden" name="type" value="internal">
                <input type="hidden" name="parent_id" :value="currentMenu.parent_id || ''">
                <input type="hidden" name="order" :value="currentMenu.order ?? 0">

            <!-- Tab Navigation Bar -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="flex border-b border-slate-200 bg-slate-50/50">
                    <button type="button" @click="activeTab = 1" 
                            class="px-6 py-3.5 text-xs font-bold transition-all border-b-2 flex items-center gap-2"
                            :class="activeTab === 1 ? 'border-emerald-600 text-emerald-700 bg-white shadow-xs' : 'border-transparent text-slate-500 hover:text-slate-800'">
                        <i class="fas fa-file-alt"></i> Visual Editor CKEditor 5
                    </button>
                    <button type="button" @click="activeTab = 2" 
                            class="px-6 py-3.5 text-xs font-bold transition-all border-b-2 flex items-center gap-2"
                            :class="activeTab === 2 ? 'border-emerald-600 text-emerald-700 bg-white shadow-xs' : 'border-transparent text-slate-500 hover:text-slate-800'">
                        <i class="fas fa-image"></i> Gambar Headline & Banner Halaman
                    </button>
                    <button type="button" @click="activeTab = 3" 
                            class="px-6 py-3.5 text-xs font-bold transition-all border-b-2 flex items-center gap-2"
                            :class="activeTab === 3 ? 'border-rose-600 text-rose-700 bg-white shadow-xs' : 'border-transparent text-slate-500 hover:text-slate-800'">
                        <i class="fas fa-file-pdf text-rose-500"></i> Dokumen PDF Halaman
                    </button>
                    <button type="button" @click="activeTab = 4" 
                            class="px-6 py-3.5 text-xs font-bold transition-all border-b-2 flex items-center gap-2"
                            :class="activeTab === 4 ? 'border-orange-600 text-orange-700 bg-white shadow-xs' : 'border-transparent text-slate-500 hover:text-slate-800'">
                        <i class="fas fa-link text-orange-500"></i> Link URL Halaman
                    </button>
                </div>

                <!-- Tab 1: CKEditor 5 Container -->
                <div x-show="activeTab === 1" class="p-6 space-y-4">
                    <div class="flex items-center justify-between bg-slate-50 p-3 rounded-xl border border-slate-200">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">EDITOR KONTEN VISUAL HALAMAN (CKEDITOR 5)</span>
                        <span class="text-[11px] text-slate-400 font-medium">Mendukung format Teks, Tabel, Gambar, Bullet, Quote & Media</span>
                    </div>

                    <div>
                        <textarea id="ckeditor_page_content" name="page_content" :value="currentMenu.page_content" class="w-full min-h-[450px]"></textarea>
                    </div>
                </div>

                <!-- Tab 2: Gambar Headline & Media -->
                <div x-show="activeTab === 2" class="p-6 space-y-6">
                    <div class="flex items-center justify-between bg-slate-50 p-4 rounded-2xl border border-slate-200">
                        <div>
                            <h4 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                                <i class="fas fa-image text-emerald-600"></i> Gambar Headline & Banner Halaman
                            </h4>
                            <p class="text-xs text-slate-500 mt-0.5">Unggah gambar header/banner utama (hanya JPG, JPEG, PNG) yang akan tampil secara otomatis di bagian atas halaman publik.</p>
                        </div>
                        <span class="text-[10px] bg-emerald-100 text-emerald-800 font-extrabold px-3 py-1 rounded-full border border-emerald-200 uppercase">Khusus JPG, JPEG, PNG (Max 5MB)</span>
                    </div>

                    <!-- Error Notification Banner for Image Upload -->
                    <template x-if="imageError">
                        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold rounded-2xl flex items-center justify-between shadow-xs">
                            <div class="flex items-center gap-2.5">
                                <i class="fas fa-exclamation-triangle text-rose-600 text-lg"></i>
                                <span x-text="imageError"></span>
                            </div>
                            <button type="button" @click="imageError = ''" class="text-rose-400 hover:text-rose-600"><i class="fas fa-times"></i></button>
                        </div>
                    </template>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Opsi 1: Upload File Gambar Dari Perangkat -->
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-3">
                            <label class="block font-bold text-slate-700 text-xs flex items-center justify-between">
                                <span>Opsi 1: Upload Gambar Dari Perangkat</span>
                                <span class="text-[10px] text-emerald-600 font-semibold">.jpg, .jpeg, .png (Max 5MB)</span>
                            </label>
                            <input type="file" name="image_file" accept=".jpg,.jpeg,.png,image/jpeg,image/png" @change="validateImageFile($event)"
                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200 cursor-pointer bg-white border border-slate-300 rounded-xl p-1">
                            <p class="text-[11px] text-slate-400">Pilih file gambar berformat JPG, JPEG, atau PNG dengan ukuran maksimum 5 MB.</p>
                        </div>

                        <!-- Opsi 2: Web URL Gambar -->
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-3">
                            <label class="block font-bold text-slate-700 text-xs">Opsi 2: Link / URL Gambar Eksternal</label>
                            <input type="text" name="image_url" x-model="currentMenu.image_url" placeholder="https://domain.com/gambar.jpg" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white font-mono text-xs">
                            <p class="text-[11px] text-slate-400">Masukkan tautan URL gambar langsung jika tidak mengunggah file dari perangkat.</p>
                        </div>
                    </div>

                    <!-- Preview & Rincian File Gambar Headline -->
                    <template x-if="currentMenu.image_url">
                        <div class="space-y-4 pt-4 border-t border-slate-200">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-700 flex items-center gap-2">
                                    <i class="fas fa-eye text-slate-500"></i> Preview Gambar Headline Saat Ini:
                                </span>
                                <template x-if="currentMenu.page_id">
                                    <form :action="'{{ url('admin/pages') }}/' + currentMenu.page_id + '/image'" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus gambar headline ini?')">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-xl border border-rose-200 transition flex items-center gap-1.5">
                                            <i class="fas fa-trash-alt"></i> Hapus Gambar
                                        </button>
                                    </form>
                                </template>
                            </div>

                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex flex-col md:flex-row items-center gap-5">
                                <div class="w-full md:w-64 h-40 rounded-xl overflow-hidden border border-slate-300 bg-white shadow-xs shrink-0 flex items-center justify-center">
                                    <img :src="currentMenu.image_url" alt="Preview Headline" class="w-full h-full object-cover">
                                </div>
                                <div class="space-y-2 text-xs text-slate-600 flex-1 w-full">
                                    <div>
                                        <span class="text-[10px] uppercase font-extrabold text-slate-400 block tracking-wider">Nama File:</span>
                                        <span class="font-bold text-slate-800 text-sm font-mono break-all" x-text="currentMenu.image_name || currentMenu.image_url.split('/').pop()"></span>
                                    </div>
                                    <template x-if="currentMenu.image_size">
                                        <div>
                                            <span class="text-[10px] uppercase font-extrabold text-slate-400 block tracking-wider">Ukuran File:</span>
                                            <span class="font-bold text-slate-700" x-text="(currentMenu.image_size / 1024 > 1024 ? (currentMenu.image_size / 1048576).toFixed(2) + ' MB' : (currentMenu.image_size / 1024).toFixed(1) + ' KB')"></span>
                                        </div>
                                    </template>
                                    <div class="pt-2 flex items-center gap-2">
                                        <label class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-2 cursor-pointer">
                                            <i class="fas fa-sync-alt"></i> Ganti Gambar
                                            <input type="file" name="image_file" accept=".jpg,.jpeg,.png,image/jpeg,image/png" onchange="document.getElementById('cmsMenuEditorForm').submit()" class="hidden">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Tab 3: Dokumen PDF Halaman -->
                <div x-show="activeTab === 3" class="p-6 space-y-6">
                    <div class="flex items-center justify-between bg-slate-50 p-4 rounded-2xl border border-slate-200">
                        <div>
                            <h4 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                                <i class="fas fa-file-pdf text-rose-600"></i> Dokumen Pendukung Halaman (PDF)
                            </h4>
                            <p class="text-xs text-slate-500 mt-0.5">Unggah dokumen berformat PDF (seperti Surat Keputusan, Pedoman, Regulasi, atau Lampiran) yang berkaitan dengan halaman ini.</p>
                        </div>
                        <span class="text-[10px] bg-rose-100 text-rose-800 font-extrabold px-3 py-1 rounded-full border border-rose-200 uppercase">Khusus Format .PDF (Max 10MB)</span>
                    </div>

                    <!-- Error Notification Banner for PDF Upload -->
                    <template x-if="pdfError">
                        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold rounded-2xl flex items-center justify-between shadow-xs">
                            <div class="flex items-center gap-2.5">
                                <i class="fas fa-exclamation-triangle text-rose-600 text-lg"></i>
                                <span x-text="pdfError"></span>
                            </div>
                            <button type="button" @click="pdfError = ''" class="text-rose-400 hover:text-rose-600"><i class="fas fa-times"></i></button>
                        </div>
                    </template>

                    <!-- Direct Fast Upload Form (If Page Already Exists) -->
                    <template x-if="currentMenu.page_id">
                        <div>
                            <form :action="'{{ url('admin/pages') }}/' + currentMenu.page_id + '/documents'" method="POST" enctype="multipart/form-data" class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-4">
                                @csrf
                                <label class="block font-bold text-slate-700 text-xs">Upload File PDF Baru</label>
                                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                    <input type="file" name="document_file" accept=".pdf,application/pdf" required @change="validatePdfFile($event)" class="flex-1 text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-100 file:text-rose-700 hover:file:bg-rose-200 cursor-pointer bg-white border border-slate-300 rounded-xl p-1">
                                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-2 shrink-0">
                                        <i class="fas fa-upload"></i> Unggah PDF
                                    </button>
                                </div>
                                <p class="text-[11px] text-slate-400">Hanya menerima file berformat <strong>.pdf</strong> dengan batas ukuran maksimum <strong>10 MB</strong>.</p>
                            </form>
                        </div>
                    </template>

                    <!-- List Dokumen PDF yang Sudah Diupload -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700">Daftar Dokumen PDF Halaman Terkait</span>
                            <span class="text-[11px] text-slate-400 font-medium" x-text="(currentMenu.documents || []).length + ' Dokumen'"></span>
                        </div>

                        <div class="divide-y divide-slate-100">
                            <template x-for="doc in (currentMenu.documents || [])" :key="doc.id">
                                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/80 transition">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg font-bold shrink-0 border border-rose-100">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                                        <div>
                                            <h5 class="font-extrabold text-slate-800 text-xs tracking-wide" x-text="doc.file_name"></h5>
                                            <div class="flex items-center gap-3 text-[11px] text-slate-400 mt-0.5">
                                                <span><i class="fas fa-hdd mr-1"></i> <span x-text="(doc.file_size ? (doc.file_size / 1024 > 1024 ? (doc.file_size / 1048576).toFixed(2) + ' MB' : (doc.file_size / 1024).toFixed(1) + ' KB') : '0 KB')"></span></span>
                                                <span>•</span>
                                                <span><i class="fas fa-calendar-alt mr-1"></i> <span x-text="new Date(doc.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })"></span></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 self-end sm:self-center">
                                        <a :href="'{{ url('page-documents') }}/' + doc.id + '/view'" target="_blank" class="px-3 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-700 font-bold text-xs rounded-xl border border-sky-200 transition flex items-center gap-1.5" title="Preview PDF">
                                            <i class="fas fa-eye"></i> Preview
                                        </a>
                                        <form :action="'{{ url('admin/page-documents') }}/' + doc.id" method="POST" onsubmit="return confirm('Hapus dokumen PDF ini?')">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-xl border border-rose-200 transition flex items-center gap-1.5" title="Hapus PDF">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </template>

                            <template x-if="!(currentMenu.documents && currentMenu.documents.length > 0)">
                                <div class="p-8 text-center space-y-2">
                                    <i class="fas fa-file-pdf text-4xl text-slate-200"></i>
                                    <p class="text-xs text-slate-400 font-medium">Belum ada dokumen PDF yang diunggah untuk halaman ini.</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Tab 4: Link URL Halaman -->
                <div x-show="activeTab === 4" class="p-6 space-y-6">
                    <div class="flex items-center justify-between bg-slate-50 p-4 rounded-2xl border border-slate-200">
                        <div>
                            <h4 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                                <i class="fas fa-link text-orange-600"></i> Link URL Halaman
                            </h4>
                            <p class="text-xs text-slate-500 mt-0.5">Tambahkan tautan/link URL eksternal yang berhubungan dengan halaman ini (misalnya website resmi, aplikasi SP4N Lapor, Google Drive, atau Media Sosial).</p>
                        </div>
                        <span class="text-[10px] bg-orange-100 text-orange-800 font-extrabold px-3 py-1 rounded-full border border-orange-200 uppercase">Format HTTP / HTTPS</span>
                    </div>

                    <!-- Error Notification Banner for Link URL -->
                    <template x-if="linkError">
                        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold rounded-2xl flex items-center justify-between shadow-xs">
                            <div class="flex items-center gap-2.5">
                                <i class="fas fa-exclamation-triangle text-rose-600 text-lg"></i>
                                <span x-text="linkError"></span>
                            </div>
                            <button type="button" @click="linkError = ''" class="text-rose-400 hover:text-rose-600"><i class="fas fa-times"></i></button>
                        </div>
                    </template>
                    
                    @if(session('error'))
                        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold rounded-2xl flex items-center justify-between shadow-xs">
                            <div class="flex items-center gap-2.5">
                                <i class="fas fa-exclamation-triangle text-rose-600 text-lg"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Link Form (Add / Edit) -->
                    <template x-if="currentMenu.page_id">
                        <div>
                            <form :action="linkForm.id ? '{{ url('admin/page-links') }}/' + linkForm.id : '{{ url('admin/pages') }}/' + currentMenu.page_id + '/links'" 
                                  method="POST" 
                                  @submit="if(!validateUrlInput(linkForm.url)) { $event.preventDefault(); }"
                                  class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-4">
                                @csrf
                                <template x-if="linkForm.id">
                                    <input type="hidden" name="_method" value="PUT">
                                </template>

                                <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                                    <h5 class="font-bold text-slate-800 text-xs flex items-center gap-2" x-text="linkForm.id ? 'Edit Link URL' : 'Tambah Link URL Baru'"></h5>
                                    <template x-if="linkForm.id">
                                        <button type="button" @click="resetLinkForm()" class="text-xs text-rose-600 font-bold hover:underline">
                                            <i class="fas fa-times"></i> Batal Edit
                                        </button>
                                    </template>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Judul Link <span class="text-rose-500">*</span></label>
                                        <input type="text" name="title" required x-model="linkForm.title" placeholder="Contoh: Website Resmi BPBD" 
                                               class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white">
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">URL Link <span class="text-rose-500">*</span></label>
                                        <input type="text" name="url" required x-model="linkForm.url" @input="linkError = ''" placeholder="Contoh: https://bpbd.probolinggokab.go.id/" 
                                               class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white font-mono">
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 text-xs mb-1">Deskripsi Link (opsional)</label>
                                    <textarea name="description" x-model="linkForm.description" rows="2" placeholder="Contoh: Kunjungi website resmi BPBD Kabupaten Probolinggo" 
                                              class="w-full px-3.5 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white text-xs"></textarea>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-2">
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1.5">Target Buka Halaman</label>
                                        <div class="flex items-center gap-4">
                                            <label class="flex items-center gap-2 cursor-pointer font-medium text-slate-700">
                                                <input type="radio" name="target" value="_blank" x-model="linkForm.target" class="text-orange-600 focus:ring-orange-500">
                                                <span>Buka halaman baru (_blank)</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer font-medium text-slate-700">
                                                <input type="radio" name="target" value="_self" x-model="linkForm.target" class="text-orange-600 focus:ring-orange-500">
                                                <span>Buka halaman yang sama (_self)</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1.5">Status Tampil</label>
                                        <div class="flex items-center gap-4">
                                            <label class="flex items-center gap-2 cursor-pointer font-medium text-slate-700">
                                                <input type="radio" name="status" value="aktif" x-model="linkForm.status" class="text-orange-600 focus:ring-orange-500">
                                                <span class="text-emerald-700 font-bold">Aktif</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer font-medium text-slate-700">
                                                <input type="radio" name="status" value="nonaktif" x-model="linkForm.status" class="text-orange-600 focus:ring-orange-500">
                                                <span class="text-slate-500">Nonaktif</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2 flex justify-end">
                                    <button type="submit" class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2">
                                        <i class="fas fa-save"></i> <span x-text="linkForm.id ? 'Perbarui Link' : 'Simpan Link'"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </template>

                    <!-- List Link URL Halaman -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700">Daftar Link URL Halaman Terkait</span>
                            <span class="text-[11px] text-slate-400 font-medium" x-text="(currentMenu.links || []).length + ' Link'"></span>
                        </div>

                        <div class="divide-y divide-slate-100">
                            <template x-for="lnk in (currentMenu.links || [])" :key="lnk.id">
                                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/80 transition">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-base">🔗</span>
                                            <h5 class="font-extrabold text-slate-800 text-xs tracking-wide" x-text="lnk.title"></h5>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" 
                                                  :class="lnk.status === 'aktif' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-200 text-slate-600 border border-slate-300'"
                                                  x-text="lnk.status === 'aktif' ? 'Aktif' : 'Nonaktif'"></span>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono bg-sky-50 text-sky-700 border border-sky-200" x-text="lnk.target || '_blank'"></span>
                                        </div>
                                        <a :href="lnk.url" target="_blank" class="text-xs text-orange-600 hover:underline font-mono block truncate max-w-md" x-text="lnk.url"></a>
                                        <template x-if="lnk.description">
                                            <p class="text-[11px] text-slate-500 italic" x-text="lnk.description"></p>
                                        </template>
                                    </div>

                                    <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                                        <button type="button" @click="editLink(lnk)" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs rounded-xl border border-blue-200 transition flex items-center gap-1.5" title="Edit Link">
                                            <i class="fas fa-pen-to-square"></i> Edit
                                        </button>
                                        <form :action="'{{ url('admin/page-links') }}/' + lnk.id" method="POST" onsubmit="return confirm('Hapus link URL ini?')">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-xl border border-rose-200 transition flex items-center gap-1.5" title="Hapus Link">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </template>

                            <template x-if="!(currentMenu.links && currentMenu.links.length > 0)">
                                <div class="p-8 text-center space-y-2">
                                    <i class="fas fa-link text-4xl text-slate-200"></i>
                                    <p class="text-xs text-slate-400 font-medium">Belum ada link URL yang ditambahkan untuk halaman ini.</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
