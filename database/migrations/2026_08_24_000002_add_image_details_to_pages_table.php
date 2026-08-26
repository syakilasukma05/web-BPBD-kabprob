<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('image_url');
            $table->string('image_name')->nullable()->after('image_path');
            $table->string('image_type')->nullable()->after('image_name');
            $table->unsignedBigInteger('image_size')->nullable()->after('image_type');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'image_name', 'image_type', 'image_size']);
        });
    }
};
