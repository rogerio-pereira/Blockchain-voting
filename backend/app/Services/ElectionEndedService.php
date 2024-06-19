<?php
namespace App\Services;

use App\Mail\ElectionEndedMail;
use App\Models\Election;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ElectionEndedService
{
    public function stop(Election $election)
    {
        try {
            $this->updateDatabase($election);

            $this->sendResultEmail($election);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Mark Election as ended
     *
     * @param Election $election
     * @return Election
     */
    private function updateDatabase(Election $election) : Election
    {
        try {
            $now = Carbon::now();
            $election->update([
                    'ended' => $now
                ]);

            return $election;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    private function sendResultEmail(Election $election)
    {
        try {
            $results = $election->getResults();

            foreach($election->votingDistricts as $district) {
                foreach($district->voters as $voter) {
                    $user = $voter->user;

                    Mail::to($user->email)
                        ->queue(new ElectionEndedMail($user, $results));
                    
                }
            }
        }
        catch(\Exception $e) {
            throw $e;
        }
    }
}