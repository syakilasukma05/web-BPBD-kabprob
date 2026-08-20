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
</head>
<body class="bg-white text-slate-700 font-sans antialiased min-h-screen flex flex-col selection:bg-orange-600 selection:text-white"
      x-data="{ mobileMenu: false }">

    <!-- Main Header / Navigation Wrap (Matching Official Header Design 100%) -->
    <header class="sticky top-0 z-50 bg-white border-b-4 border-[#375e47] shadow-xs transition-all duration-300">
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


                <!-- Right: BerAKHLAK Logo -->
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
            <div class="prose max-w-none text-[#23325c] leading-relaxed text-sm sm:text-base font-medium space-y-6">
                {!! $page->content !!}
            </div>

        </div>
    </main>



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
