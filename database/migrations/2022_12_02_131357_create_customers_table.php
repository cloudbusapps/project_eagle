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
            $table->string('ProjectName')->nullable(true);
            $table->string('Address');

            $table->string('ContactPerson');
            $table->integer('Product')->nullable(true);
            $table->integer('Type')->nullable(true);
            $table->string('Notes')->nullable(true);
            $table->string('Link')->nullable(true);
            $table->integer('IsCapable')->nullable();
            $table->integer('IsComplex')->nullable();
            $table->string('ThirdPartyId')->nullable();
            $table->string('ThirdPartyName')->nullable();
            $table->text('ThirdPartyAttachment')->nullable();

            /**
             * ----- THIRD PARTY STATUS -----
             * 0 or null - Not Applicable
             * 1 - Pending
             * 2 - Ongoing
             * 3 - Completed
             */
            $table->integer('ThirdPartyStatus')->nullable();

            /**
             * ----- DSW STATUS -----
             * 0 or null - Not Applicable
             * 1 - Started DSW
             * 2 - Ongoing DSW
             * 3 - Completed DSW
             * 4 - For Consolidation
             * 5 - Completed Requirements Consolidation
             */
            $table->integer('DSWStatus')->nullable();

            /**
             * ----- STATUS -----
             * 0 - Information
             * 1 - Capability
             * 2 - Complexity
             * 3 - DSW
             * 4 - Business Process
             * 5 - Requirement and Solution
             * 6 - Project Phases
             * 7 - Assessment
             * 8 - Proposal
             */
            $table->integer('ProposalStatus')->nullable();

            /**
             * ----- STATUS -----
             * 0 - Ongoing Creation of Proposal
             * 1 - Submitted Proposal
             * 2 - Signed Proposal
             * 3 - Rejected Proposal
             */
            $table->integer('Status')->nullable(true);

            $table->uuid('HeadId')->nullable();
            $table->uuid('OICId')->nullable();
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
