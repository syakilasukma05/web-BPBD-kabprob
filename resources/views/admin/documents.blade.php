@extends('admin.layout')

@section('page_title', 'Kelola Dokumen Publik')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, currentDoc: {} }">
    
    <!-- Page Header & Action Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <h3 class="text-xl font-extrabold text-slate-800">Daftar Dokumen Publik</h3>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Kelola file PDF/Word untuk diunduh masyarakat</p>
        </div>
        <button @click="showModal = true; editMode = false; currentDoc = { category: 'Perencanaan Kinerja', year: '2025', file_size: '1.5 MB' }" 
                class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2">
            <i class="fas fa-upload text-sm"></i> Upload Dokumen
        </button>
    </div>

    <!-- Documents Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-700 uppercase font-extrabold text-[10px] tracking-wider">
                    <tr>
                        <th class="px-5 py-4 text-center w-12">No</th>
                        <th class="px-6 py-4">Nama Dokumen</th>
                        <th class="px-5 py-4">Kategori</th>
                        <th class="px-4 py-4 text-center">Tahun</th>
                        <th class="px-4 py-4 text-center">Diunduh</th>
                        <th class="px-6 py-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($documents as $index => $doc)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- No -->
                            <td class="px-5 py-4 text-center font-bold text-slate-600">
                                {{ $documents->firstItem() + $index }}
                            </td>
                            
                            <!-- Nama Dokumen (PDF Icon + Title) -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center shrink-0">
                                        <i class="fas fa-file-pdf text-red-500 text-base"></i>
                                    </div>
                                    <div>
                                        <a href="{{ $doc->file_url }}" target="_blank" rel="noopener noreferrer" 
                                           class="font-bold text-slate-800 hover:text-orange-600 transition-colors text-xs leading-snug line-clamp-2 block">
                                            {{ $doc->title }}
                                        </a>
                                        @if($doc->description)
                                            <p class="text-[11px] text-slate-400 font-normal line-clamp-1 mt-0.5">{{ $doc->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Kategori -->
                            <td class="px-5 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200/80 inline-block">
                                    {{ $doc->category }}
                                </span>
                            </td>

                            <!-- Tahun -->
                            <td class="px-4 py-4 text-center font-bold text-slate-700">
                                {{ $doc->year ?? '2025' }}
                            </td>

                            <!-- Diunduh -->
                            <td class="px-4 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-blue-600 text-white shadow-xs">
                                    {{ $doc->download_count ?? 0 }}x
                                </span>
                            </td>

                            <!-- Aksi (Download, Edit, Delete) -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Download Button (Teal / Emerald) -->
                                    <a href="{{ $doc->file_url }}" target="_blank" title="Unduh / Lihat File" 
                                       class="w-8 h-8 rounded-lg bg-teal-50 border border-teal-200/80 text-teal-600 hover:bg-teal-600 hover:text-white flex items-center justify-center transition-all shadow-2xs">
                                        <i class="fas fa-download text-xs"></i>
                                    </a>

                                    <!-- Edit Button (Blue) -->
                                    <button @click="showModal = true; editMode = true; currentDoc = {{ json_encode($doc) }}" title="Edit Dokumen"
                                            class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-200/80 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all shadow-2xs">
                                        <i class="fas fa-pen-to-square text-xs"></i>
                                    </button>

                                    <!-- Delete Button (Rose) -->
                                    <form action="{{ route('admin.documents.destroy', $doc->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus dokumen ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Dokumen" 
                                                class="w-8 h-8 rounded-lg bg-rose-50 border border-rose-200/80 text-rose-500 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-all shadow-2xs">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                <i class="fas fa-folder-open text-3xl mb-2 text-slate-300 block"></i>
                                Belum ada dokumen publik yang diunggah.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $documents->links() }}
        </div>
    </div>

    <!-- Modal Form (Add / Edit Document) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs overflow-y-auto">
        <div class="bg-white rounded-3xl max-w-xl w-full shadow-2xl overflow-hidden flex flex-col max-h-[90vh] my-auto border border-slate-100">
            
            <!-- Modal Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-white shrink-0">
                <div>
                    <h3 class="font-extrabold text-slate-800 text-base" x-text="editMode ? 'Edit Dokumen Kinerja' : 'Tambah Dokumen Kinerja Baru'"></h3>
                    <p class="text-[11px] text-slate-400">Perbarui informasi dan file dokumen publik</p>
                </div>
                <button @click="showModal = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition"><i class="fas fa-times text-xs"></i></button>
            </div>

            <!-- Form Body -->
            <form :action="editMode ? '{{ url('admin/documents') }}/' + currentDoc.id : '{{ route('admin.documents.store') }}'" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div class="p-6 space-y-5 text-xs overflow-y-auto flex-1">
                    
                    <!-- Judul / Nama Dokumen -->
                    <div>
                        <label class="block font-extrabold text-slate-700 uppercase tracking-wider text-[11px] mb-1.5">JUDUL / NAMA DOKUMEN *</label>
                        <input type="text" name="title" required x-model="currentDoc.title" placeholder="Contoh: Rencana Kerja (Renja) Tahun Anggaran 2025/2026" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white font-medium text-slate-800">
                    </div>

                    <!-- Row: Kategori & Tahun Dokumen -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block font-extrabold text-slate-700 uppercase tracking-wider text-[11px] mb-1.5">KATEGORI *</label>
                            <input type="text" name="category" x-model="currentDoc.category" required placeholder="Pilih atau ketik kategori..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white font-medium text-slate-800">
                            
                            <!-- Category Preset Badges -->
                            <div class="flex flex-wrap gap-1.5 mt-2">
                                <button type="button" @click="currentDoc.category = 'Perencanaan Kinerja'" 
                                        :class="currentDoc.category === 'Perencanaan Kinerja' ? 'bg-orange-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" 
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors">Perencanaan Kinerja</button>
                                <button type="button" @click="currentDoc.category = 'Pengukuran Kinerja'" 
                                        :class="currentDoc.category === 'Pengukuran Kinerja' ? 'bg-orange-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" 
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors">Pengukuran Kinerja</button>
                                <button type="button" @click="currentDoc.category = 'Pelaporan Kinerja'" 
                                        :class="currentDoc.category === 'Pelaporan Kinerja' ? 'bg-orange-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" 
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors">Pelaporan Kinerja</button>
                                <button type="button" @click="currentDoc.category = 'Evaluasi Kinerja'" 
                                        :class="currentDoc.category === 'Evaluasi Kinerja' ? 'bg-orange-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" 
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors">Evaluasi Kinerja</button>
                            </div>
                        </div>

                        <div>
                            <label class="block font-extrabold text-slate-700 uppercase tracking-wider text-[11px] mb-1.5">TAHUN DOKUMEN</label>
                            <input type="text" name="year" x-model="currentDoc.year" placeholder="2025" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white font-medium text-slate-800 text-center">
                        </div>
                    </div>

                    <!-- File Dokumen Card Section -->
                    <div>
                        <label class="block font-extrabold text-slate-700 uppercase tracking-wider text-[11px] mb-1.5">FILE DOKUMEN</label>
                        
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 space-y-3">
                            <!-- Active File Info -->
                            <template x-if="currentDoc.file_url">
                                <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200">
                                    <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center shrink-0">
                                        <i class="fas fa-file-pdf text-red-500 text-lg"></i>
                                    </div>
                                    <div class="overflow-hidden flex-1">
                                        <p class="font-bold text-slate-800 text-xs truncate" x-text="currentDoc.title"></p>
                                        <p class="text-[10px] text-slate-400">
                                            Ukuran: <span x-text="currentDoc.file_size || '1.5 MB'"></span> • Diunduh <span x-text="(currentDoc.download_count || 0) + 'x'"></span>
                                        </p>
                                    </div>
                                    <a :href="currentDoc.file_url" target="_blank" class="text-xs font-bold text-orange-600 hover:underline shrink-0">Buka File</a>
                                </div>
                            </template>

                            <!-- Ganti File Input -->
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block mb-1">GANTI FILE (OPSIONAL):</span>
                                <input type="file" name="document_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 cursor-pointer">
                                <p class="text-[10px] text-slate-400 mt-1">Biarkan kosong jika tidak ingin mengganti file. Format: PDF, DOC, XLS, ZIP (Maks 10MB)</p>
                            </div>

                            <div class="pt-2 border-t border-slate-200">
                                <span class="text-[10px] text-slate-500 font-bold block mb-1">ATAU INPUT LINK / URL FILE:</span>
                                <input type="text" name="file_url" x-model="currentDoc.file_url" placeholder="https://.../file.pdf" class="w-full px-3 py-1.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white font-mono text-[11px]">
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi (Opsional) -->
                    <div>
                        <label class="block font-extrabold text-slate-700 uppercase tracking-wider text-[11px] mb-1.5">DESKRIPSI (OPSIONAL)</label>
                        <textarea name="description" x-model="currentDoc.description" rows="3" placeholder="Keterangan singkat mengenai dokumen ini..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none bg-white font-medium text-slate-800"></textarea>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between shrink-0">
                    <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
