<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('badge');            // contoh: OFFICIAL CAMPAIGN
            $table->string('title');            // contoh: Ultra Cepat! Internet Tanpa Batas Kuota
            $table->string('image')->nullable(); // path gambar banner
            $table->string('link_url')->nullable(); // tujuan klik, contoh: #produk-pilihan
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};