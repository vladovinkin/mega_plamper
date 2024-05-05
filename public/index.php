<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$isProduction = getenv('APP_ENV') === 'prod';

$app = AppFactory::create();

// Регистрация middlewares фреймворка Slim.
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(!$isProduction, true, true);

// Регистрация middlewares шаблонизатора Twig.
$twig = Twig::create(__DIR__ . '/../templates/', $isProduction ? ['cache' => __DIR__ . '/../var/cache/'] : []);
$app->add(TwigMiddleware::create($app, $twig));

// Определение правил маршрутизации
// Методы get/delete/post соответствуют HTTP-методам GET/DELETE/POST,
//  что позволяет по-разному обрабатывать, например, GET и POST
//  запросы к одному URL
$app->get('/', \App\Controller\HomeController::class . ':home');

$app->get('/articles/list', \App\Controller\ArticleApiController::class . ':listArticles');
$app->delete('/articles/batch-delete', \App\Controller\ArticleApiController::class . ':batchDeleteArticles');
$app->get('/article', \App\Controller\ArticleApiController::class . ':getArticle');
$app->post('/article', \App\Controller\ArticleApiController::class . ':createArticle');
$app->post('/article/edit', \App\Controller\ArticleApiController::class . ':editArticle');
$app->delete('/article/delete', \App\Controller\ArticleApiController::class . ':deleteArticle');

$app->run();
