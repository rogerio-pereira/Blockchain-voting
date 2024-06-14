<?php

namespace Database\Seeders;

use App\Models\Voter;
use App\Models\VotingDistrict;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VotingDistrictSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VotingDistrict::factory(10)
            ->has(Voter::factory()->count(10))
            ->create();
    }
}
