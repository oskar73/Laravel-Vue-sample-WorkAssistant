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
        Schema::create('logo_category_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedInteger('logotype_id');
            $table->integer('order')->nullable();
            $table->timestamps();
            $table->foreign('category_id')
                ->references('id')->on('logo_categories')
                ->onDelete('cascade');
            $table->foreign('logotype_id')
                ->references('id')->on('logo_types')
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
        Schema::dropIfExists('logo_category_types');
    }
};
