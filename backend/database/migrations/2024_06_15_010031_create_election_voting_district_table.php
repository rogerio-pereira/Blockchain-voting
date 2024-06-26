<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('election_voting_district', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('voting_district_id');
            $table->timestamps();

            $table->foreign('election_id')
                ->references('id')
                ->on('elections')
                ->restrictOnDelete();

            $table->foreign('voting_district_id')
                ->references('id')
                ->on('voting_districts')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('election_voting_district');
    }
};
