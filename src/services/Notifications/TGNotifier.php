<?php

namespace App\services\Notifications;

use App\services\GuzzleClient;
use App\services\SendStatus;
use GuzzleHttp\Exception\GuzzleException;
use Exception;

class TGNotifier
{
    private GuzzleClient $guzzleClient;

    public function __construct()
    {
        $this->guzzleClient = new GuzzleClient();
    }

    public function notify(string $message): array
    {
        $error = '';
        $statuses = SendStatus::getSendStatus();

        try {
            $guzzleResponse = $this->guzzleClient->send($_ENV['BOT_API_SEND_ROUTE'], $message);

            $contents = $guzzleResponse->getBody()->getContents();

            if (str_contains($contents, 'error')) {
                $error = $contents;

                $data = ['is_send' => $statuses['notSend']['statusCode']];
            } else {
                $data = ['is_send' => $statuses['send']['statusCode']];
            }
        } catch (Exception | GuzzleException $e) {
            $error = $e->getMessage();
            $data = ['is_send' => $statuses['notSend']['statusCode']];
        }

        return [
            'error' => $error,
            'data' => $data
        ];
    }
}