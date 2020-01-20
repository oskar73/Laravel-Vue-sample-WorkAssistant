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
        Schema::create('logo_favicon', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('logo_id');
            $table->unsignedBigInteger('favicon_id');
            $table->foreign('logo_id')
                ->references('id')->on('logo_types')
                ->onDelete('cascade');
            $table->foreign('favicon_id')
                ->references('id')->on('favicon_items')
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
        Schema::dropIfExists('logo_favicon');
    }
};
