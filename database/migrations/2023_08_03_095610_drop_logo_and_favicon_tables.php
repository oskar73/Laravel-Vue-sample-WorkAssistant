<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('user_logo_favicon');
        Schema::dropIfExists('logo_favicon');

        Schema::dropIfExists('group_logotypes');
        Schema::dropIfExists('group_favicons');
        Schema::dropIfExists('groups');

        Schema::dropIfExists('logo_category_types');
        Schema::dropIfExists('logo_categories');
        Schema::dropIfExists('logo_packages');
        Schema::dropIfExists('user_logo_previews');
        Schema::dropIfExists('users_logotypes');
        Schema::dropIfExists('user_logos');
        Schema::dropIfExists('user_logo_counts');
        Schema::dropIfExists('user_logo_type_downloads');
        Schema::dropIfExists('logo_types');

        Schema::dropIfExists('favicon_item_categories');
        Schema::dropIfExists('favicon_categories');
        Schema::dropIfExists('user_favicon_previews');
        Schema::dropIfExists('user_favicon');
        Schema::dropIfExists('favicon_items');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
