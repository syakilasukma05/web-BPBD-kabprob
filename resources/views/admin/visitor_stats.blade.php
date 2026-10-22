@extends('admin.layout')

@section('page_title', 'Kelola Statistik Pengunjung')

@section('content')
<div class="max-w-4xl space-y-6">
    
    <!-- Header Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl font-bold border border-blue-100 shadow-xs">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-slate-800 tracking-tight">Pengelolaan Statistik Pengunjung</h3>
                <p class="text-xs text-slate-500 mt-0.5">Kelola ringkasan statistik kunjungan (Visit & Hits) yang tampil pada halaman publik website.</p>
            </div>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold flex items-center gap-2.5 shadow-xs">
            <i class="fas fa-check-circle text-emerald-600 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Form Edit Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
            <h4 class="font-extrabold text-xs tracking-wider uppercase flex items-center gap-2">
                <i class="fas fa-edit text-blue-400"></i> Form Edit Statistik Pengunjung
            </h4>
            <span class="text-[10px] bg-slate-800 text-slate-300 px-2.5 py-1 rounded-full font-mono font-bold">Super Admin Only</span>
        </div>

        <form action="{{ route('admin.visitor_stats.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Visit Hari Ini -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">
                        Visit Hari ini <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-xs font-bold">
                            <i class="fas fa-user-clock text-orange-500"></i>
                        </span>
                        <input type="number" name="visit_today" value="{{ old('visit_today', $stats['visit_today'] ?? 124) }}" min="0" required
                               class="w-full pl-10 pr-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    </div>
                    <p class="text-[10px] text-slate-400">Jumlah pengunjung (Visit) untuk hari ini.</p>
                </div>

                <!-- Visit Bulan Ini -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">
                        Visit Bulan ini <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-xs font-bold">
                            <i class="fas fa-calendar-alt text-amber-500"></i>
                        </span>
                        <input type="number" name="visit_month" value="{{ old('visit_month', $stats['visit_month'] ?? 2840) }}" min="0" required
                               class="w-full pl-10 pr-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    </div>
                    <p class="text-[10px] text-slate-400">Jumlah pengunjung (Visit) untuk bulan ini.</p>
                </div>

                <!-- Visit Tahun Ini -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">
                        Visit Tahun ini <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-xs font-bold">
                            <i class="fas fa-globe-asia text-emerald-500"></i>
                        </span>
                        <input type="number" name="visit_year" value="{{ old('visit_year', $stats['visit_year'] ?? 34510) }}" min="0" required
                               class="w-full pl-10 pr-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    </div>
                    <p class="text-[10px] text-slate-400">Jumlah pengunjung (Visit) untuk tahun ini.</p>
                </div>

                <!-- Hits Hari Ini -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">
                        Hits Hari ini <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-xs font-bold">
                            <i class="fas fa-mouse-pointer text-sky-500"></i>
                        </span>
                        <input type="number" name="hits_today" value="{{ old('hits_today', $stats['hits_today'] ?? 380) }}" min="0" required
                               class="w-full pl-10 pr-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    </div>
                    <p class="text-[10px] text-slate-400">Jumlah tayangan/klik halaman (Hits) untuk hari ini.</p>
                </div>

                <!-- Hits Bulan Ini -->
                <div class="space-y-1.5 md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700">
                        Hits Bulan ini <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-xs font-bold">
                            <i class="fas fa-chart-line text-indigo-500"></i>
                        </span>
                        <input type="number" name="hits_month" value="{{ old('hits_month', $stats['hits_month'] ?? 8920) }}" min="0" required
                               class="w-full pl-10 pr-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    </div>
                    <p class="text-[10px] text-slate-400">Jumlah tayangan/klik halaman (Hits) untuk bulan ini.</p>
                </div>

            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="submit" 
                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2 cursor-pointer hover:scale-[1.02]">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
