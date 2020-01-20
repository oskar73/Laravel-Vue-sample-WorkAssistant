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
        Schema::create('graphic_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('graphic_id');
            $table->string('slug')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('order')->nullable();
            $table->timestamps();

            $table->foreign('graphic_id')->references('id')->on('graphics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graphic_categories');
    }
};
