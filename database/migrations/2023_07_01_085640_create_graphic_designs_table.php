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
        Schema::create('graphic_designs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('graphic_id');
            $table->unsignedBigInteger('tutorial_id')->nullable();
            $table->string('hash');
            $table->string('path');
            $table->boolean('status')->default(true);
            $table->string('name')->nullable();
            $table->boolean('premium')->default(0);
            $table->boolean('recommend')->default(0);
            $table->integer('order')->nullable();
            $table->integer('global_order')->nullable();
            $table->string('preview');
            $table->timestamps();

            $table->foreign('graphic_id')->references('id')->on('graphics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graphic_designs');
    }
};
