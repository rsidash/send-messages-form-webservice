<?php

namespace App\Http\Controllers;

use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Slim\Views\Twig;

class IndexController
{
    public function home(ServerRequest $request, Response $response)
    {
        $username = $_GET['name'] ?? 'guest';

        $view = Twig::fromRequest($request);

        return $view->render($response, 'home.twig', ['name' => $username]);
    }
}
