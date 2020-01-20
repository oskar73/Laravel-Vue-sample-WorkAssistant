<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_graphic_designs', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->after('hash')->nullable();

            $table->foreign('parent_id')->references('id')->on('user_graphic_designs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_graphic_designs', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
    }
};
