<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('public_documents', 'description')) {
            Schema::table('public_documents', function (Blueprint $table) {
                $table->text('description')->nullable()->after('category');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('public_documents', 'description')) {
            Schema::table('public_documents', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }
};
