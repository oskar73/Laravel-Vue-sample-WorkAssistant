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
        Schema::create('palettes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->comment('admin category has null of user_id');
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('type', 8)->comment('simple, advanced');
            $table->string('mode', '5')->default('light')->comment('light, dark');
            $table->text('data');
            $table->string('image')->nullable();
            $table->integer('order')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('palettes');
    }
};
