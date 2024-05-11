<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$isProduction = getenv('APP_ENV') === 'prod';

$app = AppFactory::create();

// Регистрация middlewares фреймворка Slim.
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(!$isProduction, true, true);

$methodOverrideMiddleware = new MethodOverrideMiddleware();
$app->add($methodOverrideMiddleware);

// Регистрация middlewares шаблонизатора Twig.
$twig = Twig::create(__DIR__ . '/../templates/', $isProduction ? ['cache' => __DIR__ . '/../var/cache/'] : []);
$app->add(TwigMiddleware::create($app, $twig));

// Определение правил маршрутизации
// Методы get/delete/post соответствуют HTTP-методам GET/DELETE/POST,
//  что позволяет по-разному обрабатывать, например, GET и POST
//  запросы к одному URL
$app->get('/master/list', \App\Controller\MasterController::class . ':list');
$app->delete('/master/{id}', \App\Controller\MasterController::class . ':delete');
$app->get('/master/add', \App\Controller\MasterController::class . ':add');

$app->get('/master/{id}', \App\Controller\MasterController::class . ':edit');
$app->get('/master/event/add', \App\Controller\MasterController::class . ':addEvent');
$app->delete('/master/event/{id}', \App\Controller\MasterController::class . ':removeEvent');
$app->get('/client/list', \App\Controller\ClientController::class . ':list');
$app->get('/client/new', \App\Controller\ClientController::class . ':new');
$app->get('/client/[id]', \App\Controller\ClientController::class . ':edit');
$app->redirect('/', '/master/list');

$app->run();
