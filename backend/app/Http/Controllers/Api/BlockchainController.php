<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BlockchainService;
use Illuminate\Http\Request;

class BlockchainController extends Controller
{
    public function __construct(
        private BlockchainService $service
    ) { }

    public function verify(string $id)
    {
        $response = $this->service->getById($id);

        if($response == '') {
            return response()->json(['message' => 'Vote not found.'], 404);
        }

        return $response;
    }
}
