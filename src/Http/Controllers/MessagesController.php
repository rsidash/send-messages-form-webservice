<?php

namespace App\Http\Controllers;

use App\Model\Repository\MessageRepository;
use App\Model\Validators\MessageValidator;
use App\services\GuzzleClient;
use App\services\Notifications\TGNotifier;
use App\services\SendStatus;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MessagesController
{
    private MessageValidator $validator;
    private MessageRepository $repo;
    private TGNotifier $notifier;

    public function __construct()
    {
        $this->repo = new MessageRepository();
        $this->validator = new MessageValidator();
        $this->notifier = new TGNotifier();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function index(ServerRequest $request, Response $response): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'messages/index.twig', [
            'isFirstLaunch' => true
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function history(ServerRequest $request, Response $response): ResponseInterface
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
            'statuses' => $statuses,
            'queryParams' => [
                'status' => $status,
                'orderByDirection' => $orderByDirection,
            ],
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function send(ServerRequest $request, Response $response): ResponseInterface
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

    public function resend(ServerRequest $request, Response $response): Response|ResponseInterface
    {
        $selectedIds = $request->getParam('selectedIds') ?? [];

        if (empty($selectedIds)) {
            $notSendMessages = $this->repo->getNotSend();
        } else {
            $notSendMessages = $this->repo->getNotSendByIds($selectedIds);
        }

        foreach ($notSendMessages as $message) {
            $notification = $this->notifier->notify(['id' => $message->getId()], $message->getText());

            $data = array_merge($notification['data'], ['reason' => $notification['error']]);

            $this->repo->update($data);
        }

        return $response->withRedirect('/messages/history');
    }
}
