<?php

namespace Database\Seeders;

use App\Models\Election;
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
        //Past Elections
        Election::factory(3)->create();

        $elections = Election::all();
        $votingDistricts = [1,2,3,4,5,6,7,8,9,10];  //VotingDistrictSeeders creates 10 items
        $candidates = [1,2,3,4,5];  //CandidatesSeeder creates 5 items

        foreach($elections as $election) {
            $election->votingDistricts()
                ->sync($votingDistricts);   //Add all Voting Districts to all ellections

            $election->candidates()
                ->sync($candidates);   //Add all Voting Districts to all ellections
        }

        //Ongoing Elections
        $yesterday = Carbon::now()->subDay();
        $tomorrow = Carbon::now()->addDay();
        Election::create([
            'start_date' => $yesterday,
            'end_date' => $yesterday,
            'started' => $tomorrow,
        ]);
    }
}
