<?php

namespace App\services;

class SendStatus
{
    private const SEND_STATUSES = [
        'notSend' => ['statusCode' => 0, 'title' => 'Не отправлено'],
        'send' => ['statusCode' => 1, 'title' => 'Отправлено'],
    ];

    public static function getSendStatus()
    {
        return self::SEND_STATUSES;
    }
}