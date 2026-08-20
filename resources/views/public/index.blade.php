<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $settings['site_title'] ?? 'Website Resmi | BPBD Kabupaten Probolinggo' }}</title>
    <meta name="description" content="{{ $settings['site_description'] ?? 'Website Resmi BPBD Kabupaten Probolinggo' }}">
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
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .glass-header {
            background: #ffffff;
            border-bottom: 5px solid #375e47;
        }
        .text-shadow-md {
            text-shadow: 0 2px 6px rgba(0,0,0,0.6);
        }
        .high-contrast {
            filter: contrast(160%) brightness(90%);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col selection:bg-orange-600 selection:text-white"
      x-data="{ 
          mobileMenu: false, 
          highContrast: false, 
          textToSpeech: true,
          fontSize: 100, 
          activeSlide: 0,
          totalSlides: {{ count($sliders) }},
          nextSlide() { this.activeSlide = (this.activeSlide + 1) % this.totalSlides },
          prevSlide() { this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides }
      }"
      :class="{ 'high-contrast': highContrast }"
      :style="`font-size: ${fontSize}%`"
      x-init="setInterval(() => nextSlide(), 7000)">



    <!-- Main Header / Navigation Wrap (Matching Official Header Design) -->
    <header class="sticky top-0 z-50 bg-white border-b-4 border-[#375e47] shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Left: Agency Logo -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <a href="{{ route('home') }}" class="group flex items-center gap-3">
                        <img src="{{ (!empty($settings['logo_frontend']) && !str_contains($settings['logo_frontend'], 'diskominfo.probolinggokab.go.id')) ? $settings['logo_frontend'] : asset('images/logo-bpbd.svg') }}" 
                             alt="Logo Instansi" 
                             class="h-12 w-auto object-contain transition-transform group-hover:scale-105 duration-200">
                    </a>
                </div>

                <!-- Middle: Navigation Menu (HOME, PROFIL, LAYANAN, DOKUMEN, INFORMASI, HUBUNGI, LOGIN) -->
                <nav class="hidden lg:flex items-center space-x-4 xl:space-x-6">
                    @foreach($navMenus as $menu)
                        @if(strtoupper($menu->title) === 'LOGIN')
                            @continue
                        @endif
                        @if($menu->children && count($menu->children) > 0)
                            <!-- Dropdown Menu Item -->
                            <div class="relative" x-data="{ open: false }" @mouseleave="open = false">
                                <button @mouseover="open = true" @click="open = !open" 
                                        onmouseenter="speakText('{{ e($menu->title) }}')"
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
                                     class="absolute left-0 mt-1 w-64 rounded-xl bg-white shadow-xl ring-1 ring-black/5 border border-slate-100 py-2 z-50 overflow-hidden">
                                    @foreach($menu->children as $child)
                                        <a href="{{ $child->url }}" target="{{ $child->target }}"
                                           onmouseenter="speakText('{{ e($child->title) }}')"
                                           class="block px-4 py-2.5 text-xs text-slate-700 hover:bg-[#375e47] hover:text-white transition-colors duration-150 font-semibold">
                                            {{ $child->title }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <!-- Single Link Item -->
                            <a href="{{ $menu->url }}" target="{{ $menu->target }}" 
                               onmouseenter="speakText('{{ e($menu->title) }}')"
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
                             class="h-10 w-auto object-contain">
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
                @if($menu->children && count($menu->children) > 0)
                    <div x-data="{ subOpen: false }" class="space-y-1">
                        <button @click="subOpen = !subOpen" class="w-full flex justify-between items-center px-3 py-2 text-sm font-bold text-slate-800 hover:bg-orange-50 rounded-lg">
                            <span>{{ $menu->title }}</span>
                            <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': subOpen }"></i>
                        </button>
                        <div x-show="subOpen" class="pl-4 space-y-1">
                            @foreach($menu->children as $child)
                                <a href="{{ $child->url }}" class="block px-3 py-2 text-xs font-medium text-slate-600 hover:text-orange-600 hover:bg-orange-50 rounded-md">
                                    {{ $child->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $menu->url }}" class="block px-3 py-2 text-sm font-bold text-slate-800 hover:bg-orange-50 rounded-lg">
                        {{ $menu->title }}
                    </a>
                @endif
            @endforeach

            @auth
                <div class="pt-3 border-t border-slate-100 mt-2">
                    <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 transition">
                        <i class="fas fa-tachometer-alt text-amber-400"></i> Dashboard Admin
                    </a>
                </div>
            @endauth
        </div>
    </header>

    <!-- Emergency Warning Status Ticker -->
    <div class="bg-slate-900 text-white text-xs py-2.5 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2 font-bold text-amber-400">
                <i class="fas fa-exclamation-triangle text-sm animate-bounce"></i>
                <span class="uppercase tracking-wider">Status Kebencanaan Daerah:</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <span class="px-2.5 py-0.5 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-300 font-semibold text-[11px]">
                    🌋 Gunung Bromo: <strong>Waspada (Level II)</strong>
                </span>
                <span class="px-2.5 py-0.5 rounded-full bg-blue-500/20 border border-blue-500/40 text-blue-300 font-semibold text-[11px]">
                    🌧️ Cuaca Ekstrem: <strong>Waspada Hujan Lebat</strong>
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow">
        
        <!-- DYNAMIC HERO SLIDER (BPBD Theme) -->
        <section class="relative bg-slate-950 overflow-hidden min-h-[480px] lg:min-h-[540px] flex items-center">
            @foreach($sliders as $index => $slide)
                <div x-show="activeSlide === {{ $index }}"
                     x-transition:enter="transition opacity duration-700 ease-out"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition opacity duration-500 ease-in"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0 w-full h-full">
                    
                    <!-- Background Image with Dark Orange Overlay -->
                    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat transform transition-transform duration-10000"
                         style="background-image: url('{{ $slide->image_url }}');">
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-900/80 to-orange-950/40"></div>
                    </div>

                    <!-- Slide Text Content -->
                    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center z-10 py-16">
                        <div class="max-w-2xl text-white space-y-4">
                            <span class="inline-block px-3 py-1 bg-orange-600/30 backdrop-blur-md border border-orange-400/40 text-orange-300 text-xs font-extrabold rounded-full uppercase tracking-wider">
                                Tangguh • Siap • Sigap
                            </span>
                            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight text-shadow-md">
                                {{ $slide->title }}
                            </h1>
                            <h2 class="text-lg sm:text-xl text-orange-100 font-medium leading-relaxed max-w-xl">
                                {{ $slide->subtitle }}
                            </h2>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Slider Controls (Left / Right Arrow Buttons) -->
            <button @click="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-white/20 hover:bg-orange-600 text-white rounded-full flex items-center justify-center backdrop-blur-md transition-all duration-200 focus:outline-none">
                <i class="fas fa-chevron-left text-lg"></i>
            </button>
            <button @click="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-white/20 hover:bg-orange-600 text-white rounded-full flex items-center justify-center backdrop-blur-md transition-all duration-200 focus:outline-none">
                <i class="fas fa-chevron-right text-lg"></i>
            </button>

            <!-- Slide Dots Indicators -->
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-2">
                @foreach($sliders as $index => $slide)
                    <button @click="activeSlide = {{ $index }}" 
                            class="h-2.5 rounded-full transition-all duration-300"
                            :class="activeSlide === {{ $index }} ? 'w-8 bg-orange-500' : 'w-2.5 bg-white/40 hover:bg-white/70'"></button>
                @endforeach
            </div>
        </section>

        <!-- LAPOR BENCANA DARURAT BANNER (Tepat di atas Informasi Terbaru) -->
        <section class="bg-gradient-to-r from-orange-600 via-amber-600 to-orange-700 py-6 px-4 sm:px-6 lg:px-8 shadow-md relative z-20">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 text-white">
                <div class="flex items-center gap-4 text-center sm:text-left">
                    <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-2xl shadow-inner shrink-0">
                        <i class="fas fa-bullhorn animate-pulse"></i>
                    </div>
                    <div>
                        <h3 class="text-lg sm:text-xl font-extrabold tracking-tight">Lapor Bencana & Kejadian Darurat</h3>
                        <p class="text-xs text-orange-100 font-medium">Laporkan kejadian bencana di sekitar Anda secara cepat kepada Pusdalops BPBD Kabupaten Probolinggo</p>
                    </div>
                </div>
                
                <div class="shrink-0 w-full sm:w-auto text-center">
                    <a href="{{ route('survey.form') }}" 
                       class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-full bg-white hover:bg-orange-50 text-orange-600 font-extrabold text-sm shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-0.5 border border-white">
                        <i class="fas fa-bullhorn text-orange-600"></i>
                        Lapor Bencana Darurat
                    </a>
                </div>
            </div>
        </section>

        <!-- INFORMASI KEBENCANAAN TERBARU & SIDEBAR WIDGETS SECTION -->
        <section class="py-12 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    <!-- Left Column: Informasi Terbaru (8 Cols) -->
                    <div class="lg:col-span-8 space-y-6">
                        
                        <!-- Header Title -->
                        <div class="flex justify-between items-center pb-4 border-b border-slate-200 mb-6">
                            <h2 class="text-2xl font-extrabold text-[#1e293b] tracking-tight">
                                Informasi Terbaru
                            </h2>
                            <a href="{{ route('informasi') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                                Lihat Semua <i class="fas fa-chevron-right text-[10px]"></i>
                            </a>
                        </div>

                        <!-- News Cards Grid (2 Columns on MD) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($latestNews as $news)
                                <article class="bg-white rounded-lg shadow-md hover:shadow-xl border border-slate-100 overflow-hidden flex flex-col group transition-all duration-300">
                                    
                                    <!-- Image & Date Badge Container -->
                                    <div class="relative h-56 overflow-hidden bg-slate-100">
                                        <img src="{{ $news->image_url ?? 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb' }}" 
                                             alt="{{ $news->title }}" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        
                                        <!-- Official Style Date Badge (Top Left) -->
                                        <div class="absolute top-3 left-3 flex shadow-md overflow-hidden rounded-xs text-xs font-bold">
                                            <!-- Day Number Box (Dark Green / Olive) -->
                                            <span class="bg-[#657737] text-white px-3 py-1.5 text-base font-extrabold flex items-center justify-center min-w-[38px]">
                                                {{ optional($news->published_at)->format('d') ?? '01' }}
                                            </span>
                                            <!-- Month & Year Box (White) -->
                                            <span class="bg-white text-[#1e293b] px-3.5 py-1.5 flex items-center text-xs font-extrabold uppercase tracking-wider">
                                                {{ optional($news->published_at)->isoFormat('MMMM Y') ?? optional($news->published_at)->format('F Y') ?? 'JANUARI 2026' }}
                                            </span>
                                        </div>

                                        @if($news->category)
                                            <span class="absolute top-3 right-3 bg-slate-900/80 backdrop-blur-md text-amber-300 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase">
                                                {{ $news->category }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- News Info Content -->
                                    <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                                        <h3 class="font-bold text-[#1e2b4f] text-base sm:text-lg group-hover:text-blue-700 transition-colors line-clamp-3 leading-snug">
                                            <a href="{{ $news->link_url ?: route('news.detail', $news->slug) }}" {{ $news->link_url ? 'target=_blank' : '' }}>
                                                {{ $news->title }}
                                            </a>
                                        </h3>
                                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                                            <span><i class="far fa-eye me-1"></i>{{ $news->views ?? 0 }} views</span>
                                            <a href="{{ $news->link_url ?: route('news.detail', $news->slug) }}" {{ $news->link_url ? 'target=_blank' : '' }} class="text-blue-600 font-semibold hover:underline flex items-center gap-1">
                                                Baca Selengkapnya <i class="fas fa-arrow-right text-[10px]"></i>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right Column: Dynamic Sidebar Widgets (Compact & Clean) (4 Cols) -->
                    <div class="lg:col-span-4 space-y-4">
                        @foreach($sidebarWidgets as $widget)
                            <div class="bg-white rounded-lg shadow-xs border border-slate-200/80 p-3.5 space-y-2.5">
                                <h3 class="text-base font-bold text-[#23325c] tracking-tight border-b border-slate-100 pb-1.5">
                                    {{ $widget->title }}
                                </h3>
                                <a href="{{ $widget->target_url ?? '#' }}" {{ $widget->target_url ? 'target=_blank' : '' }} class="block overflow-hidden rounded-md group">
                                    <img src="{{ $widget->image_url }}" 
                                         alt="{{ $widget->title }}" 
                                         class="w-full max-h-48 sm:max-h-52 object-cover object-top rounded-md group-hover:scale-[1.01] transition-transform duration-200">
                                </a>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </section>

        <!-- DYNAMIC LOGOS / RELATED LINKS CAROUSEL SECTION -->
        <section class="py-8 bg-white border-t border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-6">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Lembaga & Mitigasi Terkait (BNPB, BMKG, PVMBG)</span>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-8 lg:gap-12 opacity-85 hover:opacity-100 transition-opacity">
                    @foreach($relatedLinks as $link)
                        <a href="{{ $link->url }}" target="_blank" title="{{ $link->title }}" class="transform hover:scale-105 transition-transform duration-200 flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-xl border border-slate-200">
                            <img src="{{ $link->image_url }}" alt="{{ $link->title }}" class="h-8 w-auto object-contain">
                            <span class="text-xs font-bold text-slate-700">{{ $link->title }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>



    </main>

    <!-- DYNAMIC FOOTER SECTION -->
    <footer class="bg-slate-900 text-slate-300 font-sans pt-12 pb-6 border-t border-orange-600/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 pb-10 border-b border-slate-800">
                
                <!-- Col 1: Brand & Description (5 cols) -->
                <div class="lg:col-span-5 space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $settings['logo_backend'] ?? 'https://bpbd.probolinggokab.go.id/wp-content/uploads/2021/04/logo-bpbd.png' }}" 
                             alt="Footer Logo" class="h-12 w-auto object-contain">
                        <div>
                            <h3 class="font-extrabold text-white text-sm">BPBD KABUPATEN PROBOLINGGO</h3>
                            <p class="text-[10px] text-orange-400 font-bold uppercase">Badan Penanggulangan Bencana Daerah</p>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-400">
                        {{ $settings['site_description'] ?? 'Website Resmi BPBD Kabupaten Probolinggo - Pusat Informasi Kebencanaan, Peringatan Dini, dan Pelayanan Tanggap Darurat Bencana.' }}
                    </p>
                    
                    <!-- Social Media Links (Facebook, Instagram, Twitter/X, YouTube, TikTok) -->
                    <div class="pt-2 flex items-center gap-3">
                        @if(!empty($settings['social_facebook']))
                            <a href="{{ $settings['social_facebook'] }}" target="_blank" rel="noopener noreferrer" title="Facebook BPBD Kabupaten Probolinggo"
                               class="w-9 h-9 rounded-full bg-slate-800 hover:bg-blue-600 text-white flex items-center justify-center transition-all duration-200 shadow-sm border border-slate-700/50">
                                <i class="fab fa-facebook-f text-sm"></i>
                            </a>
                        @endif
                        @if(!empty($settings['social_instagram']))
                            <a href="{{ $settings['social_instagram'] }}" target="_blank" rel="noopener noreferrer" title="Instagram BPBD Kabupaten Probolinggo"
                               class="w-9 h-9 rounded-full bg-slate-800 hover:bg-pink-600 text-white flex items-center justify-center transition-all duration-200 shadow-sm border border-slate-700/50">
                                <i class="fab fa-instagram text-sm"></i>
                            </a>
                        @endif
                        @if(!empty($settings['social_twitter']))
                            <a href="{{ $settings['social_twitter'] }}" target="_blank" rel="noopener noreferrer" title="Twitter / X BPBD Kabupaten Probolinggo"
                               class="w-9 h-9 rounded-full bg-slate-800 hover:bg-slate-700 text-white flex items-center justify-center transition-all duration-200 shadow-sm border border-slate-700/50">
                                <i class="fab fa-x-twitter text-sm"></i>
                            </a>
                        @endif
                        @if(!empty($settings['social_youtube']))
                            <a href="{{ $settings['social_youtube'] }}" target="_blank" rel="noopener noreferrer" title="YouTube BPBD Kabupaten Probolinggo"
                               class="w-9 h-9 rounded-full bg-slate-800 hover:bg-red-600 text-white flex items-center justify-center transition-all duration-200 shadow-sm border border-slate-700/50">
                                <i class="fab fa-youtube text-sm"></i>
                            </a>
                        @endif
                        @if(!empty($settings['social_tiktok']))
                            <a href="{{ $settings['social_tiktok'] }}" target="_blank" rel="noopener noreferrer" title="TikTok BPBD Kabupaten Probolinggo"
                               class="w-9 h-9 rounded-full bg-slate-800 hover:bg-black text-white flex items-center justify-center transition-all duration-200 shadow-sm border border-slate-700/50">
                                <i class="fab fa-tiktok text-sm"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Col 2: Survey Link (3 cols) -->
                <div class="lg:col-span-3 space-y-3">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider border-l-2 border-orange-500 pl-2">Links Survey SKM</h4>
                    <div class="space-y-2">
                        <a href="{{ route('survey.form') }}" class="inline-block group">
                            <img src="{{ $settings['qr_code_survey'] ?? 'https://diskominfo.probolinggokab.go.id/backend/gambar/qr_code_kominfo.png' }}" 
                                 alt="QR Survey" class="h-28 w-28 object-contain rounded-lg border border-slate-700 bg-white p-1 group-hover:scale-105 transition-transform duration-200">
                        </a>
                        <div>
                            <a href="{{ route('survey.form') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-orange-600 hover:bg-orange-700 text-white font-extrabold text-[11px] shadow-sm transition-all">
                                <i class="fas fa-pen-to-square"></i> Isi Survei SKM Online
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Col 3: Address & Contact (4 cols) -->
                <div class="lg:col-span-4 space-y-3">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider border-l-2 border-orange-500 pl-2">Alamat Posko & Pusdalops</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        <i class="fas fa-map-marker-alt text-orange-500 me-2"></i>
                        {{ $settings['address'] ?? 'Jl. Soekarno - Hatta No.27, Dusun Mawar I, Pilang, Kec. Kademangan, Kota Probolinggo, Jawa Timur 67212' }}
                    </p>
                    <div class="space-y-1.5 text-xs text-slate-400 pt-2">
                        <p><i class="fas fa-phone-alt text-orange-500 me-2"></i><strong>Telepon / Call Center:</strong> {{ $settings['phone'] ?? '(0335) 422113' }}</p>
                        <p><i class="fas fa-envelope text-orange-500 me-2"></i><strong>Email:</strong> {{ $settings['email'] ?? 'bpbd@probolinggokab.go.id' }}</p>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright & Back To Top -->
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>{{ $settings['copyright_text'] ?? 'BPBD - Kabupaten Probolinggo © 2026. All Rights Reserved' }}</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="text-slate-400 hover:text-orange-400 font-medium flex items-center gap-1 transition-colors">
                        <i class="fas fa-lock text-[10px]"></i> Admin Login
                    </a>
                    <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" class="p-2 rounded-full bg-slate-800 hover:bg-orange-600 text-white transition-colors">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                </div>
            </div>
        </div>
    </footer>





    <!-- Text-To-Speech (Suara Google Cewek Bahasa Indonesia) Script -->
    <script>
        window.speakText = function(text) {
            if (!text || text.trim().length === 0) return;
            const cleanText = text.trim().replace(/\s+/g, ' ');

            try {
                if ('speechSynthesis' in window) {
                    window.speechSynthesis.cancel();
                    if (window.speechSynthesis.paused) {
                        window.speechSynthesis.resume();
                    }

                    const voices = window.speechSynthesis.getVoices();
                    const idVoice = voices.find(v => 
                        (v.lang && (v.lang.toLowerCase().startsWith('id') || v.lang.toLowerCase().includes('indonesia'))) ||
                        (v.name && (v.name.includes('Indonesia') || v.name.includes('Indonesian') || v.name.includes('Gadis') || v.name.includes('Andika')))
                    );

                    if (idVoice) {
                        const utterance = new SpeechSynthesisUtterance(cleanText);
                        utterance.voice = idVoice;
                        utterance.lang = 'id-ID';
                        utterance.rate = 0.95;
                        utterance.pitch = 1.0;
                        window.speechSynthesis.speak(utterance);
                        return;
                    }
                }

                // Fallback to Google Indonesian Female Voice stream (100% Bahasa Indonesia Asli)
                if (!window.googleTtsAudio) {
                    window.googleTtsAudio = new Audio();
                }
                window.googleTtsAudio.pause();
                window.googleTtsAudio.currentTime = 0;
                window.googleTtsAudio.src = 'https://translate.google.com/translate_tts?ie=UTF-8&q=' + encodeURIComponent(cleanText) + '&tl=id&client=tw-ob';
                window.googleTtsAudio.play().catch(function(e) {
                    console.log('Audio stream auto-play handler:', e);
                });
            } catch (err) {
                console.error('Speech error:', err);
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            let lastText = '';
            let speechTimeout = null;

            if ('speechSynthesis' in window) {
                window.speechSynthesis.getVoices();
                window.speechSynthesis.onvoiceschanged = function() {
                    window.speechSynthesis.getVoices();
                };
            }

            // Auto-speak welcome message when website opens
            let welcomeSpoken = false;
            function playWelcomeSpeech() {
                if (welcomeSpoken) return;
                welcomeSpoken = true;
                window.speakText('Selamat Datang di Website Resmi Badan Penanggulangan Bencana Daerah Kabupaten Probolinggo.');
            }

            // Try immediately on page load
            setTimeout(playWelcomeSpeech, 500);

            // Fallback for browser autoplay policies on first interaction (click/mousemove/scroll/keydown)
            const triggerAudioInteraction = function() {
                if (!welcomeSpoken) {
                    playWelcomeSpeech();
                }
            };
            ['click', 'mousemove', 'scroll', 'keydown', 'touchstart'].forEach(function(evt) {
                window.addEventListener(evt, triggerAudioInteraction, { once: true, passive: true });
            });

            const readableSelector = 'h1, h2, h3, h4, h5, h6, p, a, button, li, .textToRead, label, span';
            document.body.addEventListener('mouseover', function(e) {
                const target = e.target.closest(readableSelector);
                if (!target) return;

                const textContent = target.innerText || target.textContent;
                if (!textContent || textContent.trim().length < 2 || textContent.length > 250) return;

                if (textContent === lastText) return;

                clearTimeout(speechTimeout);
                speechTimeout = setTimeout(() => {
                    lastText = textContent;
                    window.speakText(textContent);
                }, 150);
            });
        });
    </script>

</body>
</html>
