<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page_title', 'Dashboard') | BPBD Kabupaten Probolinggo</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome & Tailwind CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff7ed',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            dark: '#0f172a',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased min-h-screen flex" x-data="{ sidebarOpen: false }">

    <!-- Mobile Backdrop -->
    <div x-show="sidebarOpen" 
         x-cloak 
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"></div>

    <!-- Sidebar Navigation -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0f172a] text-slate-300 transform transition-transform duration-200 lg:translate-x-0 flex flex-col justify-between shadow-2xl"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        
        <div>
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center px-6 bg-[#090d16] border-b border-slate-800/80 justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl {{ auth()->user()->isSuperAdmin() ? 'bg-emerald-600' : 'bg-blue-600' }} flex items-center justify-center text-white font-extrabold text-xs shadow-md">
                        {{ auth()->user()->isSuperAdmin() ? 'SA' : 'ST' }}
                    </div>
                    <div>
                        <h1 class="font-extrabold text-white text-sm tracking-wide line-clamp-1">{{ auth()->user()->name }}</h1>
                        <p class="text-[10px] {{ auth()->user()->isSuperAdmin() ? 'text-emerald-400' : 'text-blue-400' }} font-bold tracking-wider">
                            {{ auth()->user()->isSuperAdmin() ? 'Administrator Utama' : 'Staf Operasional' }}
                        </p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1">
                    <i class="fas fa-times text-base"></i>
                </button>
            </div>

            <!-- Sidebar Navigation Menu Links -->
            <nav class="p-4 space-y-1 text-xs font-semibold overflow-y-auto max-h-[calc(100vh-140px)]">
                <div class="text-[10px] text-slate-500 uppercase tracking-widest px-3 py-2 font-bold">Navigasi Utama</div>
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/40 font-bold' : 'hover:bg-slate-800/80 text-slate-300' }}">
                    <i class="fas fa-chart-pie text-sm"></i> Dashboard Utama
                </a>

                <!-- ACCESSIBLE BY BOTH ANGGOTA & SUPER ADMIN -->
                <a href="{{ route('admin.documents') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.documents') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/40 font-bold' : 'hover:bg-slate-800/80 text-slate-300' }}">
                    <i class="fas fa-file-alt text-sm text-blue-400"></i> Dokumen Kinerja
                </a>

                <a href="{{ route('admin.news') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.news') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/40 font-bold' : 'hover:bg-slate-800/80 text-slate-300' }}">
                    <i class="fas fa-newspaper text-sm text-purple-400"></i> Berita & Informasi
                </a>

                @if(!auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.staf_logs') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.staf_logs') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/40 font-bold' : 'hover:bg-slate-800/80 text-slate-300' }}">
                        <i class="fas fa-clipboard-list text-sm text-amber-400"></i> Log Aktivitas
                    </a>
                @endif

                <!-- RESTRICTED TO SUPER ADMIN ONLY -->
                @if(auth()->user()->isSuperAdmin())
                    <div class="pt-4 text-[10px] text-slate-500 uppercase tracking-widest px-3 py-1 font-bold">Pengelolaan Konten</div>

                    <a href="{{ route('admin.sliders') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.sliders') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/40 font-bold' : 'hover:bg-slate-800/80 text-slate-300' }}">
                        <i class="fas fa-images text-sm text-amber-400"></i> Banner Utama
                    </a>

                    <a href="{{ route('admin.navigation') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.navigation*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/40 font-bold' : 'hover:bg-slate-800/80 text-slate-300' }}">
                        <i class="fas fa-sitemap text-sm text-cyan-400"></i> Struktur Navigasi
                    </a>

                    <a href="{{ route('admin.menus') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.menus*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/40 font-bold' : 'hover:bg-slate-800/80 text-slate-300' }}">
                        <i class="fas fa-file-signature text-sm text-emerald-400"></i> CMS Editor Konten
                    </a>

                    <a href="{{ route('admin.pages') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.pages') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/40 font-bold' : 'hover:bg-slate-800/80 text-slate-300' }}">
                        <i class="fas fa-file-signature text-sm text-rose-400"></i> Halaman Profil & Layanan
                    </a>

                    <a href="{{ route('admin.widgets') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.widgets') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/40 font-bold' : 'hover:bg-slate-800/80 text-slate-300' }}">
                        <i class="fas fa-th-large text-sm text-emerald-400"></i> Widget Sidebar
                    </a>

                    <a href="{{ route('admin.links') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.links') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/40 font-bold' : 'hover:bg-slate-800/80 text-slate-300' }}">
                        <i class="fas fa-link text-sm text-teal-400"></i> Tautan Terkait Logo
                    </a>

                    <a href="{{ route('admin.media') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.media') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/40 font-bold' : 'hover:bg-slate-800/80 text-slate-300' }}">
                        <i class="fas fa-photo-video text-sm text-amber-400"></i> Galeri Media (Foto & Video)
                    </a>

                    <a href="{{ route('admin.users') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.users') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/40 font-bold' : 'hover:bg-slate-800/80 text-slate-300' }}">
                        <i class="fas fa-users-cog text-sm text-indigo-400"></i> Manajemen Pengguna
                    </a>

                    <a href="{{ route('admin.logs') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.logs') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/40 font-bold' : 'hover:bg-slate-800/80 text-slate-300' }}">
                        <i class="fas fa-history text-sm text-amber-400"></i> Log Aktivitas
                    </a>

                    <a href="{{ route('admin.settings') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.settings') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/40 font-bold' : 'hover:bg-slate-800/80 text-slate-300' }}">
                        <i class="fas fa-cog text-sm text-slate-400"></i> Pengaturan Website
                    </a>
                @endif

                <div class="pt-4 text-[10px] text-slate-500 uppercase tracking-widest px-3 py-1 font-bold">Portal Publik</div>
                
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800/80 text-slate-400 hover:text-white transition-all">
                    <i class="fas fa-external-link-alt text-sm"></i> Lihat Website Utama
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-800 bg-[#090d16]">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-rose-600 text-slate-200 hover:text-white font-bold text-xs rounded-xl shadow-md transition-all">
                    <i class="fas fa-sign-out-alt"></i> Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-grow lg:ml-64 flex flex-col min-h-screen w-full overflow-x-hidden">
        
        <!-- Topbar Header -->
        <header class="h-20 bg-white border-b border-slate-200 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden text-slate-600 hover:text-slate-900 p-2 rounded-lg hover:bg-slate-100">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <div>
                    <h2 class="font-extrabold text-slate-800 text-base sm:text-lg tracking-tight">@yield('page_title', 'Dashboard Administrator')</h2>
                    <p class="text-[11px] text-slate-500 hidden sm:block">Sistem Informasi Manajemen BPBD Kabupaten Probolinggo</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-slate-800 leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-emerald-600 font-semibold uppercase">{{ auth()->user()->isSuperAdmin() ? 'Administrator Utama' : 'Staf Operasional' }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-sm border border-emerald-200 shadow-xs">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <main class="p-4 sm:p-8 flex-grow space-y-6">
            
            <!-- Flash Success Message -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Flash Error Message -->
            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle text-rose-600 text-lg"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Validation Errors List Alert -->
            @if($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1.5 shadow-xs">
                    <div class="flex items-center gap-2 font-extrabold text-rose-900">
                        <i class="fas fa-exclamation-circle text-rose-600 text-base"></i>
                        <span>Terjadi Kesalahan Validasi Input:</span>
                    </div>
                    <ul class="list-disc list-inside pl-6 space-y-1 text-slate-700 font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            @yield('content')
        </main>


    </div>

    <!-- CKEditor 5 Global Script & Protection Guard -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        // Global HTML Sanitizer for CKEditor links (tel:, mailto:, http:, https:)
        window.sanitizeCkHtml = function(html) {
            if (!html) return '';
            let clean = String(html);
            
            // Clean double-escaped entities if present
            if (clean.includes('&lt;') || clean.includes('&gt;') || clean.includes('&amp;')) {
                const txt = document.createElement('textarea');
                txt.innerHTML = clean;
                clean = txt.value;
            }

            // Fix tel:, mailto:, http:, https: links:
            // Remove leading slashes/quotes like /"tel: or /tel:
            clean = clean.replace(/href=["']\/?(?:"|%22)?(tel|mailto|https?):/gi, function(match, proto) {
                return 'href="' + proto + ':';
            });

            // Clean trailing slashes, quotes, and invalid characters from tel: and mailto: hrefs
            clean = clean.replace(/href=["'](tel:[0-9+\-\s()]+?)\/*["']/gi, 'href="$1"');
            clean = clean.replace(/href=["'](mailto:[^"'\/\s]+?)\/*["']/gi, 'href="$1"');
            
            // General replace for any corrupt tel/mailto links
            clean = clean.replace(/href=["']\/?(?:"|%22)?((?:tel|mailto):[^"'\s>]+?)(?:\/|%22|")?["']/gi, function(match, targetUrl) {
                let sanitized = targetUrl.replace(/\/+$/, '').replace(/^["']|["']$/g, '');
                return 'href="' + sanitized + '"';
            });

            return clean.trim();
        };

        // Global Click Guard: Prevent browser navigation when user clicks inside ANY CKEditor area
        document.addEventListener('click', function(e) {
            const linkTag = e.target.closest('a');
            const insideCkEditor = e.target.closest('.ck-editor, .ck-content, .ck-editable, .ck-editor__editable');
            if (linkTag && insideCkEditor) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        }, true);

        // Standardized Global CKEditor Builder
        window.initStandardCKEditor = function(textareaId, initialContent, onChangeCallback) {
            const textarea = document.getElementById(textareaId);
            if (!textarea) return Promise.reject('Textarea not found');

            textarea.removeAttribute('readonly');
            textarea.removeAttribute('disabled');

            const cleanData = window.sanitizeCkHtml(initialContent || textarea.value || '');
            textarea.value = cleanData;

            const config = {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', 'code', '|',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'link', 'insertTable', 'blockQuote', '|',
                    'undo', 'redo'
                ],
                link: {
                    defaultProtocol: 'https://',
                    addTargetToExternalLinks: false
                },
                placeholder: 'Tuliskan isi konten profesional di sini...'
            };

            const attach = (editorClass) => {
                return editorClass.create(textarea, config).then(editor => {
                    editor.isReadOnly = false;
                    if (typeof editor.disableReadOnlyMode === 'function') {
                        editor.disableReadOnlyMode('manual');
                    }
                    if (editor.editing && editor.editing.view) {
                        editor.editing.view.isReadOnly = false;
                    }

                    editor.setData(cleanData);

                    editor.model.document.on('change:data', () => {
                        const currentData = window.sanitizeCkHtml(editor.getData());
                        textarea.value = currentData;
                        if (typeof onChangeCallback === 'function') {
                            onChangeCallback(currentData);
                        }
                    });

                    return editor;
                });
            };

            if (typeof ClassicEditor !== 'undefined') {
                return attach(ClassicEditor);
            } else if (typeof CKEDITOR !== 'undefined' && CKEDITOR.ClassicEditor) {
                return attach(CKEDITOR.ClassicEditor);
            }

            return Promise.reject('CKEditor library not loaded');
        };
    </script>
</body>
</html>

