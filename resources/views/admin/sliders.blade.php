@extends('admin.layout')

@section('page_title', 'Kelola Banner Utama')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, currentSlider: {} }">
    
    <!-- Header Action -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Daftar Banner Hero Slider</h3>
            <p class="text-xs text-slate-500">Ubah judul, subtitle, gambar background, dan urutan banner homepage BPBD</p>
        </div>
        <button @click="showModal = true; editMode = false; currentSlider = { is_active: true, order: 0 }" 
                class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Banner Baru
        </button>
    </div>

    <!-- Sliders List Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-4">Urutan</th>
                    <th class="px-6 py-4">Preview Background</th>
                    <th class="px-6 py-4">Judul Banner</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($sliders as $slider)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800">#{{ $slider->order }}</td>
                        <td class="px-6 py-4">
                            <img src="{{ $slider->image_url }}" alt="Preview" class="h-14 w-28 object-cover rounded-lg border border-slate-200">
                        </td>
                        <td class="px-6 py-4">
                            <h4 class="font-bold text-slate-800 text-xs">{{ $slider->title }}</h4>
                            <p class="text-slate-500 text-[11px] mt-0.5">{{ $slider->subtitle }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ $slider->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                {{ $slider->is_active ? 'AKTIF' : 'NONAKTIF' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <button @click="showModal = true; editMode = true; currentSlider = {{ json_encode($slider) }}" 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus banner ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Form (Add / Edit Slider) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden flex flex-col max-h-[85vh] my-auto">
            
            <!-- Fixed Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-white shrink-0">
                <h3 class="font-bold text-slate-800 text-sm" x-text="editMode ? 'Edit Banner Hero Slider' : 'Tambah Banner Slider Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition"><i class="fas fa-times"></i></button>
            </div>

            <!-- Scrollable Form Body -->
            <form :action="editMode ? '{{ url('admin/sliders') }}/' + currentSlider.id : '{{ route('admin.sliders.store') }}'" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Judul Utama Banner</label>
                        <input type="text" name="title" required x-model="currentSlider.title" placeholder="Contoh: Selamat Datang di Portal Resmi" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Subtitle / Keterangan</label>
                        <input type="text" name="subtitle" x-model="currentSlider.subtitle" placeholder="Contoh: Badan Penanggulangan Bencana Daerah..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>

                    <!-- Gambar Background Banner (Upload & URL) -->
                    <div class="space-y-2 bg-slate-50 p-3 rounded-xl border border-slate-200">
                        <label class="block font-bold text-slate-700">Gambar Background Banner</label>
                        <div>
                            <span class="text-[10px] text-slate-500 font-semibold block mb-1">Opsi 1: Upload File Gambar Dari Perangkat</span>
                            <input type="file" name="image_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-100 file:text-orange-700 hover:file:bg-orange-200 cursor-pointer">
                        </div>
                        <div class="pt-1 border-t border-slate-200">
                            <span class="text-[10px] text-slate-500 font-semibold block mb-1">Opsi 2: Atau Pakai Link / URL Gambar (Web)</span>
                            <input type="text" name="image_url" x-model="currentSlider.image_url" placeholder="https://..." class="w-full px-3 py-1.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Teks Tombol CTA</label>
                            <input type="text" name="button_text" x-model="currentSlider.button_text" placeholder="Lapor Bencana" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">URL Link Tombol</label>
                            <input type="text" name="button_url" x-model="currentSlider.button_url" placeholder="/kontak" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Urutan Tampil (Order)</label>
                            <input type="number" name="order" x-model="currentSlider.order" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        </div>
                        <div class="flex items-center pt-5">
                            <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                                <input type="checkbox" name="is_active" value="1" :checked="currentSlider.is_active" class="w-4 h-4 text-orange-600 rounded">
                                Aktifkan Slide
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Fixed Footer with Batal and Simpan Buttons -->
                <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex justify-end gap-2 shrink-0">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-md transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Banner
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
