<?php

namespace App\services;

use GuzzleHttp\Exception\GuzzleException;
use Exception;

class TGNotifier
{
    private const SEND_STATUSES = [
        'send' => 1,
        'notSend' => 2,
    ];

    private GuzzleClient $guzzleClient;

    public function __construct()
    {
        $this->guzzleClient = new GuzzleClient();
    }

    public function notify(array $data, string $message)
    {
        $error = '';

        try {
            $guzzleResponse = $this->guzzleClient->send($_ENV['BOT_API_SEND_ROUTE'], $message);

            $contents = $guzzleResponse->getBody()->getContents();

            if (str_contains($contents, 'error')) {
                $error = $contents;
//                $data = ['text' => $message, 'status_id' => self::SEND_STATUSES['notSend']];
                $data = array_merge($data, ['status_id' => self::SEND_STATUSES['notSend']]);
            } else {
                $data = array_merge($data, ['status_id' => self::SEND_STATUSES['notSend']]);
            }
        } catch (Exception | GuzzleException $e) {
            $error = $e->getMessage();
            $data = array_merge($data, ['status_id' => self::SEND_STATUSES['notSend']]);
        }

        return [
            'error' => $error,
            'data' => $data
        ];
    }
}