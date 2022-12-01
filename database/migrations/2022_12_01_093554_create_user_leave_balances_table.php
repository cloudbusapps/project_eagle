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
        Schema::create('user_leave_balances', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('UserId');
            $table->uuid('LeaveTypeId');
            $table->decimal('Balance', 10, 2)->default(0);
            $table->decimal('Accumulated', 10, 2)->default(0);
            $table->uuid('Created_By_Id')->nullable();
            $table->uuid('Updated_By_Id')->nullable();
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
        Schema::dropIfExists('user_leave_balances');
    }
};
