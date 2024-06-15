<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ElectionRequest;
use App\Mail\ElectionStarted;
use App\Models\Election;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ElectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Election::with('votingDistricts')
                    ->with('candidates')
                    ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ElectionRequest $request)
    {
        $data = $request->validated();

        $election = Election::create($data);

        $election->votingDistricts()->sync($data['voting_districts']);
        $election->candidates()->sync($data['candidates']);

        return $election;
    }

    public function start(string $id)
    {
        $election = Election::with('votingDistricts.voters.user')
                        ->with('candidates')
                        ->findOrFail($id);
                        
        if($election->started != null) {
            return response()->json(['message' => "Can't start this election because it's already been started."], 400);
        }

        if($election->ended != null) {
            return response()->json(['message' => "Can't start this election because it's already been ended."], 400);
        }

        $now = Carbon::now();
        $election->update([
                'started' => $now
            ]);

        foreach($election->votingDistricts as $district) {
            foreach($district->voters as $voter) {
                $user = $voter->user;

                Mail::to($user->email)
                    ->queue(new ElectionStarted($election, $user));
            }
        }
    }

    public function stop(string $id)
    {
        $election = Election::findOrFail($id);

        if($election->ended != null) {
            return response()->json(['message' => "Can't stop this election because it's already been ended."], 400);
        }

        $now = Carbon::now();
        $election->update([
                'ended' => $now
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $election = Election::with('votingDistricts')
                        ->with('candidates')
                        ->findOrFail($id);

        return $election;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ElectionRequest $request, string $id)
    {
        $data = $request->validated();

        $election = Election::findOrFail($id);
        $election->update($data);

        $election->votingDistricts()->sync($data['voting_districts']);
        $election->candidates()->sync($data['candidates']);

        return $election;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $election = Election::findOrFail($id);
        $election->delete();

        return response()->json([], 204);
    }
}
