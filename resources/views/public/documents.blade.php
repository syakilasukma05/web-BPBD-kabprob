<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dokumen Publik & Laporan | BPBD Kabupaten Probolinggo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col">
    
    <!-- Navigation Header -->
    <header class="bg-white border-b-4 border-[#375e47] sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ !empty($settings['logo_frontend']) ? $settings['logo_frontend'] : asset('images/logo-bpbd.svg') }}" alt="Logo" class="h-12 w-auto object-contain">
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Documents List -->
            <div class="lg:col-span-2 space-y-4">
                @forelse($documents as $doc)
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition-shadow duration-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0 text-xl font-bold border border-rose-100">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="space-y-1">
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-md uppercase tracking-wider">{{ $doc->category }}</span>
                                <h3 class="font-bold text-[#23325c] text-sm leading-snug">{{ $doc->title }}</h3>
                                <p class="text-xs text-slate-500 flex items-center gap-3">
                                    <span><i class="far fa-calendar-alt text-slate-400 me-1"></i> {{ $doc->year ?? date('Y') }}</span>
                                    <span><i class="fas fa-hdd text-slate-400 me-1"></i> {{ $doc->file_size ?? 'PDF' }}</span>
                                    <span><i class="fas fa-download text-slate-400 me-1"></i> {{ $doc->download_count ?? 0 }}x diunduh</span>
                                </p>
                            </div>
                        </div>
                        <a href="{{ $doc->file_url }}" target="_blank" class="w-full sm:w-auto text-center px-4 py-2.5 bg-[#23325c] hover:bg-[#1a2544] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center justify-center gap-2 flex-shrink-0">
                            <i class="fas fa-download"></i> Unduh File
                        </a>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center text-slate-400">
                        <i class="fas fa-folder-open text-4xl mb-3 text-slate-300"></i>
                        <p class="text-sm font-semibold">Belum ada dokumen publik yang tersedia.</p>
                    </div>
                @endforelse

                <div class="mt-6">
                    {{ $documents->links() }}
                </div>
            </div>

            <!-- Sidebar Widgets -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <h3 class="font-extrabold text-[#23325c] text-sm uppercase tracking-wider border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fas fa-[#375e47] fa-phone-alt text-rose-600"></i> Kontak Pusdalops 24 Jam
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Jika Anda membutuhkan pertolongan darurat bencana atau informasi mendesak, silakan hubungi Pusdalops PB BPBD.
                    </p>
                    <a href="tel:08123123911" class="block w-full py-3 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs text-center rounded-xl shadow-md transition-all">
                        <i class="fas fa-headset me-1"></i> Call Center: 0812-3123-911
                    </a>
                </div>
            </div>
        </div>

    </main>

    <footer class="bg-[#23325c] text-white py-6 mt-12 text-center text-xs border-t border-slate-700">
        <p>{{ $settings['copyright_text'] ?? 'BPBD Kabupaten Probolinggo © 2026. All Rights Reserved.' }}</p>
    </footer>

</body>
</html>
