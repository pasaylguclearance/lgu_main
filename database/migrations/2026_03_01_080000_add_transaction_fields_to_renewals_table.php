<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionFieldsToRenewalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('renewals', function (Blueprint $table) {
            if (!Schema::hasColumn('renewals', 'or_no')) {
                $table->string('or_no')->nullable()->after('application_id');
            }
            if (!Schema::hasColumn('renewals', 'issued_or_date')) {
                $table->string('issued_or_date')->nullable()->after('or_no');
            }
            if (!Schema::hasColumn('renewals', 'cedula_no')) {
                $table->string('cedula_no')->nullable()->after('issued_or_date');
            }
            if (!Schema::hasColumn('renewals', 'issued_date')) {
                $table->string('issued_date')->nullable()->after('cedula_no');
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
        Schema::table('renewals', function (Blueprint $table) {
            $dropColumns = [];
            foreach (['or_no', 'issued_or_date', 'cedula_no', 'issued_date'] as $column) {
                if (Schema::hasColumn('renewals', $column)) {
                    $dropColumns[] = $column;
                }
            }
            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
}

