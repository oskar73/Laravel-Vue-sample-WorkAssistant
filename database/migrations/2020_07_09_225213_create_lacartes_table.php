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
        Schema::create('lacartes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('slug');
            $table->text('description');
            $table->string('price');
            $table->string('slashed_price')->nullable();
            $table->text('links')->nullable();
            $table->boolean('featured')->default(0);
            $table->boolean('status')->default(1);
            $table->boolean('new')->default(0);
            $table->boolean('meeting')->default(0);
            $table->boolean('form')->default(0);
            $table->unsignedBigInteger('form_id')->nullable();
            $table->unsignedBigInteger('email_id')->nullable();
            $table->integer('order')->nullable();
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
        Schema::dropIfExists('lacartes');
    }
};
