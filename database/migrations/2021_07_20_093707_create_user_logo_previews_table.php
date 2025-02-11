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
        Schema::create('user_logo_previews', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_logo_id')->unsigned();
            $table->longText('content')->nullable();
            $table->timestamps();
            $table->foreign('user_logo_id')->references('id')->on('users_logotypes')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_logo_previews');
    }
};
