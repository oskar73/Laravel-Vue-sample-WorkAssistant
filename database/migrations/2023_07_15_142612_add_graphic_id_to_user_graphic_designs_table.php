<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_graphic_designs', function (Blueprint $table) {
            $table->unsignedBigInteger('graphic_id')->after('hash');

            $table->foreign('graphic_id')->on('graphics')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_graphic_designs', function (Blueprint $table) {
            $table->dropForeign('user_graphic_designs_graphic_id_foreign');
            $table->dropColumn('graphic_id');
        });
    }
};
