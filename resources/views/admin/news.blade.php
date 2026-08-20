@extends('admin.layout')

@section('page_title', 'Kelola Berita & Informasi Kebencanaan')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, currentNews: {} }">
    
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Daftar Berita & Pengumuman</h3>
            <p class="text-xs text-slate-500">Kelola artikel berita publik yang tampil di homepage dan portal berita BPBD</p>
        </div>
        <button @click="showModal = true; editMode = false; currentNews = { category: 'Peringatan Dini', published_at: '{{ date('Y-m-d') }}' }" 
                class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i> Publish Berita Baru
        </button>
    </div>

    <!-- News Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-4">Thumbnail</th>
                    <th class="px-6 py-4">Judul Berita</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Tanggal Publish</th>
                    <th class="px-6 py-4">Views</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($newsList as $news)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <img src="{{ $news->image_url ?? 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb' }}" alt="News" class="h-12 w-16 object-cover rounded-lg border border-slate-200">
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <h4 class="font-bold text-slate-800 text-xs line-clamp-2">{{ $news->title }}</h4>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-orange-100 text-orange-800">
                                {{ $news->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-medium">
                            {{ optional($news->published_at)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-bold">
                            {{ $news->views }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <button @click="showModal = true; editMode = true; currentNews = {{ json_encode($news) }}" 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.news.destroy', $news->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus berita ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">
            {{ $newsList->links() }}
        </div>
    </div>

    <!-- Modal Form (Add / Edit News) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl overflow-hidden flex flex-col max-h-[85vh] my-auto">
            
            <!-- Fixed Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-white shrink-0">
                <h3 class="font-bold text-slate-800 text-sm" x-text="editMode ? 'Edit Berita & Artikel' : 'Publish Berita Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition"><i class="fas fa-times"></i></button>
            </div>

            <!-- Scrollable Form Body -->
            <form :action="editMode ? '{{ url('admin/news') }}/' + currentNews.id : '{{ route('admin.news.store') }}'" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Judul Berita</label>
                        <input type="text" name="title" required x-model="currentNews.title" placeholder="Judul berita terbaru..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                            <select name="category" x-model="currentNews.category" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                                <option value="Peringatan Dini">Peringatan Dini</option>
                                <option value="Tanggap Darurat">Tanggap Darurat</option>
                                <option value="Mitigasi Bencana">Mitigasi Bencana</option>
                                <option value="Kegiatan Posko">Kegiatan Posko</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Tanggal Publish</label>
                            <input type="date" name="published_at" x-model="currentNews.published_at" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        </div>
                    </div>

                    <!-- Thumbnail Gambar Berita (Upload & URL) -->
                    <div class="space-y-2 bg-slate-50 p-3 rounded-xl border border-slate-200">
                        <label class="block font-bold text-slate-700">Gambar Thumbnail Berita</label>
                        <div>
                            <span class="text-[10px] text-slate-500 font-semibold block mb-1">Opsi 1: Upload File Gambar Dari Perangkat</span>
                            <input type="file" name="image_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-100 file:text-orange-700 hover:file:bg-orange-200 cursor-pointer">
                        </div>
                        <div class="pt-1 border-t border-slate-200">
                            <span class="text-[10px] text-slate-500 font-semibold block mb-1">Opsi 2: Atau Pakai Link / URL Gambar (Web)</span>
                            <input type="text" name="image_url" x-model="currentNews.image_url" placeholder="https://..." class="w-full px-3 py-1.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Link Target / Sumber Berita (Opsional)</label>
                        <input type="text" name="link_url" x-model="currentNews.link_url" placeholder="Contoh: https://detik.com/... atau https://instagram.com/..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none font-mono text-[11px]">
                        <p class="text-[10px] text-slate-400 mt-1">*Jika diisi, pengunjung yang mengklik berita akan diarahkan langsung ke link ini.</p>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Isi Berita (Content)</label>
                        <textarea name="content" rows="4" x-model="currentNews.content" placeholder="Tuliskan berita lengkap..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none"></textarea>
                    </div>
                </div>

                <!-- Fixed Footer with Batal and Simpan Buttons -->
                <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex justify-end gap-2 shrink-0">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-md transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Artikel
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
