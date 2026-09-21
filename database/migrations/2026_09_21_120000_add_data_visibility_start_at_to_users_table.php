<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Per-account data visibility window. NULL (the default for every existing
 * and future user) means "no restriction" — behaviour is unchanged for all
 * accounts until the column is set for a specific users.id via
 * `php artisan user:visibility`.
 */
class AddDataVisibilityStartAtToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dateTime('data_visibility_start_at')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('data_visibility_start_at');
        });
    }
}
