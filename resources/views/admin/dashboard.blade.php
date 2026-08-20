@extends('admin.layout')

@section('page_title', 'Dashboard Utama')

@section('content')
<div class="space-y-6">
    
    <!-- Welcome Banner Card -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-slate-800">
        <div class="relative z-10 space-y-3">
            <span class="px-3 py-1 bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 text-xs font-bold rounded-full uppercase tracking-wider">
                PANEL PENGELOLAAN WEBSITE RESMI
            </span>
            <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight">Selamat Datang di Panel Administrator, {{ auth()->user()->name }}</h2>
            <p class="text-xs text-slate-300 max-w-2xl leading-relaxed">
                Anda login sebagai <strong class="text-amber-300 font-bold">{{ auth()->user()->isSuperAdmin() ? 'Administrator Utama' : 'Staf Operasional' }}</strong> BPBD Kabupaten Probolinggo. 
                @if(auth()->user()->isSuperAdmin())
                    Anda memiliki hak akses penuh untuk mengelola seluruh informasi publik, dokumen kinerja, berita, menu navigasi, dan hak akses pengguna.
                @else
                    Hak akses Anda meliputi pengelolaan berkas <strong>Dokumen Kinerja</strong> dan artikel <strong>Informasi & Berita Kebencanaan</strong>.
                @endif
            </p>
        </div>
        <div class="absolute right-4 -bottom-6 opacity-10 text-white text-9xl pointer-events-none">
            <i class="fas fa-shield-alt"></i>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold border border-blue-100">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500">Dokumen Kinerja</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $stats['documents'] }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold border border-purple-100">
                <i class="fas fa-newspaper"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500">Total Berita</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $stats['news'] }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold border border-emerald-100">
                <i class="fas fa-images"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500">Banner Utama</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $stats['sliders'] }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold border border-amber-100">
                <i class="fas fa-eye"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500">Total Pembaca</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $stats['total_views'] }}</h3>
            </div>
        </div>

    </div>

    <!-- Quick Access Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Latest Documents Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fas fa-file-alt text-blue-600"></i> Dokumen Kinerja Terkini
                </h3>
                <a href="{{ route('admin.documents') }}" class="text-xs text-blue-600 font-bold hover:underline">Kelola Dokumen &rarr;</a>
            </div>
            <div class="space-y-3">
                @foreach($latestDocs as $doc)
                    <div class="flex items-center justify-between p-3 bg-slate-50/80 rounded-xl border border-slate-100 text-xs">
                        <div>
                            <h4 class="font-bold text-slate-800 line-clamp-1">{{ $doc->title }}</h4>
                            <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">{{ $doc->category }}</span>
                        </div>
                        <a href="{{ $doc->file_url }}" target="_blank" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Latest News Management Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fas fa-newspaper text-purple-600"></i> Berita Terkini
                </h3>
                <a href="{{ route('admin.news') }}" class="text-xs text-purple-600 font-bold hover:underline">Kelola Berita &rarr;</a>
            </div>
            <div class="space-y-3">
                @foreach($latestNews as $news)
                    <div class="flex items-center gap-3 p-3 bg-slate-50/80 rounded-xl border border-slate-100">
                        <img src="{{ $news->image_url ?? 'https://images.unsplash.com/photo-1504711434969-e33886168f5c' }}" alt="News" class="w-12 h-12 rounded-lg object-cover">
                        <div class="flex-grow">
                            <h4 class="text-xs font-bold text-slate-800 line-clamp-1">{{ $news->title }}</h4>
                            <p class="text-[10px] text-slate-400">{{ optional($news->published_at)->format('d M Y') }} • {{ $news->views }} pembaca</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
