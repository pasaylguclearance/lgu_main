<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname');
            $table->string('middlename');
            $table->string('lastname');
            $table->string('suffix');
            $table->string('profile_img');
            $table->string('status')->default('1');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('created_by')->default('0');
            $table->string('updated_by')->default('0');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert([
            [
                'firstname' => 'Super',
                'middlename' => '',
                'lastname' => 'Admin',
                'suffix' => '',
                'profile_img' => 'default.jpg',
                'email' => 'superadmin@gmail.com',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'password' => Hash::make('P@ssw0rd')
            ],
            [
                'firstname' => 'Juan',
                'middlename' => '',
                'lastname' => 'Dela Cruz',
                'suffix' => '',
                'profile_img' => 'default.jpg',
                'email' => 'agent01@gmail.com',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'password' => Hash::make('P@ssw0rd')
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
