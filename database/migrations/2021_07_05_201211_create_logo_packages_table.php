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
        Schema::create('logo_packages', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description")->nullable();
            $table->boolean("recurrent")->default(1);
            $table->string("price");
            $table->string("period")->nullable();
            $table->string("period_unit")->nullable();
            $table->string("free_limit")->default(0);
            $table->string("premium_limit")->default(0);
            $table->boolean("status")->default(1);
            $table->integer("order")->nullable();
            $table->boolean("recommend")->default(0);
            $table->text("meta")->nullable();
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
        Schema::dropIfExists('logo_packages');
    }
};
