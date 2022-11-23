<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Http\Controllers;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$twig = Twig::create(__DIR__ . '/../templates', ['cache' => false]);
$app  = AppFactory::create();
$app->addErrorMiddleware($_ENV['DISPLAY_ERROR_DETAILS'], $_ENV['DISPLAY_ERROR_DETAILS'], $_ENV['DISPLAY_ERROR_DETAILS']);
$app->add(TwigMiddleware::create($app, $twig));

$app->get('/', Controllers\IndexController::class . ':home');
$app->get('/messages', Controllers\MessagesController::class . ':index');
$app->post('/messages', Controllers\MessagesController::class . ':send');
$app->get('/messages/history', Controllers\MessagesController::class . ':history');
$app->post('/messages/history/resend_all', Controllers\MessagesController::class . ':resendAll');
$app->post('/messages/history/resend_selected', Controllers\MessagesController::class . ':resendSelected');
$app->post('/messages/history/delete_all', Controllers\MessagesController::class . ':deleteAllNotSend');
$app->post('/messages/history/delete_selected', Controllers\MessagesController::class . ':deleteSelectedNotSend');

$app->run();