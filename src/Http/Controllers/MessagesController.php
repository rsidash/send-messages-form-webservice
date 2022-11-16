<?php

namespace App\Http\Controllers;

use App\Model\Repository\MessageRepository;
use App\Model\Repository\StatusRepository;
use App\Model\Validators\MessageValidator;
use App\services\GuzzleClient;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Slim\Views\Twig;

class MessagesController
{
    private const SEND_STATUSES = [
            'send' => 1,
            'notSend' => 2,
        ];

    private MessageValidator $validator;
    private GuzzleClient $guzzleClient;
    private MessageRepository $repo;
    private string $sendError = '';
    private StatusRepository $statusRepo;
    private array $statuses;

    public function __construct()
    {
        $this->repo = new MessageRepository();
        $this->validator = new MessageValidator();
        $this->guzzleClient = new GuzzleClient();
        $this->statusRepo = new StatusRepository();
        $this->statuses = $this->statusRepo->getAll();
    }

    public function index(ServerRequest $request, Response $response)
    {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'messages/index.twig', [
            'isFirstLaunch' => true
        ]);
    }

    public function history(ServerRequest $request, Response $response)
    {
        $statusId = (int) $_GET['status_id'] ?? 0;
        $orderByDirection = $_GET['orderBy'] ?? 'desc';

        $view = Twig::fromRequest($request);

        if ($statusId == 0) {
            $messagesHistory = $this->repo->getAll($orderByDirection);
        } else {
            $messagesHistory = $this->repo->filterByStatus($statusId, $orderByDirection);
        }

        return $view->render($response, 'messages/history.twig', [
            'messagesHistory' => $messagesHistory,
            'statuses' => $this->statuses,
        ]);
    }

    public function send(ServerRequest $request, Response $response)
    {
        $message = $request->getParsedBodyParam('message', []);

        $validationErrors = $this->validator->validate(['message' => $message]);

        $view = Twig::fromRequest($request);

        if (!$validationErrors) {
            try {
                $response = $this->guzzleClient->send($_ENV['BOT_API_SEND_ROUTE'], $message);
                $jsonDecoded = json_decode($response, true);

                if (array_key_exists('error', $jsonDecoded)) {
                    $this->sendError = $jsonDecoded['error'];
                    $data = ['text' => $message, 'status_id' => self::SEND_STATUSES['notSend']];
                } else {
                    $data = ['text' => $message, 'status_id' => self::SEND_STATUSES['send']];
                }
            } catch (Exception | GuzzleException $e) {
                $this->sendError = $e->getMessage();
                $data = ['text' => $message, 'status_id' => self::SEND_STATUSES['notSend']];
            }

            $this->repo->create($data);
        }

        return $view->render($response, 'messages/index.twig', [
            'message' => $message,
            'validationErrors' => $validationErrors,
            'sendError' => $this->sendError,
        ]);
    }

    public function resend(ServerRequest $request, Response $response, array $args)
    {
        $allNotSendMessages = $this->repo->getNotSend();

        foreach ($allNotSendMessages as $message) {
            try {
                $response = $this->guzzleClient->send($_ENV['BOT_API_SEND_ROUTE'], $message->getText());
                $jsonDecoded = json_decode($response, true);

                if (array_key_exists('error', $jsonDecoded)) {
                    $data = ['id' => $message->getId(), 'status_id' => self::SEND_STATUSES['notSend']];
                } else {
                    $data = ['id' => $message->getId(), 'status_id' => self::SEND_STATUSES['send']];
                }
            } catch (Exception | GuzzleException $e) {
                $data = ['id' => $message->getId(), 'status_id' => self::SEND_STATUSES['notSend']];
            }

            $this->repo->update($data);
        }

        return $response->withRedirect('/messages/history');
    }
}
