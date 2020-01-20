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
        Schema::create('domain_customs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('web_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('subdomain');
            $table->boolean('pointed')->default(1);
            $table->text('data')->nullable();
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
        Schema::dropIfExists('domain_customs');
    }
};
