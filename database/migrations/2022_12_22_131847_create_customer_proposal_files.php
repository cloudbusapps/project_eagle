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
        Schema::create('customer_proposal_files', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('CustomerId');
            $table->string('File');
            $table->date('Date');
            
            /*
            0 = Unsigned
            1 = Signed
            */
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
        Schema::dropIfExists('customer_proposal_files');
    }
};
