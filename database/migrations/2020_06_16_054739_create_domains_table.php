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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('web_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->string('name');
            $table->string('domainID');
            $table->boolean('freePositiveSSL')->default(0);
            $table->boolean('nonRealTimeDomain')->default(0);
            $table->string('orderID')->nullable();
            $table->boolean('registered')->default(1);
            $table->boolean('transfered')->default(0);
            $table->string('transactionID')->nullable();
            $table->boolean('whoisguardEnable')->default(0);
            $table->string('expired_at')->nullable();
            $table->string('chargedAmountNC')->nullable();
            $table->string('chargedAmountBB')->nullable();
            $table->boolean('pointed')->default(1);
            $table->string('status')->default('active');
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
        Schema::dropIfExists('domains');
    }
};
