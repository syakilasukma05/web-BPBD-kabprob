<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('news_items', 'order')) {
            Schema::table('news_items', function (Blueprint $table) {
                $table->integer('order')->default(1)->after('category');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('news_items', 'order')) {
            Schema::table('news_items', function (Blueprint $table) {
                $table->dropColumn('order');
            });
        }
    }
};
