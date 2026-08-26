<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('navigation_menus', function (Blueprint $table) {
            $table->string('type')->default('internal')->after('url');
        });

        // 1. Set type = 'external' for any existing menu with http:// or https:// URL
        DB::table('navigation_menus')
            ->where('url', 'like', 'http://%')
            ->orWhere('url', 'like', 'https://%')
            ->update(['type' => 'external']);

        // 2. Clean up duplicate SP4N Lapor entries under HUBUNGI (keep primary and set to external)
        $sp4nMenus = DB::table('navigation_menus')->where('title', 'like', '%SP4N%Lapor%')->get();
        if ($sp4nMenus->count() > 0) {
            $primarySp4n = $sp4nMenus->first();
            DB::table('navigation_menus')->where('id', $primarySp4n->id)->update([
                'url' => 'https://www.lapor.go.id/',
                'target' => '_blank',
                'type' => 'external',
                'is_active' => true,
            ]);

            // Delete any duplicate SP4N Lapor menu entries
            if ($sp4nMenus->count() > 1) {
                $duplicateIds = $sp4nMenus->skip(1)->pluck('id')->toArray();
                DB::table('navigation_menus')->whereIn('id', $duplicateIds)->delete();
            }
        }

        // 3. Remove dummy page record for sp4n-lapor if created
        DB::table('pages')->where('slug', 'sp4n-lapor')->delete();
    }

    public function down(): void
    {
        Schema::table('navigation_menus', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
