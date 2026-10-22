<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('media_items', 'photos')) {
            Schema::table('media_items', function (Blueprint $table) {
                $table->json('photos')->nullable()->after('file_url');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('media_items', 'photos')) {
            Schema::table('media_items', function (Blueprint $table) {
                $table->dropColumn('photos');
            });
        }
    }
};
