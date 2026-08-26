<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Find or create HUBUNGI parent menu
        $hubungi = DB::table('navigation_menus')->where('title', 'HUBUNGI')->first();
        $parentId = $hubungi ? $hubungi->id : null;

        if ($parentId) {
            // Update SP4N Lapor
            DB::table('navigation_menus')
                ->where('parent_id', $parentId)
                ->where('title', 'like', '%SP4N%')
                ->update([
                    'title' => 'SP4N Lapor',
                    'url' => 'https://www.lapor.go.id/',
                    'target' => '_blank',
                    'type' => 'external',
                    'is_active' => true,
                    'order' => 1
                ]);

            // Update or Insert Kontak Pusdalops BPBD
            $kontakMenu = DB::table('navigation_menus')
                ->where('parent_id', $parentId)
                ->where(function($q) {
                    $q->where('title', 'like', '%Kontak%')->orWhere('url', 'like', '%kontak%');
                })->first();

            if ($kontakMenu) {
                DB::table('navigation_menus')->where('id', $kontakMenu->id)->update([
                    'title' => 'Kontak Pusdalops BPBD',
                    'url' => 'https://bpbd.probolinggokab.go.id/',
                    'target' => '_blank',
                    'type' => 'external',
                    'is_active' => true,
                    'order' => 2
                ]);
            } else {
                DB::table('navigation_menus')->insert([
                    'title' => 'Kontak Pusdalops BPBD',
                    'url' => 'https://bpbd.probolinggokab.go.id/',
                    'type' => 'external',
                    'parent_id' => $parentId,
                    'order' => 2,
                    'target' => '_blank',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Update or Insert Layanan Darurat
            $layananMenu = DB::table('navigation_menus')
                ->where('parent_id', $parentId)
                ->where(function($q) {
                    $q->where('title', 'like', '%Layanan%')->orWhere('url', 'like', '%layanan%');
                })->first();

            if ($layananMenu) {
                DB::table('navigation_menus')->where('id', $layananMenu->id)->update([
                    'title' => 'Layanan Darurat',
                    'url' => 'https://bpbd.probolinggokab.go.id/',
                    'target' => '_blank',
                    'type' => 'external',
                    'is_active' => true,
                    'order' => 3
                ]);
            } else {
                DB::table('navigation_menus')->insert([
                    'title' => 'Layanan Darurat',
                    'url' => 'https://bpbd.probolinggokab.go.id/',
                    'type' => 'external',
                    'parent_id' => $parentId,
                    'order' => 3,
                    'target' => '_blank',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // 2. Clean up dummy internal page records for SP4N Lapor, Kontak, Layanan Darurat if present
        DB::table('pages')->whereIn('slug', ['sp4n-lapor', 'layanan-kedaruratan', 'kontak'])->delete();
    }

    public function down(): void
    {
        // Revert not required for data fix
    }
};
