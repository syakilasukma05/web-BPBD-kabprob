@extends('admin.layout')

@section('page_title', 'Log Aktivitas Staf')

@section('content')
<div class="space-y-6">

    <!-- Header Title -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                <span>📋 Log Aktivitas Staf Pusdalops</span>
            </h3>
            <p class="text-xs text-slate-500 mt-1">Rekam jejak histori kegiatan dan aksi yang khusus dilakukan oleh akun Staf Pusdalops BPBD.</p>
        </div>
    </div>

    <!-- Statistic Cards for Staff -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold border border-blue-100">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div>
                <span class="text-xs text-slate-500 font-semibold block">Total Log Staf</span>
                <span class="text-2xl font-extrabold text-slate-900">{{ number_format($stats['total_logs']) }}</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold border border-emerald-100">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div>
                <span class="text-xs text-slate-500 font-semibold block">Aktivitas Hari Ini</span>
                <span class="text-2xl font-extrabold text-slate-900">{{ number_format($stats['today_logs']) }}</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold border border-indigo-100">
                <i class="fas fa-user-shield"></i>
            </div>
            <div>
                <span class="text-xs text-slate-500 font-semibold block">Staf Aktif</span>
                <span class="text-2xl font-extrabold text-slate-900">{{ number_format($stats['unique_staf']) }}</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold border border-amber-100">
                <i class="fas fa-clock"></i>
            </div>
            <div class="overflow-hidden">
                <span class="text-xs text-slate-500 font-semibold block">Aktivitas Terakhir</span>
                <span class="text-xs font-bold text-slate-800 truncate block">
                    {{ $stats['last_activity'] ? $stats['last_activity']->created_at->diffForHumans() : '-' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.staf_logs') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between gap-3">
            <div class="w-full md:w-1/2 relative">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama staf, aktivitas, deskripsi, IP address..." 
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none">
            </div>

            <div class="w-full md:w-auto flex flex-wrap items-center gap-2">
                <select name="action" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Semua Aktivitas Staf --</option>
                    @foreach($actionTypes as $type)
                        <option value="{{ $type }}" {{ request('action') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-[#0f172a] hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>

                @if(request()->hasAny(['search', 'action']))
                    <a href="{{ route('admin.staf_logs') }}" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Staff Activity Logs Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-extrabold text-[10px] tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5 text-center w-12">No</th>
                        <th class="px-6 py-3.5">Tanggal & Waktu</th>
                        <th class="px-6 py-3.5">Nama Staf</th>
                        <th class="px-6 py-3.5">Aktivitas</th>
                        <th class="px-6 py-3.5">Modul</th>
                        <th class="px-6 py-3.5">IP Address</th>
                        <th class="px-6 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($logs as $index => $log)
                        @php
                            // Determine module based on action and description
                            $modul = 'Staf Pusdalops';
                            $actionLower = strtolower($log->action . ' ' . $log->description);
                            if (str_contains($actionLower, 'dokumen')) {
                                $modul = 'Dokumen Kinerja';
                            } elseif (str_contains($actionLower, 'berita') || str_contains($actionLower, 'informasi')) {
                                $modul = 'Berita & Informasi';
                            } elseif (str_contains($actionLower, 'login') || str_contains($actionLower, 'logout') || str_contains($actionLower, 'sesi')) {
                                $modul = 'Autentikasi Sesi';
                            } elseif (str_contains($actionLower, 'profil') || str_contains($actionLower, 'halaman')) {
                                $modul = 'Pengelolaan Halaman';
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-4 text-center font-bold text-slate-500">
                                {{ $logs->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-500 text-[11px]">
                                <div class="font-bold text-slate-800">{{ $log->created_at ? $log->created_at->timezone('Asia/Jakarta')->format('d F Y H:i') : '-' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $log->created_at ? $log->created_at->timezone('Asia/Jakarta')->diffForHumans() : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-blue-600 text-white font-extrabold text-[11px] flex items-center justify-center shadow-xs">
                                        {{ Str::upper(Str::substr($log->user_name ?? 'S', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $log->user_name ?? 'Staf Pusdalops' }}</div>
                                        <span class="text-[10px] font-bold px-1.5 py-0.2 rounded bg-blue-100 text-blue-800">
                                            Staf Pusdalops
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-slate-800 block">{{ $log->action }}</span>
                                @if($log->description)
                                    <span class="text-[11px] text-slate-500 block truncate max-w-xs" title="{{ $log->description }}">
                                        {{ $log->description }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    <i class="fas fa-cube text-blue-500 text-[10px]"></i>
                                    {{ $modul }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-mono text-slate-600 text-[11px]">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <i class="fas fa-check-circle me-1 text-emerald-600"></i> Berhasil
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-xl">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <p class="font-bold text-slate-600 text-sm">Belum Ada Catatan Log Aktivitas Staf</p>
                                <p class="text-xs text-slate-400 mt-1">Seluruh aktivitas yang dilakukan oleh Staf Pusdalops akan otomatis muncul di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($logs->hasPages())
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
