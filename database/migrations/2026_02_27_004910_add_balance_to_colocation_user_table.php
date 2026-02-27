<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('colocation_user', function (Blueprint $table) {
            $table->decimal('balance', 10, 2)->default(0)->after('left_at');
        });
    }

    public function down(): void
    {
        Schema::table('colocation_user', function (Blueprint $table) {
            $table->dropColumn('balance');
        });
    }
};