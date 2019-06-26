<?php

namespace Tests\Feature;

use App\Models\ApiToken;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function getChats()
    {
        $this->seed();

        $token = ApiToken::findOrFail(1);

        $response = $this->json('GET', 'api/v1/chat/rooms', [], ['HTTP_Authorization' => 'Bearer ' . $token->api_token]);

        dd($response->json());
    }
}
