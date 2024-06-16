<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\VoteRequest;
use App\Models\ElectionVoter;
use App\Models\Vote;
use App\Services\VoteService;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoteController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Vote::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VoteRequest $request, VoteService $service)
    {
        $data = $request->validated();

        $user = Auth::user();
        $voter = $user->voter;

        //Check if user is registered to vote
        if($voter == null) {
            return response()->json(['message' => 'User is not registered to vote.'], 403);
        }

        //Check if user already voted in this election
        $alreadyVoted = ElectionVoter::where('election_id', $data['election_id'])
                            ->where('voter_id', $voter->id)
                            ->first();
        if($alreadyVoted) {
            return response()->json(['message' => 'User already voted to this election.'], 403);
        }

        try{
            $service->vote($user, $voter, $data['election_id'], $data['candidate_id']);

            return response()->json(['message' => 'Vote registered. Check your email for confirmation.'], 201);
        }
        catch(\Exception $e)
        {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vote = Vote::findOrFail($id);

        return $vote;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return response()->json(['message' => 'Method not allowed'], 405);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json(['message' => 'Method not allowed'], 405);
    }
}
