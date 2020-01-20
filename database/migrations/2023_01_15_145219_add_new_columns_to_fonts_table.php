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
        Schema::table('fonts', function (Blueprint $table) {
            if (!Schema::hasColumn('fonts', 'public_path')) {
                $table->string('public_path')->after('name');
            }
            if (!Schema::hasColumn('fonts', 'extension')) {
                $table->string('extension')->after('public_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fonts', function (Blueprint $table) {
            $table->dropColumn(['public_path', 'extension']);
        });
    }
};
