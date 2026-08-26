@extends('admin.layout')

@section('page_title', 'Pengaturan Website & Identitas Instansi')

@section('content')
<div class="max-w-4xl space-y-6">
    
    <div>
        <h3 class="text-lg font-bold text-slate-800">Identitas & Kontak Instansi</h3>
        <p class="text-xs text-slate-500">Semua perubahan di sini akan secara otomatis memperbarui logo, nama dinas, email, telepon, dan footer publik</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-6 text-xs">
        @csrf

        <!-- Site Title & Description -->
        <div class="space-y-4">
            <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase pb-2 border-b border-slate-100 text-emerald-700">
                1. Judul & Meta SEO
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Website (SEO Title)</label>
                    <input type="text" name="site_title" value="{{ $settings['site_title'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Kabupaten / Daerah</label>
                    <input type="text" name="regency_name" value="{{ $settings['regency_name'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Resmi Instansi Dinas</label>
                <input type="text" name="agency_name" value="{{ $settings['agency_name'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Deskripsi Singkat Footer</label>
                <textarea name="site_description" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ $settings['site_description'] ?? '' }}</textarea>
            </div>
        </div>

        <!-- Logos & Branding -->
        <div class="space-y-4">
            <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase pb-2 border-b border-slate-100 text-emerald-700">
                2. Gambar Logo & Branding
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <!-- Logo Topbar Frontend (Kiri Header) -->
                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200 space-y-3 flex flex-col justify-between">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Logo Instansi (Kiri Header)</label>
                        <p class="text-[10px] text-slate-500 mb-2">Tampil di navigasi paling atas sebelah kiri</p>
                        
                        <div class="h-16 w-full bg-white rounded-xl border border-slate-200 p-2 flex items-center justify-center mb-3">
                            <img src="{{ !empty($settings['logo_frontend']) ? $settings['logo_frontend'] : asset('images/logo-bpbd.svg') }}" 
                                 alt="Logo Frontend Preview" class="max-h-full max-w-full object-contain">
                        </div>
                        
                        <div>
                            <span class="text-[10px] text-slate-500 font-semibold block mb-1">Upload Logo Baru (PNG/SVG):</span>
                            <input type="file" name="logo_frontend_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-700 cursor-pointer">
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-200">
                        <span class="text-[10px] text-slate-500 font-semibold block mb-1">Atau Gunakan Link / URL Gambar:</span>
                        <input type="text" name="logo_frontend" value="{{ $settings['logo_frontend'] ?? '' }}" placeholder="https://.../logo.png" class="w-full px-2.5 py-1.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white font-mono text-[10px]">
                    </div>
                </div>

                <!-- Logo BerAKHLAK Header (Kanan Header) -->
                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200 space-y-3 flex flex-col justify-between">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Logo BerAKHLAK (Kanan Header)</label>
                        <p class="text-[10px] text-slate-500 mb-2">Tampil di navigasi paling atas sebelah kanan</p>
                        
                        <div class="h-16 w-full bg-white rounded-xl border border-slate-200 p-2 flex items-center justify-center mb-3">
                            <img src="{{ $settings['logo_berakhlak'] ?? 'https://diskominfo.probolinggokab.go.id/frontend/images/img-berakhlak.png' }}" 
                                 alt="Logo BerAKHLAK Preview" class="max-h-full max-w-full object-contain">
                        </div>
                        
                        <div>
                            <span class="text-[10px] text-slate-500 font-semibold block mb-1">Upload Logo Baru (PNG/SVG):</span>
                            <input type="file" name="logo_berakhlak_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-700 cursor-pointer">
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-200">
                        <span class="text-[10px] text-slate-500 font-semibold block mb-1">Atau Gunakan Link / URL Gambar:</span>
                        <input type="text" name="logo_berakhlak" value="{{ $settings['logo_berakhlak'] ?? '' }}" placeholder="https://.../berakhlak.png" class="w-full px-2.5 py-1.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white font-mono text-[10px]">
                    </div>
                </div>

                <!-- Logo Backend / Footer -->
                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200 space-y-3 flex flex-col justify-between">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Logo Footer / Admin</label>
                        <p class="text-[10px] text-slate-500 mb-2">Tampil di bagian footer bawah dan admin panel</p>
                        
                        <div class="h-16 w-full bg-white rounded-xl border border-slate-200 p-2 flex items-center justify-center mb-3">
                            <img src="{{ $settings['logo_backend'] ?? 'https://bpbd.probolinggokab.go.id/wp-content/uploads/2021/04/logo-bpbd.png' }}" 
                                 alt="Logo Backend Preview" class="max-h-full max-w-full object-contain">
                        </div>
                        
                        <div>
                            <span class="text-[10px] text-slate-500 font-semibold block mb-1">Upload Logo Baru (PNG/SVG):</span>
                            <input type="file" name="logo_backend_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-700 cursor-pointer">
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-200">
                        <span class="text-[10px] text-slate-500 font-semibold block mb-1">Atau Gunakan Link / URL Gambar:</span>
                        <input type="text" name="logo_backend" value="{{ $settings['logo_backend'] ?? '' }}" placeholder="https://.../logo-footer.png" class="w-full px-2.5 py-1.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white font-mono text-[10px]">
                    </div>
                </div>

            </div>

            <!-- Link Survey Settings -->
            <div class="space-y-4 pt-4 border-t border-slate-100" x-data="{
                qrPreview: '{{ $settings['qr_code_survey'] ?? '' }}',
                qrName: '{{ $settings['qr_image_name'] ?? '' }}',
                qrSize: '{{ $settings['qr_image_size'] ?? '' }}',
                qrError: '',
                validateQrCode(event) {
                    const file = event.target.files[0];
                    this.qrError = '';
                    if (!file) return;

                    const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/x-png'];
                    const allowedExts = ['png', 'jpg', 'jpeg'];
                    const ext = file.name.split('.').pop().toLowerCase();

                    if (!allowedTypes.includes(file.type.toLowerCase()) && !allowedExts.includes(ext)) {
                        this.qrError = 'Format gambar QR Code tidak valid. Upload hanya PNG, JPG, atau JPEG.';
                        event.target.value = '';
                        return;
                    }

                    if (file.size > 5 * 1024 * 1024) {
                        this.qrError = 'Ukuran gambar QR Code tidak boleh melebihi 5 MB.';
                        event.target.value = '';
                        return;
                    }

                    this.qrName = file.name;
                    this.qrSize = file.size;

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.qrPreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                },
                removeQrCode() {
                    this.qrPreview = '';
                    this.qrName = '';
                    this.qrSize = '';
                    if (this.$refs.qrFileInput) {
                        this.$refs.qrFileInput.value = '';
                    }
                }
            }">
                <h5 class="font-extrabold text-slate-800 text-xs flex items-center gap-2 uppercase tracking-wider text-emerald-700">
                    <i class="fas fa-qrcode text-emerald-600"></i> Pengaturan Link Survei
                </h5>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Link Survei <span class="text-xs font-normal text-slate-500">(Ketika QR Code / Banner Diklik)</span></label>
                    <input type="text" name="survey_url" value="{{ $settings['survey_url'] ?? 'https://sukma.jatimprov.go.id/' }}" placeholder="https://sukma.jatimprov.go.id/" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono text-xs">
                    <p class="text-[11px] text-slate-400 mt-1">URL ini menjadi tujuan ketika QR Code dipindai atau Banner survei diklik.</p>
                </div>

                <!-- Error Notification Banner -->
                <template x-if="qrError">
                    <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold rounded-xl flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-exclamation-circle text-rose-600"></i>
                            <span x-text="qrError"></span>
                        </div>
                        <button type="button" @click="qrError = ''" class="text-rose-400 hover:text-rose-600"><i class="fas fa-times"></i></button>
                    </div>
                </template>

                <!-- Opsi Upload QR Code dari Perangkat -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                    <label class="block font-bold text-slate-700 text-xs flex items-center justify-between">
                        <span>Upload Gambar QR Code Dari Perangkat</span>
                        <span class="text-[10px] text-emerald-600 font-semibold">.png, .jpg, .jpeg (Max 5MB)</span>
                    </label>
                    <input type="file" name="qr_code_file" accept=".png,.jpg,.jpeg" @change="validateQrCode($event)" ref="qrFileInput"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200 cursor-pointer bg-white border border-slate-300 rounded-xl p-1">
                    <p class="text-[11px] text-slate-400">Pilih file gambar QR Code berformat PNG, JPG, atau JPEG dengan ukuran maksimum 5 MB.</p>
                </div>

                <!-- Preview Gambar QR Code & Rincian File -->
                <template x-if="qrPreview">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                        <span class="text-[10px] uppercase font-extrabold text-slate-500 block tracking-wider">Preview Gambar QR Code:</span>
                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <div class="w-28 h-28 rounded-xl overflow-hidden border border-slate-300 bg-white shadow-xs shrink-0 flex items-center justify-center p-1">
                                <img :src="qrPreview" alt="Preview QR Code" class="w-full h-full object-contain">
                            </div>
                            <div class="space-y-2 text-xs text-slate-600 flex-1 w-full">
                                <div>
                                    <span class="text-[10px] text-slate-400 block">Nama File:</span>
                                    <span class="font-bold text-slate-800 text-xs font-mono break-all" x-text="qrName || qrPreview.split('/').pop()"></span>
                                </div>
                                <template x-if="qrSize">
                                    <div>
                                        <span class="text-[10px] text-slate-400">Ukuran File: </span>
                                        <span class="font-bold text-slate-700" x-text="(qrSize / 1024 > 1024 ? (qrSize / 1048576).toFixed(2) + ' MB' : (qrSize / 1024).toFixed(1) + ' KB')"></span>
                                    </div>
                                </template>
                                <div class="flex items-center gap-2 pt-1">
                                    <button type="button" @click="$refs.qrFileInput.click()" class="px-3 py-1 bg-emerald-100 hover:bg-emerald-200 text-emerald-800 font-bold text-[11px] rounded-lg transition flex items-center gap-1">
                                        <i class="fas fa-sync-alt text-[10px]"></i> Ganti QR Code
                                    </button>
                                    <button type="button" @click="removeQrCode()" class="px-3 py-1 bg-rose-100 hover:bg-rose-200 text-rose-800 font-bold text-[11px] rounded-lg transition flex items-center gap-1">
                                        <i class="fas fa-trash-alt text-[10px]"></i> Hapus QR Code
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="space-y-4">
            <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase pb-2 border-b border-slate-100 text-emerald-700">
                3. Informasi Alamat & Kontak
            </h4>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Alamat Kantor Lengkap</label>
                <input type="text" name="address" value="{{ $settings['address'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nomor Telepon Kantor</label>
                    <input type="text" name="phone" value="{{ $settings['phone'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email Resmi Kantor</label>
                    <input type="email" name="email" value="{{ $settings['email'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

        <!-- Pengaturan Tombol Hero (CTA Banner) -->
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase pb-2 border-b border-slate-100 text-emerald-700 flex items-center gap-2">
                <i class="fas fa-sliders-h text-emerald-600"></i> Pengaturan Tombol Hero (CTA Banner)
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Field SP4N Lapor -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                        <span class="font-extrabold text-slate-800 text-xs flex items-center gap-2">
                            <i class="fas fa-bullhorn text-orange-600"></i> Tombol SP4N Lapor
                        </span>
                        <span class="text-[10px] bg-orange-100 text-orange-800 font-bold px-2 py-0.5 rounded-md">Hero CTA 1</span>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1 text-xs">URL Tujuan SP4N Lapor</label>
                        <input type="text" name="hero_sp4n_url" value="{{ $settings['hero_sp4n_url'] ?? ($settings['sp4n_lapor_url'] ?? 'https://www.lapor.go.id/') }}" placeholder="https://www.lapor.go.id/" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono text-xs bg-white">
                        @error('hero_sp4n_url')
                            <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs pt-1">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1 text-[11px]">Target Buka</label>
                            <select name="hero_sp4n_target" class="w-full px-3 py-1.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white font-medium">
                                <option value="_blank" {{ ($settings['hero_sp4n_target'] ?? '_blank') === '_blank' ? 'selected' : '' }}>Tab Baru (_blank)</option>
                                <option value="_self" {{ ($settings['hero_sp4n_target'] ?? '_blank') === '_self' ? 'selected' : '' }}>Tab Saat Ini (_self)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1 text-[11px]">Status Tampil</label>
                            <select name="hero_sp4n_status" class="w-full px-3 py-1.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white font-medium">
                                <option value="aktif" {{ ($settings['hero_sp4n_status'] ?? 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ ($settings['hero_sp4n_status'] ?? 'aktif') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Field Hubungi Kami -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                        <span class="font-extrabold text-slate-800 text-xs flex items-center gap-2">
                            <i class="fab fa-whatsapp text-emerald-600 text-sm"></i> Tombol Hubungi Kami
                        </span>
                        <span class="text-[10px] bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-md">Hero CTA 2</span>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1 text-xs">URL Tujuan Hubungi (Format WhatsApp)</label>
                        <input type="text" name="hero_contact_url" value="{{ $settings['hero_contact_url'] ?? ($settings['whatsapp_url'] ?? 'https://wa.me/628123456789') }}" placeholder="https://wa.me/628123456789" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono text-xs bg-white">
                        @error('hero_contact_url')
                            <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs pt-1">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1 text-[11px]">Target Buka</label>
                            <select name="hero_contact_target" class="w-full px-3 py-1.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white font-medium">
                                <option value="_blank" {{ ($settings['hero_contact_target'] ?? '_blank') === '_blank' ? 'selected' : '' }}>Tab Baru (_blank)</option>
                                <option value="_self" {{ ($settings['hero_contact_target'] ?? '_blank') === '_self' ? 'selected' : '' }}>Tab Saat Ini (_self)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1 text-[11px]">Status Tampil</label>
                            <select name="hero_contact_status" class="w-full px-3 py-1.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white font-medium">
                                <option value="aktif" {{ ($settings['hero_contact_status'] ?? 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ ($settings['hero_contact_status'] ?? 'aktif') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Teks Copyright Footer</label>
                <input type="text" name="copyright_text" value="{{ $settings['copyright_text'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>
        </div>

        <!-- 4. Media Sosial Resmi -->
        <div class="space-y-4">
            <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase pb-2 border-b border-slate-100 text-emerald-700">
                4. Akun Media Sosial Resmi
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1"><i class="fab fa-facebook text-blue-600 me-1"></i> URL Facebook</label>
                    <input type="text" name="social_facebook" value="{{ $settings['social_facebook'] ?? '' }}" placeholder="https://facebook.com/..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1"><i class="fab fa-instagram text-pink-600 me-1"></i> URL Instagram</label>
                    <input type="text" name="social_instagram" value="{{ $settings['social_instagram'] ?? '' }}" placeholder="https://instagram.com/..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1"><i class="fab fa-x-twitter text-slate-900 me-1"></i> URL Twitter / X</label>
                    <input type="text" name="social_twitter" value="{{ $settings['social_twitter'] ?? '' }}" placeholder="https://x.com/..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1"><i class="fab fa-youtube text-red-600 me-1"></i> URL YouTube</label>
                    <input type="text" name="social_youtube" value="{{ $settings['social_youtube'] ?? '' }}" placeholder="https://youtube.com/..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1"><i class="fab fa-tiktok text-slate-900 me-1"></i> URL TikTok</label>
                    <input type="text" name="social_tiktok" value="{{ $settings['social_tiktok'] ?? '' }}" placeholder="https://tiktok.com/@..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition-all">
                <i class="fas fa-save me-1.5"></i> Simpan Semua Pengaturan
            </button>
        </div>

    </form>

</div>
@endsection
