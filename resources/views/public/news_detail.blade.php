<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $news->title }} | BPBD Kabupaten Probolinggo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
<body class="bg-slate-50 text-slate-800 font-sans">
    
    <!-- Navigation Header -->
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

    <main class="max-w-4xl mx-auto px-4 py-12 space-y-6">
        <div class="space-y-3">
            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">{{ $news->category }}</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">{{ $news->title }}</h1>
            <p class="text-xs text-slate-500">Dipublikasikan pada {{ optional($news->published_at)->format('d F Y') }} • {{ $news->views }} kali dibaca</p>
        </div>

        @if($news->image_url)
            <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="w-full h-auto max-h-[420px] object-cover rounded-2xl shadow-sm">
        @endif

        <div class="prose max-w-none text-sm text-slate-700 leading-relaxed space-y-4 bg-white p-6 rounded-2xl border border-slate-200">
            @if(preg_match('/<[a-z][\s\S]*>/i', $news->content))
                {!! $news->content !!}
            @else
                {!! nl2br(e($news->content)) !!}
            @endif
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
                const newsTitle = document.querySelector('h1')?.innerText || '{{ $news->title ?? "Berita BPBD" }}';
                window.speakText('Selamat membaca berita: ' + newsTitle);
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
