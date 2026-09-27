<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->foreignId('download_category_id')->nullable()->constrained('download_categories')->nullOnDelete();
            $table->string('file');
            $table->string('tipe_file'); // pdf, docx, xlsx, etc.
            $table->string('ukuran_file'); // e.g. "1.2 MB"
            $table->integer('jumlah_unduhan')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('downloads');
    }
};
