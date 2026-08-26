@extends('admin.layout')

@section('page_title', 'Kelola Galeri Media (Foto & Video)')

@section('content')
<div class="space-y-6" x-data="{ showModal: {{ isset($mediaItem) ? 'true' : 'false' }}, editMode: {{ isset($mediaItem) ? 'true' : 'false' }}, currentItem: {{ isset($mediaItem) ? json_encode($mediaItem, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) : '{ type: \'foto\', is_active: true }' }} }">

    <!-- Header Title & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Kelola Galeri Media</h3>
            <p class="text-xs text-slate-500 mt-1">Kelola foto dokumentasi kegiatan kebencanaan dan video publikasi resmi BPBD Kabupaten Probolinggo.</p>
        </div>
        <button @click="showModal = true; editMode = false; currentItem = { type: '{{ $type }}', is_active: true }" 
                class="px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center justify-center gap-2 transition">
            <i class="fas fa-plus"></i> Tambah Media Baru
        </button>
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

    <!-- Statistic & Filter Tabs -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.media', ['type' => 'foto']) }}" 
           class="p-5 rounded-2xl border transition-all flex items-center justify-between {{ $type === 'foto' ? 'bg-amber-500 text-white border-amber-600 shadow-md' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl {{ $type === 'foto' ? 'bg-white/20 text-white' : 'bg-amber-50 text-amber-600' }} flex items-center justify-center text-xl font-bold">
                    <i class="fas fa-image"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-sm">Galeri Foto</h4>
                    <span class="text-xs opacity-90">{{ number_format($stats['total_foto']) }} Gambar Dokumentasi</span>
                </div>
            </div>
            <i class="fas fa-chevron-right text-xs opacity-70"></i>
        </a>

        <a href="{{ route('admin.media', ['type' => 'video']) }}" 
           class="p-5 rounded-2xl border transition-all flex items-center justify-between {{ $type === 'video' ? 'bg-rose-600 text-white border-rose-700 shadow-md' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl {{ $type === 'video' ? 'bg-white/20 text-white' : 'bg-rose-50 text-rose-600' }} flex items-center justify-center text-xl font-bold">
                    <i class="fas fa-video"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-sm">Galeri Video</h4>
                    <span class="text-xs opacity-90">{{ number_format($stats['total_video']) }} Konten Video / YouTube</span>
                </div>
            </div>
            <i class="fas fa-chevron-right text-xs opacity-70"></i>
        </a>

        <div class="p-5 rounded-2xl bg-white border border-slate-200 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-globe"></i>
            </div>
            <div>
                <h4 class="font-extrabold text-sm text-slate-800">Tampilan Portal Publik</h4>
                <a href="{{ route('public.galeri') }}" target="_blank" class="text-xs text-blue-600 font-bold hover:underline flex items-center gap-1 mt-0.5">
                    Lihat Galeri Publik <i class="fas fa-external-link-alt text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Media Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($mediaItems as $item)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden flex flex-col justify-between group hover:shadow-md transition duration-200">
                <div class="space-y-3">
                    <!-- Media Preview Area -->
                    <div class="relative h-48 bg-slate-900 overflow-hidden">
                        @if($item->type === 'video')
                            @if($item->youtube_embed_url)
                                <iframe src="{{ $item->youtube_embed_url }}" class="w-full h-full border-0" allowfullscreen></iframe>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <i class="fas fa-play-circle text-4xl"></i>
                                </div>
                            @endif
                        @else
                            <img src="{{ $item->file_url ?: 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb' }}" 
                                 alt="{{ $item->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @endif

                        <!-- Type & Status Badges -->
                        <div class="absolute top-3 left-3 flex gap-1.5">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold text-white {{ $item->type === 'video' ? 'bg-rose-600' : 'bg-amber-600' }} uppercase">
                                <i class="fas {{ $item->type === 'video' ? 'fa-video' : 'fa-image' }} me-1"></i> {{ strtoupper($item->type) }}
                            </span>
                        </div>

                        <div class="absolute top-3 right-3">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold {{ $item->is_active ? 'bg-emerald-500 text-white' : 'bg-slate-600 text-white' }}">
                                {{ $item->is_active ? 'Tampil' : 'Draft' }}
                            </span>
                        </div>
                    </div>

                    <!-- Content Details -->
                    <div class="px-4 pt-2 space-y-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">{{ $item->category }}</span>
                        <h4 class="font-bold text-slate-800 text-xs line-clamp-2 leading-snug">{{ $item->title }}</h4>
                        @if($item->caption)
                            <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed font-normal">{{ $item->caption }}</p>
                        @endif
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="p-4 border-t border-slate-100 mt-4 flex items-center justify-between bg-slate-50/50">
                    <span class="text-[10px] text-slate-400 font-mono">Urutan: {{ $item->order }}</span>

                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.media.edit', preg_replace('/[\"\']/', '', $item->id)) }}" 
                           @click.prevent="showModal = true; editMode = true; currentItem = {{ json_encode($item, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }}" 
                           class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block" title="Edit Media">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('admin.media.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus media ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Media">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400">
                <i class="fas fa-photo-video text-5xl mb-3 text-slate-300"></i>
                <p class="text-xs font-semibold">Belum ada data media ({{ $type }}) yang tersimpan.</p>
            </div>
        @endforelse
    </div>

    @if($mediaItems->hasPages())
        <div class="mt-6">
            {{ $mediaItems->links() }}
        </div>
    @endif

    <!-- Add / Edit Modal Form -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden my-auto">
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-white">
                <h3 class="font-bold text-slate-800 text-sm" x-text="editMode ? 'Edit Item Media' : 'Tambah Item Media Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>

            <form :action="editMode ? '{{ url('admin/media') }}/' + String(currentItem.id || '').replace(/['&quot;\\]/g, '') : '{{ route('admin.media.store') }}'" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  class="p-6 space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Media</label>
                    <input type="text" name="title" required x-model="currentItem.title" placeholder="Contoh: Dokumentasi Simulasi Bencana Sukapura" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tipe Media</label>
                        <select name="type" x-model="currentItem.type" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                            <option value="foto">Foto / Gambar</option>
                            <option value="video">Video / YouTube</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                        <input type="text" name="category" x-model="currentItem.category" placeholder="Kegiatan BPBD / Evakuasi / Edukasi" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>
                </div>

                <!-- Input Khusus Foto -->
                <div x-show="currentItem.type === 'foto'" class="space-y-3 p-3 bg-amber-50/50 rounded-xl border border-amber-200">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Upload File Gambar (Komputer)</label>
                        <input type="file" name="media_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-600 file:text-white hover:file:bg-amber-700">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Atau Gunakan Link URL Gambar External</label>
                        <input type="url" name="file_url" x-model="currentItem.file_url" placeholder="https://..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>
                </div>

                <!-- Input Khusus Video -->
                <div x-show="currentItem.type === 'video'" class="space-y-3 p-3 bg-rose-50/50 rounded-xl border border-rose-200">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">URL Video YouTube / Embed Link</label>
                        <input type="url" name="video_url" x-model="currentItem.video_url" placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        <span class="text-[10px] text-slate-500 mt-1 block">Sistem otomatis mengkonversi link YouTube standar menjadi embed player.</span>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Keterangan / Deskripsi Singkat</label>
                    <textarea name="caption" rows="2" x-model="currentItem.caption" placeholder="Keterangan singkat kegiatan..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Urutan Tampil</label>
                        <input type="number" name="order" x-model="currentItem.order" class="w-24 px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>

                    <div class="flex items-center gap-2 pt-4">
                        <input type="checkbox" id="is_active" name="is_active" value="1" :checked="currentItem.is_active" class="w-4 h-4 text-orange-600 rounded">
                        <label for="is_active" class="font-bold text-slate-700">Tampilkan di Publik</label>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-md transition">Simpan Media</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
