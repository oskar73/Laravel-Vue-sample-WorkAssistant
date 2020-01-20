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
        Schema::create('favicon_item_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('favicon_id');
            $table->integer('order')->nullable();
            $table->timestamps();
            $table->foreign('category_id')
                ->references('id')->on('favicon_categories')
                ->onDelete('cascade');
            $table->foreign('favicon_id')
                ->references('id')->on('favicon_items')
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
        Schema::dropIfExists('favicon_item_categories');
    }
};
