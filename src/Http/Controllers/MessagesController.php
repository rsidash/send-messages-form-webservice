<?php

namespace App\Http\Controllers;

use App\Model\Repository\MessageRepository;
use App\Model\Repository\StatusRepository;
use App\Model\Validators\MessageValidator;
use App\services\GuzzleClient;
use App\services\TGNotifier;
use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Slim\Views\Twig;

class MessagesController
{
    private MessageValidator $validator;
    private GuzzleClient $guzzleClient;
    private MessageRepository $repo;
    private string $sendError = '';
    private StatusRepository $statusRepo;
    private TGNotifier $notifier;
    private array $statuses;

    public function __construct()
    {
        $this->repo = new MessageRepository();
        $this->validator = new MessageValidator();
        $this->guzzleClient = new GuzzleClient();
        $this->statusRepo = new StatusRepository();
        $this->statuses = $this->statusRepo->getAll();
        $this->notifier = new TGNotifier();
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
        $statusId = (int)$_GET['status_id'] ?? 0;
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
            $notification = $this->notifier->notify(['text' => $message], $message);

            $this->sendError = $notification['error'];

            $this->repo->create($notification['data']);
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
            $notification = $this->notifier->notify(['id' => $message->getId()], $message->getText());

            $this->repo->update($notification['data']);
        }

        return $response->withRedirect('/messages/history');
    }
}
