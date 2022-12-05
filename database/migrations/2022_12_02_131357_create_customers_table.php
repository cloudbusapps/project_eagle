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
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->string('CustomerName');
            $table->string('Industry');
            $table->string('ProjectName');
            $table->string('Address');

            $table->string('ContactPerson');
            $table->integer('Product')->nullable(true);
            $table->integer('Type')->nullable(true);
            $table->string('Notes');
            $table->string('Link');
            $table->boolean('isComplex');
            $table->integer('Status');
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
        Schema::dropIfExists('customers');
    }
};
