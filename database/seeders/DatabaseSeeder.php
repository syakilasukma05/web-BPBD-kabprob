<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\SiteSetting;
use App\Models\NavigationMenu;
use App\Models\HeroSlider;
use App\Models\NewsItem;
use App\Models\SidebarWidget;
use App\Models\RelatedLink;
use App\Models\PublicDocument;
use App\Models\Page;
use App\Models\ActivityLog;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users (Super Admin & Anggota Staf BPBD)
        User::updateOrCreate(
            ['email' => 'admin@bpbd.probolinggokab.go.id'],
            [
                'name' => 'Super Admin BPBD',
                'password' => Hash::make('BpbdProbolinggo2026!'),
                'role' => 'super_admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'anggota@bpbd.probolinggokab.go.id'],
            [
                'name' => 'Staf Pusdalops BPBD',
                'password' => Hash::make('BpbdProbolinggo2026!'),
                'role' => 'anggota',
            ]
        );

        // 2. Site Settings BPBD Kabupaten Probolinggo
        $settings = [
            'site_title' => 'Website Resmi | Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Probolinggo',
            'site_description' => 'Website Resmi BPBD Kabupaten Probolinggo - Pusat Informasi Kebencanaan, Peringatan Dini, dan Pelayanan Tanggap Darurat Bencana.',
            'agency_name' => 'Badan Penanggulangan Bencana Daerah (BPBD)',
            'regency_name' => 'Kabupaten Probolinggo',
            'logo_frontend' => '/images/logo-bpbd.svg',
            'logo_backend' => '/images/logo-bpbd.svg',
            'logo_berakhlak' => 'https://diskominfo.probolinggokab.go.id/frontend/images/img-berakhlak.png',
            'qr_code_survey' => 'https://diskominfo.probolinggokab.go.id/backend/gambar/qr_code_kominfo.png',
            'address' => 'Jl. Soekarno - Hatta No.27, Dusun Mawar I, Pilang, Kec. Kademangan, Kota Probolinggo, Jawa Timur 67212',
            'phone' => '(0335) 422113 / Call Center 24 Jam: 0812-3123-911',
            'email' => 'bpbd@probolinggokab.go.id',
            'survey_url' => 'https://sukma.jatimprov.go.id/',
            'instagram_url' => 'https://www.instagram.com/bpbdkabprobolinggo/',
            'copyright_text' => 'BPBD - Kabupaten Probolinggo © 2026. All Rights Reserved. Siap, Sigap, Tanggap Bencana!',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::set($key, $value);
        }

        // 3. Navigation Menus BPBD
        if (NavigationMenu::count() === 0) {
            $home = NavigationMenu::create(['title' => 'HOME', 'url' => '/', 'order' => 1]);
            
            $profil = NavigationMenu::create(['title' => 'PROFIL', 'url' => '#', 'order' => 2]);
            NavigationMenu::create(['title' => 'Struktur Organisasi BPBD', 'url' => '/halaman/struktur-organisasi', 'parent_id' => $profil->id, 'order' => 1]);
            NavigationMenu::create(['title' => 'Visi Misi Kebencanaan', 'url' => '/halaman/visi-misi', 'parent_id' => $profil->id, 'order' => 2]);
            NavigationMenu::create(['title' => 'Tugas dan Fungsi BPBD', 'url' => '/halaman/tugas-dan-fungsi', 'parent_id' => $profil->id, 'order' => 3]);
            NavigationMenu::create(['title' => 'Peta Rawan Bencana Daerah', 'url' => '/halaman/peta-rawan-bencana', 'parent_id' => $profil->id, 'order' => 4]);

            $layanan = NavigationMenu::create(['title' => 'LAYANAN', 'url' => '#', 'order' => 3]);
            NavigationMenu::create(['title' => 'Layanan Pusdalops PB (24 Jam)', 'url' => '/halaman/layanan-pusdalops', 'parent_id' => $layanan->id, 'order' => 1]);
            NavigationMenu::create(['title' => 'Sistem Peringatan Dini (EWS)', 'url' => '/halaman/peringatan-dini-ews', 'parent_id' => $layanan->id, 'order' => 2]);
            NavigationMenu::create(['title' => 'Standar Pelayanan Penanggulangan Bencana', 'url' => '/halaman/standar-pelayanan-publik', 'parent_id' => $layanan->id, 'order' => 3]);
            NavigationMenu::create(['title' => 'Permohonan Bantuan Logistik & Tenda', 'url' => '/halaman/bantuan-logistik', 'parent_id' => $layanan->id, 'order' => 4]);

            $dokumen = NavigationMenu::create(['title' => 'DOKUMEN', 'url' => '#', 'order' => 4]);
            NavigationMenu::create(['title' => 'Kajian Risiko Bencana (KRB)', 'url' => '/dokumen', 'parent_id' => $dokumen->id, 'order' => 1]);
            NavigationMenu::create(['title' => 'Rencana Penanggulangan Bencana (RPB)', 'url' => '/dokumen', 'parent_id' => $dokumen->id, 'order' => 2]);
            NavigationMenu::create(['title' => 'Renstra & Laporan Kinerja BPBD', 'url' => '/dokumen', 'parent_id' => $dokumen->id, 'order' => 3]);
            NavigationMenu::create(['title' => 'SOP Tanggap Darurat Bencana', 'url' => '/dokumen', 'parent_id' => $dokumen->id, 'order' => 4]);

            $informasi = NavigationMenu::create(['title' => 'INFORMASI', 'url' => '#', 'order' => 5]);
            NavigationMenu::create(['title' => 'Berita Kebencanaan', 'url' => '/informasi', 'parent_id' => $informasi->id, 'order' => 1]);
            NavigationMenu::create(['title' => 'Peringatan Dini Cuaca BMKG', 'url' => 'https://www.bmkg.go.id/', 'parent_id' => $informasi->id, 'order' => 2, 'target' => '_blank']);
            NavigationMenu::create(['title' => 'Status Gunung Bromo', 'url' => '/halaman/status-gunung-bromo', 'parent_id' => $informasi->id, 'order' => 3]);
            NavigationMenu::create(['title' => 'Galeri Posko Kebencanaan', 'url' => '/galeri', 'parent_id' => $informasi->id, 'order' => 4]);

            $hubungi = NavigationMenu::create(['title' => 'HUBUNGI', 'url' => '#', 'order' => 6]);
            NavigationMenu::create(['title' => 'Lapor Bencana 24 Jam', 'url' => '/kontak', 'parent_id' => $hubungi->id, 'order' => 1]);
            NavigationMenu::create(['title' => 'Kontak Pusdalops BPBD', 'url' => '/kontak', 'parent_id' => $hubungi->id, 'order' => 2]);

            NavigationMenu::create(['title' => 'LOGIN', 'url' => '/login', 'order' => 7]);
        }

        // 4. Hero Sliders BPBD
        if (HeroSlider::count() === 0) {
            HeroSlider::create([
                'title' => 'Selamat Datang di Portal Resmi BPBD',
                'subtitle' => 'Badan Penanggulangan Bencana Daerah Kabupaten Probolinggo - Tangguh, Siap, Sigap!',
                'image_url' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=1600&auto=format&fit=crop',
                'button_text' => 'Lapor Bencana Darurat',
                'button_url' => '/kontak',
                'order' => 1,
                'is_active' => true,
            ]);
            HeroSlider::create([
                'title' => 'Pusdalops PB Siaga 24 Jam',
                'subtitle' => 'Pemantauan Peringatan Dini Cuaca, Erupsi Bromo, & Evakuasi Darurat Bencana Kebencanaan',
                'image_url' => 'https://images.unsplash.com/photo-1509099836639-18ba1795216d?q=80&w=1600&auto=format&fit=crop',
                'button_text' => 'Lihat Informasi Peringatan',
                'button_url' => '/informasi',
                'order' => 2,
                'is_active' => true,
            ]);
        }

        // 5. News Items BPBD
        if (NewsItem::count() === 0) {
            NewsItem::create([
                'title' => 'BPBD Kabupaten Probolinggo Tingkatkan Kesiapsiagaan Posko Pemantauan Gunung Bromo Level II Waspada',
                'slug' => 'bpbd-kabupaten-probolinggo-tingkatkan-kesiapsiagaan-posko-gunung-bromo',
                'summary' => 'Tim Pusdalops PB BPBD terus melakukan koordinasi dengan PVMBG terkait aktivitas vulkanik Gunung Bromo.',
                'content' => 'Probolinggo - Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Probolinggo mengimbau wisatawan dan warga lereng Gunung Bromo untuk tetap tenang namun waspada...',
                'image_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?q=80&w=800&auto=format&fit=crop',
                'category' => 'Peringatan Dini',
                'published_at' => '2026-08-01',
                'views' => 312,
                'is_featured' => true,
            ]);
        }

        // 6. Sidebar Widgets BPBD
        if (SidebarWidget::count() === 0) {
            SidebarWidget::create([
                'title' => 'Call Center Emergency BPBD 24 Jam',
                'image_url' => 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?q=80&w=600&auto=format&fit=crop',
                'target_url' => '/kontak',
                'order' => 1,
                'is_active' => true,
            ]);
        }

        // 7. Related Links BPBD
        if (RelatedLink::count() === 0) {
            RelatedLink::create([
                'title' => 'BNPB Indonesia',
                'image_url' => 'https://bpbd.probolinggokab.go.id/wp-content/uploads/2021/04/logo-bpbd.png',
                'url' => 'https://bnpb.go.id/',
                'order' => 1,
                'is_active' => true,
            ]);
        }

        // 8. Public Documents BPBD
        if (PublicDocument::count() === 0) {
            PublicDocument::create([
                'title' => 'Dokumen Rencana Penanggulangan Bencana (RPB) Kabupaten Probolinggo 2024-2029',
                'category' => 'Perencanaan Kinerja',
                'file_url' => 'https://bpbd.probolinggokab.go.id/dokumen/rpb_probolinggo.pdf',
                'download_count' => 128,
            ]);
        }

        // 9. Pages BPBD
        if (Page::count() === 0) {
            Page::create([
                'title' => 'Struktur Organisasi BPBD',
                'slug' => 'struktur-organisasi',
                'content' => '<h2>Struktur Organisasi BPBD Kabupaten Probolinggo</h2><p>Badan Penanggulangan Bencana Daerah terdiri dari unsur Pengarah dan unsur Pelaksana.</p>',
                'is_published' => true,
            ]);
        }
        Page::create([
            'title' => 'Visi Misi Kebencanaan',
            'slug' => 'visi-misi',
            'content' => '<h2>Visi & Misi Kebencanaan</h2><p><strong>Visi:</strong> Terwujudnya Kabupaten Probolinggo yang Tangguh Bencana dan Siap Siaga Menghadapi Ancaman Darurat Kebencanaan.</p><p><strong>Misi:</strong> 1. Melindungi masyarakat dari ancaman bencana melalui pengurangan risiko bencana. 2. Membangun penanggulangan bencana yang terencana, terpadu, dan terkoordinasi. 3. Memperkuat kapasitas Pusdalops & TRC BPBD.</p>',
            'is_published' => true,
        ]);
        Page::create([
            'title' => 'Tugas dan Fungsi BPBD',
            'slug' => 'tugas-dan-fungsi',
            'content' => '<h2>Tugas dan Fungsi BPBD</h2><p>BPBD mempunyai tugas menetapkan pedoman dan pengarahan terhadap penanggulangan bencana yang mencakup pencegahan bencana, penanganan darurat, rehabilitasi, serta rekonstruksi secara adil dan terkoordinasi.</p>',
            'is_published' => true,
        ]);
        Page::create([
            'title' => 'Peta Rawan Bencana',
            'slug' => 'peta-rawan-bencana',
            'content' => '<h2>Peta Kawasan Rawan Bencana (KRB)</h2><p>Kabupaten Probolinggo memiliki beragam potensi ancaman kebencanaan seperti Erupsi Gunung Bromo (Sukapura), Tanah Longsor (Tiris, Krucil), Banjir Bandang (Dringu, Gading), dan Gelombang Pasang Pantai Utara.</p>',
            'is_published' => true,
        ]);

        // 10. Activity Logs initial data
        ActivityLog::truncate();
        $admin = User::where('role', 'super_admin')->first();
        $staf = User::where('role', 'anggota')->first();

        ActivityLog::create([
            'user_id' => $admin ? $admin->id : 1,
            'user_name' => $admin ? $admin->name : 'Super Admin BPBD',
            'user_role' => 'super_admin',
            'action' => 'Inisialisasi Sistem',
            'description' => 'Sistem BPBD Kabupaten Probolinggo berhasil dikonfigurasi dan diinisialisasi.',
            'ip_address' => '127.0.0.1',
            'created_at' => now()->subHours(5),
        ]);

        ActivityLog::create([
            'user_id' => $admin ? $admin->id : 1,
            'user_name' => $admin ? $admin->name : 'Super Admin BPBD',
            'user_role' => 'super_admin',
            'action' => 'Update Pengaturan',
            'description' => 'Memperbarui identitas instansi dan logo resmi BPBD Kabupaten Probolinggo.',
            'ip_address' => '127.0.0.1',
            'created_at' => now()->subHours(3),
        ]);

        ActivityLog::create([
            'user_id' => $staf ? $staf->id : 2,
            'user_name' => $staf ? $staf->name : 'Staf Pusdalops BPBD',
            'user_role' => 'anggota',
            'action' => 'Tambah Berita',
            'description' => 'Mempublikasikan berita baru: BPBD Kabupaten Probolinggo Tingkatkan Kesiapsiagaan Posko Gunung Bromo.',
            'ip_address' => '127.0.0.1',
            'created_at' => now()->subMinutes(45),
        ]);
    }
}
