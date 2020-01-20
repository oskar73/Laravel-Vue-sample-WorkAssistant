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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->boolean('package')->default(1);
            $table->string('name');
            $table->string('slug');
            $table->text('description');
            $table->text('links')->nullable();
            $table->boolean('featured')->default(0);
            $table->boolean('status')->default(1);
            $table->boolean('new')->default(0);
            $table->boolean('meeting')->default(0);
            $table->boolean('form')->default(0);
            $table->string('module')->default(0);
            $table->string('featured_module')->default(0);
            $table->string('page')->default(1);
            $table->string('storage')->default(0.5);
            $table->string('website')->default(1);
            $table->string('domain')->default(0);
            $table->unsignedBigInteger('form_id')->nullable();
            $table->unsignedBigInteger('email_id')->nullable();
            $table->integer('order')->nullable();
            $table->integer('step')->default(1);
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
        Schema::dropIfExists('packages');
    }
};
