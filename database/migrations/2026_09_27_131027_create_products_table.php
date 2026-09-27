<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();       // contoh: saka-lite-50
            $table->string('category');              // rumah, gamer, bisnis
            $table->string('badge');                 // Rumah Tangga, Best Value, dll
            $table->string('icon');                   // nama icon Material Symbols
            $table->string('name');                   // Saka Lite 50
            $table->text('description');
            $table->string('speed');                  // "50" atau "Up to 1"
            $table->string('unit');                    // Mbps / Gbps
            $table->string('old_price')->nullable();  // Rp 250.000
            $table->string('discount')->nullable();   // HEMAT 20%
            $table->string('price');                   // Rp 199.000
            $table->boolean('is_popular')->default(false);
            $table->json('features');                  // array fitur, disimpan sebagai JSON
            $table->integer('sort_order')->default(0); // urutan tampil
            $table->boolean('is_active')->default(true); // bisa disembunyikan tanpa dihapus
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};