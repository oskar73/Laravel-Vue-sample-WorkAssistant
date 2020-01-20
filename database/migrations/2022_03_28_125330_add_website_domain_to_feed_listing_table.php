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
        Schema::table('feed_listing', function (Blueprint $table) {
            //
            $table->string('website_domain')->nullable()->after('website_id');
            $table->text('text')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feed_listing', function (Blueprint $table) {
            //
            $table->dropColumn('website_domain');
        });
    }
};
