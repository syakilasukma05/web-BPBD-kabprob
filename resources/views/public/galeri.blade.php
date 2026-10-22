<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Galeri Dokumentasi & Video Kebencanaan | BPBD Kabupaten Probolinggo</title>
    <meta name="description" content="Galeri dokumentasi foto kegiatan penanggulangan bencana, simulasi mitigasi, serta video tanggap darurat BPBD Kabupaten Probolinggo.">
    <link rel="icon" type="image/png" href="{{ $settings['logo_frontend'] ?? '' }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
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
                            100: '#ffedd5',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                            dark: '#0f172a',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .dropdown-scroll {
            max-height: 360px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f8fafc;
        }
        .dropdown-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .dropdown-scroll::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 0 12px 12px 0;
        }
        .dropdown-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        @keyframes ticker-marquee {
            0% { transform: translate3d(0, 0, 0); }
            100% { transform: translate3d(-50%, 0, 0); }
        }
        .animate-news-ticker {
            display: inline-flex;
            white-space: nowrap;
            animation: ticker-marquee 30s linear infinite;
        }
        .animate-news-ticker:hover {
            animation-play-state: paused;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col antialiased selection:bg-orange-600 selection:text-white" x-data="{ activeMedia: null, mobileMenu: false }">
    
    <!-- 1. Top Info Bar Baru (Berita Terbaru Bar) -->
    <div class="bg-[#1b263b] text-white border-b border-slate-700/80 text-xs py-2 px-4 shadow-sm z-50 overflow-hidden">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-3">
            <div class="flex items-center gap-2 shrink-0 z-10 bg-[#1b263b] pr-2 sm:pr-4">
                <span class="inline-flex items-center gap-1.5 bg-orange-600 hover:bg-orange-700 text-white font-extrabold text-[11px] px-3 py-1 rounded-md uppercase tracking-wider shadow-xs transition-colors">
                    <i class="fas fa-newspaper text-amber-200"></i> BERITA TERBARU:
                </span>
            </div>

            <div class="flex-1 overflow-hidden relative h-5 flex items-center">
                @if(isset($latestNews) && count($latestNews) > 0)
                    @php
                        $newsItemsList = count($latestNews) < 3 ? array_merge($latestNews->all(), $latestNews->all(), $latestNews->all(), $latestNews->all()) : array_merge($latestNews->all(), $latestNews->all());
                    @endphp
                    <div class="w-full overflow-hidden whitespace-nowrap">
                        <div class="animate-news-ticker items-center">
                            @foreach($newsItemsList as $news)
                                @php
                                    $newsUrl = $news->link_url ?: (Route::has('news.detail') ? route('news.detail', $news->slug) : '/berita/' . $news->slug);
                                @endphp
                                <a href="{{ $newsUrl }}" class="inline-flex items-center gap-2 text-slate-200 hover:text-amber-300 font-medium text-xs me-8 transition-colors">
                                    <span class="text-orange-400">📰</span>
                                    <span>{{ $news->title }}</span>
                                    @if($news->published_at)
                                        <span class="text-[10px] text-slate-400 font-mono">({{ \Carbon\Carbon::parse($news->published_at)->format('d M Y') }})</span>
                                    @endif
                                </a>
                                <span class="text-slate-600 font-bold me-8">•</span>
                            @endforeach
                        </div>
                    </div>
                @else
                    <span class="text-slate-400 italic text-[11px]">BPBD Kabupaten Probolinggo - Siap, Sigap, Tanggap Bencana!</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Header / Navigation Wrap -->
    <header class="sticky top-0 z-50 bg-white border-b-4 border-[#375e47] shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Left: Agency Logo -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <a href="{{ route('home') }}" class="group flex items-center gap-3">
                        <img src="{{ (!empty($settings['logo_frontend']) && !str_contains($settings['logo_frontend'], 'diskominfo.probolinggokab.go.id')) ? $settings['logo_frontend'] : asset('images/logo-bpbd.svg') }}" 
                             alt="Logo Instansi" 
                             class="h-9 sm:h-10 w-auto object-contain transition-transform group-hover:scale-105 duration-200">
                        <div class="flex flex-col justify-center leading-tight">
                            <span class="font-extrabold text-xs sm:text-sm md:text-base text-[#1b263b] tracking-tight group-hover:text-[#375e47] transition-colors uppercase">
                                BPBD KABUPATEN PROBOLINGGO
                            </span>
                            <span class="font-bold text-[9px] sm:text-[11px] text-orange-600 tracking-wider uppercase">
                                BADAN PENANGGULANGAN BENCANA DAERAH
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Middle: Navigation Menu (HOME, PROFIL, LAYANAN, DOKUMEN, INFORMASI, HUBUNGI) -->
                <nav class="hidden lg:flex items-center space-x-4 xl:space-x-6">
                    @foreach($navMenus as $menu)
                        @if(in_array(strtoupper($menu->title), ['LOGIN']))
                            @continue
                        @endif
                        @if($menu->children && count($menu->children) > 0)
                            <!-- Dropdown Menu Item -->
                            <div class="relative" x-data="{ open: false }" @mouseleave="open = false">
                                <button @mouseover="open = true" @click="open = !open" 
                                        class="px-2 py-2 text-sm font-extrabold tracking-wide text-[#23325c] hover:text-[#375e47] uppercase transition-colors duration-150 flex items-center gap-1">
                                    {{ $menu->title }}
                                    <i class="fas fa-chevron-down text-[10px] opacity-70 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                                </button>

                                <div x-show="open" 
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-2"
                                     class="absolute left-0 mt-1 w-64 rounded-xl bg-white shadow-xl ring-1 ring-black/5 border border-slate-100 py-2 z-50 dropdown-scroll">
                                    @foreach($menu->children as $child)
                                        @php
                                            $isSpanLapor = \Illuminate\Support\Str::contains(strtolower($child->title ?? ''), ['sp4n', 'lapor']);
                                            $isExt = ($child->menu_type === 'external_link' || $child->is_external || preg_match('/^https?:\/\//i', trim($child->url ?? '')));
                                            $linkUrl = $isExt ? ($child->external_url ?? $child->url) : $child->url;
                                            $targetAttr = $isSpanLapor ? '_blank' : ($child->target ?: ($isExt ? '_blank' : '_self'));
                                            $relAttr = ($isSpanLapor || $targetAttr === '_blank') ? 'rel="noopener noreferrer"' : '';
                                        @endphp
                                        <a href="{{ $linkUrl }}" target="{{ $targetAttr }}" {!! $relAttr !!}
                                           class="block px-4 py-2.5 text-xs text-slate-700 hover:bg-[#375e47] hover:text-white transition-colors duration-150 font-semibold">
                                            {{ $child->title }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <!-- Single Link Item -->
                            @php
                                $isExt = ($menu->menu_type === 'external_link' || $menu->is_external || preg_match('/^https?:\/\//i', trim($menu->url ?? '')));
                                $linkUrl = $isExt ? ($menu->external_url ?? $menu->url) : $menu->url;
                                $targetAttr = $menu->target ?: ($isExt ? '_blank' : '_self');
                            @endphp
                            <a href="{{ $linkUrl }}" target="{{ $targetAttr }}" 
                               class="px-2 py-2 text-sm font-extrabold tracking-wide text-[#23325c] hover:text-[#375e47] uppercase transition-colors duration-150">
                                {{ $menu->title }}
                            </a>
                        @endif
                    @endforeach
                </nav>

                <!-- Right: BerAKHLAK Logo & Mobile Toggle -->
                <div class="flex items-center gap-3">
                    <div class="hidden xl:block flex-shrink-0">
                        <img src="{{ $settings['logo_berakhlak'] ?? 'https://diskominfo.probolinggokab.go.id/frontend/images/img-berakhlak.png' }}" 
                             alt="BerAKHLAK" 
                             class="h-7 sm:h-8 w-auto object-contain">
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenu = !mobileMenu" 
                            class="lg:hidden inline-flex items-center justify-center p-2.5 rounded-lg text-slate-700 hover:text-[#375e47] hover:bg-slate-100 focus:outline-none">
                        <i class="fas text-lg" :class="mobileMenu ? 'fa-times' : 'fa-bars'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div x-show="mobileMenu" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="lg:hidden border-b border-slate-200 bg-white px-4 pt-2 pb-6 space-y-2">
            @foreach($navMenus as $menu)
                @if(in_array(strtoupper($menu->title), ['LOGIN']))
                    @continue
                @endif
                @if($menu->children && count($menu->children) > 0)
                    <div x-data="{ subOpen: false }" class="space-y-1">
                        <button @click="subOpen = !subOpen" class="w-full flex justify-between items-center px-3 py-2 text-sm font-bold text-slate-800 hover:bg-orange-50 rounded-lg">
                            <span>{{ $menu->title }}</span>
                            <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': subOpen }"></i>
                        </button>
                        <div x-show="subOpen" class="pl-4 space-y-1">
                            @foreach($menu->children as $child)
                                @php
                                    $isSpanLapor = \Illuminate\Support\Str::contains(strtolower($child->title ?? ''), ['sp4n', 'lapor']);
                                    $isExt = ($child->menu_type === 'external_link' || $child->is_external || preg_match('/^https?:\/\//i', trim($child->url ?? '')));
                                    $linkUrl = $isExt ? ($child->external_url ?? $child->url) : $child->url;
                                    $targetAttr = $isSpanLapor ? '_blank' : ($child->target ?: ($isExt ? '_blank' : '_self'));
                                    $relAttr = ($isSpanLapor || $targetAttr === '_blank') ? 'rel="noopener noreferrer"' : '';
                                @endphp
                                <a href="{{ $linkUrl }}" target="{{ $targetAttr }}" {!! $relAttr !!} class="block px-3 py-2 text-xs font-medium text-slate-600 hover:text-orange-600 hover:bg-orange-50 rounded-md">
                                    {{ $child->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    @php
                        $isExt = ($menu->menu_type === 'external_link' || $menu->is_external || preg_match('/^https?:\/\//i', trim($menu->url ?? '')));
                        $linkUrl = $isExt ? ($menu->external_url ?? $menu->url) : $menu->url;
                        $targetAttr = $menu->target ?: ($isExt ? '_blank' : '_self');
                    @endphp
                    <a href="{{ $linkUrl }}" target="{{ $targetAttr }}" class="block px-3 py-2 text-sm font-bold text-slate-800 hover:bg-orange-50 rounded-lg">
                        {{ $menu->title }}
                    </a>
                @endif
            @endforeach
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 py-10 w-full space-y-8">
        
        <!-- Page Title & Tabs -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-slate-200 pb-6">
            <div>
                <span class="px-3 py-1 bg-amber-100 text-amber-800 text-xs font-extrabold rounded-full inline-block uppercase tracking-wider mb-2">Dokumentasi Lapangan & Posko</span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#23325c]">Galeri Foto & Video Kebencanaan</h1>
                <p class="text-xs sm:text-sm text-slate-500 max-w-2xl mt-1">
                    Kumpulan dokumentasi foto kegiatan penanggulangan bencana, simulasi mitigasi, serta video tanggap darurat BPBD Kabupaten Probolinggo.
                </p>
            </div>

            <!-- Filter Buttons -->
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('public.galeri', ['type' => 'semua']) }}" 
                   class="px-4 py-2 text-xs font-extrabold rounded-xl transition-all {{ $type === 'semua' ? 'bg-[#23325c] text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
                    Semua
                </a>
                <a href="{{ route('public.galeri', ['type' => 'foto']) }}" 
                   class="px-4 py-2 text-xs font-extrabold rounded-xl transition-all {{ $type === 'foto' ? 'bg-amber-600 text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
                    <i class="fas fa-image me-1"></i> Foto
                </a>
                <a href="{{ route('public.galeri', ['type' => 'video']) }}" 
                   class="px-4 py-2 text-xs font-extrabold rounded-xl transition-all {{ $type === 'video' ? 'bg-rose-600 text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
                    <i class="fas fa-video me-1"></i> Video
                </a>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($mediaList as $media)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden group hover:shadow-xl transition-all duration-300 flex flex-col justify-between cursor-pointer"
                     @click="activeMedia = {{ json_encode($media) }}">
                    
                    <div class="relative h-52 bg-slate-900 overflow-hidden">
                        @if($media->type === 'video')
                            <img src="{{ $media->youtube_thumbnail_url ?: ($media->file_url ?: 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb') }}" 
                                 alt="{{ $media->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center group-hover:bg-black/20 transition-colors">
                                <div class="w-14 h-14 rounded-full bg-rose-600/90 text-white flex items-center justify-center text-xl shadow-xl group-hover:scale-110 group-hover:bg-rose-600 transition-all">
                                    <i class="fas fa-play ml-1"></i>
                                </div>
                            </div>
                        @else
                            <img src="{{ $media->file_url ?: 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb' }}" 
                                 alt="{{ $media->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                <div class="w-10 h-10 rounded-full bg-white/90 text-slate-800 flex items-center justify-center text-sm shadow-md opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fas fa-search-plus"></i>
                                </div>
                            </div>
                        @endif

                        <div class="absolute top-3 left-3 flex gap-1.5 flex-wrap">
                            <span class="px-2.5 py-1 bg-black/60 backdrop-blur-md text-white text-[10px] font-extrabold rounded-md uppercase tracking-wider">
                                {{ $media->category }}
                            </span>
                            @if($media->type === 'foto' && $media->total_photos > 1)
                                <span class="px-2.5 py-1 bg-indigo-600/90 backdrop-blur-md text-white text-[10px] font-extrabold rounded-md uppercase tracking-wider">
                                    <i class="fas fa-layer-group me-1"></i> {{ $media->total_photos }} Foto
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="p-4 space-y-1.5 flex-grow">
                        <h3 class="font-bold text-[#23325c] text-xs line-clamp-2 leading-snug group-hover:text-amber-600 transition-colors">
                            {{ $media->title }}
                        </h3>
                        @if($media->caption)
                            <div class="text-[11px] text-slate-500 line-clamp-2 font-normal leading-relaxed prose prose-xs">
                                {!! strip_tags($media->caption) !!}
                            </div>
                        @endif
                    </div>

                    <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-100 text-[10px] text-slate-400 font-medium flex items-center justify-between">
                        <span><i class="far fa-calendar-alt me-1"></i> {{ $media->created_at->format('d M Y') }}</span>
                        <span class="font-bold text-[#375e47] flex items-center gap-1">
                            {{ $media->type === 'video' ? 'Putar Video' : ($media->total_photos > 1 ? 'Buka Album (' . $media->total_photos . ' Foto)' : 'Lihat Foto') }} <i class="fas fa-arrow-right text-[9px]"></i>
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400">
                    <i class="fas fa-photo-video text-5xl mb-3 text-slate-300"></i>
                    <p class="text-xs font-semibold">Belum ada item galeri media yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>

        @if($mediaList->hasPages())
            <div class="mt-8">
                {{ $mediaList->links() }}
            </div>
        @endif

    </main>

    <!-- Modal Lightbox Preview -->
    <div x-show="activeMedia !== null" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md">
        <div class="bg-white rounded-3xl max-w-4xl w-full shadow-2xl overflow-hidden space-y-0 my-auto border border-slate-200">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
                <div>
                    <span class="text-[10px] font-extrabold text-amber-600 uppercase tracking-wider block" x-text="activeMedia?.category"></span>
                    <h3 class="font-extrabold text-slate-900 text-sm" x-text="activeMedia?.title"></h3>
                </div>
                <button @click="activeMedia = null" class="text-slate-400 hover:text-slate-700 p-2"><i class="fas fa-times text-lg"></i></button>
            </div>

            <div class="bg-black max-h-[65vh] flex items-center justify-center overflow-hidden">
                <template x-if="activeMedia?.type === 'video'">
                    <div class="w-full aspect-video bg-black">
                        <iframe :src="activeMedia?.youtube_embed_url || activeMedia?.video_url" 
                                class="w-full h-full border-0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                allowfullscreen></iframe>
                    </div>
                </template>
                <template x-if="activeMedia?.type === 'foto'">
                    <img :src="activeMedia?.file_url || activeMedia?.cover_photo" :alt="activeMedia?.title" class="max-h-[60vh] w-auto object-contain mx-auto">
                </template>
            </div>

            <div class="p-6 bg-white space-y-3 max-h-[30vh] overflow-y-auto">
                <!-- Album Photos Thumbnail Picker -->
                <template x-if="activeMedia?.type === 'foto' && activeMedia?.photo_urls?.length > 1">
                    <div class="space-y-1.5 pb-2 border-b border-slate-100">
                        <h4 class="text-[11px] font-bold text-slate-700">Foto Dalam Album Ini (<span x-text="activeMedia?.photo_urls?.length"></span> Foto):</h4>
                        <div class="flex items-center gap-2 overflow-x-auto p-1 bg-slate-50 rounded-xl border border-slate-200">
                            <template x-for="(imgUrl, idx) in activeMedia?.photo_urls" :key="idx">
                                <img :src="imgUrl" 
                                     @click="activeMedia.file_url = imgUrl" 
                                     class="h-14 w-20 object-cover rounded-lg cursor-pointer border border-slate-300 hover:scale-105 transition-transform flex-shrink-0"
                                     :class="{ 'ring-2 ring-orange-500 border-orange-500': (activeMedia.file_url === imgUrl || (!activeMedia.file_url && idx === 0)) }">
                            </template>
                        </div>
                    </div>
                </template>

                <div class="text-xs text-slate-600 leading-relaxed font-medium prose prose-xs max-w-none" x-html="activeMedia?.caption || 'Tidak ada keterangan tambahan.'"></div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                    <span x-text="'Dipublikasikan: ' + (activeMedia?.created_at ? new Date(activeMedia.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-')"></span>
                    <button @click="activeMedia = null" class="px-4 py-1.5 bg-[#23325c] text-white font-bold text-xs rounded-xl shadow-xs">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-[#23325c] text-white py-6 mt-12 text-center text-xs border-t border-slate-700">
        <p>{{ $settings['copyright_text'] ?? 'BPBD Kabupaten Probolinggo © 2026. All Rights Reserved.' }}</p>
    </footer>

</body>
</html>
