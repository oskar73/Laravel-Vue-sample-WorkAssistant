<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('newsletter_ads_listing_tracks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("listing_id");
            $table->string("ip");
            $table->string("device");
            $table->timestamps();
            $table->foreign('listing_id')
                ->references('id')->on('newsletter_ads_listings')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_ads_listing_tracks');
    }
};
