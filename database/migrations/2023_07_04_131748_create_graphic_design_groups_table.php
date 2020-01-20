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
        Schema::create('graphic_design_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('pair_id');
            $table->boolean('status')->default(true);

            $table->foreign('owner_id')->references('id')->on('graphic_designs')->onDelete('cascade');
            $table->foreign('pair_id')->references('id')->on('graphic_designs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graphic_design_groups');
    }
};
