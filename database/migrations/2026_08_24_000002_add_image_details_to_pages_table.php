<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            if (!Schema::hasColumn('pages', 'image_url')) {
                $table->text('image_url')->nullable()->after('content');
            }
            if (!Schema::hasColumn('pages', 'image_path')) {
                $table->string('image_path')->nullable()->after('image_url');
            }
            if (!Schema::hasColumn('pages', 'image_name')) {
                $table->string('image_name')->nullable()->after('image_path');
            }
            if (!Schema::hasColumn('pages', 'image_type')) {
                $table->string('image_type')->nullable()->after('image_name');
            }
            if (!Schema::hasColumn('pages', 'image_size')) {
                $table->unsignedBigInteger('image_size')->nullable()->after('image_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $columnsToDrop = array_filter(['image_url', 'image_path', 'image_name', 'image_type', 'image_size'], function($col) {
                return Schema::hasColumn('pages', $col);
            });
            if (!empty($columnsToDrop)) {
                $table->dropColumn(array_values($columnsToDrop));
            }
        });
    }
};
