<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->title }} | BPBD Kabupaten Probolinggo</title>
    <meta name="description" content="{{ Str::limit(strip_tags($page->content), 150) }}">
    
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
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .prose ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem; text-align: left; }
        .prose ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem; text-align: left; }
        .prose h1 { font-size: 1.875rem; font-weight: 800; margin-top: 1.5rem; margin-bottom: 0.75rem; color: #1e293b; text-align: left; }
        .prose h2 { font-size: 1.5rem; font-weight: 800; margin-top: 1.25rem; margin-bottom: 0.75rem; color: #1e293b; text-align: left; }
        .prose h3 { font-size: 1.25rem; font-weight: 700; margin-top: 1rem; margin-bottom: 0.5rem; color: #334155; text-align: left; }
        .prose p { margin-bottom: 0.85rem; line-height: 1.7; }
        .prose table { width: 100%; border-collapse: collapse; margin-bottom: 1.25rem; }
        .prose table td, .prose table th { border: 1px solid #cbd5e1; padding: 0.6rem 0.85rem; text-align: left; }
        .prose table th { background-color: #f8fafc; font-weight: 700; }
        .prose blockquote { border-left: 4px solid #ea580c; padding-left: 1rem; font-style: italic; color: #475569; margin-bottom: 1.25rem; }
        .prose img { max-width: 100%; height: auto; border-radius: 0.75rem; margin: 1rem auto; }
        .prose figure.image { display: inline-block; max-width: 100%; margin: 1rem 0; }
        .prose figure.image img { margin: 0; }
        .prose figure.image figcaption { font-size: 0.75rem; color: #64748b; text-align: center; margin-top: 0.35rem; }
        .text-align-left, .prose .text-align-left { text-align: left !important; }
        .text-align-center, .prose .text-align-center { text-align: center !important; }
        .text-align-right, .prose .text-align-right { text-align: right !important; }
        .text-align-justify, .prose .text-align-justify { text-align: justify !important; }
    </style>
</head>
<body class="bg-white text-slate-700 font-sans antialiased min-h-screen flex flex-col selection:bg-orange-600 selection:text-white"
      x-data="{ mobileMenu: false, pdfModalOpen: false, currentPdfUrl: '', currentPdfTitle: '' }">

    <!-- Main Header / Navigation Wrap (Matching Official Header Design 100%) -->
    <header class="sticky top-0 z-50 bg-white border-b-4 border-[#375e47] shadow-xs transition-all duration-300">
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

                <!-- Middle: Navigation Menu (HOME, PROFIL, LAYANAN, DOKUMEN, INFORMASI, HUBUNGI, LOGIN) -->
                <nav class="hidden lg:flex items-center space-x-4 xl:space-x-6">
                    @foreach($navMenus as $menu)
                        @if(in_array(strtoupper($menu->title), ['LOGIN']))
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
                                        @php
                                            $isSpanLapor = \Illuminate\Support\Str::contains(strtolower($child->title ?? ''), ['sp4n', 'lapor']);
                                            $isExt = ($child->menu_type === 'external_link' || $child->is_external || preg_match('/^https?:\/\//i', trim($child->url ?? '')));
                                            $linkUrl = $isExt ? ($child->external_url ?? $child->url) : $child->url;
                                            $targetAttr = $isSpanLapor ? '_blank' : ($child->target ?: ($isExt ? '_blank' : '_self'));
                                            $relAttr = ($isSpanLapor || $targetAttr === '_blank') ? 'rel="noopener noreferrer"' : '';
                                        @endphp
                                        <a href="{{ $linkUrl }}" target="{{ $targetAttr }}" {!! $relAttr !!}
                                           onmouseenter="speakText('{{ e($child->title) }}')"
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
                               onmouseenter="speakText('{{ e($menu->title) }}')"
                               class="px-2 py-2 text-sm font-extrabold tracking-wide text-[#23325c] hover:text-[#375e47] uppercase transition-colors duration-150">
                                {{ $menu->title }}
                            </a>
                        @endif
                    @endforeach
                </nav>


                <!-- Right: BerAKHLAK Logo -->
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
    </header>

    <!-- MAIN PAGE CONTENT CONTAINER (Matching Official Diskominfo Visi Misi Page Layout 100%) -->
    <main class="flex-grow bg-white py-16 px-4 sm:px-6">
        <div class="max-w-4xl mx-auto space-y-8 text-center">
            
            <!-- Featured Page Image (If uploaded or linked) -->
            @if(!empty($page->image_url))
                <div class="mb-8 overflow-hidden rounded-2xl border border-slate-200 shadow-sm bg-slate-50 p-2">
                    <img src="{{ $page->image_url }}" alt="{{ $page->title }}" class="w-full max-h-[600px] object-contain mx-auto rounded-xl">
                </div>
            @endif

            <!-- Dynamic Content Output (Formatted centered matching screenshot) -->
            @php
                $cleanContent = trim(strip_tags($page->content ?? ''));
                $isPlaceholder = ($cleanContent === 'Halaman sedang dalam pembaruan konten.' || empty($cleanContent));
                $hasDocs = $page->documents && count($page->documents) > 0;
            @endphp
            @if(!$isPlaceholder || !$hasDocs)
                <div class="prose max-w-none text-[#23325c] leading-relaxed text-sm sm:text-base font-medium space-y-6">
                    {!! $page->content !!}
                </div>
            @endif

            <!-- Dokumen Pendukung Halaman (PDF) -->
            @if($page->documents && count($page->documents) > 0)
                <div class="mt-12 pt-8 border-t border-slate-200 text-left">
                    <h3 class="text-lg font-extrabold text-[#23325c] tracking-tight flex items-center gap-2.5 mb-6">
                        <i class="fas fa-folder-open text-orange-600"></i> Dokumen Pendukung Halaman
                    </h3>

                    <div class="space-y-4">
                        @foreach($page->documents as $doc)
                            <div class="bg-slate-50 rounded-2xl p-4 sm:p-5 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-orange-300 hover:shadow-md transition">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-11 h-11 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl font-bold shrink-0 border border-rose-200 shadow-2xs">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-slate-800 text-sm tracking-wide">{{ $doc->file_name }}</h4>
                                        <p class="text-xs text-slate-500 font-medium mt-0.5">
                                            Ukuran: {{ $doc->file_size ? ($doc->file_size > 1048576 ? number_format($doc->file_size / 1048576, 2) . ' MB' : number_format($doc->file_size / 1024, 1) . ' KB') : '0 KB' }}
                                            &bull; Diunggah: {{ $doc->created_at ? $doc->created_at->format('d M Y') : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2.5 self-start sm:self-center shrink-0">
                                    <button type="button" 
                                            @click="currentPdfUrl = '{{ route('page_documents.view', $doc->id) }}'; currentPdfTitle = '{{ e($doc->file_name) }}'; pdfModalOpen = true"
                                            class="px-4 py-2.5 bg-[#375e47] hover:bg-[#2d4d3a] text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-2 transform hover:scale-[1.02]">
                                        <i class="fas fa-eye"></i> Lihat PDF
                                    </button>
                                    <a href="{{ route('page_documents.download', $doc->id) }}" 
                                       class="px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-2 transform hover:scale-[1.02]">
                                        <i class="fas fa-download"></i> Download PDF
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Link Terkait Halaman -->
            @php
                $activePageLinks = $page->links ? $page->links->where('status', 'aktif') : collect();
            @endphp
            @if($activePageLinks && $activePageLinks->count() > 0)
                <div class="mt-12 pt-8 border-t border-slate-200 text-left">
                    <h3 class="text-lg font-extrabold text-[#23325c] tracking-tight flex items-center gap-2.5 mb-6">
                        <i class="fas fa-link text-orange-600"></i> Link Terkait
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($activePageLinks as $link)
                            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 flex flex-col justify-between gap-3 hover:border-orange-300 hover:shadow-md transition">
                                <div class="space-y-1.5">
                                    <h4 class="font-extrabold text-slate-800 text-sm tracking-wide flex items-center gap-2">
                                        <span class="text-base">🔗</span>
                                        <span>{{ $link->title }}</span>
                                    </h4>
                                    @if($link->description)
                                        <p class="text-xs text-slate-500 font-medium leading-relaxed">
                                            Keterangan: {{ $link->description }}
                                        </p>
                                    @endif
                                </div>

                                <div class="pt-2 flex justify-start">
                                    <a href="{{ $link->url }}" target="{{ $link->target ?? '_blank' }}" rel="noopener noreferrer"
                                       class="px-4 py-2.5 bg-[#375e47] hover:bg-[#2d4d3a] text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-2 transform hover:scale-[1.02]">
                                        <i class="fas fa-external-link-alt text-[10px]"></i> Kunjungi
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </main>

    <!-- PDF Interactive Preview Modal -->
    <div x-show="pdfModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm overflow-hidden">
        <div class="bg-white rounded-2xl max-w-5xl w-full h-[90vh] shadow-2xl overflow-hidden flex flex-col" @click.away="pdfModalOpen = false">
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-200 bg-slate-50 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center text-sm font-bold">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <h4 class="font-extrabold text-slate-800 text-sm truncate max-w-lg" x-text="currentPdfTitle"></h4>
                </div>
                <div class="flex items-center gap-2">
                    <a :href="currentPdfUrl.replace('/view', '/download')" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-1.5">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                    <button type="button" @click="pdfModalOpen = false" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl transition">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>
            <div class="flex-1 bg-slate-100 p-2">
                <template x-if="pdfModalOpen">
                    <iframe :src="currentPdfUrl" class="w-full h-full rounded-xl border border-slate-200 bg-white" title="PDF Preview"></iframe>
                </template>
            </div>
        </div>
    </div>



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

            let welcomeSpoken = false;
            function playWelcomeSpeech() {
                if (welcomeSpoken) return;
                welcomeSpoken = true;
                const pageTitle = document.querySelector('h1')?.innerText || '{{ $page->title ?? "Halaman BPBD" }}';
                window.speakText('Anda sedang membuka halaman ' + pageTitle + ' BPBD Kabupaten Probolinggo.');
            }

            setTimeout(playWelcomeSpeech, 500);

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
