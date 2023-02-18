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
        Schema::create('timekeeping_details', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('TimekeepingId');
            $table->time('StartTime');
            $table->time('EndTime');
            $table->uuid('ProjectId');
            $table->string('ProjectName')->nullable();
            $table->text('Description');
            $table->decimal('Hours', 10, 2)->default(0);
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
        Schema::dropIfExists('timekeeping_details');
    }
};
