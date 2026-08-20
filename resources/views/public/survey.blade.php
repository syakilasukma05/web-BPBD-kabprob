<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Survei Kepuasan Masyarakat (SKM) | BPBD Kabupaten Probolinggo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col">
    
    <!-- Navigation Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ !empty($settings['logo_frontend']) ? $settings['logo_frontend'] : asset('images/logo-bpbd.svg') }}" alt="Logo" class="w-11 h-11 object-contain">
                <div>
                    <span class="font-extrabold text-slate-900 text-sm block">BPBD KABUPATEN PROBOLINGGO</span>
                    <span class="text-[10px] text-orange-600 font-bold tracking-wider uppercase block">Survei Kepuasan Masyarakat</span>
                </div>
            </a>
            <a href="{{ route('home') }}" class="text-xs font-bold text-slate-600 hover:text-orange-600 flex items-center gap-1">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-3xl mx-auto px-4 py-10 w-full space-y-6">
        
        <div class="text-center space-y-2">
            <span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs font-extrabold rounded-full inline-block uppercase tracking-wider">Formulir SKM Online</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Survei Kepuasan Masyarakat (SKM)</h1>
            <p class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto">
                Bantu kami meningkatkan kualitas pelayanan BPBD Kabupaten Probolinggo dengan memberikan penilaian dan ulasan Anda di bawah ini.
            </p>
        </div>

        @if(session('success_survey'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs sm:text-sm font-bold flex items-center gap-3 shadow-xs">
                <i class="fas fa-check-circle text-emerald-600 text-2xl"></i>
                <div>
                    <p>{{ session('success_survey') }}</p>
                    <span class="text-[11px] text-emerald-600 font-normal">Tanggapan Anda sangat berharga untuk pelayanan masyarakat yang lebih baik.</span>
                </div>
            </div>
        @endif

        <!-- Survey Card Form -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md p-6 sm:p-8 space-y-6">
            
            <form action="{{ route('survey.submit') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Rating Selector -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-3 text-center">
                        Bagaimana Tingkat Kepuasan Anda Terhadap Pelayanan BPBD?
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3" x-data="{ selectedRating: 'Sangat Puas' }">
                        <label @click="selectedRating = 'Sangat Puas'" 
                               :class="selectedRating === 'Sangat Puas' ? 'border-orange-500 bg-orange-50/80 text-orange-800 ring-2 ring-orange-400/30' : 'border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100'"
                               class="p-4 rounded-2xl border text-center cursor-pointer transition-all duration-200 flex flex-col items-center gap-2">
                            <input type="radio" name="rating" value="Sangat Puas" x-model="selectedRating" class="sr-only">
                            <span class="text-3xl">😍</span>
                            <span class="text-xs font-bold">Sangat Puas</span>
                        </label>

                        <label @click="selectedRating = 'Puas'" 
                               :class="selectedRating === 'Puas' ? 'border-emerald-500 bg-emerald-50/80 text-emerald-800 ring-2 ring-emerald-400/30' : 'border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100'"
                               class="p-4 rounded-2xl border text-center cursor-pointer transition-all duration-200 flex flex-col items-center gap-2">
                            <input type="radio" name="rating" value="Puas" x-model="selectedRating" class="sr-only">
                            <span class="text-3xl">😊</span>
                            <span class="text-xs font-bold">Puas</span>
                        </label>

                        <label @click="selectedRating = 'Cukup'" 
                               :class="selectedRating === 'Cukup' ? 'border-amber-500 bg-amber-50/80 text-amber-800 ring-2 ring-amber-400/30' : 'border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100'"
                               class="p-4 rounded-2xl border text-center cursor-pointer transition-all duration-200 flex flex-col items-center gap-2">
                            <input type="radio" name="rating" value="Cukup" x-model="selectedRating" class="sr-only">
                            <span class="text-3xl">😐</span>
                            <span class="text-xs font-bold">Cukup</span>
                        </label>

                        <label @click="selectedRating = 'Kurang Puas'" 
                               :class="selectedRating === 'Kurang Puas' ? 'border-rose-500 bg-rose-50/80 text-rose-800 ring-2 ring-rose-400/30' : 'border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100'"
                               class="p-4 rounded-2xl border text-center cursor-pointer transition-all duration-200 flex flex-col items-center gap-2">
                            <input type="radio" name="rating" value="Kurang Puas" x-model="selectedRating" class="sr-only">
                            <span class="text-3xl">🙁</span>
                            <span class="text-xs font-bold">Kurang Puas</span>
                        </label>
                    </div>
                </div>

                <!-- Responden Info -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Responden *</label>
                        <input type="text" name="name" required placeholder="Contoh: Ahmad Fauzi" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-orange-500 focus:outline-none bg-slate-50/50">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email / No. WhatsApp *</label>
                        <input type="text" name="email" required placeholder="Contoh: ahmad@gmail.com atau 0812..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-orange-500 focus:outline-none bg-slate-50/50">
                    </div>
                </div>

                <!-- Review / Ulasan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Ulasan, Kritik & Saran Pelayanan *</label>
                    <textarea name="message" required rows="4" placeholder="Tuliskan masukan atau pengalaman Anda terkait pelayanan BPBD Kabupaten Probolinggo..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-orange-500 focus:outline-none bg-slate-50/50"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <a href="{{ $settings['survey_url'] ?? 'https://sukma.jatimprov.go.id/' }}" target="_blank" rel="noopener noreferrer" class="text-xs text-slate-500 hover:text-orange-600 font-medium flex items-center gap-1.5">
                        <i class="fas fa-external-link-alt text-[10px]"></i> Buka Portal SuKMa-e Jatim (Opsional)
                    </a>
                    <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> Kirim Hasil Survei
                    </button>
                </div>

            </form>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <p>{{ $settings['copyright_text'] ?? 'BPBD - Kabupaten Probolinggo © 2026. All Rights Reserved' }}</p>
    </footer>

</body>
</html>
