<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('color_palettes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->boolean('gradient')->default(1);
            $table->string('name')->nullable();
            $table->text('preview')->nullable();
            $table->text('data');
            $table->integer('order')->nullable();
            $table->timestamps();
            $table->foreign('category_id')
                ->references('id')->on('color_categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('color_palettes');
    }
};
