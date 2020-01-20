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
        Schema::create('guest_supports', function (Blueprint $table) {
            $table->id();
            $table->string('guest_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->foreign('guest_id')
                ->references('id')->on('guests')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('users')
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
        Schema::dropIfExists('guest_supports');
    }
};
