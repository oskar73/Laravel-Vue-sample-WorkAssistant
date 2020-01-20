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
        Schema::create('blog_ads_gags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spot_id');
            $table->string('title')->nullable();
            $table->text('text')->nullable();
            $table->string('url')->nullable();
            $table->boolean('google_ads')->default(0);
            $table->text('code')->nullable();
            $table->timestamps();
            $table->foreign('spot_id')
                ->references('id')->on('blog_ads_spots')
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
        Schema::dropIfExists('blog_ads_gags');
    }
};
