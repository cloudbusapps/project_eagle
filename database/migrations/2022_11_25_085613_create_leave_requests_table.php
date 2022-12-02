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
            $table->decimal('LeaveDuration', 10, 2);
            $table->text('Reason');
            $table->integer('Status')->default(0);
            $table->uuid('Created_By_Id');
            $table->uuid('Updated_By_Id');
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
