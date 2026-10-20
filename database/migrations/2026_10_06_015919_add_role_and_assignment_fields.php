<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('sales')->after('email'); // sales | teknisi
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreignId('technician_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->date('survey_date')->nullable()->after('technician_id');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['technician_id']);
            $table->dropColumn(['technician_id', 'survey_date']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};