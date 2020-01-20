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
        Schema::create('user_graphic_designs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('hash');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('design_id');
            $table->longText('design_content');
            $table->boolean('downloadable')->default(0);
            $table->string('progress')->nullable();
            $table->string('version')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('design_id')->references('id')->on('graphic_designs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_graphic_designs');
    }
};
