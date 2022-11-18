<?php

namespace App\Http\Controllers;

use App\Model\Repository\MessageRepository;
use App\Model\Validators\MessageValidator;
use App\services\GuzzleClient;
use App\services\Notifications\TGNotifier;
use App\services\SendStatus;
use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Slim\Views\Twig;

class MessagesController
{
    private MessageValidator $validator;
    private GuzzleClient $guzzleClient;
    private MessageRepository $repo;
    private TGNotifier $notifier;

    public function __construct()
    {
        $this->repo = new MessageRepository();
        $this->validator = new MessageValidator();
        $this->guzzleClient = new GuzzleClient();
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
        $status = $request->getParam('status') ?? 'all';
        $orderByDirection = $request->getParam('orderBy') ?? 'desc';

        $statuses = SendStatus::getSendStatus();
        $isSend = $statuses['notSend']['statusCode'];

        if ($status == 'send') {
            $isSend = $statuses['send']['statusCode'];
        }

        $messagesHistory = $this->repo->filterByStatus($isSend, $orderByDirection);

        if ($status == 'all') {
            $messagesHistory = $this->repo->getAll($orderByDirection);
        }

        $view = Twig::fromRequest($request);

        return $view->render($response, 'messages/history.twig', [
            'messagesHistory' => $messagesHistory,
            'queryParams' => [
                'status' => $status,
                'orderByDirection' => $orderByDirection,
            ],
        ]);
    }

    public function send(ServerRequest $request, Response $response)
    {
        $sendError = '';

        $message = $request->getParsedBodyParam('message', []);

        $validationErrors = $this->validator->validate(['message' => $message]);

        $view = Twig::fromRequest($request);

        if (empty($validationErrors)) {
            $notification = $this->notifier->notify(['text' => $message], $message);

            $sendError = $notification['error'];

            $data = array_merge($notification['data'], ['reason' => $notification['error']]);

            $this->repo->create($data);
        }

        return $view->render($response, 'messages/index.twig', [
            'message' => $message,
            'validationErrors' => $validationErrors,
            'sendError' => $sendError,
        ]);
    }

    public function resend(ServerRequest $request, Response $response)
    {
        $allNotSendMessages = $this->repo->getNotSend();

        foreach ($allNotSendMessages as $message) {
            $notification = $this->notifier->notify(['id' => $message->getId()], $message->getText());

            $data = array_merge($notification['data'], ['reason' => $notification['error']]);

            $this->repo->update($data);
        }

        return $response->withRedirect('/messages/history');
    }
}
