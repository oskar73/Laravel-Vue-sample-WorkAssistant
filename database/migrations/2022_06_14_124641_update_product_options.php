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
        Schema::table('product_categories', function (Blueprint $table) {
            $table->unsignedBigInteger("user_id");
        });
        Schema::table('product_sub_categories', function (Blueprint $table) {
            $table->unsignedBigInteger("user_id");
        });
        Schema::table('product_units', function (Blueprint $table) {
            $table->unsignedBigInteger("user_id");
        });
        Schema::table('product_colors', function (Blueprint $table) {
            $table->unsignedBigInteger("user_id");
        });
        Schema::table('product_sizes', function (Blueprint $table) {
            $table->unsignedBigInteger("user_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
