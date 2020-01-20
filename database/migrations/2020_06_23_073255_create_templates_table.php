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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('header_id')->nullable();
            $table->unsignedBigInteger('footer_id')->nullable();
            $table->string('name', 45);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->longText('css')->nullable();
            $table->longText('script')->nullable();
            $table->integer('order')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('featured')->default(0);
            $table->boolean('new')->default(0);
            $table->unsignedBigInteger('user_count')->default(0);
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
        Schema::dropIfExists('templates');
    }
};
