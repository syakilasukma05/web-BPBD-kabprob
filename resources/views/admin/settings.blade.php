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

                <div class="space-y-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">URL Link Survei SKM (Ketika QR / Banner Diklik)</label>
                        <input type="text" name="survey_url" value="{{ $settings['survey_url'] ?? 'https://sukma.jatimprov.go.id/' }}" placeholder="https://sukma.jatimprov.go.id/" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">URL Image QR Code Survey</label>
                        <input type="text" name="qr_code_survey" value="{{ $settings['qr_code_survey'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
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
