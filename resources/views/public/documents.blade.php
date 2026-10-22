<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dokumen Publik & Laporan | BPBD Kabupaten Probolinggo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col"
      x-data="{ mobileMenu: false, pdfModalOpen: false, currentPdfUrl: '', currentPdfTitle: '' }">
    
    <!-- Navigation Header -->
    <header class="bg-white border-b-4 border-[#375e47] sticky top-0 z-50 shadow-xs">
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
                <a href="{{ route('home') }}" class="text-xs font-extrabold text-[#23325c] hover:text-[#375e47] flex items-center gap-1">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 py-10 w-full space-y-8">
        
        <div class="border-b border-slate-200 pb-6">
            <span class="px-3 py-1 bg-emerald-100 text-[#375e47] text-xs font-extrabold rounded-full inline-block uppercase tracking-wider mb-2">Pusat Informasi Public</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#23325c]">Dokumen Publik & Laporan Kebencanaan</h1>
            <p class="text-xs sm:text-sm text-slate-500 max-w-2xl mt-1">
                Unduh dokumen Kajian Risiko Bencana (KRB), Rencana Penanggulangan Bencana (RPB), Renstra, serta SOP Tanggap Darurat BPBD Kabupaten Probolinggo.
            </p>
        </div>

        <!-- Content Page Halaman (Jika Tersedia) -->
        @if(isset($page) && !empty(trim(strip_tags($page->content ?? ''))) && trim(strip_tags($page->content)) !== 'Halaman sedang dalam pembaruan konten.')
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm prose max-w-none text-[#23325c] text-sm leading-relaxed">
                {!! $page->content !!}
            </div>
        @endif

        <!-- Dokumen Pendukung Halaman (PDF yang Diunggah via CMS Editor Konten) -->
        @if(isset($page) && $page->documents && count($page->documents) > 0)
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                <h3 class="text-base font-extrabold text-[#23325c] tracking-tight flex items-center gap-2.5 border-b border-slate-100 pb-3">
                    <i class="fas fa-folder-open text-orange-600"></i> Dokumen Pendukung Halaman
                </h3>

                <div class="space-y-3">
                    @foreach($page->documents as $doc)
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:border-orange-300 hover:shadow-sm transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-lg font-bold shrink-0 border border-rose-200">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-slate-800 text-xs sm:text-sm tracking-wide">{{ $doc->file_name }}</h4>
                                    <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-0.5">
                                        Ukuran: {{ $doc->file_size ? ($doc->file_size > 1048576 ? number_format($doc->file_size / 1048576, 2) . ' MB' : number_format($doc->file_size / 1024, 1) . ' KB') : '0 KB' }}
                                        &bull; Diunggah: {{ $doc->created_at ? $doc->created_at->format('d M Y') : '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 self-start sm:self-center shrink-0">
                                <button type="button" 
                                        @click="currentPdfUrl = '{{ route('page_documents.view', $doc->id) }}'; currentPdfTitle = '{{ e($doc->file_name) }}'; pdfModalOpen = true"
                                        class="px-3.5 py-2 bg-[#375e47] hover:bg-[#2d4d3a] text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 transform hover:scale-[1.02]">
                                    <i class="fas fa-eye"></i> Lihat PDF
                                </button>
                                <a href="{{ route('page_documents.download', $doc->id) }}" 
                                   class="px-3.5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 transform hover:scale-[1.02]">
                                    <i class="fas fa-download"></i> Download PDF
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

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

    <footer class="bg-[#23325c] text-white py-6 mt-12 text-center text-xs border-t border-slate-700">
        <p>{{ $settings['copyright_text'] ?? 'BPBD Kabupaten Probolinggo © 2026. All Rights Reserved.' }}</p>
    </footer>

</body>
</html>
