<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\VoterRequest;
use App\Models\Voter;

class VoterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Voter::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VoterRequest $request)
    {
        $data = $request->validated();

        return Voter::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $district = Voter::findOrFail($id);

        return $district;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VoterRequest $request, string $id)
    {
        $data = $request->validated();

        $district = Voter::findOrFail($id);
        $district->update($data);

        return $district;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $district = Voter::findOrFail($id);
        $district->delete();

        return response()->json([], 204);
    }
}
