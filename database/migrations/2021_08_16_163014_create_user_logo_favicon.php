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
        Schema::create('user_logo_favicon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_logo_id');
            $table->unsignedBigInteger('user_favicon_id');
            $table->foreign('user_logo_id')
                ->references('id')->on('users_logotypes')
                ->onDelete('cascade');
            $table->foreign('user_favicon_id')
                ->references('id')->on('user_favicon')
                ->onDelete('cascade');
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
        Schema::dropIfExists('user_logo_favicon');
    }
};
