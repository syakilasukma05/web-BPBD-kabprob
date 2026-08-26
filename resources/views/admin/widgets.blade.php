@extends('admin.layout')

@section('page_title', 'Kelola Widget Sidebar Homepage')

@section('content')
<div class="space-y-6" x-data="{ showModal: {{ isset($widgetItem) ? 'true' : 'false' }}, editMode: {{ isset($widgetItem) ? 'true' : 'false' }}, currentWidget: {{ isset($widgetItem) ? json_encode($widgetItem, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) : '{}' }} }">
    
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Daftar Widget Sidebar (Kanan)</h3>
            <p class="text-xs text-slate-500">Atur spanduk, banner call center, dan gambar informasi publik pada kolom sidebar kanan homepage</p>
        </div>
        <button @click="showModal = true; editMode = false; currentWidget = { is_active: true, order: 0 }" 
                class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Widget Baru
        </button>
    </div>

    <!-- Widgets Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-4">Urutan</th>
                    <th class="px-6 py-4">Preview Banner</th>
                    <th class="px-6 py-4">Judul Widget</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($widgets as $widget)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800">#{{ $widget->order }}</td>
                        <td class="px-6 py-4">
                            <img src="{{ $widget->image_url }}" alt="Preview" class="h-14 w-28 object-cover rounded-lg border border-slate-200">
                        </td>
                        <td class="px-6 py-4">
                            <h4 class="font-bold text-slate-800 text-xs">{{ $widget->title }}</h4>
                            <p class="text-slate-400 font-mono text-[10px] mt-0.5">{{ $widget->target_url ?? 'Tidak ada link' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ $widget->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                {{ $widget->is_active ? 'AKTIF' : 'NONAKTIF' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <a href="{{ route('admin.widgets.edit', preg_replace('/[\"\']/', '', $widget->id)) }}" 
                               @click.prevent="showModal = true; editMode = true; currentWidget = {{ json_encode($widget, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }}" 
                               class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block" title="Edit Widget"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.widgets.destroy', $widget->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus widget ini?')">
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

    <!-- Modal Form (Add / Edit Widget) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden flex flex-col max-h-[85vh] my-auto">
            
            <!-- Fixed Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-white shrink-0">
                <h3 class="font-bold text-slate-800 text-sm" x-text="editMode ? 'Edit Widget Sidebar' : 'Tambah Widget Sidebar Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition"><i class="fas fa-times"></i></button>
            </div>

            <!-- Scrollable Form Body -->
            <form :action="editMode ? '{{ url('admin/widgets') }}/' + String(currentWidget.id || '').replace(/['&quot;\\]/g, '') : '{{ route('admin.widgets.store') }}'" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Judul Widget</label>
                        <input type="text" name="title" required x-model="currentWidget.title" placeholder="Contoh: Call Center Emergency 24 Jam" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>

                    <!-- Gambar Banner Widget (Upload & URL) -->
                    <div class="space-y-2 bg-slate-50 p-3 rounded-xl border border-slate-200">
                        <label class="block font-bold text-slate-700">Gambar Banner Widget</label>
                        <div>
                            <span class="text-[10px] text-slate-500 font-semibold block mb-1">Opsi 1: Upload File Gambar Dari Perangkat</span>
                            <input type="file" name="image_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-100 file:text-orange-700 hover:file:bg-orange-200 cursor-pointer">
                        </div>
                        <div class="pt-1 border-t border-slate-200">
                            <span class="text-[10px] text-slate-500 font-semibold block mb-1">Opsi 2: Atau Pakai Link / URL Gambar (Web)</span>
                            <input type="text" name="image_url" x-model="currentWidget.image_url" placeholder="https://..." class="w-full px-3 py-1.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Target URL Link Saat Diklik (Opsional)</label>
                        <input type="text" name="target_url" x-model="currentWidget.target_url" placeholder="/kontak atau https://..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Urutan (Order)</label>
                            <input type="number" name="order" x-model="currentWidget.order" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        </div>
                        <div class="flex items-center pt-5">
                            <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                                <input type="checkbox" name="is_active" value="1" :checked="currentWidget.is_active" class="w-4 h-4 text-orange-600 rounded">
                                Aktifkan Widget
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Fixed Footer with Batal and Simpan Buttons -->
                <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex justify-end gap-2 shrink-0">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-md transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Widget
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
