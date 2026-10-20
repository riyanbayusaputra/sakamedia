<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->timestamp('activated_at')->nullable()->after('surveyed_at')->index();
        });

        // Data lama: yang sudah Terpasang diberi tanggal perkiraan (kapan terakhir diubah)
        DB::table('subscriptions')
            ->where('status', 'terpasang')
            ->whereNull('activated_at')
            ->update(['activated_at' => DB::raw('updated_at')]);
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex(['activated_at']);
            $table->dropColumn('activated_at');
        });
    }
};