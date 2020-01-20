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
        Schema::create('newsletter_ads_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('position_id');
            $table->string('type')->default('period');
            $table->integer('period')->nullable();
            $table->integer('impression')->nullable();
            $table->string('price')->nullable();
            $table->string('slashed_price')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('standard')->default(0);
            $table->timestamps();
            $table->foreign('position_id')
                ->references('id')->on('newsletter_ads_positions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_ads_prices');
    }
};
