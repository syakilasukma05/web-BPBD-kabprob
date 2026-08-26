<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Galeri Dokumentasi & Video Kebencanaan | BPBD Kabupaten Probolinggo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col" x-data="{ activeMedia: null }">
    
    <!-- Navigation Header -->
    <header class="bg-white border-b-4 border-[#375e47] sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="group flex items-center gap-3">
                <img src="{{ !empty($settings['logo_frontend']) ? $settings['logo_frontend'] : asset('images/logo-bpbd.svg') }}" alt="Logo" class="h-11 sm:h-12 w-auto object-contain transition-transform group-hover:scale-105 duration-200">
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
                <a href="{{ route('home') }}" class="text-xs font-extrabold text-[#23325c] hover:text-[#375e47] flex items-center gap-1">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
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

                        <span class="absolute top-3 left-3 px-2.5 py-1 bg-black/60 backdrop-blur-md text-white text-[10px] font-extrabold rounded-md uppercase tracking-wider">
                            {{ $media->category }}
                        </span>
                    </div>

                    <div class="p-4 space-y-1.5 flex-grow">
                        <h3 class="font-bold text-[#23325c] text-xs line-clamp-2 leading-snug group-hover:text-amber-600 transition-colors">
                            {{ $media->title }}
                        </h3>
                        @if($media->caption)
                            <p class="text-[11px] text-slate-500 line-clamp-2 font-normal leading-relaxed">
                                {{ $media->caption }}
                            </p>
                        @endif
                    </div>

                    <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-100 text-[10px] text-slate-400 font-medium flex items-center justify-between">
                        <span><i class="far fa-calendar-alt me-1"></i> {{ $media->created_at->format('d M Y') }}</span>
                        <span class="font-bold text-[#375e47] flex items-center gap-1">
                            {{ $media->type === 'video' ? 'Putar Video' : 'Lihat Foto' }} <i class="fas fa-arrow-right text-[9px]"></i>
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
        <div class="bg-white rounded-3xl max-w-4xl w-full shadow-2xl overflow-hidden space-y-0 my-auto">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
                <div>
                    <span class="text-[10px] font-extrabold text-amber-600 uppercase tracking-wider block" x-text="activeMedia?.category"></span>
                    <h3 class="font-extrabold text-slate-900 text-sm" x-text="activeMedia?.title"></h3>
                </div>
                <button @click="activeMedia = null" class="text-slate-400 hover:text-slate-700 p-2"><i class="fas fa-times text-lg"></i></button>
            </div>

            <div class="bg-black max-h-[75vh] flex items-center justify-center overflow-hidden">
                <template x-if="activeMedia?.type === 'video'">
                    <div class="w-full aspect-video bg-black">
                        <iframe :src="activeMedia?.youtube_embed_url || activeMedia?.video_url" 
                                class="w-full h-full border-0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                allowfullscreen></iframe>
                    </div>
                </template>
                <template x-if="activeMedia?.type === 'foto'">
                    <img :src="activeMedia?.file_url" :alt="activeMedia?.title" class="max-h-[70vh] w-auto object-contain mx-auto">
                </template>
            </div>

            <div class="p-6 bg-white space-y-2">
                <p class="text-xs text-slate-600 leading-relaxed font-medium" x-text="activeMedia?.caption || 'Tidak ada keterangan tambahan.'"></p>
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
