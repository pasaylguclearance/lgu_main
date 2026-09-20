<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIssuedAtToNewApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('new_applications', 'issued_at')) {
                $table->string('issued_at')->nullable();
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
        Schema::table('new_applications', function (Blueprint $table) {
            $table->dropColumn('issued_at');
        });
    }
}
