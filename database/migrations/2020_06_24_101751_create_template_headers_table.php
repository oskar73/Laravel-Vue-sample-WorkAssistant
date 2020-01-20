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
        Schema::create('template_headers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->longText('css')->nullable();
            $table->longText('script')->nullable();
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
        Schema::dropIfExists('template_headers');
    }
};
