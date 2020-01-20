<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateDomainTldsTable.
 */
return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domain_tlds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Name')->unique();
            $table->string('NonRealTime')->nullable();
            $table->integer('MinRegisterYears')->nullable();
            $table->integer('MaxRegisterYears')->nullable();
            $table->integer('MinRenewYears')->nullable();
            $table->integer('MaxRenewYears')->nullable();
            $table->integer('RenewalMinDays')->nullable();
            $table->integer('RenewalMaxDays')->nullable();
            $table->integer('ReactivateMaxDays')->nullable();
            $table->integer('MinTransferYears')->nullable();
            $table->integer('MaxTransferYears')->nullable();
            $table->string('IsApiRegisterable')->nullable();
            $table->string('IsApiRenewable')->nullable();
            $table->string('IsApiTransferable')->nullable();
            $table->string('IsEppRequired')->nullable();
            $table->string('IsDisableModContact')->nullable();
            $table->string('IsDisableWGAllot')->nullable();
            $table->string('IsIncludeInExtendedSearchOnly')->nullable();
            $table->integer('SequenceNumber')->nullable();
            $table->string('Type')->nullable();
            $table->string('SubType')->nullable();
            $table->string('IsSupportsIDN')->nullable();
            $table->string('Category')->nullable();
            $table->string('SupportsRegistrarLock')->nullable();
            $table->integer('AddGracePeriodDays')->nullable();
            $table->string('WhoisVerification')->nullable();
            $table->string('ProviderApiDelete')->nullable();
            $table->string('TldState')->nullable();
            $table->string('SearchGroup')->nullable();
            $table->string('Registry')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('recommend')->default(0);
            $table->integer('sortOrder')->default(0);
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
        Schema::drop('domain_tlds');
    }
};
