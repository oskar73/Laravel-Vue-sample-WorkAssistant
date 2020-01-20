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
        Schema::create('logo_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash');
            $table->string('path');
            $table->boolean('enabled')->default(true);
            $table->string('name')->nullable();
            $table->boolean('premium')->default(0);
            $table->boolean('recommend')->default(0);
            $table->integer('order')->nullable();
            $table->integer('global_order')->nullable();
            $table->unsignedBigInteger('tutorial_id')->nullable();
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
        Schema::dropIfExists('logo_types');
    }
};
