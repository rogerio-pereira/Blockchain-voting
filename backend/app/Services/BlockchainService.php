<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BlockchainService
{
    public function get()
    {
        $url = config('services.blockchain.reader.url');
        $token  = config('services.blockchain.reader.authorization_key');
        
        return Http::withHeaders([
                        'Authorization' => $token
                    ])
                    ->acceptJson()
                    ->get($url);
    }

    public function getById($id)
    {
        $url = config('services.blockchain.reader.url');
        $token  = config('services.blockchain.reader.authorization_key');
        
        return Http::withHeaders([
                        'Authorization' => $token
                    ])
                    ->acceptJson()
                    ->get($url.="/{$id}");
    }

    public function store(array $data)
    {
        $url = config('services.blockchain.writer.url');
        $token  = config('services.blockchain.writer.authorization_key');

        return Http::withHeaders([
                        'Authorization' => $token
                    ])
                    ->acceptJson()
                    ->post($url, $data);
    }
}