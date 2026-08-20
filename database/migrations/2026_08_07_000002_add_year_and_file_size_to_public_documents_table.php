<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('public_documents', function (Blueprint $table) {
            $table->string('year')->nullable()->default('2025')->after('category');
            $table->string('file_size')->nullable()->default('1.5 MB')->after('year');
        });
    }

    public function down(): void
    {
        Schema::table('public_documents', function (Blueprint $table) {
            $table->dropColumn(['year', 'file_size']);
        });
    }
};
