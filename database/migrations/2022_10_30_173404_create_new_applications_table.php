<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('application_no');
            $table->string('cedula_no')->nullable();
            $table->string('or_no')->nullable();
            $table->string('issued_at')->nullable();
            $table->string('issued_date')->nullable();
            $table->string('issued_or_date')->nullable();
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('suffix')->nullable();
            $table->unsignedBigInteger('purpose_id');
            $table->string('application_type');
            $table->string('house_no');
            $table->string('street');
            $table->string('barangay');
            $table->unsignedBigInteger('municipality_id');
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->string('birthdate');
            $table->string('birth_place');
            $table->string('gender');
            $table->unsignedBigInteger('nationality_id');
            $table->string('civil_status');
            $table->unsignedBigInteger('religion_id');
            $table->string('height');
            $table->string('weight');
            $table->string('hair_color');
            $table->string('eye_color');
            $table->string('contact_number')->nullable();
            $table->string('mole')->nullable();
            $table->string('scar')->nullable();
            $table->string('tattoo')->nullable();
            $table->string('birthmark')->nullable();
            $table->string('harelip')->nullable();
            $table->string('skin_tag')->nullable();
            $table->string('occupation_id')->nullable();
            $table->string('occupation')->nullable();
            $table->string('employer')->nullable();
            $table->string('employer_address')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('contact_address')->nullable();
            $table->string('ucid')->nullable();
            $table->text('finger_print_right')->nullable();
            $table->text('finger_print_left')->nullable();
            $table->text('fingerprint_right_data')->nullable();
            $table->text('fingerprint_left_data')->nullable();
            $table->text('picture')->nullable();
            $table->text('signature')->nullable();
            $table->enum('status',['ACTIVE','INACTIVE'])->default('ACTIVE');
            $table->string('reference_num')->nullable();
            $table->enum('type',['ONLINE','NEW'])->default('NEW');
            $table->enum('stage',['APPLICATION','FINGERPRINT','PICTURE','SIGNATURE'])->default('APPLICATION');
            $table->string('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('nationality_id')
                ->references('id')
                ->on('nationalities');

            $table->foreign('religion_id')
                ->references('id')
                ->on('religions');

            $table->foreign('municipality_id')
                ->references('id')
                ->on('municipalities');

            $table->foreign('purpose_id')
                ->references('id')
                ->on('purposes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_applications');
    }
}
