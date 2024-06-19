<?php

namespace Database\Seeders;

use App\Models\Election;
use App\Models\Vote;
use App\Models\Voter;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ElectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Elections
        Election::factory(3)->create();

        //Ongoing Election
        $yesterday = Carbon::now()->subDay();
        $tomorrow = Carbon::now()->addDay();
        $running = Election::create([
                            'start_date' => $yesterday,
                            'end_date' => $yesterday,
                            'started' => $tomorrow,
                        ]);

        //Sync all districts and Candidates to all elections
        $elections = Election::all();
        $votingDistricts = [1,2,3,4,5,6,7,8,9,10];  //VotingDistrictSeeders creates 10 items
        $candidates = [1,2,3,4,5];  //CandidatesSeeder creates 5 items
        foreach($elections as $election) {
            $election->votingDistricts()
                ->sync($votingDistricts);   //Add all Voting Districts to all ellections

            $election->candidates()
                ->sync($candidates);   //Add all Voting Districts to all ellections
        }

        //Votes to ongoing election
        $voters = Voter::get();
        foreach($voters as $voter)
        {
            Vote::create([
                'election_id' => $running->id,
                'voter_hash' => bcrypt($voter->id),
                'candidate_id' => rand(1,5),
            ]);
        }
    }
}
