<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected $token;

    public function __construct()
    {
        $this->token = env('FONNTE_TOKEN');
    }

    public function sendMessage(string $phone, string $message)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        $response = Http::withHeaders([
            'Authorization' => $this->token,
            'Accept' => 'application/json'
        ])->post('https://api.fonnte.com/send', [
            'target'  => $phone,
            'message' => $message,
        ]);

        Log::info('FonnteService response', [
            'status' => $response->status(),
            'body'   => $response->json(),
        ]);

        return $response->json();
    }
}
