<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CandidateRequest;
use App\Models\Candidate;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Candidate::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CandidateRequest $request)
    {
        $data = $request->validated();

        return Candidate::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $candidate = Candidate::findOrFail($id);

        return $candidate;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CandidateRequest $request, string $id)
    {
        $data = $request->validated();

        $candidate = Candidate::findOrFail($id);
        $candidate->update($data);

        return $candidate;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->delete();

        return response()->json([], 204);
    }
}
