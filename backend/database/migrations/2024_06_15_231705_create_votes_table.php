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
        Schema::create('votes', function (Blueprint $table) {
            $table->uuid('id');
            $table->unsignedBigInteger('election_id');
            $table->string('voter_hash');    //Hashing voter to make voting secret, this way we can check the hash if needed, but it won't be public
            $table->unsignedBigInteger('candidate_id');
            $table->string('blockchain_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('election_id')
                ->references('id')
                ->on('elections')
                ->restrictOnDelete();

            $table->foreign('candidate_id')
                ->references('id')
                ->on('candidates')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
