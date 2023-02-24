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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->uuid('Id');
            $table->string('DocumentNumber')->nullable();
            $table->uuid('UserId');
            $table->uuid('LeaveTypeId'); // VACATION, SICK
            $table->decimal('LeaveBalance', 10, 2);
            $table->date('StartDate');
            $table->date('EndDate');
            $table->integer('IsWholeDay')->default(1); // 0 - No | 1 - Yes
            $table->time('StartTime')->nullable();
            $table->time('EndTime')->nullable();
            $table->decimal('LeaveDuration', 10, 2);
            $table->text('Reason');

            /*
            Status
            0 - Pending
            1 - For Approval
            2 - Approved
            3 - Rejected
            */
            $table->integer('Status')->default(0);
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
        // Schema::dropIfExists('leave_request_files');
        Schema::dropIfExists('leave_requests');
    }
};
