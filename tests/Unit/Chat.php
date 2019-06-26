<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class Chat extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function chat_rooms()
    {
        $this->seed();

        $token = ApiToken::findOrFail(1);

        $response = $this->json('GET', 'api/v1/chat/rooms', [], ['Authorization' => 'Bearer ' . $token->api_token]);

        dd($response);
    }
}
