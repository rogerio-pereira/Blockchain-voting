<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ElectionRequest;
use App\Models\Election;

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
