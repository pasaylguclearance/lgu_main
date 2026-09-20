<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissingColumnsToNewApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('new_applications', 'or_no')) {
                $table->string('or_no')->nullable();
            }
            if (!Schema::hasColumn('new_applications', 'cedula_no')) {
                $table->string('cedula_no')->nullable();
            }
            if (!Schema::hasColumn('new_applications', 'issued_or_date')) {
                $table->string('issued_or_date')->nullable();
            }
            if (!Schema::hasColumn('new_applications', 'issued_date')) {
                $table->string('issued_date')->nullable();
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
            $table->dropColumn(['or_no', 'cedula_no', 'issued_or_date', 'issued_date']);
        });
    }
}
