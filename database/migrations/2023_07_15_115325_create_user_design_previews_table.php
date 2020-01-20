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
        Schema::create('user_design_previews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_design_id')->unsigned();
            $table->longText('content')->nullable();
            $table->timestamps();

            $table->foreign('user_design_id')->references('id')->on('user_graphic_designs')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_design_previews');
    }
};
