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
        Schema::create('template_page_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id');
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->longText('data');
            $table->timestamps();
            $table->foreign('page_id')
                ->references('id')->on('template_pages')
                ->onDelete('cascade');
            $table->foreign('category_id')
                ->references('id')->on('section_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('template_page_sections');
    }
};
