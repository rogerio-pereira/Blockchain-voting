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
        return Election::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ElectionRequest $request)
    {
        $data = $request->validated();

        $election = Election::create($data);

        $election->votingDistricts()->sync($data['voting_districts']);

        return $election;
    }

    /**
     * Display the specified resource.
     */
    public function show(Election $election)
    {
        $election = Election::findOrFail($election);

        return $election;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ElectionRequest $request, Election $election)
    {
        $data = $request->validated();

        $election = Election::findOrFail($election);
        $election->update($data);

        $election->votingDistricts()->sync($data['voting_districts']);

        return $election;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Election $election)
    {
        $election = Election::findOrFail($election);
        $election->delete();

        return response()->json([], 204);
    }
}
