<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('team')->nullable()->after('survey_date');
            $table->string('odp')->nullable();
            $table->string('odp_port')->nullable();
            $table->string('odc')->nullable();
            $table->json('materials')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('location_source')->nullable(); // gps | link
            $table->text('location_link')->nullable();
            $table->string('ktp_photo')->nullable();
            $table->timestamp('surveyed_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'team', 'odp', 'odp_port', 'odc', 'materials',
                'latitude', 'longitude', 'location_source', 'location_link',
                'ktp_photo', 'surveyed_at',
            ]);
        });
    }
};