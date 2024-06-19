<?php
namespace App\Services;

use App\Mail\ElectionStartedMail;
use App\Models\Election;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ElectionStartedService
{
    public function start(Election $election)
    {
        try {
            $this->updateDatabase($election);

            $this->sendStartedEmail($election);
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
                'started' => $now
            ]);

            return $election;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    private function sendStartedEmail(Election $election)
    {
        try {
            foreach($election->votingDistricts as $district) {
                foreach($district->voters as $voter) {
                    $user = $voter->user;
    
                    Mail::to($user->email)
                        ->queue(new ElectionStartedMail($election, $user));
                }
            }
        }
        catch(\Exception $e) {
            throw $e;
        }
    }
}