<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modem_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // gpon | epon
            $table->timestamps();
        });

        Schema::create('modems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modem_model_id')->constrained('modem_models')->restrictOnDelete();
            $table->string('serial_number')->unique();
            $table->string('status')->default('tersedia'); // tersedia | terpasang | rusak
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete();
            $table->timestamp('installed_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modems');
        Schema::dropIfExists('modem_models');
    }
};