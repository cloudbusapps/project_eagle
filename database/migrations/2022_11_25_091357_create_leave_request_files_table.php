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
        Schema::create('leave_request_files', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('LeaveRequestId')->unsigned();
            $table->string('File');
            $table->uuid('CreatedById');
            $table->uuid('UpdatedById');
            $table->timestamps();

            // $table->foreign('LeaveRequestId')->references('Id')->on('leave_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_request_files');
    }
};
