<?php

namespace App\services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

class GuzzleClient
{
    /**
     * @param string $uri
     * @param string $message
     * @return Response
     */
    public function send(string $uri, string $message): Response
    {
        $client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ],
            'base_uri' => $_ENV['BOT_API_BASE_URI'],
            'timeout' => $_ENV['BOT_API_REQUEST_TIMEOUT'],
        ]);

        $promise = $client->postAsync($uri, ['json' => ['message' => $message]]);

        return $promise->wait();
    }
}