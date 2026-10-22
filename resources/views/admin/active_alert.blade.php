@extends('admin.layout')

@section('page_title', 'Edit Peringatan Aktif')

@section('content')
<div x-data="activeAlertManager()" class="space-y-6">

    <!-- Top Toolbar Header Card (Pola Identik Edit Isi Halaman) -->
    <div class="flex items-center justify-between bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali Ke Dashboard
            </a>
            <div>
                <h3 class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight">Edit Peringatan Aktif</h3>
                <p class="text-xs text-slate-400 font-mono">Pengelolaan Konten Badge Peringatan Dini Kebencanaan Halaman Publik</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" @click="submitAlert()" 
                    class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center gap-2 cursor-pointer">
                <i class="fas fa-save"></i> Simpan Peringatan Aktif
            </button>
        </div>
    </div>

    <!-- Alert / Notifikasi -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-2xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2.5">
                <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold rounded-2xl space-y-1 shadow-xs">
            <div class="flex items-center gap-2 text-rose-700 font-extrabold">
                <i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan validasi:
            </div>
            <ul class="list-disc list-inside pl-2 space-y-0.5 text-rose-600 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Form -->
    <form id="activeAlertForm" action="{{ route('admin.active_alert.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Card 1: Pengaturan Status, Judul, & Link -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
            
            <div class="flex items-center justify-between bg-emerald-50/80 p-4 rounded-2xl border border-emerald-200">
                <div>
                    <h4 class="font-extrabold text-emerald-950 text-sm flex items-center gap-2">
                        <i class="fas fa-triangle-exclamation text-emerald-600"></i> Pengaturan Elemen Peringatan Aktif
                    </h4>
                    <p class="text-xs text-emerald-800 mt-0.5">Kelola visibilitas badge peringatan dini di beranda publik serta detail pesan kedaruratan bagi masyarakat.</p>
                </div>
                <span class="text-[10px] bg-emerald-100 text-emerald-800 font-extrabold px-3 py-1 rounded-full border border-emerald-200 uppercase tracking-wider">
                    Emergency Alert
                </span>
            </div>

            <div class="space-y-5 text-xs">
                
                <!-- Status Peringatan Aktif -->
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <label class="block font-bold text-slate-800 text-xs mb-2">
                        Status Peringatan Aktif <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex flex-wrap items-center gap-6 pt-1">
                        <label class="flex items-center gap-2.5 cursor-pointer font-bold text-slate-700">
                            <input type="radio" name="status" value="aktif" x-model="status" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Aktif (Badge Tampil di Homepage)
                            </span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer font-bold text-slate-700">
                            <input type="radio" name="status" value="nonaktif" x-model="status" class="w-4 h-4 text-slate-600 focus:ring-slate-400">
                            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-300">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span> Nonaktif (Sembunyikan Badge)
                            </span>
                        </label>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-2">
                        <i class="fas fa-info-circle text-slate-400"></i> Jika status <strong>Aktif</strong>, badge peringatan akan tampil di pojok kanan atas banner utama. Jika <strong>Nonaktif</strong>, badge tidak ditampilkan.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Judul Peringatan -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5 text-xs">
                            Judul Peringatan (Teks Badge) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $title ?? 'PERINGATAN AKTIF') }}" required 
                               placeholder="Contoh: PERINGATAN AKTIF" 
                               class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white font-bold text-slate-800 text-sm">
                        <p class="text-[11px] text-slate-400 mt-1">Teks judul yang akan tampil pada badge peringatan di homepage publik.</p>
                    </div>

                    <!-- Link Tujuan (Opsional) -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5 text-xs">
                            Link Tujuan (Opsional)
                        </label>
                        <input type="text" name="link" value="{{ old('link', $link ?? '') }}" 
                               placeholder="Contoh: https://probolinggokab.go.id/... atau /informasi/peringatan-dini" 
                               class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white font-mono text-xs">
                        <p class="text-[11px] text-slate-400 mt-1">Tautan eksternal/internal jika badge warning diklik pengunjung (kosongkan jika tanpa tautan).</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Card 2: Visual Editor CKEditor 5 (Mengikuti Pola menus.blade.php) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                <div>
                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-file-alt text-emerald-600"></i> ISI / PESAN PERINGATAN (CKEDITOR 5)
                    </span>
                    <p class="text-[11px] text-slate-500 mt-0.5">Mendukung format visual teks tebal, daftar poin evakuasi, tabel posko, dan kutipan himbauan resmi.</p>
                </div>
                <span class="text-[10px] bg-emerald-100 text-emerald-800 font-extrabold px-3 py-1 rounded-full border border-emerald-200 uppercase">
                    CKEditor 5
                </span>
            </div>

            <div>
                <textarea id="ckeditor_alert_message" name="message" class="w-full min-h-[350px]">{!! old('message', $message ?? '') !!}</textarea>
            </div>
            <p class="text-[11px] text-slate-400">Pesan ini akan tampil dalam dialog pop-up ketika masyarakat mengklik badge peringatan aktif pada beranda jika link eksternal tidak diisi.</p>
        </div>

        <!-- Bottom Action Bar -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                Batal
            </a>
            <button type="button" @click="submitAlert()" 
                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center gap-2 cursor-pointer">
                <i class="fas fa-save"></i> Simpan Peringatan Aktif
            </button>
        </div>

    </form>

</div>

<script>
function activeAlertManager() {
    return {
        ckEditor: null,
        status: '{{ old('status', $status ?? 'aktif') }}',
        init() {
            this.$nextTick(() => {
                const textarea = document.getElementById('ckeditor_alert_message');
                const initialContent = textarea ? textarea.value : '';
                window.initStandardCKEditor('ckeditor_alert_message', initialContent, (data) => {
                    if (textarea) textarea.value = data;
                }).then(editor => {
                    this.ckEditor = editor;
                }).catch(err => {
                    console.warn('CKEditor init warning:', err);
                });
            });
        },
        submitAlert() {
            const textarea = document.getElementById('ckeditor_alert_message');
            const editor = this.ckEditor || (textarea ? textarea.ckeditorInstance : null);
            if (editor && typeof editor.getData === 'function') {
                const data = editor.getData();
                if (textarea) textarea.value = data;
            }
            const form = document.getElementById('activeAlertForm');
            if (form) form.submit();
        }
    };
}
</script>
@endsection
