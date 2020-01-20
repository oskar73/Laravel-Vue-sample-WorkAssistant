<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateDomainPricesTable.
 */
return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domain_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tld');
            $table->string('Action');
            $table->integer('Duration');
            $table->string('DurationType')->nullable();
            $table->string('Price')->nullable();
            $table->string('PricingType')->nullable();
            $table->string('AdditionalCost')->nullable();
            $table->string('RegularPrice')->nullable();
            $table->string('RegularPriceType')->nullable();
            $table->string('RegularAdditionalCost')->nullable();
            $table->string('RegularAdditionalCostType')->nullable();
            $table->string('YourPrice')->nullable();
            $table->string('YourPriceType')->nullable();
            $table->string('YourAdditonalCost')->nullable();
            $table->string('YourAdditonalCostType')->nullable();
            $table->string('PromotionPrice')->nullable();
            $table->string('Currency')->nullable();
            $table->string('addPrice')->nullable();
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
        Schema::drop('domain_prices');
    }
};
