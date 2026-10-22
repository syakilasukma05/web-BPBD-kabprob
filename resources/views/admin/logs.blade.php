@extends('admin.layout')

@section('page_title', 'Log Aktivitas Sistem')

@section('content')
<div class="space-y-6" x-data="{ showClearModal: false, selectedLog: null }">

    <!-- Header Title & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Log Aktivitas Sistem</h3>
            <p class="text-xs text-slate-500 mt-1">Rekam jejak histori kegiatan dan aksi yang dilakukan oleh Super Admin & Staf di dalam sistem.</p>
        </div>
        
        @if($logs->total() > 0)
            <button @click="showClearModal = true" 
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold rounded-xl border border-rose-200 shadow-xs transition-all">
                <i class="fas fa-trash-alt"></i> Bersihkan Semua Log
            </button>
        @endif
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900"><i class="fas fa-times"></i></button>
        </div>
    @endif

    <!-- Statistic Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold border border-amber-100">
                <i class="fas fa-history"></i>
            </div>
            <div>
                <span class="text-xs text-slate-500 font-semibold block">Total Catatan Log</span>
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
                <i class="fas fa-users"></i>
            </div>
            <div>
                <span class="text-xs text-slate-500 font-semibold block">User Aktif</span>
                <span class="text-2xl font-extrabold text-slate-900">{{ number_format($stats['unique_users']) }}</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold border border-blue-100">
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

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.logs') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between gap-3">
            <div class="w-full md:w-1/2 relative">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari berdasarkan nama user, tindakan, detail deskripsi, IP..." 
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-amber-500 focus:bg-white focus:outline-none">
            </div>

            <div class="w-full md:w-auto flex flex-wrap items-center gap-2">
                <select name="action" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    <option value="">-- Semua Jenis Tindakan --</option>
                    @foreach($actionTypes as $type)
                        <option value="{{ $type }}" {{ request('action') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-[#0f172a] hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>

                @if(request()->hasAny(['search', 'action']))
                    <a href="{{ route('admin.logs') }}" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Activity Log Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-extrabold text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Pengguna</th>
                        <th class="px-6 py-4">Tindakan</th>
                        <th class="px-6 py-4">Detail Deskripsi</th>
                        <th class="px-6 py-4">IP Address</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-slate-500 text-[11px]">
                                <div class="font-bold text-slate-800">{{ $log->created_at->format('d M Y, H:i:s') }}</div>
                                <div class="text-[10px] text-slate-400">{{ $log->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg {{ $log->user_role === 'super_admin' ? 'bg-emerald-600' : 'bg-blue-600' }} text-white font-bold text-xs flex items-center justify-center shadow-xs">
                                        {{ Str::upper(Str::substr($log->user_name ?? 'U', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $log->user_name ?? 'Sistem' }}</div>
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded {{ $log->user_role === 'super_admin' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $log->user_role === 'super_admin' ? 'Super Admin' : 'Anggota Staf' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $actionColor = match(true) {
                                        Str::contains($log->action, ['Login', 'Tambah']) => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        Str::contains($log->action, ['Update', 'Perbarui']) => 'bg-amber-100 text-amber-800 border-amber-200',
                                        Str::contains($log->action, ['Hapus', 'Logout', 'Bersihkan']) => 'bg-rose-100 text-rose-800 border-rose-200',
                                        default => 'bg-slate-100 text-slate-800 border-slate-200'
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold border {{ $actionColor }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4 max-w-md">
                                <p class="text-xs text-slate-700 line-clamp-2 leading-relaxed font-normal">
                                    {{ $log->description ?? '-' }}
                                </p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-mono text-[11px] text-slate-500">
                                <span class="px-2 py-1 bg-slate-100 rounded-md">{{ $log->ip_address ?? '127.0.0.1' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-1">
                                <button @click="selectedLog = {{ json_encode($log) }}" 
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Lihat Detail Log">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <form action="{{ route('admin.logs.destroy', $log->id) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus entri log ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Log">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <i class="fas fa-history text-4xl mb-3 text-slate-300"></i>
                                <p class="text-xs font-semibold">Belum ada data log aktivitas yang tercatat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Detail Log -->
    <div x-show="selectedLog !== null" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fas fa-info-circle text-amber-500"></i> Detail Log Aktivitas
                </h3>
                <button @click="selectedLog = null" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>
            
            <template x-if="selectedLog">
                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 font-semibold block">Waktu Kejadian:</span>
                        <span class="font-bold text-slate-800" x-text="selectedLog.created_at"></span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold block">Pelaku (Pengguna):</span>
                        <span class="font-bold text-slate-800" x-text="selectedLog.user_name + ' (' + selectedLog.user_role + ')'"></span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold block">Tindakan:</span>
                        <span class="font-extrabold text-amber-600" x-text="selectedLog.action"></span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold block">Deskripsi Lengkap:</span>
                        <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl font-mono text-[11px] text-slate-700 whitespace-pre-wrap mt-1" x-text="selectedLog.description || '-'"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100">
                        <div>
                            <span class="text-slate-400 font-semibold block">IP Address:</span>
                            <span class="font-mono text-slate-800" x-text="selectedLog.ip_address"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block">Browser/Agent:</span>
                            <span class="font-mono text-[10px] text-slate-600 line-clamp-1" x-text="selectedLog.user_agent" :title="selectedLog.user_agent"></span>
                        </div>
                    </div>
                </div>
            </template>

            <div class="pt-2 text-right border-t border-slate-100">
                <button @click="selectedLog = null" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal Clear All Confirm -->
    <div x-show="showClearModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl p-6 space-y-4 text-center">
            <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto text-2xl font-bold">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-800 text-base">Bersihkan Seluruh Log Aktivitas?</h3>
                <p class="text-xs text-slate-500 mt-1">Tindakan ini akan menghapus semua riwayat catatan log aktivitas dari basis data dan tidak dapat dikembalikan.</p>
            </div>
            <div class="flex items-center justify-center gap-3 pt-2">
                <button @click="showClearModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl">Batal</button>
                <form action="{{ route('admin.logs.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md">
                        Ya, Bersihkan Semua
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
