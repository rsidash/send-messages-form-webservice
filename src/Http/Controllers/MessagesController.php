<?php

namespace App\Http\Controllers;

use App\Model\Validators\MessageValidator;
use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Slim\Views\Twig;

class MessagesController
{
    private MessageValidator $validator;
    private bool $isSend = false;

    public function __construct()
    {
        $this->validator = new MessageValidator();
    }

    public function index(ServerRequest $request, Response $response)
    {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'messages/index.twig', [
            'isSend' => $this->isSend,
        ]);
    }

    public function send(ServerRequest $request, Response $response)
    {
        $message = $request->getParsedBodyParam('message', []);

        $errors = $this->validator->validate(['message' => $message]);

        $view = Twig::fromRequest($request);

        $this->isSend = true;

        return $view->render($response, 'messages/index.twig', [
            'message' => $message,
            'errors' => $errors,
            'isSend' => $this->isSend,
        ]);
    }
}
