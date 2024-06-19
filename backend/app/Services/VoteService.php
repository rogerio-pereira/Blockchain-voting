<?php

namespace App\Services;

use App\Mail\VoteRegistered;
use App\Models\ElectionVoter;
use App\Models\User;
use App\Models\Vote;
use App\Models\Voter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class VoteService
{
    public function __construct(
        public BlockchainService $blockchain
    ) { }

    public function vote(User $user, Voter $voter, int $electionId, int $candidateId)
    {
        try {
            $vote = $this->registerVote($voter, $electionId, $candidateId);
            
            $blockchainId = $this->sendToBlockchain($vote);

            $vote->update([
                    'blockchain_id' => $blockchainId
                ]);

            $this->sendEmail($user, $vote);
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
    
    /**
     * Save Vote To Database
     *
     * @param Voter $voter
     * @param integer $electionId
     * @param integer $candidateId
     * @return Vote
     */
    private function registerVote(Voter $voter, int $electionId, int $candidateId) : Vote
    {
        $voterHash = bcrypt($voter->id);
        
        DB::beginTransaction();
        try{
            $vote = Vote::create([
                'election_id' => $electionId,
                'voter_hash' => $voterHash,
                'candidate_id' => $candidateId,
            ]);

            ElectionVoter::create([
                'election_id' => $electionId,
                'voter_id' => $voter->id,
            ]);

            DB::commit();
            return $vote;
        }
        catch(\Exception $e)
        {
            DB::rollback();

            throw $e;
        }
    }

    private function sendToBlockchain(Vote $vote) : string
    {
        try {
            $arrayVote = $vote->toArray();

            $response = $this->blockchain->store($arrayVote);

            return $response['documentId'];
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }

    private function sendEmail(User $user, Vote $vote)
    {
        $vote->load('candidate');

        try {
            Mail::to($user->email)
                ->queue(new VoteRegistered($user, $vote));
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
}