<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('Perencanaan Kinerja'); // Perencanaan, Pengukuran, Pelaporan, Evaluasi
            $table->string('file_path')->nullable();
            $table->string('file_url')->nullable();
            $table->integer('download_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_documents');
    }
};
