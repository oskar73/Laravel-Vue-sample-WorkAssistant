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
        Schema::create('user_favicon', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('hash');

            // Create foreign key to logo id
            $table->unsignedBigInteger('favicon_id');
            $table->foreign('favicon_id')->references('id')->on('favicon_items')->onDelete('cascade');

            // Create foreign key to user id
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->longText('favicon_content');
            $table->boolean('downloadable')->default(0);
            $table->string('progress')->nullable();

            $table->string('version')->nullable();
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
        Schema::dropIfExists('user_favicon');
    }
};
