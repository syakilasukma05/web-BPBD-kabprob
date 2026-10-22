<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Informasi & Berita Kebencanaan | BPBD Kabupaten Probolinggo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans">
    
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="group flex items-center gap-3">
                <img src="{{ !empty($settings['logo_frontend']) ? $settings['logo_frontend'] : asset('images/logo-bpbd.svg') }}" alt="Logo" class="h-9 sm:h-10 w-auto object-contain transition-transform group-hover:scale-105 duration-200">
                <div class="flex flex-col justify-center leading-tight">
                    <span class="font-extrabold text-xs sm:text-sm md:text-base text-[#1b263b] tracking-tight group-hover:text-[#375e47] transition-colors uppercase">
                        BPBD KABUPATEN PROBOLINGGO
                    </span>
                    <span class="font-bold text-[9px] sm:text-[11px] text-orange-600 tracking-wider uppercase">
                        BADAN PENANGGULANGAN BENCANA DAERAH
                    </span>
                </div>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="text-xs font-bold text-slate-600 hover:text-orange-600">
                    &larr; Kembali ke Beranda
                </a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-1.5 bg-slate-900 text-white rounded-lg text-xs font-bold hover:bg-slate-800 transition">
                        <i class="fas fa-tachometer-alt text-amber-400 me-1"></i> Dashboard
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-12 space-y-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Portal Informasi & Berita Kebencanaan</h1>
            <p class="text-xs text-slate-500">Pusat informasi berita kebencanaan, peringatan dini, dan siaran pers resmi BPBD Kabupaten Probolinggo</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($newsList as $news)
                <article class="bg-white rounded-lg shadow-md hover:shadow-xl border border-slate-100 overflow-hidden flex flex-col group transition-all duration-300">
                    <div class="relative h-48 overflow-hidden bg-slate-100">
                        <img src="{{ $news->image_url ?? 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb' }}" alt="{{ $news->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        
                        <!-- Official Style Date Badge (Top Left) -->
                        <div class="absolute top-3 left-3 flex shadow-md overflow-hidden rounded-xs text-xs font-bold">
                            <span class="bg-[#657737] text-white px-3 py-1.5 text-sm font-extrabold flex items-center justify-center min-w-[34px]">
                                {{ optional($news->published_at)->format('d') ?? '01' }}
                            </span>
                            <span class="bg-white text-[#1e293b] px-3 py-1.5 flex items-center text-[11px] font-extrabold uppercase tracking-wider">
                                {{ optional($news->published_at)->format('M Y') ?? 'JAN 2026' }}
                            </span>
                        </div>
                    </div>
                    <div class="p-5 flex-grow flex flex-col justify-between space-y-3">
                        <span class="text-[10px] font-extrabold text-blue-600 uppercase tracking-wider">{{ $news->category }}</span>
                        <h3 class="font-bold text-[#1e2b4f] text-sm group-hover:text-blue-700 transition-colors line-clamp-3 leading-snug">
                            <a href="{{ $news->link_url ?: route('news.detail', $news->slug) }}" {{ $news->link_url ? 'target=_blank' : '' }}>
                                {{ $news->title }}
                            </a>
                        </h3>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $newsList->links() }}
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
                window.speakText('Halaman Informasi Kebencanaan BPBD Kabupaten Probolinggo.');
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
