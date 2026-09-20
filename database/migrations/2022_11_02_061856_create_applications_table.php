<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('new_application_id');
            $table->string('type');
            $table->date('date');
            $table->string('status')->default('ON-PROCESS');
            $table->string('finding')->nullable();
            $table->string('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('new_application_id')
                ->references('id')
                ->on('new_applications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
