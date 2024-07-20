<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $client = new Client();
        $response = $client->post('http://127.0.0.1:5000/chat', [
            'json' => [
                'message' => $request->input('message')
            ]
        ]);

        $responseBody = json_decode($response->getBody(), true);
        return response()->json($responseBody);
    }
}
