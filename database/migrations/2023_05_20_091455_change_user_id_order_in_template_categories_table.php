<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `template_categories` MODIFY COLUMN user_id BIGINT(20) AFTER parent_id');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `template_categories` MODIFY COLUMN user_id BIGINT(20) AFTER updated_at');
    }
};
