<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VotingDistrict;
use Illuminate\Http\Request;

class VotingDistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return VotingDistrict::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validated();

        return VotingDistrict::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $district = VotingDistrict::findOrFail($id);

        return $district;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validated();

        $district = VotingDistrict::findOrFail($id);
        $district->update($data);

        return $district;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $district = VotingDistrict::findOrFail($id);
        $district->delete();

        return response()->json([], 204);
    }
}
