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
        Schema::create('customer_business_process_files', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('CustomerId')->unsigned();
            $table->string('File');
            $table->longText('Note');
            $table->uuid('CreatedById');
            $table->uuid('UpdatedById');
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
        Schema::dropIfExists('customer_business_process_files');
    }
};
