<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vote>
 */
class VoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->make();
        $userHash = bcrypt($user->id);

        return [
            'election_id' => Election::factory(),
            'voter_hash' => $userHash,
            'candidate_id' => Candidate::factory(),
        ];
    }
}
