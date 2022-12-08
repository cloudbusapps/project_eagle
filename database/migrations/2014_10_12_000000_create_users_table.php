<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->string('EmployeeNumber')->unique()->nullable();
            $table->string('FirstName');
            $table->string('MiddleName')->nullable();
            $table->string('LastName');
            $table->string('Gender')->nullable();
            $table->text('Address')->nullable();
            $table->string('ContactNumber')->nullable();
            $table->string('Profile')->default('default.png');
            $table->uuid('DepartmentId')->nullable();
            $table->uuid('DesignationId')->nullable();
            $table->string('Title')->nullable();
            $table->text('About')->nullable();
            $table->string('email')->unique();
            $table->date('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('IsAdmin')->default(false);
            $table->integer('Status')->default(1); // [0 - Inactive, 1 - Active]
            $table->uuid('CreatedById')->nullable();
            $table->uuid('UpdatedById')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users CASCADE');
    }
};
