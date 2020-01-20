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
        Schema::create('user_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("order_item_id");
            $table->boolean("package")->default(1);
            $table->string("status")->default("pending");
            $table->text("item")->nullable();
            $table->text("modules")->nullable();
            $table->text("price")->nullable();
            $table->string("website")->default(0);
            $table->integer("current_website")->default(0);
            $table->string("storage")->default(0);
            $table->string("page")->default(0);
            $table->string("module")->default(0);
            $table->string("featured_module")->default(0);
            $table->string('domain')->default(0);
            $table->boolean('domain_get')->default(0);
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
        Schema::dropIfExists('user_packages');
    }
};
