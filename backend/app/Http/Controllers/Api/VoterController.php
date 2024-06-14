<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VoterRequest;
use App\Models\User;
use App\Models\Voter;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

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
        $data['user']['role'] = 'Voter';    //Make sure role will be Voter

        $user = User::create($data['user']);
        $user->refresh();   //Load default role value
        $user->voter()->create($data['voter']);

        event(new Registered($user));

        Auth::login($user);
        
        $token = $user->regenerateToken();
        $user = $user->toArray();
        $user['token'] = $token;

        return response()->json([
                        'user' => $user
                    ], 201);
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
