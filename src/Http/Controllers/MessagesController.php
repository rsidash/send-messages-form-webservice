<?php

namespace App\Http\Controllers;

use App\Model\Repository\MessageRepository;
use App\Model\Validators\MessageValidator;
use App\services\GuzzleClient;
use Exception;
use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Slim\Views\Twig;

class MessagesController
{
    private MessageValidator $validator;
    private GuzzleClient $guzzleClient;
    private MessageRepository $repo;
    private string $sendError = '';

    public function __construct()
    {
        $this->repo = new MessageRepository();
        $this->validator = new MessageValidator();
        $this->guzzleClient = new GuzzleClient();
    }

    public function index(ServerRequest $request, Response $response)
    {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'messages/index.twig', [
            'sendStatus' => $this->sendError,
        ]);
    }

    public function send(ServerRequest $request, Response $response)
    {
        $statuses = [
            'send' => 1,
            'notSend' => 2,
        ];

        $message = $request->getParsedBodyParam('message', []);

        $validationErrors = $this->validator->validate(['message' => $message]);

        $view = Twig::fromRequest($request);

        if (!$validationErrors) {
            try {
                $this->guzzleClient->send($_ENV['BOT_API_SEND_ROUTE'], $message);
                $data = ['text' => $message, 'status_id' => $statuses['send']];
            } catch (Exception $e) {
                $this->sendError = $e->getMessage();
                $data = ['text' => $message, 'status_id' => $statuses['notSend']];
            }

            $this->repo->create($data);
        }

        return $view->render($response, 'messages/index.twig', [
            'message' => $message,
            'validationErrors' => $validationErrors,
            'sendError' => $this->sendError,
        ]);
    }
}
